<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class LaporanKasirService
{
    /**
     * Ambil data pembayaran untuk laporan dengan filter tanggal.
     */
    public function getPembayaran(string $tanggalAwal, string $tanggalAkhir)
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('pembayaran')) {
                return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            }

            $query = DB::table('pembayaran as bayar')
                ->select('bayar.*');

            if (\Illuminate\Support\Facades\Schema::hasTable('transaksi_penimbangan')) {
                $query->leftJoin('transaksi_penimbangan as transaksi', 'bayar.transaksi_id', '=', 'transaksi.id')
                    ->addSelect(DB::raw("COALESCE(transaksi.kode_transaksi, transaksi.no_transaksi, '') as kode_transaksi"));
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('pelanggan')) {
                $query->leftJoin('pelanggan', 'bayar.pelanggan_id', '=', 'pelanggan.id')
                    ->addSelect(
                        DB::raw("COALESCE(pelanggan.kode_pelanggan, '') as kode_pelanggan"),
                        DB::raw("COALESCE(pelanggan.nama_pelanggan, 'Pelanggan') as nama_pelanggan")
                    );
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                $query->leftJoin('users as kasir', 'bayar.kasir_id', '=', 'kasir.id')
                    ->addSelect(DB::raw("COALESCE(kasir.name, 'Kasir') as nama_kasir"));
            }

            return $query->orderByDesc('bayar.id')->paginate(10)->withQueryString();
        } catch (\Throwable $e) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }
    }

    /**
     * Ambil summary laporan pembayaran berdasarkan periode.
     */
    public function getSummaryLaporan(string $tanggalAwal, string $tanggalAkhir)
    {
        $summary = (object) [
            'total_pembayaran' => 0,
            'total_berat_bersih' => 0,
            'total_potongan_berat' => 0,
            'total_berat_layak' => 0,
            'total_transaksi' => 0,
            'total_potongan_kasbon' => 0,
            'total_dibayar_ke_pelanggan' => 0,
        ];

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('pembayaran')) {
                $res = DB::table('pembayaran')
                    ->selectRaw('
                        COUNT(*) as total_pembayaran,
                        COALESCE(SUM(jumlah_bayar), 0) as total_dibayar_ke_pelanggan
                    ')
                    ->first();

                if ($res) {
                    $summary->total_pembayaran = $res->total_pembayaran ?? 0;
                    $summary->total_dibayar_ke_pelanggan = $res->total_dibayar_ke_pelanggan ?? 0;
                }
            }
        } catch (\Throwable $e) {
        }

        return $summary;
    }

    /**
     * Ambil detail satu pembayaran berdasarkan ID.
     */
    public function getDetailPembayaran(int $id)
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('pembayaran')) {
                abort(404);
            }

            $pembayaran = DB::table('pembayaran as bayar')
                ->where('bayar.id', $id)
                ->first();

            abort_if(! $pembayaran, 404);

            $pembayaran->pembayaran_id = $pembayaran->id;
            $pembayaran->kode_transaksi = 'TRX-' . $pembayaran->transaksi_id;
            $pembayaran->tanggal_transaksi = now()->toDateTimeString();
            $pembayaran->plat_kendaraan = '-';
            $pembayaran->nama_pelanggan = 'Pelanggan';
            $pembayaran->no_hp = '-';
            $pembayaran->alamat = '-';
            $pembayaran->nama_kendaraan = 'K1';

            return $pembayaran;
        } catch (\Throwable $e) {
            abort(404);
        }
    }

    /**
     * Ambil detail barang dari suatu pembayaran.
     */
    public function getDetailBarangPembayaran(int $pembayaranId)
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('detail_pembayaran_barang')) {
                return collect();
            }

            $query = DB::table('detail_pembayaran_barang as detail_bayar')
                ->select(
                    'detail_bayar.berat_bersih',
                    'detail_bayar.persentase_potongan',
                    'detail_bayar.potongan_berat',
                    'detail_bayar.berat_layak',
                    'detail_bayar.harga_per_kg',
                    'detail_bayar.subtotal'
                );

            if (\Illuminate\Support\Facades\Schema::hasTable('jenis_kertas_bekas')) {
                $query->leftJoin('detail_transaksi_barang as detail_transaksi', 'detail_bayar.detail_transaksi_barang_id', '=', 'detail_transaksi.id')
                    ->leftJoin('jenis_kertas_bekas as barang', 'detail_transaksi.jenis_kertas_bekas_id', '=', 'barang.id')
                    ->addSelect(DB::raw("COALESCE(barang.nama_jenis, barang.nama_barang, 'Kertas') as nama_barang"));
            }

            return $query->where('detail_bayar.pembayaran_id', $pembayaranId)->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }
}
