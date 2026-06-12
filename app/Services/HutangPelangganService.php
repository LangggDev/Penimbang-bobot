<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class HutangPelangganService
{
    /**
     * Ambil hutang/kasbon aktif milik pelanggan.
     */
    public function getHutangAktif(int $pelangganId): ?object
    {
        return DB::table('hutang_pelanggan')
            ->where('pelanggan_id', $pelangganId)
            ->where('status', 'belum_lunas')
            ->orderByDesc('tanggal_hutang')
            ->first();
    }

    /**
     * Cek apakah pelanggan masih punya hutang aktif.
     */
    public function punyaHutangAktif(int $pelangganId): bool
    {
        return DB::table('hutang_pelanggan')
            ->where('pelanggan_id', $pelangganId)
            ->where('status', 'belum_lunas')
            ->exists();
    }

    /**
     * Simpan riwayat pembayaran hutang dan perbarui sisa hutang pelanggan.
     * Dipanggil ketika potongan kasbon > 0 saat proses pembayaran.
     */
    public function catatPembayaranHutang(
        object $hutangAktif,
        int $pembayaranId,
        float $potonganKasbon,
        float $sisaHutangSetelah,
        string $kodePembayaran
    ): void {
        $nomorPotongan = DB::table('pembayaran_hutang')
            ->where('hutang_pelanggan_id', $hutangAktif->id)
            ->count() + 1;

        $urutanHariIni = DB::table('pembayaran_hutang')
            ->whereDate('tanggal_bayar', now()->toDateString())
            ->count() + 1;

        $kodePembayaranHutang = 'PH-' . now()->format('Ymd') . '-' . str_pad($urutanHariIni, 4, '0', STR_PAD_LEFT);

        DB::table('pembayaran_hutang')->insert([
            'kode_pembayaran_hutang' => $kodePembayaranHutang,
            'hutang_pelanggan_id'    => $hutangAktif->id,
            'pembayaran_id'          => $pembayaranId,
            'nomor_potongan'         => $nomorPotongan,
            'nominal_bayar'          => $potonganKasbon,
            'jenis_pembayaran'       => 'potongan_transaksi',
            'tanggal_bayar'          => now(),
            'kasir_id'               => auth()->id(),
            'keterangan'             => 'Potongan kasbon dari pembayaran ' . $kodePembayaran,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

        $totalTerbayarBaru = round((float) $hutangAktif->total_terbayar + $potonganKasbon, 2);
        $statusHutangBaru  = $sisaHutangSetelah <= 0 ? 'lunas' : 'belum_lunas';

        DB::table('hutang_pelanggan')
            ->where('id', $hutangAktif->id)
            ->update([
                'total_terbayar' => $totalTerbayarBaru,
                'sisa_hutang'    => $sisaHutangSetelah,
                'status'         => $statusHutangBaru,
                'updated_at'     => now(),
            ]);
    }

    // -------------------------------------------------------------------------
    // Manajemen Kasbon (CRUD hutang_pelanggan)
    // -------------------------------------------------------------------------

    /**
     * Ambil daftar kasbon dengan filter keyword dan status.
     */
    public function getDaftarKasbon(?string $keyword, string $status)
    {
        $query = DB::table('hutang_pelanggan as hutang')
            ->join('pelanggan', 'hutang.pelanggan_id', '=', 'pelanggan.id')
            ->select(
                'hutang.*',
                'pelanggan.kode_pelanggan',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_hp'
            )
            ->orderByDesc('hutang.tanggal_hutang');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('hutang.kode_hutang', 'like', "%{$keyword}%")
                    ->orWhere('pelanggan.nama_pelanggan', 'like', "%{$keyword}%")
                    ->orWhere('pelanggan.kode_pelanggan', 'like', "%{$keyword}%");
            });
        }

        if ($status !== 'semua') {
            $query->where('hutang.status', $status);
        }

        return $query->paginate(8)->withQueryString();
    }

    /**
     * Hitung summary kasbon (belum lunas, lunas, total sisa hutang).
     */
    public function getSummaryKasbon(): array
    {
        return [
            'belum_lunas'     => DB::table('hutang_pelanggan')->where('status', 'belum_lunas')->count(),
            'lunas'           => DB::table('hutang_pelanggan')->where('status', 'lunas')->count(),
            'total_sisa_hutang' => DB::table('hutang_pelanggan')->where('status', 'belum_lunas')->sum('sisa_hutang'),
        ];
    }

    /**
     * Ambil satu record kasbon beserta data pelanggan.
     */
    public function findKasbonDenganPelanggan(int $id): ?object
    {
        return DB::table('hutang_pelanggan as hutang')
            ->join('pelanggan', 'hutang.pelanggan_id', '=', 'pelanggan.id')
            ->select(
                'hutang.*',
                'pelanggan.kode_pelanggan',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_hp'
            )
            ->where('hutang.id', $id)
            ->first();
    }

    /**
     * Cek apakah kasbon sudah memiliki riwayat pembayaran.
     */
    public function sudahAdaPembayaran(int $hutangId): bool
    {
        return DB::table('pembayaran_hutang')
            ->where('hutang_pelanggan_id', $hutangId)
            ->exists();
    }

    /**
     * Generate kode hutang unik format KSB-YYYYMMDD-XXXX.
     */
    public function generateKodeHutang(): string
    {
        $tanggal  = now()->format('Ymd');
        $lastKode = DB::table('hutang_pelanggan')
            ->where('kode_hutang', 'like', 'KSB-' . $tanggal . '-%')
            ->orderByDesc('kode_hutang')
            ->value('kode_hutang');

        $urutanHariIni = 1;

        if ($lastKode) {
            $urutanHariIni = ((int) substr($lastKode, -4)) + 1;
        }

        do {
            $kodeHutang   = 'KSB-' . $tanggal . '-' . str_pad($urutanHariIni, 4, '0', STR_PAD_LEFT);
            $kodeSudahAda = DB::table('hutang_pelanggan')
                ->where('kode_hutang', $kodeHutang)
                ->exists();

            if ($kodeSudahAda) {
                $urutanHariIni++;
            }
        } while ($kodeSudahAda);

        return $kodeHutang;
    }

    /**
     * Simpan kasbon baru ke database.
     */
    public function simpanKasbon(string $kodeHutang, array $data): void
    {
        DB::table('hutang_pelanggan')->insert([
            'kode_hutang'    => $kodeHutang,
            'pelanggan_id'   => $data['pelanggan_id'],
            'tanggal_hutang' => $data['tanggal_hutang'],
            'total_hutang'   => $data['total_hutang'],
            'total_terbayar' => 0,
            'sisa_hutang'    => $data['total_hutang'],
            'status'         => 'belum_lunas',
            'keterangan'     => $data['keterangan'] ?? null,
            'created_by'     => auth()->id(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    /**
     * Update kasbon. Jika sudah ada pembayaran, hanya keterangan yang boleh diubah.
     */
    public function updateKasbon(object $hutang, bool $sudahAdaPembayaran, array $data): void
    {
        if ($sudahAdaPembayaran) {
            DB::table('hutang_pelanggan')
                ->where('id', $hutang->id)
                ->update([
                    'keterangan' => $data['keterangan'] ?? null,
                    'updated_at' => now(),
                ]);
            return;
        }

        $totalHutang = (float) $data['total_hutang'];

        DB::table('hutang_pelanggan')
            ->where('id', $hutang->id)
            ->update([
                'tanggal_hutang' => $data['tanggal_hutang'],
                'total_hutang'   => $totalHutang,
                'total_terbayar' => 0,
                'sisa_hutang'    => $totalHutang,
                'status'         => $totalHutang <= 0 ? 'lunas' : 'belum_lunas',
                'keterangan'     => $data['keterangan'] ?? null,
                'updated_at'     => now(),
            ]);
    }

    /**
     * Hapus kasbon. Hanya bisa dihapus jika belum ada riwayat pembayaran.
     */
    public function hapusKasbon(int $hutangId): void
    {
        DB::table('hutang_pelanggan')
            ->where('id', $hutangId)
            ->delete();
    }
}
