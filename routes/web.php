<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'qc') {
        return redirect()->route('qc.dashboard');
    }

    if ($user->role === 'penimbang') {
        return redirect()->route('penimbang.dashboard');
    }

    if ($user->role === 'kasir') {
        return redirect()->route('kasir.dashboard');
    }

    abort(403, 'Role tidak dikenali.');
})->middleware(['auth'])->name('dashboard');

Route::get('/profile', function () {
    return redirect()->route('dashboard');
})->middleware(['auth'])->name('profile.edit');


Route::middleware(['auth'])->group(function () {
    Route::view('/qc/dashboard', 'dashboard.qc')->name('qc.dashboard');
    Route::view('/penimbang/dashboard', 'dashboard.penimbang')->name('penimbang.dashboard');
    Route::view('/kasir/dashboard', 'dashboard.kasir')->name('kasir.dashboard');
});

Route::middleware(['auth'])
    ->prefix('penimbang')
    ->name('penimbang.')
    ->group(function () {

    Route::get('/pelanggan/{id}/edit', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $pelanggan = DB::table('pelanggan')->where('id', $id)->first();

    abort_if(!$pelanggan, 404);

    return view('penimbang.pelanggan.edit', [
        'pelanggan' => $pelanggan,
    ]);
})->name('pelanggan.edit');


Route::put('/pelanggan/{id}', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $pelanggan = DB::table('pelanggan')->where('id', $id)->first();

    abort_if(!$pelanggan, 404);

    request()->validate([
        'nama_pelanggan' => ['required', 'string', 'max:255'],
        'no_hp' => ['nullable', 'string', 'max:30'],
        'alamat' => ['nullable', 'string'],
        'status' => ['required', 'in:aktif,nonaktif'],
    ]);

    DB::table('pelanggan')
        ->where('id', $id)
        ->update([
            'nama_pelanggan' => request('nama_pelanggan'),
            'no_hp' => request('no_hp'),
            'alamat' => request('alamat'),
            'status' => request('status'),
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('penimbang.pelanggan.index')
        ->with('success', 'Data pelanggan berhasil diperbarui.');
})->name('pelanggan.update');


Route::delete('/pelanggan/{id}', function ($id) {
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
                'status' => 'nonaktif',
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
})->name('pelanggan.destroy');

    Route::get('/pelanggan/create', function () {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $tanggal = now()->format('Ymd');

    $urutanHariIni = DB::table('pelanggan')
        ->whereDate('created_at', now()->toDateString())
        ->count() + 1;

    $kodePelanggan = 'PLG-' . $tanggal . '-' . str_pad($urutanHariIni, 3, '0', STR_PAD_LEFT);

    return view('penimbang.pelanggan.create', [
        'kodePelanggan' => $kodePelanggan,
    ]);
})->name('pelanggan.create');


Route::post('/pelanggan', function () {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    request()->validate([
        'kode_pelanggan' => ['required', 'string', 'max:50'],
        'nama_pelanggan' => ['required', 'string', 'max:255'],
        'no_hp' => ['nullable', 'string', 'max:30'],
        'alamat' => ['nullable', 'string'],
    ]);

    DB::table('pelanggan')->insert([
        'kode_pelanggan' => request('kode_pelanggan'),
        'nama_pelanggan' => request('nama_pelanggan'),
        'no_hp' => request('no_hp'),
        'alamat' => request('alamat'),
        'status' => 'aktif',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()
        ->route('penimbang.pelanggan.index')
        ->with('success', 'Data pelanggan berhasil ditambahkan.');
})->name('pelanggan.store');
        Route::get('/pelanggan', function () {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $keyword = request('q');
    $status = request('status', 'aktif');

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
        'total' => DB::table('pelanggan')->count(),
        'aktif' => DB::table('pelanggan')->where('status', 'aktif')->count(),
        'nonaktif' => DB::table('pelanggan')->where('status', 'nonaktif')->count(),
    ];

    return view('penimbang.pelanggan.index', [
        'pelanggan' => $pelanggan,
        'summary' => $summary,
        'keyword' => $keyword,
        'status' => $status,
    ]);
})->name('pelanggan.index');

        Route::get('/transaksi', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'index'])
            ->name('transaksi.index');

        Route::get('/transaksi/create', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'create'])
            ->name('transaksi.create');

        Route::post('/transaksi', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'store'])
            ->name('transaksi.store');

        Route::get('/pelanggan/{id}/timbangan-pertama', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'timbanganPertama'])
            ->name('pelanggan.timbangan-pertama');

        Route::post('/pelanggan/{id}/timbangan-pertama', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'simpanTimbanganPertama'])
            ->name('pelanggan.timbangan-pertama.store');

        Route::get('/transaksi/{id}/timbangan-kedua', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'timbanganKedua'])
            ->name('transaksi.timbangan-kedua');

        Route::post('/transaksi/{id}/timbang-bertahap', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'simpanTimbangBertahap'])
            ->name('transaksi.timbang-bertahap.store');

        Route::post('/transaksi/{id}/selesai-penimbangan', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'selesaiPenimbangan'])
            ->name('transaksi.selesai-penimbangan');

        Route::get('/transaksi/{id}/detail', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'detail'])
            ->name('transaksi.show');

        Route::get('/dashboard', function () {
            abort_unless(auth()->user()->role === 'penimbang', 403);

            $tanggalMulai = request('tanggal_mulai', now()->toDateString());
            $tanggalSelesai = request('tanggal_selesai', now()->toDateString());

            // Total Transaksi
            $totalTransaksiHariIni = DB::table('transaksi_penimbangan')
                ->where('petugas_timbang_id', auth()->id())
                ->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$tanggalMulai, $tanggalSelesai])
                ->count();

            // Total Berat Bersih
            $totalBeratBersihHariIni = DB::table('detail_transaksi_barang as detail')
                ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
                ->where('transaksi.petugas_timbang_id', auth()->id())
                ->whereBetween(DB::raw('DATE(transaksi.tanggal_transaksi)'), [$tanggalMulai, $tanggalSelesai])
                ->sum('detail.total_berat_bersih') ?? 0;

            // Total Draft
            $totalDraft = DB::table('transaksi_penimbangan')
                ->where('petugas_timbang_id', auth()->id())
                ->where('status', 'draft_penimbangan')
                ->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$tanggalMulai, $tanggalSelesai])
                ->count();

            // Total Menunggu QC
            $totalMenungguQc = DB::table('transaksi_penimbangan')
                ->where('petugas_timbang_id', auth()->id())
                ->where('status', 'menunggu_qc')
                ->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$tanggalMulai, $tanggalSelesai])
                ->count();

            // Transaksi Terbaru (untuk periode yang dipilih)
            $transaksiTerbaru = DB::table('transaksi_penimbangan as transaksi')
                ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
                ->join('jenis_kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'jenis_kendaraan.id')
                ->select(
                    'transaksi.id',
                    'transaksi.kode_transaksi',
                    'transaksi.tanggal_transaksi',
                    'transaksi.status',
                    'pelanggan.nama_pelanggan',
                    'jenis_kendaraan.nama_kendaraan'
                )
                ->where('transaksi.petugas_timbang_id', auth()->id())
                ->whereBetween(DB::raw('DATE(transaksi.tanggal_transaksi)'), [$tanggalMulai, $tanggalSelesai])
                ->orderByDesc('transaksi.tanggal_transaksi')
                ->limit(5)
                ->get();

            return view('dashboard.penimbang', [
                'totalTransaksiHariIni' => $totalTransaksiHariIni,
                'totalBeratBersihHariIni' => $totalBeratBersihHariIni,
                'totalDraft' => $totalDraft,
                'totalMenungguQc' => $totalMenungguQc,
                'transaksiTerbaru' => $transaksiTerbaru,
                'tanggalMulai' => $tanggalMulai,
                'tanggalSelesai' => $tanggalSelesai,
            ]);
        })->name('dashboard');

        Route::get('/transaksi/{id}/print-antrian', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'printAntrian'])
            ->name('transaksi.print-antrian');

    });

    Route::middleware(['auth'])
    ->prefix('qc')
    ->name('qc.')
    ->group(function () {

    Route::get('/riwayat', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'riwayatIndex'])
        ->name('riwayat.index');


Route::get('/riwayat/{qcId}/edit', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'riwayatEdit'])
    ->name('riwayat.edit');


Route::put('/riwayat/{qcId}', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'riwayatUpdate'])
    ->name('riwayat.update');

        Route::get('/penilaian/{detailId}/create', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'create'])
            ->name('penilaian.create');


Route::post('/penilaian/{detailId}', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'store'])
    ->name('penilaian.store');

       Route::get('/dashboard', function () {
            abort_unless(auth()->user()->role === 'qc', 403);

            $tanggalMulai = request('tanggal_mulai', now()->toDateString());
            $tanggalSelesai = request('tanggal_selesai', now()->toDateString());

            $summary = [
                'menunggu' => DB::table('detail_transaksi_barang as detail')
                    ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
                    ->whereIn('transaksi.status', [
                        'menunggu_qc',
                        'proses_qc',
                    ])
                    ->where('detail.status_qc', 'belum_dinilai')
                    ->where('detail.total_berat_bersih', '>', 100)
                    ->count(),

                'sudah_dinilai' => DB::table('detail_transaksi_barang as detail')
                    ->join('qc_penilaian as qc', 'detail.id', '=', 'qc.detail_transaksi_barang_id')
                    ->where('detail.status_qc', 'sudah_dinilai')
                    ->where('detail.total_berat_bersih', '>', 100)
                    ->whereBetween(DB::raw('DATE(qc.waktu_qc)'), [$tanggalMulai, $tanggalSelesai])
                    ->count(),

                'revisi' => DB::table('detail_transaksi_barang as detail')
                    ->join('qc_penilaian as qc', 'detail.id', '=', 'qc.detail_transaksi_barang_id')
                    ->where('detail.status_qc', 'revisi')
                    ->where('detail.total_berat_bersih', '>', 100)
                    ->whereBetween(DB::raw('DATE(qc.waktu_qc)'), [$tanggalMulai, $tanggalSelesai])
                    ->count(),
            ];

            $detailTerbaru = DB::table('detail_transaksi_barang as detail')
                ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
                ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
                ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
                ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
                ->select(
                    'detail.id as detail_id',
                    'detail.status_qc',
                    'transaksi.kode_transaksi',
                    'transaksi.tanggal_transaksi',
                    'transaksi.berat_timbang_pertama',
                    'pelanggan.nama_pelanggan',
                    'kertas.nama_barang as nama_kertas',
                    'kendaraan.nama_kendaraan'
                )
                ->whereIn('transaksi.status', [
                    'menunggu_qc',
                    'proses_qc',
                ])
                ->where('detail.status_qc', 'belum_dinilai')
                ->where('detail.total_berat_bersih', '>', 100)
                ->orderByDesc('transaksi.tanggal_transaksi')
                ->limit(5)
                ->get();

            return view('dashboard.qc', [
                'summary' => $summary,
                'detailTerbaru' => $detailTerbaru,
                'tanggalMulai' => $tanggalMulai,
                'tanggalSelesai' => $tanggalSelesai,
            ]);
        })->name('dashboard');


        Route::get('/penilaian', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'index'])
            ->name('penilaian.index');

        Route::get('/fuzzy', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'fuzzyIndex'])
            ->name('fuzzy.index');


Route::get('/fuzzy/{fuzzyId}', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'fuzzyShow'])
    ->name('fuzzy.show');


    });

    Route::middleware(['auth'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

    Route::get('/pembayaran', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'index'])
        ->name('pembayaran.index');

    Route::get('/pembayaran/{transaksiId}', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'show'])
        ->name('pembayaran.show');


    Route::post('/pembayaran/{transaksiId}', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'store'])
        ->name('pembayaran.store');

    Route::get('/kasbon', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonIndex'])
        ->name('kasbon.index');


    Route::get('/kasbon/create', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonCreate'])
        ->name('kasbon.create');


    Route::post('/kasbon', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonStore'])
        ->name('kasbon.store');


    Route::get('/kasbon/{id}/edit', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonEdit'])
        ->name('kasbon.edit');


    Route::put('/kasbon/{id}', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonUpdate'])
        ->name('kasbon.update');


    Route::delete('/kasbon/{id}', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonDestroy'])
        ->name('kasbon.destroy');

    Route::get('/laporan', [\App\Http\Controllers\Kasir\KasirLaporanController::class, 'index'])
        ->name('laporan.index');

    Route::get('/dashboard', function () {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $tanggalMulai = request('tanggal_mulai', now()->toDateString());
        $tanggalSelesai = request('tanggal_selesai', now()->toDateString());

        // Total Pembayaran
        $totalPembayaran = DB::table('pembayaran')
            ->whereBetween(DB::raw('DATE(tanggal_bayar)'), [$tanggalMulai, $tanggalSelesai])
            ->count();

        // Total Dibayar Ke Pelanggan
        $totalDibayarKePelanggan = DB::table('pembayaran')
            ->whereBetween(DB::raw('DATE(tanggal_bayar)'), [$tanggalMulai, $tanggalSelesai])
            ->sum('total_dibayar_ke_pelanggan') ?? 0;

        // Total Kasbon
        $totalKasbon = DB::table('hutang_pelanggan')
            ->whereBetween(DB::raw('DATE(tanggal_hutang)'), [$tanggalMulai, $tanggalSelesai])
            ->count();

        // Total Potongan Kasbon
        $totalPotonganKasbon = DB::table('pembayaran')
            ->whereBetween(DB::raw('DATE(tanggal_bayar)'), [$tanggalMulai, $tanggalSelesai])
            ->sum('potongan_kasbon') ?? 0;

        // Pembayaran Terbaru (untuk periode yang dipilih)
        $pembayaranTerbaru = DB::table('pembayaran as bayar')
            ->join('transaksi_penimbangan as transaksi', 'bayar.transaksi_id', '=', 'transaksi.id')
            ->join('pelanggan', 'bayar.pelanggan_id', '=', 'pelanggan.id')
            ->select(
                'bayar.id',
                'bayar.kode_pembayaran',
                'bayar.tanggal_bayar',
                'bayar.total_transaksi',
                'bayar.total_dibayar_ke_pelanggan',
                'transaksi.kode_transaksi',
                'pelanggan.nama_pelanggan'
            )
            ->whereBetween(DB::raw('DATE(bayar.tanggal_bayar)'), [$tanggalMulai, $tanggalSelesai])
            ->orderByDesc('bayar.tanggal_bayar')
            ->limit(5)
            ->get();

        return view('dashboard.kasir', [
            'totalPembayaran' => $totalPembayaran,
            'totalDibayarKePelanggan' => $totalDibayarKePelanggan,
            'totalKasbon' => $totalKasbon,
            'totalPotonganKasbon' => $totalPotonganKasbon,
            'pembayaranTerbaru' => $pembayaranTerbaru,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);
    })->name('dashboard');

    Route::get('/laporan/pembayaran/{id}', [\App\Http\Controllers\Kasir\KasirLaporanController::class, 'detail'])
        ->name('laporan.detail');

    Route::get('/laporan/pembayaran/{id}/print', [\App\Http\Controllers\Kasir\KasirLaporanController::class, 'print'])
        ->name('laporan.print');

    });