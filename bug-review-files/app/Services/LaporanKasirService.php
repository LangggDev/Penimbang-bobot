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
        return DB::table('pembayaran')
            ->join('transaksi_penimbangan as transaksi', 'pembayaran.transaksi_id', '=', 'transaksi.id')
            ->join('pelanggan', 'pembayaran.pelanggan_id', '=', 'pelanggan.id')
            ->leftJoin('users as kasir', 'pembayaran.kasir_id', '=', 'kasir.id')
            ->select(
                'pembayaran.*',
                'transaksi.kode_transaksi',
                'pelanggan.kode_pelanggan',
                'pelanggan.nama_pelanggan',
                'kasir.name as nama_kasir'
            )
            ->whereDate('pembayaran.tanggal_bayar', '>=', $tanggalAwal)
            ->whereDate('pembayaran.tanggal_bayar', '<=', $tanggalAkhir)
            ->orderByDesc('pembayaran.tanggal_bayar')
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Ambil summary laporan pembayaran berdasarkan periode.
     */
    public function getSummaryLaporan(string $tanggalAwal, string $tanggalAkhir)
    {
        return DB::table('pembayaran')
            ->whereDate('tanggal_bayar', '>=', $tanggalAwal)
            ->whereDate('tanggal_bayar', '<=', $tanggalAkhir)
            ->selectRaw('
                COUNT(*) as total_pembayaran,
                COALESCE(SUM(total_berat_bersih), 0) as total_berat_bersih,
                COALESCE(SUM(total_potongan_berat), 0) as total_potongan_berat,
                COALESCE(SUM(total_berat_layak), 0) as total_berat_layak,
                COALESCE(SUM(total_transaksi), 0) as total_transaksi,
                COALESCE(SUM(potongan_kasbon), 0) as total_potongan_kasbon,
                COALESCE(SUM(total_dibayar_ke_pelanggan), 0) as total_dibayar_ke_pelanggan
            ')
            ->first();
    }

    /**
     * Ambil detail satu pembayaran berdasarkan ID.
     */
    public function getDetailPembayaran(int $id)
    {
        return DB::table('pembayaran')
            ->join('transaksi_penimbangan as transaksi', 'pembayaran.transaksi_id', '=', 'transaksi.id')
            ->join('pelanggan', 'pembayaran.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->select(
                'pembayaran.*',
                'pembayaran.id as pembayaran_id',
                'transaksi.kode_transaksi',
                'transaksi.tanggal_transaksi',
                'transaksi.plat_kendaraan',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_hp',
                'pelanggan.alamat',
                'kendaraan.nama_kendaraan'
            )
            ->where('pembayaran.id', $id)
            ->first();
    }

    /**
     * Ambil detail barang dari suatu pembayaran.
     */
    public function getDetailBarangPembayaran(int $pembayaranId)
    {
        return DB::table('detail_pembayaran_barang as detail_bayar')
            ->join('detail_transaksi_barang as detail_transaksi', 'detail_bayar.detail_transaksi_barang_id', '=', 'detail_transaksi.id')
            ->join('jenis_kertas_bekas as barang', 'detail_transaksi.jenis_kertas_bekas_id', '=', 'barang.id')
            ->select(
                'barang.nama_barang',
                'detail_bayar.berat_bersih',
                'detail_bayar.persentase_potongan',
                'detail_bayar.potongan_berat',
                'detail_bayar.berat_layak',
                'detail_bayar.harga_per_kg',
                'detail_bayar.subtotal'
            )
            ->where('detail_bayar.pembayaran_id', $pembayaranId)
            ->orderBy('detail_transaksi.urutan')
            ->get();
    }
}
