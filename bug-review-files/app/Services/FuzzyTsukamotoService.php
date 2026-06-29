<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * FuzzyTsukamotoService
 *
 * Menangani seluruh proses Fuzzy Tsukamoto untuk menghitung bobot ketidaklayakan
 * kertas bekas berdasarkan empat variabel input:
 *  - Jenis Kendaraan (K1/K2/K3)
 *  - Berat Kotor (kg)
 *  - Berat Bersih (kg)
 *  - Kualitas Kertas (skala 1–10)
 *
 * Alur proses:
 *  1. Fuzzifikasi  — nilai crisp diubah menjadi derajat keanggotaan (μ)
 *  2. Inferensi    — setiap rule dievaluasi dengan operator minimum (AND)
 *  3. Defuzzifikasi — nilai akhir dihitung dengan rata-rata terbobot (weighted average)
 *
 * Catatan penting:
 *  - Barang dengan berat bersih <= 100 kg TIDAK melalui proses ini.
 *  - Fuzzy hanya dijalankan setelah QC berhasil menyimpan penilaian.
 *  - Hasil fuzzy disimpan ke tabel fuzzy_hasil agar kasir dapat menggunakannya.
 *  - Kasir tidak menghitung ulang fuzzy — hanya membaca hasil yang sudah tersimpan.
 */
class FuzzyTsukamotoService
{

    /**
     * Memeriksa apakah satu detail barang sudah siap dihitung fuzzy,
     * lalu menjalankan perhitungan jika semua syarat terpenuhi.
     *
     * Syarat siap hitung:
     *  - Berat bersih > 0 (sudah ditimbang)
     *  - Sudah ada penilaian QC (qc_id tidak null)
     *  - Nilai kualitas kertas > 0
     *  - Status QC = 'sudah_dinilai'
     *
     * Entry point utama yang dipanggil dari QcPenilaianService setelah penilaian disimpan.
     */
    public function hitungDetailJikaSiap(int $detailTransaksiBarangId): bool
    {
        $data = DB::table('detail_transaksi_barang as detail')
            ->leftJoin('qc_penilaian as qc', 'qc.detail_transaksi_barang_id', '=', 'detail.id')
            ->select(
                'detail.id',
                'detail.total_berat_bersih',
                'detail.status_qc',
                'qc.id as qc_id',
                'qc.nilai_kualitas_kertas'
            )
            ->where('detail.id', $detailTransaksiBarangId)
            ->first();

        if (! $data) {
            return false;
        }

        // Barang dengan berat bersih <= 100 kg tidak melalui proses QC/Fuzzy.
        // Sesuai alur bisnis, barang kecil langsung masuk pembayaran dengan potongan 0%.
        $siapDihitung =
            (float) $data->total_berat_bersih > 0
            && $data->qc_id !== null
            && (float) $data->nilai_kualitas_kertas > 0
            && $data->status_qc === 'sudah_dinilai';

        if (! $siapDihitung) {
            return false;
        }

        $this->hitungDetail($detailTransaksiBarangId);

        return true;
    }


    /**
     * Menjalankan fuzzy untuk semua detail barang dalam satu transaksi.
     * Digunakan oleh route penimbang saat selesai penimbangan.
     *
     * Mengembalikan jumlah detail yang berhasil dihitung.
     */
    public function hitungTransaksiJikaSiap(int $transaksiId): int
    {
        $details = DB::table('detail_transaksi_barang')
            ->where('transaksi_id', $transaksiId)
            ->pluck('id');

        $jumlahBerhasil = 0;

        foreach ($details as $detailId) {
            if ($this->hitungDetailJikaSiap((int) $detailId)) {
                $jumlahBerhasil++;
            }
        }

        return $jumlahBerhasil;
    }

    public function hitungDetail(int $detailTransaksiBarangId): array
    {
        $data = DB::table('detail_transaksi_barang as detail')
            ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
            ->join('qc_penilaian as qc', 'qc.detail_transaksi_barang_id', '=', 'detail.id')
            ->select(
                'detail.id as detail_id',
                'detail.total_berat_bersih',
                'transaksi.id as transaksi_id',
                'transaksi.berat_timbang_pertama',
                'qc.id as qc_id',
                'qc.nilai_jenis_kendaraan',
                'qc.nilai_berat_kotor',
                'qc.nilai_berat_bersih',
                'qc.nilai_kualitas_kertas'
            )
            ->where('detail.id', $detailTransaksiBarangId)
            ->first();

        if (! $data) {
            throw new RuntimeException('Data detail barang atau QC tidak ditemukan.');
        }

        $beratBersih = (float) $data->total_berat_bersih;
        $kualitasKertas = (float) $data->nilai_kualitas_kertas;
        $jenisKendaraan = (float) $data->nilai_jenis_kendaraan;

        $beratKotor = (float) ($data->nilai_berat_kotor > 0
            ? $data->nilai_berat_kotor
            : $data->berat_timbang_pertama);

        if ($beratBersih <= 0) {
            throw new RuntimeException('Berat bersih detail barang masih 0.');
        }

        if ($kualitasKertas <= 0) {
            throw new RuntimeException('Nilai kualitas kertas belum diisi.');
        }

        // Tahap fuzzifikasi: mengubah nilai input crisp menjadi derajat keanggotaan
        // pada setiap himpunan fuzzy berdasarkan fungsi keanggotaan masing-masing variabel.
        $himpunan = $this->ambilHimpunan();
        $fuzzifikasiDetail = $this->buatFuzzifikasiDetail([
            'jenis_kendaraan' => $jenisKendaraan,
            'berat_kotor'     => $beratKotor,
            'berat_bersih'    => $beratBersih,
            'kualitas_kertas' => $kualitasKertas,
        ], $himpunan);

        // Tahap inferensi Tsukamoto: menghitung alpha-predicate tiap rule
        // menggunakan operator minimum (AND) dari seluruh derajat keanggotaan input.
        $rules = DB::table('fuzzy_rule')
            ->where('status', 'aktif')
            ->orderBy('id')
            ->get();

        if ($rules->count() === 0) {
            throw new RuntimeException('Fuzzy rule belum tersedia.');
        }

        $totalAlphaZ = 0;
        $totalAlpha  = 0;
        $inferensiDetail = [];

        foreach ($rules as $rule) {
            // Hitung derajat keanggotaan (μ) masing-masing premis berdasarkan fungsi keanggotaan.
            $muJenisKendaraan = $this->membership(
                $jenisKendaraan,
                $this->getHimpunan($himpunan, 'jenis_kendaraan', $rule->jenis_kendaraan)
            );

            $muBeratKotor = $this->membership(
                $beratKotor,
                $this->getHimpunan($himpunan, 'berat_kotor', $rule->berat_kotor)
            );

            $muBeratBersih = $this->membership(
                $beratBersih,
                $this->getHimpunan($himpunan, 'berat_bersih', $rule->berat_bersih)
            );

            $muKualitasKertas = $this->membership(
                $kualitasKertas,
                $this->getHimpunan($himpunan, 'kualitas_kertas', $rule->kualitas_kertas)
            );

            // Alpha-predicate = min dari semua derajat keanggotaan premis (operator AND).
            $alpha = min(
                $muJenisKendaraan,
                $muBeratKotor,
                $muBeratBersih,
                $muKualitasKertas
            );

            // Hanya rule dengan alpha > 0 yang dianggap aktif agar detail perhitungan lebih ringkas.
            if ($alpha <= 0) {
                continue;
            }

            $outputHimpunan = $this->getHimpunan(
                $himpunan,
                'bobot_ketidaklayakan',
                $rule->bobot_ketidaklayakan
            );

            $z = $this->inverseOutput($alpha, $outputHimpunan);

            $totalAlpha += $alpha;
            $totalAlphaZ += $alpha * $z;

            $inferensiDetail[] = [
    'kode_rule' => $rule->kode_rule,

    'rule_text' => 'IF Jenis Kendaraan ' . $rule->jenis_kendaraan
        . ' AND Berat Kotor ' . $rule->berat_kotor
        . ' AND Berat Bersih ' . $rule->berat_bersih
        . ' AND Kualitas Kertas ' . $rule->kualitas_kertas
        . ' THEN Bobot Ketidaklayakan ' . $rule->bobot_ketidaklayakan,

    'premis' => [
        'jenis_kendaraan' => $rule->jenis_kendaraan,
        'berat_kotor' => $rule->berat_kotor,
        'berat_bersih' => $rule->berat_bersih,
        'kualitas_kertas' => $rule->kualitas_kertas,
    ],

    'output' => [
        'bobot_ketidaklayakan' => $rule->bobot_ketidaklayakan,
        'tipe_fungsi' => $outputHimpunan->tipe_fungsi,
        'domain_min' => (float) $outputHimpunan->domain_min,
        'domain_max' => (float) $outputHimpunan->domain_max,
        'nilai_a' => (float) $outputHimpunan->nilai_a,
        'nilai_b' => (float) $outputHimpunan->nilai_b,
        'nilai_c' => (float) $outputHimpunan->nilai_c,
        'nilai_d' => (float) $outputHimpunan->nilai_d,
    ],

    'membership' => [
        'jenis_kendaraan' => round($muJenisKendaraan, 4),
        'berat_kotor' => round($muBeratKotor, 4),
        'berat_bersih' => round($muBeratBersih, 4),
        'kualitas_kertas' => round($muKualitasKertas, 4),
    ],

    'alpha' => [
        'rumus' => 'α = min(μ jenis kendaraan, μ berat kotor, μ berat bersih, μ kualitas kertas)',
        'perhitungan' => 'α = min('
            . round($muJenisKendaraan, 4) . ', '
            . round($muBeratKotor, 4) . ', '
            . round($muBeratBersih, 4) . ', '
            . round($muKualitasKertas, 4) . ') = '
            . round($alpha, 4),
        'nilai' => round($alpha, 4),
    ],

    'z' => [
        'rumus' => $this->rumusInverseOutput($alpha, $outputHimpunan),
        'nilai' => round($z, 4),
    ],

    'alpha_z' => [
        'rumus' => 'αz = α × z',
        'perhitungan' => round($alpha, 4) . ' × ' . round($z, 4) . ' = ' . round($alpha * $z, 4),
        'nilai' => round($alpha * $z, 4),
    ],
];
        }

        if ($totalAlpha <= 0) {
            throw new RuntimeException('Tidak ada rule fuzzy yang aktif untuk data ini.');
        }

        // Tahap defuzzifikasi: menghitung nilai akhir bobot ketidaklayakan
        // menggunakan rata-rata terbobot (weighted average).
        // Rumus: Z = Σ(αi × zi) / Σαi
        $nilaiBobotKetidaklayakan = round($totalAlphaZ / $totalAlpha, 2);
        $persentasePotongan = $nilaiBobotKetidaklayakan;
        $potonganBerat = round($beratBersih * $persentasePotongan / 100, 2);
        $beratLayak = round($beratBersih - $potonganBerat, 2);

        $detailPerhitunganLengkap = [
    'input' => [
        'jenis_kendaraan' => [
            'label' => 'Jenis Kendaraan',
            'nilai' => $jenisKendaraan,
        ],
        'berat_kotor' => [
            'label' => 'Berat Kotor',
            'nilai' => $beratKotor,
            'satuan' => 'kg',
        ],
        'berat_bersih' => [
            'label' => 'Berat Bersih',
            'nilai' => $beratBersih,
            'satuan' => 'kg',
        ],
        'kualitas_kertas' => [
            'label' => 'Kualitas Kertas',
            'nilai' => $kualitasKertas,
        ],
    ],

    'fuzzifikasi' => $fuzzifikasiDetail,

    'inferensi' => [
        'metode' => 'Tsukamoto',
        'operator' => 'AND',
        'rumus_alpha' => 'α-predikat = min(μ1, μ2, μ3, μ4)',
        'jumlah_rule_aktif' => count($inferensiDetail),
        'rules' => $inferensiDetail,
    ],

    'defuzzifikasi' => [
        'rumus' => 'Z = Σ(αi × zi) / Σαi',
        'total_alpha' => round($totalAlpha, 4),
        'total_alpha_z' => round($totalAlphaZ, 4),
        'perhitungan' => 'Z = ' . round($totalAlphaZ, 4) . ' / ' . round($totalAlpha, 4)
            . ' = ' . round($nilaiBobotKetidaklayakan, 4),
        'hasil' => $nilaiBobotKetidaklayakan,
    ],

    'hasil_akhir' => [
        'nilai_bobot_ketidaklayakan' => $nilaiBobotKetidaklayakan,
        'persentase_potongan' => $persentasePotongan,
        'potongan_berat' => [
            'rumus' => 'Potongan berat = berat bersih × persentase potongan / 100',
            'perhitungan' => $beratBersih . ' × ' . $persentasePotongan . ' / 100 = ' . $potonganBerat,
            'nilai' => $potonganBerat,
            'satuan' => 'kg',
        ],
        'berat_layak' => [
            'rumus' => 'Berat layak = berat bersih - potongan berat',
            'perhitungan' => $beratBersih . ' - ' . $potonganBerat . ' = ' . $beratLayak,
            'nilai' => $beratLayak,
            'satuan' => 'kg',
        ],
    ],
];

        // Hasil fuzzy disimpan agar dapat digunakan kasir saat menghitung pembayaran.
        // Kasir tidak menghitung ulang fuzzy — hanya membaca nilai dari tabel ini.
        // Detail rule disimpan sebagai jejak perhitungan dalam kolom detail_perhitungan (JSON)
        // agar QC/Kasir dapat melihat proses fuzzy secara lengkap.
        DB::table('fuzzy_hasil')->updateOrInsert(
            [
                'detail_transaksi_barang_id' => $data->detail_id,
            ],
            [
                'qc_penilaian_id'            => $data->qc_id,
                'nilai_bobot_ketidaklayakan' => $nilaiBobotKetidaklayakan,
                'persentase_potongan'        => $persentasePotongan,
                'potongan_berat'             => $potonganBerat,
                'berat_layak'                => $beratLayak,
                'detail_perhitungan'         => json_encode($detailPerhitunganLengkap, JSON_PRETTY_PRINT),
                'created_at'                 => now(),
                'updated_at'                 => now(),
            ]
        );

        return [
            'detail_transaksi_barang_id' => $data->detail_id,
            'qc_penilaian_id' => $data->qc_id,
            'nilai_bobot_ketidaklayakan' => $nilaiBobotKetidaklayakan,
            'persentase_potongan' => $persentasePotongan,
            'potongan_berat' => $potonganBerat,
            'berat_layak' => $beratLayak,
            'total_alpha' => round($totalAlpha, 4),
            'total_alpha_z' => round($totalAlphaZ, 4),
        ];
    }

    private function ambilHimpunan()
    {
        return DB::table('fuzzy_himpunan as himpunan')
            ->join('fuzzy_variabel as variabel', 'himpunan.fuzzy_variabel_id', '=', 'variabel.id')
            ->select(
                'himpunan.*',
                'variabel.kode_variabel'
            )
            ->get()
            ->groupBy(function ($item) {
                return strtolower($item->kode_variabel) . '.' . strtolower($item->kode_himpunan);
            });
    }

    private function getHimpunan($himpunan, string $kodeVariabel, string $kodeHimpunan)
    {
        $key = strtolower($kodeVariabel) . '.' . strtolower($kodeHimpunan);

        $item = $himpunan->get($key)?->first();

        if (! $item) {
            throw new RuntimeException("Himpunan fuzzy tidak ditemukan: {$kodeVariabel} - {$kodeHimpunan}");
        }

        return $item;
    }

    private function membership(float $x, object $h): float
    {
        $tipe = strtolower($h->tipe_fungsi);

        $a = (float) $h->nilai_a;
        $b = (float) $h->nilai_b;
        $c = (float) $h->nilai_c;
        $d = (float) $h->nilai_d;

        return match ($tipe) {
            'singleton' => abs($x - $a) < 0.0001 ? 1.0 : 0.0,

            'linear_turun' => $this->linearTurun($x, $c, $d),

            'linear_naik' => $this->linearNaik($x, $a, $b),

            'segitiga' => $this->segitiga($x, $a, $b, $c),

            'trapesium' => $this->trapesium($x, $a, $b, $c, $d),

            default => 0.0,
        };
    }

    private function linearTurun(float $x, float $mulaiTurun, float $akhirTurun): float
    {
        if ($x <= $mulaiTurun) {
            return 1.0;
        }

        if ($x >= $akhirTurun) {
            return 0.0;
        }

        return ($akhirTurun - $x) / ($akhirTurun - $mulaiTurun);
    }

    private function linearNaik(float $x, float $mulaiNaik, float $puncak): float
    {
        if ($x <= $mulaiNaik) {
            return 0.0;
        }

        if ($x >= $puncak) {
            return 1.0;
        }

        return ($x - $mulaiNaik) / ($puncak - $mulaiNaik);
    }

    private function segitiga(float $x, float $a, float $b, float $c): float
    {
        if ($x <= $a || $x >= $c) {
            return 0.0;
        }

        if (abs($x - $b) < 0.0001) {
            return 1.0;
        }

        if ($x < $b) {
            return ($x - $a) / ($b - $a);
        }

        return ($c - $x) / ($c - $b);
    }

    private function trapesium(float $x, float $a, float $b, float $c, float $d): float
    {
        if ($x <= $a || $x >= $d) {
            return 0.0;
        }

        if ($x >= $b && $x <= $c) {
            return 1.0;
        }

        if ($x > $a && $x < $b) {
            return ($x - $a) / ($b - $a);
        }

        return ($d - $x) / ($d - $c);
    }

    private function inverseOutput(float $alpha, object $h): float
    {
        $tipe = strtolower($h->tipe_fungsi);

        $domainMin = (float) $h->domain_min;
        $domainMax = (float) $h->domain_max;

        $a = (float) $h->nilai_a;
        $b = (float) $h->nilai_b;
        $c = (float) $h->nilai_c;
        $d = (float) $h->nilai_d;

        return match ($tipe) {
            'linear_turun' => $domainMax - ($alpha * ($domainMax - $domainMin)),

            'linear_naik' => $domainMin + ($alpha * ($domainMax - $domainMin)),

            // Untuk output "Sedang" yang berbentuk trapesium,
            // kita ambil titik tengah dari dua sisi inverse.
            'trapesium' => (($a + ($alpha * ($b - $a))) + ($d - ($alpha * ($d - $c)))) / 2,

            'segitiga' => $b,

            'singleton' => $a,

            default => ($domainMin + $domainMax) / 2,
        };
    }

    private function buatFuzzifikasiDetail(array $input, $himpunan): array
{
    return [
        'jenis_kendaraan' => [
            'label' => 'Jenis Kendaraan',
            'nilai_crisp' => $input['jenis_kendaraan'],
            'himpunan' => [
                $this->buatFuzzifikasiItem($input['jenis_kendaraan'], $this->getHimpunan($himpunan, 'jenis_kendaraan', 'K1')),
                $this->buatFuzzifikasiItem($input['jenis_kendaraan'], $this->getHimpunan($himpunan, 'jenis_kendaraan', 'K2')),
                $this->buatFuzzifikasiItem($input['jenis_kendaraan'], $this->getHimpunan($himpunan, 'jenis_kendaraan', 'K3')),
            ],
        ],

        'berat_kotor' => [
            'label' => 'Berat Kotor',
            'nilai_crisp' => $input['berat_kotor'],
            'satuan' => 'kg',
            'himpunan' => [
                $this->buatFuzzifikasiItem($input['berat_kotor'], $this->getHimpunan($himpunan, 'berat_kotor', 'Ringan')),
                $this->buatFuzzifikasiItem($input['berat_kotor'], $this->getHimpunan($himpunan, 'berat_kotor', 'Sedang')),
                $this->buatFuzzifikasiItem($input['berat_kotor'], $this->getHimpunan($himpunan, 'berat_kotor', 'Berat')),
            ],
        ],

        'berat_bersih' => [
            'label' => 'Berat Bersih',
            'nilai_crisp' => $input['berat_bersih'],
            'satuan' => 'kg',
            'himpunan' => [
                $this->buatFuzzifikasiItem($input['berat_bersih'], $this->getHimpunan($himpunan, 'berat_bersih', 'Ringan')),
                $this->buatFuzzifikasiItem($input['berat_bersih'], $this->getHimpunan($himpunan, 'berat_bersih', 'Sedang')),
                $this->buatFuzzifikasiItem($input['berat_bersih'], $this->getHimpunan($himpunan, 'berat_bersih', 'Berat')),
            ],
        ],

        'kualitas_kertas' => [
            'label' => 'Kualitas Kertas',
            'nilai_crisp' => $input['kualitas_kertas'],
            'himpunan' => [
                $this->buatFuzzifikasiItem($input['kualitas_kertas'], $this->getHimpunan($himpunan, 'kualitas_kertas', 'Baik')),
                $this->buatFuzzifikasiItem($input['kualitas_kertas'], $this->getHimpunan($himpunan, 'kualitas_kertas', 'Sedang')),
                $this->buatFuzzifikasiItem($input['kualitas_kertas'], $this->getHimpunan($himpunan, 'kualitas_kertas', 'Buruk')),
            ],
        ],
    ];
}

private function buatFuzzifikasiItem(float $x, object $h): array
{
    $mu = $this->membership($x, $h);

    return [
        'kode_himpunan' => $h->kode_himpunan,
        'nama_himpunan' => $h->nama_himpunan,
        'tipe_fungsi' => $h->tipe_fungsi,
        'domain_min' => (float) $h->domain_min,
        'domain_max' => (float) $h->domain_max,
        'nilai_a' => (float) $h->nilai_a,
        'nilai_b' => (float) $h->nilai_b,
        'nilai_c' => (float) $h->nilai_c,
        'nilai_d' => (float) $h->nilai_d,
        'rumus' => $this->rumusMembership($x, $h),
        'nilai_mu' => round($mu, 4),
    ];
}

private function rumusMembership(float $x, object $h): string
{
    $tipe = strtolower($h->tipe_fungsi);

    $a = (float) $h->nilai_a;
    $b = (float) $h->nilai_b;
    $c = (float) $h->nilai_c;
    $d = (float) $h->nilai_d;

    if ($tipe === 'singleton') {
        return "Singleton: μ(x) = 1 jika x = {$a}, selain itu 0. Nilai x = {$x}.";
    }

    if ($tipe === 'linear_turun') {
        if ($x <= $c) {
            return "Karena x <= {$c}, maka μ(x) = 1.";
        }

        if ($x >= $d) {
            return "Karena x >= {$d}, maka μ(x) = 0.";
        }

        $hasil = ($d - $x) / ($d - $c);

        return "Karena {$c} < x < {$d}, maka μ(x) = ({$d} - {$x}) / ({$d} - {$c}) = " . round($hasil, 4) . ".";
    }

    if ($tipe === 'linear_naik') {
        if ($x <= $a) {
            return "Karena x <= {$a}, maka μ(x) = 0.";
        }

        if ($x >= $b) {
            return "Karena x >= {$b}, maka μ(x) = 1.";
        }

        $hasil = ($x - $a) / ($b - $a);

        return "Karena {$a} < x < {$b}, maka μ(x) = ({$x} - {$a}) / ({$b} - {$a}) = " . round($hasil, 4) . ".";
    }

    if ($tipe === 'segitiga') {
        if ($x <= $a || $x >= $c) {
            return "Karena x <= {$a} atau x >= {$c}, maka μ(x) = 0.";
        }

        if (abs($x - $b) < 0.0001) {
            return "Karena x = {$b}, maka μ(x) = 1.";
        }

        if ($x < $b) {
            $hasil = ($x - $a) / ($b - $a);

            return "Karena {$a} < x < {$b}, maka μ(x) = ({$x} - {$a}) / ({$b} - {$a}) = " . round($hasil, 4) . ".";
        }

        $hasil = ($c - $x) / ($c - $b);

        return "Karena {$b} < x < {$c}, maka μ(x) = ({$c} - {$x}) / ({$c} - {$b}) = " . round($hasil, 4) . ".";
    }

    if ($tipe === 'trapesium') {
        if ($x <= $a || $x >= $d) {
            return "Karena x <= {$a} atau x >= {$d}, maka μ(x) = 0.";
        }

        if ($x >= $b && $x <= $c) {
            return "Karena {$b} <= x <= {$c}, maka μ(x) = 1.";
        }

        if ($x > $a && $x < $b) {
            $hasil = ($x - $a) / ($b - $a);

            return "Karena {$a} < x < {$b}, maka μ(x) = ({$x} - {$a}) / ({$b} - {$a}) = " . round($hasil, 4) . ".";
        }

        $hasil = ($d - $x) / ($d - $c);

        return "Karena {$c} < x < {$d}, maka μ(x) = ({$d} - {$x}) / ({$d} - {$c}) = " . round($hasil, 4) . ".";
    }

    return 'Rumus membership tidak dikenali.';
}

private function rumusInverseOutput(float $alpha, object $h): string
{
    $tipe = strtolower($h->tipe_fungsi);

    $domainMin = (float) $h->domain_min;
    $domainMax = (float) $h->domain_max;

    $a = (float) $h->nilai_a;
    $b = (float) $h->nilai_b;
    $c = (float) $h->nilai_c;
    $d = (float) $h->nilai_d;

    if ($tipe === 'linear_turun') {
        $z = $domainMax - ($alpha * ($domainMax - $domainMin));

        return "Linear turun: z = {$domainMax} - ({$alpha} × ({$domainMax} - {$domainMin})) = " . round($z, 4);
    }

    if ($tipe === 'linear_naik') {
        $z = $domainMin + ($alpha * ($domainMax - $domainMin));

        return "Linear naik: z = {$domainMin} + ({$alpha} × ({$domainMax} - {$domainMin})) = " . round($z, 4);
    }

    if ($tipe === 'trapesium') {
        $z1 = $a + ($alpha * ($b - $a));
        $z2 = $d - ($alpha * ($d - $c));
        $z = ($z1 + $z2) / 2;

        return "Trapesium: z1 = {$a} + ({$alpha} × ({$b} - {$a})) = " . round($z1, 4)
            . ", z2 = {$d} - ({$alpha} × ({$d} - {$c})) = " . round($z2, 4)
            . ", z = (z1 + z2) / 2 = " . round($z, 4);
    }

    if ($tipe === 'segitiga') {
        return "Segitiga: z diambil dari titik puncak b = {$b}.";
    }

    if ($tipe === 'singleton') {
        return "Singleton: z = {$a}.";
    }

    return 'Rumus inverse output tidak dikenali.';
}
}