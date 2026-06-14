<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * QcPenilaianService
 *
 * Menangani semua logic bisnis QC Penilaian:
 *  - Mengambil daftar barang yang menunggu penilaian QC
 *  - Menyimpan dan mengupdate penilaian QC
 *  - Mengambil riwayat penilaian QC
 *  - Mengambil data hasil fuzzy untuk tampilan QC
 *  - Memanggil FuzzyTsukamotoService setelah penilaian berhasil disimpan
 */
class QcPenilaianService
{
    public function __construct(
        private FuzzyTsukamotoService $fuzzyService
    ) {}

    // -----------------------------------------------------------------------
    // PENILAIAN — Daftar & Form
    // -----------------------------------------------------------------------

    /**
     * Mengambil daftar detail barang yang menunggu penilaian QC.
     *
     * Hanya barang dengan total_berat_bersih > 100 kg yang masuk QC.
     * Barang dengan berat bersih <= 100 kg tidak muncul di sini sesuai alur bisnis.
     */
    public function getDaftarMenungguQc()
    {
        return DB::table('detail_transaksi_barang as detail')
            ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->select(
                'detail.id as detail_id',
                'detail.status_qc',
                'detail.total_berat_kotor',
                'detail.total_berat_bersih',
                'detail.keterangan_barang',
                'transaksi.id as transaksi_id',
                'transaksi.kode_transaksi',
                'transaksi.tanggal_transaksi',
                'transaksi.berat_timbang_pertama',
                'transaksi.status as status_transaksi',
                'pelanggan.nama_pelanggan',
                'kertas.nama_barang as nama_kertas',
                'kertas.kode_barang as kode_kertas',
                'kendaraan.nama_kendaraan',
            )
            ->whereIn('transaksi.status', [
                'menunggu_qc',
                'proses_qc',
            ])
            ->where('detail.status_qc', 'belum_dinilai')
            // Barang dengan berat bersih <= 100 kg tidak masuk QC sesuai alur bisnis.
            ->where('detail.total_berat_bersih', '>', 100)
            ->orderByDesc('transaksi.tanggal_transaksi')
            ->paginate(8)
            ->withQueryString();
    }

    /**
     * Mengambil ringkasan statistik penilaian QC untuk halaman index.
     */
    public function getSummaryPenilaian(): array
    {
        return [
            'menunggu' => DB::table('detail_transaksi_barang as detail')
                ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
                ->whereIn('transaksi.status', ['menunggu_qc', 'proses_qc'])
                ->where('detail.status_qc', 'belum_dinilai')
                ->where('detail.total_berat_bersih', '>', 100)
                ->count(),

            'sudah_dinilai' => DB::table('detail_transaksi_barang as detail')
                ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
                ->whereIn('transaksi.status', [
                    'menunggu_qc',
                    'proses_qc',
                    'menunggu_pembayaran',
                ])
                ->where('detail.status_qc', 'sudah_dinilai')
                ->where('detail.total_berat_bersih', '>', 100)
                ->count(),

            'revisi' => DB::table('detail_transaksi_barang as detail')
                ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
                ->whereIn('transaksi.status', [
                    'menunggu_qc',
                    'proses_qc',
                    'menunggu_pembayaran',
                ])
                ->where('detail.status_qc', 'revisi')
                ->where('detail.total_berat_bersih', '>', 100)
                ->count(),
        ];
    }

    /**
     * Mengambil detail satu barang untuk ditampilkan di form penilaian QC.
     *
     * @throws RuntimeException jika detail tidak ditemukan.
     */
    public function getDetailUntukPenilaian(int $detailId): object
    {
        $detail = DB::table('detail_transaksi_barang as detail')
            ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->select(
                'detail.id as detail_id',
                'detail.transaksi_id',
                'detail.status_qc',
                'detail.total_berat_kotor',
                'detail.total_berat_bersih',
                'detail.keterangan_barang',
                'transaksi.kode_transaksi',
                'transaksi.tanggal_transaksi',
                'transaksi.berat_timbang_pertama',
                'transaksi.status as status_transaksi',
                'pelanggan.nama_pelanggan',
                'kertas.nama_barang as nama_kertas',
                'kertas.kode_barang as kode_kertas',
                'kendaraan.nama_kendaraan'
            )
            ->where('detail.id', $detailId)
            ->first();

        abort_if(! $detail, 404);

        return $detail;
    }

    /**
     * Menyimpan penilaian QC, mengupdate status_qc barang,
     * lalu menjalankan Fuzzy Tsukamoto secara otomatis.
     *
     * Setelah semua barang > 100 kg pada transaksi tersebut sudah dinilai dan
     * hasil fuzzy sudah tersimpan, status transaksi diubah ke 'menunggu_pembayaran'.
     */
    public function simpanPenilaian(int $detailId, array $data): void
    {
        $detail = DB::table('detail_transaksi_barang as detail')
            ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->select(
                'detail.id as detail_id',
                'detail.transaksi_id',
                'detail.total_berat_kotor',
                'detail.total_berat_bersih',
                'transaksi.berat_timbang_pertama',
                'kendaraan.nama_kendaraan'
            )
            ->where('detail.id', $detailId)
            ->first();

        abort_if(! $detail, 404);

        // Mapping nama kendaraan ke nilai numerik untuk input fuzzy jenis_kendaraan.
        $namaKendaraan = strtoupper($detail->nama_kendaraan);
        $nilaiJenisKendaraan = match ($namaKendaraan) {
            'K1' => 1,
            'K2' => 2,
            'K3' => 3,
            default => 1,
        };

        DB::transaction(function () use ($detail, $nilaiJenisKendaraan, $data) {
            // Simpan atau update penilaian QC ke tabel qc_penilaian.
            DB::table('qc_penilaian')->updateOrInsert(
                ['detail_transaksi_barang_id' => $detail->detail_id],
                [
                    'qc_user_id'           => auth()->id(),
                    'nilai_jenis_kendaraan' => $nilaiJenisKendaraan,
                    'nilai_berat_kotor'     => $detail->berat_timbang_pertama,
                    'nilai_berat_bersih'    => $detail->total_berat_bersih,
                    'nilai_kualitas_kertas' => $data['nilai_kualitas_kertas'],
                    'catatan'              => $data['catatan'] ?? null,
                    'waktu_qc'             => now(),
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]
            );

            // Tandai detail barang sudah dinilai QC.
            DB::table('detail_transaksi_barang')
                ->where('id', $detail->detail_id)
                ->update([
                    'status_qc'  => 'sudah_dinilai',
                    'updated_at' => now(),
                ]);
        });

        // Jalankan Fuzzy Tsukamoto setelah penilaian QC berhasil disimpan.
        $this->jalankanFuzzySetelahQc($detailId);

        // Cek apakah masih ada barang > 100 kg yang belum memiliki hasil fuzzy.
        $detailTerbaru = DB::table('detail_transaksi_barang')
            ->where('id', $detailId)
            ->first();

        $masihButuhQc = DB::table('detail_transaksi_barang as detail')
            ->leftJoin('fuzzy_hasil as fuzzy', 'detail.id', '=', 'fuzzy.detail_transaksi_barang_id')
            ->where('detail.transaksi_id', $detailTerbaru->transaksi_id)
            ->where('detail.total_berat_bersih', '>', 100)
            ->whereNull('fuzzy.id')
            ->exists();

        // Jika semua barang > 100 kg sudah memiliki hasil fuzzy,
        // ubah status transaksi agar kasir dapat melakukan pembayaran.
        if (! $masihButuhQc) {
            DB::table('transaksi_penimbangan')
                ->where('id', $detailTerbaru->transaksi_id)
                ->update([
                    'status'     => 'menunggu_pembayaran',
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Memanggil FuzzyTsukamotoService untuk menghitung fuzzy setelah QC disimpan.
     *
     * Method ini hanya meneruskan ke FuzzyTsukamotoService.
     * Semua rumus fuzzy tetap di FuzzyTsukamotoService, tidak dipindah ke sini.
     */
    public function jalankanFuzzySetelahQc(int $detailId): bool
    {
        return $this->fuzzyService->hitungDetailJikaSiap($detailId);
    }

    // -----------------------------------------------------------------------
    // RIWAYAT PENILAIAN
    // -----------------------------------------------------------------------

    /**
     * Mengambil daftar riwayat penilaian QC dengan opsional pencarian keyword.
     */
    public function getRiwayatPenilaian(?string $keyword)
    {
        $query = DB::table('qc_penilaian as qc')
            ->join('detail_transaksi_barang as detail', 'qc.detail_transaksi_barang_id', '=', 'detail.id')
            ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->select(
                'qc.id as qc_id',
                'qc.nilai_kualitas_kertas',
                'qc.catatan',
                'qc.waktu_qc',
                'detail.id as detail_id',
                'detail.status_qc',
                'transaksi.kode_transaksi',
                'transaksi.tanggal_transaksi',
                'transaksi.berat_timbang_pertama',
                'pelanggan.nama_pelanggan',
                'kertas.nama_barang as nama_kertas',
                'kertas.kode_barang as kode_kertas',
                'kendaraan.nama_kendaraan'
            )
            ->orderByDesc('qc.waktu_qc');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('transaksi.kode_transaksi', 'like', "%{$keyword}%")
                  ->orWhere('pelanggan.nama_pelanggan', 'like', "%{$keyword}%")
                  ->orWhere('kertas.nama_barang', 'like', "%{$keyword}%");
            });
        }

        return $query->paginate(8)->withQueryString();
    }

    /**
     * Mengambil data satu riwayat QC untuk halaman edit.
     *
     * @throws RuntimeException jika data tidak ditemukan.
     */
    public function getDetailRiwayatEdit(int $qcId): object
    {
        $qc = DB::table('qc_penilaian as qc')
            ->join('detail_transaksi_barang as detail', 'qc.detail_transaksi_barang_id', '=', 'detail.id')
            ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->select(
                'qc.id as qc_id',
                'qc.nilai_kualitas_kertas',
                'qc.catatan',
                'qc.waktu_qc',
                'detail.id as detail_id',
                'detail.status_qc',
                'transaksi.kode_transaksi',
                'transaksi.berat_timbang_pertama',
                'pelanggan.nama_pelanggan',
                'kertas.nama_barang as nama_kertas',
                'kertas.kode_barang as kode_kertas',
                'kendaraan.nama_kendaraan'
            )
            ->where('qc.id', $qcId)
            ->first();

        abort_if(! $qc, 404);

        return $qc;
    }

    /**
     * Mengupdate penilaian QC yang sudah ada, lalu menjalankan ulang fuzzy.
     *
     * Digunakan dari halaman riwayat QC ketika QC ingin merevisi nilai.
     */
    public function updatePenilaian(int $qcId, array $data): void
    {
        $qc = DB::table('qc_penilaian')
            ->where('id', $qcId)
            ->first();

        abort_if(! $qc, 404);

        DB::table('qc_penilaian')
            ->where('id', $qcId)
            ->update([
                'qc_user_id'            => auth()->id(),
                'nilai_kualitas_kertas' => $data['nilai_kualitas_kertas'],
                'catatan'               => $data['catatan'] ?? null,
                'waktu_qc'              => now(),
                'updated_at'            => now(),
            ]);

        // Ambil data QC terbaru setelah update untuk mendapatkan detail_transaksi_barang_id.
        $qcTerbaru = DB::table('qc_penilaian')
            ->where('id', $qcId)
            ->first();

        if ($qcTerbaru) {
            // Jalankan ulang Fuzzy Tsukamoto setelah penilaian diperbarui.
            $this->jalankanFuzzySetelahQc((int) $qcTerbaru->detail_transaksi_barang_id);
        }
    }

    // -----------------------------------------------------------------------
    // FUZZY — Tampilan Hasil
    // -----------------------------------------------------------------------

    /**
     * Mengambil daftar hasil fuzzy dengan opsional pencarian keyword.
     *
     * Data ini hanya untuk ditampilkan. Kasir menggunakan hasil yang sama
     * dari tabel fuzzy_hasil — tidak dihitung ulang.
     */
    public function getDaftarHasilFuzzy(?string $keyword)
    {
        $query = DB::table('fuzzy_hasil as fuzzy')
            ->join('detail_transaksi_barang as detail', 'fuzzy.detail_transaksi_barang_id', '=', 'detail.id')
            ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
            ->join('qc_penilaian as qc', 'fuzzy.qc_penilaian_id', '=', 'qc.id')
            ->select(
                'fuzzy.id as fuzzy_id',
                'fuzzy.nilai_bobot_ketidaklayakan',
                'fuzzy.persentase_potongan',
                'fuzzy.potongan_berat',
                'fuzzy.berat_layak',
                'fuzzy.created_at',
                'detail.total_berat_bersih',
                'transaksi.kode_transaksi',
                'pelanggan.nama_pelanggan',
                'kertas.nama_barang',
                'kertas.kode_barang',
                'qc.nilai_kualitas_kertas'
            )
            ->orderByDesc('fuzzy.created_at');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('transaksi.kode_transaksi', 'like', "%{$keyword}%")
                    ->orWhere('pelanggan.nama_pelanggan', 'like', "%{$keyword}%")
                    ->orWhere('kertas.nama_barang', 'like', "%{$keyword}%");
            });
        }

        return $query->paginate(8)->withQueryString();
    }

    /**
     * Mengambil detail lengkap satu hasil fuzzy untuk halaman show.
     *
     * @throws RuntimeException jika data tidak ditemukan.
     */
    public function getDetailHasilFuzzy(int $fuzzyId): array
    {
        $hasil = DB::table('fuzzy_hasil as fuzzy')
            ->join('detail_transaksi_barang as detail', 'fuzzy.detail_transaksi_barang_id', '=', 'detail.id')
            ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->join('qc_penilaian as qc', 'fuzzy.qc_penilaian_id', '=', 'qc.id')
            ->select(
                'fuzzy.*',
                'detail.total_berat_kotor',
                'detail.total_tara',
                'detail.total_berat_bersih',
                'transaksi.kode_transaksi',
                'transaksi.berat_timbang_pertama',
                'transaksi.berat_timbang_kedua',
                'pelanggan.nama_pelanggan',
                'kertas.nama_barang',
                'kertas.kode_barang',
                'kendaraan.nama_kendaraan',
                'qc.nilai_jenis_kendaraan',
                'qc.nilai_berat_kotor',
                'qc.nilai_berat_bersih',
                'qc.nilai_kualitas_kertas',
                'qc.catatan as catatan_qc'
            )
            ->where('fuzzy.id', $fuzzyId)
            ->first();

        abort_if(! $hasil, 404);

        // Decode detail perhitungan fuzzy dari JSON (disimpan saat fuzzy dijalankan).
        $perhitungan = json_decode($hasil->detail_perhitungan ?? '{}', true) ?: [];

        return [
            'hasil'       => $hasil,
            'perhitungan' => $perhitungan,
        ];
    }
}
