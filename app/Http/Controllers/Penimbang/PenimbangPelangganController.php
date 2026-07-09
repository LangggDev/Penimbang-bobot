<?php

namespace App\Http\Controllers\Penimbang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * PenimbangPelangganController
 *
 * Mengontrol alur request untuk manajemen data pelanggan oleh penimbang, meliputi:
 *  - Daftar pelanggan dengan filter dan paginasi.
 *  - Form tambah pelanggan baru dengan kode auto-generate.
 *  - Form edit dan update data pelanggan.
 *  - Hapus atau nonaktifkan pelanggan berdasarkan riwayat transaksi/hutang.
 */
class PenimbangPelangganController extends Controller
{
    /**
     * Menampilkan daftar pelanggan dengan filter keyword dan status, serta paginasi.
     *
     * Route: GET /penimbang/pelanggan  (penimbang.pelanggan.index)
     */
    public function index(Request $request)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $keyword = $request->input('q');
        $status  = $request->input('status', 'aktif');

        $query = DB::table('pelanggan')
            ->select('id', 'kode_pelanggan', 'nama_pelanggan', 'no_hp', 'alamat', 'status', 'created_at')
            ->whereNotExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('transaksi_penimbangan')
                    ->whereColumn('transaksi_penimbangan.pelanggan_id', 'pelanggan.id')
                    ->whereIn('transaksi_penimbangan.status', [
                        'draft_penimbangan',
                        'menunggu_qc',
                        'proses_qc',
                        'menunggu_pembayaran',
                    ]);
            })
            ->orderByDesc('created_at');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_pelanggan', 'like', "%{$keyword}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$keyword}%")
                  ->orWhere('no_hp', 'like', "%{$keyword}%")
                  ->orWhere('alamat', 'like', "%{$keyword}%");
            });
        }

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $pelanggan = $query->paginate(8)->withQueryString();

        $summary = [
            'total'    => DB::table('pelanggan')->count(),
            'aktif'    => DB::table('pelanggan')->where('status', 'aktif')->count(),
            'nonaktif' => DB::table('pelanggan')->where('status', 'nonaktif')->count(),
        ];

        return view('penimbang.pelanggan.index', [
            'pelanggan' => $pelanggan,
            'summary'   => $summary,
            'keyword'   => $keyword,
            'status'    => $status,
        ]);
    }

    /**
     * Menampilkan form tambah pelanggan baru dengan kode pelanggan auto-generate.
     *
     * Route: GET /penimbang/pelanggan/create  (penimbang.pelanggan.create)
     */
    public function create()
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $tanggal = now()->format('Ymd');

        $urutanHariIni = DB::table('pelanggan')
            ->whereDate('created_at', now()->toDateString())
            ->count() + 1;

        $kodePelanggan = 'PLG-' . $tanggal . '-' . str_pad($urutanHariIni, 3, '0', STR_PAD_LEFT);

        return view('penimbang.pelanggan.create', [
            'kodePelanggan' => $kodePelanggan,
        ]);
    }

    /**
     * Menyimpan data pelanggan baru ke database.
     *
     * Route: POST /penimbang/pelanggan  (penimbang.pelanggan.store)
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $request->validate([
            'kode_pelanggan' => ['required', 'string', 'max:50'],
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp'          => ['nullable', 'string', 'max:30'],
            'alamat'         => ['nullable', 'string'],
        ]);

        DB::table('pelanggan')->insert([
            'kode_pelanggan' => $request->input('kode_pelanggan'),
            'nama_pelanggan' => $request->input('nama_pelanggan'),
            'no_hp'          => $request->input('no_hp'),
            'alamat'         => $request->input('alamat'),
            'status'         => 'aktif',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()
            ->route('penimbang.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit data pelanggan.
     *
     * Route: GET /penimbang/pelanggan/{id}/edit  (penimbang.pelanggan.edit)
     */
    public function edit(int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $pelanggan = DB::table('pelanggan')->where('id', $id)->first();

        abort_if(!$pelanggan, 404);

        return view('penimbang.pelanggan.edit', [
            'pelanggan' => $pelanggan,
        ]);
    }

    /**
     * Memperbarui data pelanggan di database.
     *
     * Route: PUT /penimbang/pelanggan/{id}  (penimbang.pelanggan.update)
     */
    public function update(Request $request, int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $pelanggan = DB::table('pelanggan')->where('id', $id)->first();

        abort_if(!$pelanggan, 404);

        $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp'          => ['nullable', 'string', 'max:30'],
            'alamat'         => ['nullable', 'string'],
            'status'         => ['required', 'in:aktif,nonaktif'],
        ]);

        DB::table('pelanggan')
            ->where('id', $id)
            ->update([
                'nama_pelanggan' => $request->input('nama_pelanggan'),
                'no_hp'          => $request->input('no_hp'),
                'alamat'         => $request->input('alamat'),
                'status'         => $request->input('status'),
                'updated_at'     => now(),
            ]);

        return redirect()
            ->route('penimbang.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Menghapus atau menonaktifkan pelanggan.
     * Jika pelanggan memiliki riwayat transaksi atau hutang, data hanya dinonaktifkan (tidak dihapus).
     *
     * Route: DELETE /penimbang/pelanggan/{id}  (penimbang.pelanggan.destroy)
     */
    public function destroy(int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $pelanggan = DB::table('pelanggan')->where('id', $id)->first();

        abort_if(!$pelanggan, 404);

        $punyaTransaksi = DB::table('transaksi_penimbangan')
            ->where('pelanggan_id', $id)
            ->exists();

        $punyaHutang = DB::table('hutang_pelanggan')
            ->where('pelanggan_id', $id)
            ->exists();

        if ($punyaTransaksi || $punyaHutang) {
            DB::table('pelanggan')
                ->where('id', $id)
                ->update([
                    'status'     => 'nonaktif',
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('penimbang.pelanggan.index')
                ->with('success', 'Pelanggan sudah memiliki riwayat transaksi/hutang, jadi data tidak dihapus dan hanya dinonaktifkan.');
        }

        DB::table('pelanggan')->where('id', $id)->delete();

        return redirect()
            ->route('penimbang.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
