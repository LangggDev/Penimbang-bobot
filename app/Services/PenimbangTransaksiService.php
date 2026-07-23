<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * PenimbangTransaksiService
 *
 * Menangani logika bisnis untuk petugas penimbang:
 *  - Mengambil daftar transaksi dan statistik summary.
 *  - Membuat transaksi dan menyimpan timbangan pertama.
 *  - Mengambil data pendukung (pelanggan, jenis kendaraan, jenis kertas bekas aktif).
 *  - Menyimpan timbang bertahap per item barang.
 *  - Menyelesaikan penimbangan dan menentukan status berikutnya.
 *  - Menyediakan data cetak nomor antrian.
 */
class PenimbangTransaksiService
{
    /**
     * Mengambil daftar transaksi penimbangan untuk petugas penimbang.
     */
    public function getDaftarTransaksi(string $status = 'semua')
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('transaksi_penimbangan')) {
                return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
            }

            $query = DB::table('transaksi_penimbangan as transaksi')
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

            if (\Illuminate\Support\Facades\Schema::hasTable('jenis_kendaraan')) {
                $query->leftJoin('jenis_kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'jenis_kendaraan.id')
                    ->addSelect(DB::raw("COALESCE(jenis_kendaraan.nama_kendaraan, 'K1') as nama_kendaraan"));
            }

            if ($status !== 'semua') {
                $query->where('transaksi.status', $status);
            }

            return $query->orderByDesc('transaksi.id')->paginate(8)->withQueryString();
        } catch (\Throwable $e) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
        }
    }

    /**
     * Mengambil summary jumlah transaksi berdasarkan status.
     */
    public function getSummaryTransaksi(): array
    {
        return [
            'total' => DB::table('transaksi_penimbangan')
                ->where('petugas_timbang_id', auth()->id())
                ->count(),

            'draft' => DB::table('transaksi_penimbangan')
                ->where('petugas_timbang_id', auth()->id())
                ->where('status', 'draft_penimbangan')
                ->count(),

            'menunggu_qc' => DB::table('transaksi_penimbangan')
                ->where('petugas_timbang_id', auth()->id())
                ->where('status', 'menunggu_qc')
                ->count(),

            'selesai' => DB::table('transaksi_penimbangan')
                ->where('petugas_timbang_id', auth()->id())
                ->where('status', 'selesai')
                ->count(),
        ];
    }

    /**
     * Mengambil daftar pelanggan aktif.
     */
    public function getPelangganAktif()
    {
        return DB::table('pelanggan')
            ->where('status', 'aktif')
            ->orderBy('nama_pelanggan')
            ->get();
    }

    /**
     * Mengambil daftar jenis kendaraan aktif.
     */
    public function getJenisKendaraanAktif()
    {
        return DB::table('jenis_kendaraan')
            ->where('status', 'aktif')
            ->orderBy('nama_kendaraan')
            ->get();
    }

    /**
     * Mengambil daftar jenis kertas bekas aktif.
     */
    public function getJenisKertasBekasAktif()
    {
        return DB::table('jenis_kertas_bekas')
            ->where('status', 'aktif')
            ->orderBy('nama_barang')
            ->get();
    }

    /**
     * Menyimpan transaksi baru (manual).
     */
    public function simpanTransaksi(array $data): void
    {
        $tanggal = now()->format('Ymd');
        $urutanHariIni = DB::table('transaksi_penimbangan')
            ->whereDate('tanggal_transaksi', now()->toDateString())
            ->count() + 1;

        $kodeTransaksi = 'TRX-' . $tanggal . '-' . str_pad($urutanHariIni, 4, '0', STR_PAD_LEFT);

        DB::table('transaksi_penimbangan')->insert([
            'kode_transaksi' => $kodeTransaksi,
            'pelanggan_id' => $data['pelanggan_id'],
            'jenis_kendaraan_id' => $data['jenis_kendaraan_id'],
            'tanggal_transaksi' => $data['tanggal_transaksi'],
            'status' => 'menunggu_qc', // Status awal sesuai behavior aslinya
            'petugas_timbang_id' => auth()->id(),
            'catatan' => $data['catatan'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Mengambil data pelanggan untuk input timbangan pertama.
     */
    public function getPelangganUntukTimbanganPertama(int $id)
    {
        $pelanggan = DB::table('pelanggan')
            ->where('id', $id)
            ->where('status', 'aktif')
            ->first();

        abort_if(!$pelanggan, 404);

        return $pelanggan;
    }

    /**
     * Menyimpan timbangan pertama dan detail jenis kertas bekas secara transactional.
     */
    public function simpanTimbanganPertama(int $id, array $data): void
    {
        $pelanggan = $this->getPelangganUntukTimbanganPertama($id);

        $tanggalKode = now()->format('Ymd');
        $urutanHariIni = DB::table('transaksi_penimbangan')
            ->whereDate('created_at', now()->toDateString())
            ->count() + 1;

        $kodeTransaksi = 'TRX-' . $tanggalKode . '-' . str_pad($urutanHariIni, 4, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($kodeTransaksi, $pelanggan, $data) {
            $transaksiId = DB::table('transaksi_penimbangan')->insertGetId([
                'kode_transaksi' => $kodeTransaksi,
                'pelanggan_id' => $pelanggan->id,
                'jenis_kendaraan_id' => $data['jenis_kendaraan_id'],
                'plat_kendaraan' => $data['plat_kendaraan'] ?? null,
                'tanggal_transaksi' => \Carbon\Carbon::parse($data['tanggal_transaksi']),
                'berat_timbang_pertama' => $data['berat_timbang_pertama'],
                'berat_timbang_kedua' => 0,
                'status' => 'draft_penimbangan',
                'petugas_timbang_id' => auth()->id(),
                'catatan' => $data['catatan'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($data['jenis_kertas_bekas_ids'] as $index => $jenisKertasBekasId) {
                DB::table('detail_transaksi_barang')->insert([
                    'transaksi_id' => $transaksiId,
                    'jenis_kertas_bekas_id' => $jenisKertasBekasId,
                    'keterangan_barang' => null,
                    'total_berat_kotor' => 0,
                    'total_tara' => 0,
                    'total_berat_bersih' => 0,
                    'status_qc' => 'belum_dinilai',
                    'urutan' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    /**
     * Mengambil detail data transaksi untuk halaman input timbangan kedua.
     */
    public function getDetailTimbanganKedua(int $transaksiId): array
    {
        $transaksi = DB::table('transaksi_penimbangan as transaksi')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->select(
                'transaksi.*',
                'pelanggan.kode_pelanggan',
                'pelanggan.nama_pelanggan',
                'kendaraan.nama_kendaraan'
            )
            ->where('transaksi.id', $transaksiId)
            ->where('transaksi.petugas_timbang_id', auth()->id())
            ->first();

        abort_if(!$transaksi, 404);

        $detailBarang = DB::table('detail_transaksi_barang as detail')
            ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
            ->select(
                'detail.id as detail_id',
                'detail.status_qc',
                'detail.total_berat_bersih',
                'detail.urutan',
                'kertas.kode_barang',
                'kertas.nama_barang'
            )
            ->where('detail.transaksi_id', $transaksi->id)
            ->orderBy('detail.urutan')
            ->get();

        $riwayatTimbang = DB::table('riwayat_penimbangan_barang as riwayat')
            ->join('detail_transaksi_barang as detail', 'riwayat.detail_transaksi_barang_id', '=', 'detail.id')
            ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
            ->select(
                'riwayat.id',
                'riwayat.detail_transaksi_barang_id',
                'riwayat.urutan_timbang',
                'riwayat.berat_kotor',
                'riwayat.tara',
                'riwayat.berat_bersih',
                'riwayat.waktu_timbang',
                'riwayat.catatan',
                'kertas.nama_barang',
                'kertas.kode_barang'
            )
            ->where('detail.transaksi_id', $transaksi->id)
            ->orderBy('riwayat.urutan_timbang')
            ->get();

        $detailSudahDitimbangIds = $riwayatTimbang
            ->pluck('detail_transaksi_barang_id')
            ->unique()
            ->values();

        $detailBelumDitimbang = $detailBarang
            ->whereNotIn('detail_id', $detailSudahDitimbangIds)
            ->values();

        $beratTerakhir = $riwayatTimbang->isNotEmpty()
            ? (float) $riwayatTimbang->last()->tara
            : (float) $transaksi->berat_timbang_pertama;

        $totalBeratBersih = (float) $detailBarang->sum('total_berat_bersih');

        return [
            'transaksi' => $transaksi,
            'detailBarang' => $detailBarang,
            'detailBelumDitimbang' => $detailBelumDitimbang,
            'riwayatTimbang' => $riwayatTimbang,
            'beratTerakhir' => $beratTerakhir,
            'totalBeratBersih' => $totalBeratBersih,
        ];
    }

    /**
     * Menyimpan timbang bertahap secara transactional.
     *
     * BRANCHING LOGIC berdasarkan jumlah detail barang pada transaksi:
     *
     * == MODE SINGLE ITEM (jumlah detail = 1) ==
     *  - User menginput berat kendaraan akhir / timbangan kedua.
     *  - Rumus: total_berat_bersih = berat_timbang_pertama - berat_kendaraan_akhir
     *  - Contoh: 1650 - 1180 = 470
     *  - Disimpan: berat_kotor=berat_timbang_pertama, tara=berat_kendaraan_akhir, berat_bersih=470
     *
     * == MODE MULTI ITEM (jumlah detail > 1) ==
     *  - User menginput berat bersih barang yang dibongkar.
     *  - Rumus sisa: sisa = berat_sebelumnya - berat_barang_dibongkar
     *  - Contoh: 2200-200=2000, 2000-300=1700, 1700-600=1100
     *  - Disimpan: berat_kotor=berat_sebelumnya, tara=sisa, berat_bersih=berat_barang_dibongkar
     */
    public function simpanTimbangBertahap(int $transaksiId, array $data): void
    {
        $transaksi = DB::table('transaksi_penimbangan')
            ->where('id', $transaksiId)
            ->where('petugas_timbang_id', auth()->id())
            ->first();

        abort_if(!$transaksi, 404);

        $detail = DB::table('detail_transaksi_barang')
            ->where('id', $data['detail_transaksi_barang_id'])
            ->where('transaksi_id', $transaksi->id)
            ->first();

        abort_if(!$detail, 404);

        $sudahPernahDitimbang = DB::table('riwayat_penimbangan_barang')
            ->where('detail_transaksi_barang_id', $detail->id)
            ->exists();

        if ($sudahPernahDitimbang) {
            throw ValidationException::withMessages([
                'detail_transaksi_barang_id' => 'Jenis kertas ini sudah pernah ditimbang bertahap.',
            ]);
        }

        // Cek jumlah detail barang untuk menentukan mode
        $jumlahDetail = DB::table('detail_transaksi_barang')
            ->where('transaksi_id', $transaksi->id)
            ->count();

        $isSingleItem = ($jumlahDetail === 1);

        $urutanTimbang = DB::table('riwayat_penimbangan_barang as riwayat')
            ->join('detail_transaksi_barang as detail', 'riwayat.detail_transaksi_barang_id', '=', 'detail.id')
            ->where('detail.transaksi_id', $transaksi->id)
            ->count() + 1;

        if ($isSingleItem) {
            // ---------------------------------------------------------------
            // MODE SINGLE ITEM
            // User menginput berat kendaraan akhir (timbangan kedua).
            // Rumus: berat_bersih = berat_timbang_pertama - berat_kendaraan_akhir
            // Contoh: 1650 - 1180 = 470
            // ---------------------------------------------------------------
            $beratKendaraanAkhir = (float) $data['berat_kendaraan_akhir'];
            $beratTimbangPertama = (float) $transaksi->berat_timbang_pertama;

            if ($beratKendaraanAkhir >= $beratTimbangPertama) {
                throw ValidationException::withMessages([
                    'berat_kendaraan_akhir' => 'Berat kendaraan akhir harus lebih kecil dari berat timbang pertama (' . $beratTimbangPertama . ' kg).',
                ]);
            }

            $beratBersih = round($beratTimbangPertama - $beratKendaraanAkhir, 2);

            DB::transaction(function () use (
                $detail,
                $urutanTimbang,
                $beratTimbangPertama,
                $beratKendaraanAkhir,
                $beratBersih,
                $data
            ) {
                // berat_kotor = berat_timbang_pertama
                // tara        = berat_kendaraan_akhir
                // berat_bersih = selisih
                DB::table('riwayat_penimbangan_barang')->insert([
                    'detail_transaksi_barang_id' => $detail->id,
                    'urutan_timbang'             => $urutanTimbang,
                    'berat_kotor'                => $beratTimbangPertama,
                    'tara'                       => $beratKendaraanAkhir,
                    'berat_bersih'               => $beratBersih,
                    'waktu_timbang'              => now(),
                    'petugas_timbang_id'         => auth()->id(),
                    'catatan'                    => $data['catatan'] ?? null,
                    'created_at'                 => now(),
                    'updated_at'                 => now(),
                ]);

                DB::table('detail_transaksi_barang')
                    ->where('id', $detail->id)
                    ->update([
                        'total_berat_kotor'  => $beratTimbangPertama,
                        'total_tara'         => $beratKendaraanAkhir,
                        'total_berat_bersih' => $beratBersih,
                        'updated_at'         => now(),
                    ]);
            });

        } else {
            // ---------------------------------------------------------------
            // MODE MULTI ITEM
            // User menginput berat bersih barang yang dibongkar per item.
            // Rumus sisa: sisa = berat_sebelumnya - berat_barang_dibongkar
            // Contoh: 2200-200=2000, 2000-300=1700, 1700-600=1100
            // ---------------------------------------------------------------
            $riwayatTerakhir = DB::table('riwayat_penimbangan_barang as riwayat')
                ->join('detail_transaksi_barang as detail', 'riwayat.detail_transaksi_barang_id', '=', 'detail.id')
                ->where('detail.transaksi_id', $transaksi->id)
                ->orderByDesc('riwayat.urutan_timbang')
                ->select('riwayat.*')
                ->first();

            $beratSebelumBongkar = $riwayatTerakhir
                ? (float) $riwayatTerakhir->tara
                : (float) $transaksi->berat_timbang_pertama;

            $beratBarangDibongkar = (float) $data['berat_barang_dibongkar'];

            if ($beratBarangDibongkar > $beratSebelumBongkar) {
                throw ValidationException::withMessages([
                    'berat_barang_dibongkar' => 'Berat bersih barang yang dibongkar tidak boleh lebih besar dari berat sebelumnya (' . $beratSebelumBongkar . ' kg).',
                ]);
            }

            // sisa berat setelah bongkar
            $sisaBeratSetelahBongkar = round($beratSebelumBongkar - $beratBarangDibongkar, 2);

            DB::transaction(function () use (
                $detail,
                $urutanTimbang,
                $beratSebelumBongkar,
                $sisaBeratSetelahBongkar,
                $beratBarangDibongkar,
                $data
            ) {
                DB::table('riwayat_penimbangan_barang')->insert([
                    'detail_transaksi_barang_id' => $detail->id,
                    'urutan_timbang'             => $urutanTimbang,
                    'berat_kotor'                => $beratSebelumBongkar,
                    'tara'                       => $sisaBeratSetelahBongkar,
                    'berat_bersih'               => $beratBarangDibongkar,
                    'waktu_timbang'              => now(),
                    'petugas_timbang_id'         => auth()->id(),
                    'catatan'                    => $data['catatan'] ?? null,
                    'created_at'                 => now(),
                    'updated_at'                 => now(),
                ]);

                DB::table('detail_transaksi_barang')
                    ->where('id', $detail->id)
                    ->update([
                        'total_berat_kotor'  => $beratSebelumBongkar,
                        'total_tara'         => $sisaBeratSetelahBongkar,
                        'total_berat_bersih' => $beratBarangDibongkar,
                        'updated_at'         => now(),
                    ]);
            });
        }
    }

    /**
     * Menyelesaikan penimbangan dan menentukan status berikutnya.
     * Tidak memanggil FuzzyTsukamotoService.
     */
    public function selesaiPenimbangan(int $transaksiId): string
    {
        $transaksi = DB::table('transaksi_penimbangan')
            ->where('id', $transaksiId)
            ->where('petugas_timbang_id', auth()->id())
            ->first();

        abort_if(!$transaksi, 404);

        $adaDetailBelumDitimbang = DB::table('detail_transaksi_barang')
            ->where('transaksi_id', $transaksi->id)
            ->where('total_berat_bersih', '<=', 0)
            ->exists();

        if ($adaDetailBelumDitimbang) {
            throw ValidationException::withMessages([
                'selesai' => 'Semua jenis kertas harus sudah ditimbang sebelum menyelesaikan penimbangan.',
            ]);
        }

        $riwayatTerakhir = DB::table('riwayat_penimbangan_barang as riwayat')
            ->join('detail_transaksi_barang as detail', 'riwayat.detail_transaksi_barang_id', '=', 'detail.id')
            ->where('detail.transaksi_id', $transaksi->id)
            ->orderByDesc('riwayat.urutan_timbang')
            ->select('riwayat.*')
            ->first();

        // Check apakah ada barang dengan berat > 100 kg (perlu QC)
        $adaBarangMasukQc = DB::table('detail_transaksi_barang')
            ->where('transaksi_id', $transaksi->id)
            ->where('total_berat_bersih', '>', 100)
            ->exists();

        // Check apakah ada barang dengan berat <= 100 kg (tidak perlu QC)
        $adaBarangTidakMasukQc = DB::table('detail_transaksi_barang')
            ->where('transaksi_id', $transaksi->id)
            ->where('total_berat_bersih', '<=', 100)
            ->exists();

        // Tentukan status berikutnya
        if ($adaBarangMasukQc) {
            $statusBerikutnya = 'menunggu_qc';
            DB::table('detail_transaksi_barang')
                ->where('transaksi_id', $transaksi->id)
                ->where('total_berat_bersih', '>', 100)
                ->update(['status_qc' => 'belum_dinilai']);
        } else {
            $statusBerikutnya = 'menunggu_pembayaran';
        }

        DB::transaction(function () use ($transaksi, $riwayatTerakhir, $statusBerikutnya) {
            DB::table('transaksi_penimbangan')
                ->where('id', $transaksi->id)
                ->update([
                    'berat_timbang_kedua' => $riwayatTerakhir ? $riwayatTerakhir->tara : 0,
                    'status' => $statusBerikutnya,
                    'updated_at' => now(),
                ]);
        });

        return $adaBarangMasukQc
            ? 'Penimbangan selesai. Barang dengan berat > 100 kg siap masuk QC.'
            : 'Penimbangan selesai. Semua barang siap masuk pembayaran.';
    }

    /**
     * Mengambil detail transaksi lengkap untuk halaman show.
     */
    public function getDetailTransaksi(int $transaksiId): array
    {
        $transaksi = DB::table('transaksi_penimbangan as transaksi')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->select(
                'transaksi.*',
                'pelanggan.kode_pelanggan',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_hp',
                'pelanggan.alamat',
                'kendaraan.nama_kendaraan'
            )
            ->where('transaksi.id', $transaksiId)
            ->where('transaksi.petugas_timbang_id', auth()->id())
            ->first();

        abort_if(!$transaksi, 404);

        $detailBarang = DB::table('detail_transaksi_barang as detail')
            ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
            ->leftJoin('qc_penilaian as qc', 'qc.detail_transaksi_barang_id', '=', 'detail.id')
            ->select(
                'detail.id as detail_id',
                'detail.keterangan_barang',
                'detail.total_berat_kotor',
                'detail.total_tara',
                'detail.total_berat_bersih',
                'detail.status_qc',
                'detail.urutan',
                'kertas.kode_barang',
                'kertas.nama_barang',
                'qc.id as qc_id',
                'qc.nilai_kualitas_kertas',
                'qc.catatan as catatan_qc',
                'qc.waktu_qc'
            )
            ->where('detail.transaksi_id', $transaksi->id)
            ->orderBy('detail.urutan')
            ->get();

        $riwayatTimbang = DB::table('riwayat_penimbangan_barang as riwayat')
            ->join('detail_transaksi_barang as detail', 'riwayat.detail_transaksi_barang_id', '=', 'detail.id')
            ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
            ->select(
                'riwayat.id',
                'riwayat.detail_transaksi_barang_id',
                'riwayat.urutan_timbang',
                'riwayat.berat_kotor',
                'riwayat.tara',
                'riwayat.berat_bersih',
                'riwayat.waktu_timbang',
                'riwayat.catatan',
                'kertas.kode_barang',
                'kertas.nama_barang'
            )
            ->where('detail.transaksi_id', $transaksi->id)
            ->orderBy('riwayat.urutan_timbang')
            ->get();

        $riwayatByDetail = $riwayatTimbang->groupBy('detail_transaksi_barang_id');

        $summary = [
            'jumlah_jenis' => $detailBarang->count(),
            'total_berat_bersih' => $detailBarang->sum('total_berat_bersih'),
            'sudah_qc' => $detailBarang->where('status_qc', 'sudah_dinilai')->count(),
            'belum_qc' => $detailBarang->where('status_qc', 'belum_dinilai')->count(),
            'jumlah_timbang' => $riwayatTimbang->count(),
        ];

        return [
            'transaksi' => $transaksi,
            'detailBarang' => $detailBarang,
            'riwayatTimbang' => $riwayatTimbang,
            'riwayatByDetail' => $riwayatByDetail,
            'summary' => $summary,
        ];
    }

    /**
     * Mengambil data untuk cetak nomor antrian.
     */
    public function getPrintAntrian(int $transaksiId): array
    {
        $transaksi = DB::table('transaksi_penimbangan as transaksi')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
            ->select(
                'transaksi.*',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_hp',
                'pelanggan.alamat',
                'kendaraan.nama_kendaraan'
            )
            ->where('transaksi.id', $transaksiId)
            ->first();

        abort_if(!$transaksi, 404);

        $totalBeratBersih = DB::table('detail_transaksi_barang')
            ->where('transaksi_id', $transaksi->id)
            ->sum('total_berat_bersih');

        $tanggalTransaksi = \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->toDateString();

        $urutanHariIni = DB::table('transaksi_penimbangan')
            ->whereDate('tanggal_transaksi', $tanggalTransaksi)
            ->where('id', '<=', $transaksi->id)
            ->count();

        $nomorAntrian = sprintf('%03d', $urutanHariIni);

        return [
            'transaksi' => $transaksi,
            'totalBeratBersih' => $totalBeratBersih,
            'nomorAntrian' => $nomorAntrian,
        ];
    }
}
