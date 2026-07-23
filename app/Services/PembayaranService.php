<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PembayaranService
{
    /**
     * Ambil daftar transaksi yang siap dibayar (belum memiliki pembayaran).
     * Aturan: semua detail barang harus sudah ditimbang (berat_bersih > 0).
     * Barang <= 100 kg bypass fuzzy, barang > 100 kg wajib punya fuzzy.
     */
    public function getDaftarTransaksiSiapBayar(?string $keyword)
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('transaksi_penimbangan') && ! \Illuminate\Support\Facades\Schema::hasTable('transaksi')) {
                return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
            }

            $transaksiTable = \Illuminate\Support\Facades\Schema::hasTable('transaksi_penimbangan') ? 'transaksi_penimbangan' : 'transaksi';

            $query = DB::table("{$transaksiTable} as transaksi")
                ->select(
                    'transaksi.id',
                    DB::raw("COALESCE(transaksi.kode_transaksi, transaksi.no_transaksi, '') as kode_transaksi"),
                    DB::raw("COALESCE(transaksi.tanggal_transaksi, transaksi.created_at) as tanggal_transaksi"),
                    'transaksi.status'
                );

            if (\Illuminate\Support\Facades\Schema::hasTable('pelanggan')) {
                $query->leftJoin('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
                    ->addSelect(DB::raw("COALESCE(pelanggan.nama_pelanggan, 'Pelanggan') as nama_pelanggan"));
            }

            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('transaksi.kode_transaksi', 'like', "%{$keyword}%");
                });
            }

            return $query->orderByDesc('transaksi.id')->paginate(8)->withQueryString();
        } catch (\Throwable $e) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
        }
    }
            )
            ->whereIn('transaksi.status', [
                'draft_penimbangan',
                'menunggu_qc',
                'proses_qc',
                'menunggu_pembayaran',
            ])
            ->whereNull('pembayaran.id')
            ->groupBy(
                'transaksi.id',
                'transaksi.kode_transaksi',
                'transaksi.tanggal_transaksi',
                'transaksi.status',
                'transaksi.plat_kendaraan',
                'pelanggan.nama_pelanggan',
                'kendaraan.nama_kendaraan'
            )
            // wajib punya detail barang
            ->havingRaw('COUNT(DISTINCT detail.id) > 0')
            // tidak boleh ada detail yang berat bersihnya masih 0
            ->havingRaw('SUM(CASE WHEN detail.total_berat_bersih <= 0 THEN 1 ELSE 0 END) = 0')
            // semua barang harus siap bayar:
            // <= 100 kg langsung siap, > 100 kg harus punya fuzzy
            ->havingRaw("
                COUNT(DISTINCT detail.id) =
                COUNT(DISTINCT CASE
                    WHEN fuzzy.id IS NOT NULL OR detail.total_berat_bersih <= 100
                    THEN detail.id
                END)
            ")
            ->orderByDesc('transaksi.tanggal_transaksi');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('transaksi.kode_transaksi', 'like', "%{$keyword}%")
                    ->orWhere('pelanggan.nama_pelanggan', 'like', "%{$keyword}%")
                    ->orWhere('transaksi.plat_kendaraan', 'like', "%{$keyword}%");
            });
        }

        return $query;
    }

    /**
     * Hitung summary untuk halaman index pembayaran.
     */
    public function getSummaryIndex($queryClone): array
    {
        $dataSummary = $queryClone->get();

        return [
            'menunggu_pembayaran'    => $dataSummary->count(),
            'sudah_dibayar'          => DB::table('pembayaran')->count(),
            'total_berat_layak_pending' => $dataSummary->sum('total_berat_layak'),
        ];
    }

    /**
     * Ambil data transaksi untuk form pembayaran beserta detail barang.
     * Mengembalikan null jika tidak ditemukan atau sudah dibayar.
     */
    public function getTransaksiUntukPembayaran(int $transaksiId): ?object
    {
        return DB::table('transaksi_penimbangan as transaksi')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->leftJoin('pembayaran', 'transaksi.id', '=', 'pembayaran.transaksi_id')
            ->select(
                'transaksi.*',
                'pelanggan.kode_pelanggan',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_hp',
                'pelanggan.alamat',
                'kendaraan.nama_kendaraan',
                'pembayaran.id as pembayaran_id'
            )
            ->where('transaksi.id', $transaksiId)
            ->whereIn('transaksi.status', [
                'draft_penimbangan',
                'menunggu_qc',
                'proses_qc',
                'menunggu_pembayaran',
            ])
            ->first();
    }

    /**
     * Ambil detail barang transaksi dengan kalkulasi bypass fuzzy untuk <= 100 kg.
     */
    public function getDetailBarangTransaksi(int $transaksiId)
    {
        return DB::table('detail_transaksi_barang as detail')
            ->join('jenis_kertas_bekas as barang', 'detail.jenis_kertas_bekas_id', '=', 'barang.id')
            ->leftJoin('fuzzy_hasil as fuzzy', 'detail.id', '=', 'fuzzy.detail_transaksi_barang_id')
            ->select(
                'detail.id as detail_id',
                'detail.total_berat_bersih',
                'detail.urutan',
                'barang.kode_barang',
                'barang.nama_barang',
                'fuzzy.id as fuzzy_id',

                DB::raw('CASE WHEN detail.total_berat_bersih <= 100 THEN 1 ELSE 0 END as bypass_fuzzy'),

                DB::raw("
                    CASE
                        WHEN fuzzy.id IS NOT NULL THEN fuzzy.nilai_bobot_ketidaklayakan
                        WHEN detail.total_berat_bersih <= 100 THEN 0
                        ELSE NULL
                    END as nilai_bobot_ketidaklayakan
                "),

                DB::raw("
                    CASE
                        WHEN fuzzy.id IS NOT NULL THEN fuzzy.persentase_potongan
                        WHEN detail.total_berat_bersih <= 100 THEN 0
                        ELSE NULL
                    END as persentase_potongan
                "),

                DB::raw("
                    CASE
                        WHEN fuzzy.id IS NOT NULL THEN fuzzy.potongan_berat
                        WHEN detail.total_berat_bersih <= 100 THEN 0
                        ELSE NULL
                    END as potongan_berat
                "),

                DB::raw("
                    CASE
                        WHEN fuzzy.id IS NOT NULL THEN fuzzy.berat_layak
                        WHEN detail.total_berat_bersih <= 100 THEN detail.total_berat_bersih
                        ELSE NULL
                    END as berat_layak
                ")
            )
            ->where('detail.transaksi_id', $transaksiId)
            ->orderBy('detail.urutan')
            ->get();
    }

    /**
     * Filter detail barang yang belum siap dibayar.
     * Indikator: berat_bersih <= 0, atau > 100 kg tanpa fuzzy.
     */
    public function filterBelumSiap($detailBarang)
    {
        return $detailBarang->filter(function ($detail) {
            if ((float) $detail->total_berat_bersih <= 0) {
                return true;
            }

            if ((float) $detail->total_berat_bersih <= 100) {
                return false;
            }

            return !$detail->fuzzy_id || !$detail->berat_layak;
        });
    }

    /**
     * Hitung summary dari kumpulan detail barang.
     */
    public function hitungSummaryDetailBarang($detailBarang): array
    {
        return [
            'jumlah_barang'       => $detailBarang->count(),
            'total_berat_bersih'  => $detailBarang->sum('total_berat_bersih'),
            'total_potongan_berat' => $detailBarang->sum('potongan_berat'),
            'total_berat_layak'   => $detailBarang->sum('berat_layak'),
        ];
    }

    /**
     * Hitung rincian per barang dan total keseluruhan dari input harga.
     * Mengembalikan ['rincian' => [...], 'total_berat_bersih', 'total_potongan_berat',
     *                'total_berat_layak', 'total_transaksi']
     */
    public function hitungRincianPembayaran($detailBarang, array $hargaInput): array
    {
        $rincianBarang    = [];
        $totalBeratBersih = 0;
        $totalPotonganBerat = 0;
        $totalBeratLayak  = 0;
        $totalTransaksi   = 0;

        foreach ($detailBarang as $detail) {
            $hargaPerKg = (float) $hargaInput[$detail->detail_id];
            $beratLayak = (float) ($detail->berat_layak ?? 0);
            $subtotal   = round($beratLayak * $hargaPerKg, 2);

            $rincianBarang[] = [
                'detail'      => $detail,
                'harga_per_kg' => $hargaPerKg,
                'subtotal'    => $subtotal,
            ];

            $totalBeratBersih  += (float) $detail->total_berat_bersih;
            $totalPotonganBerat += (float) $detail->potongan_berat;
            $totalBeratLayak   += (float) $detail->berat_layak;
            $totalTransaksi    += $subtotal;
        }

        return [
            'rincian'            => $rincianBarang,
            'total_berat_bersih' => $totalBeratBersih,
            'total_potongan_berat' => $totalPotonganBerat,
            'total_berat_layak'  => $totalBeratLayak,
            'total_transaksi'    => $totalTransaksi,
        ];
    }

    /**
     * Generate kode pembayaran unik format PAY-YYYYMMDD-XXXX.
     */
    public function generateKodePembayaran(): string
    {
        $tanggal       = now()->format('Ymd');
        $urutanHariIni = DB::table('pembayaran')
            ->whereDate('tanggal_bayar', now()->toDateString())
            ->count() + 1;

        return 'PAY-' . $tanggal . '-' . str_pad($urutanHariIni, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Simpan pembayaran beserta detail barang ke database dalam satu transaksi DB.
     * Sekaligus mengubah status transaksi penimbangan menjadi 'selesai'.
     */
    public function simpanPembayaran(
        object $transaksi,
        array $rincianBarang,
        string $kodePembayaran,
        float $totalBeratBersih,
        float $totalPotonganBerat,
        float $totalBeratLayak,
        float $totalTransaksi,
        float $potonganKasbon,
        float $sisaHutangSebelum,
        float $sisaHutangSetelah,
        float $totalDibayarKePelanggan,
        ?object $hutangAktif,
        string $metodePembayaran,
        ?string $catatan
    ): int {
        $pembayaranId = 0;

        DB::transaction(function () use (
            $transaksi,
            $rincianBarang,
            $kodePembayaran,
            $totalBeratBersih,
            $totalPotonganBerat,
            $totalBeratLayak,
            $totalTransaksi,
            $potonganKasbon,
            $sisaHutangSebelum,
            $sisaHutangSetelah,
            $totalDibayarKePelanggan,
            $hutangAktif,
            $metodePembayaran,
            $catatan,
            &$pembayaranId
        ) {
            $pembayaranId = DB::table('pembayaran')->insertGetId([
                'kode_pembayaran'          => $kodePembayaran,
                'transaksi_id'             => $transaksi->id,
                'pelanggan_id'             => $transaksi->pelanggan_id,
                'tanggal_bayar'            => now(),

                'total_berat_bersih'       => round($totalBeratBersih, 2),
                'total_potongan_berat'     => round($totalPotonganBerat, 2),
                'total_berat_layak'        => round($totalBeratLayak, 2),
                'total_transaksi'          => round($totalTransaksi, 2),

                'sisa_hutang_sebelum'      => $sisaHutangSebelum,
                'potongan_kasbon'          => $potonganKasbon,
                'total_dibayar_ke_pelanggan' => $totalDibayarKePelanggan,
                'sisa_hutang_setelah'      => $sisaHutangSetelah,

                'metode_pembayaran'        => $metodePembayaran,
                'status_pembayaran'        => 'dibayar',
                'kasir_id'                 => auth()->id(),
                'catatan'                  => $catatan,

                'created_at'               => now(),
                'updated_at'               => now(),
            ]);

            foreach ($rincianBarang as $row) {
                $detail = $row['detail'];

                DB::table('detail_pembayaran_barang')->insert([
                    'pembayaran_id'              => $pembayaranId,
                    'detail_transaksi_barang_id' => $detail->detail_id,
                    'fuzzy_hasil_id'             => $detail->fuzzy_id,
                    'nama_barang_snapshot'       => $detail->nama_barang,

                    'berat_bersih'               => $detail->total_berat_bersih,
                    'persentase_potongan'        => $detail->persentase_potongan,
                    'potongan_berat'             => $detail->potongan_berat,
                    'berat_layak'                => $detail->berat_layak,
                    'harga_per_kg'               => $row['harga_per_kg'],
                    'subtotal'                   => $row['subtotal'],
                    'urutan'                     => $detail->urutan,

                    'created_at'                 => now(),
                    'updated_at'                 => now(),
                ]);
            }

            // Ubah status transaksi menjadi selesai
            DB::table('transaksi_penimbangan')
                ->where('id', $transaksi->id)
                ->update([
                    'status'     => 'selesai',
                    'updated_at' => now(),
                ]);
        });

        return $pembayaranId;
    }

    /**
     * Cek apakah transaksi sudah memiliki pembayaran.
     */
    public function sudahDibayar(int $transaksiId): bool
    {
        return DB::table('pembayaran')
            ->where('transaksi_id', $transaksiId)
            ->exists();
    }
}
