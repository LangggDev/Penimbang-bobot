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

        Route::get('/transaksi', function () {
            abort_unless(auth()->user()->role === 'penimbang', 403);

            $status = request('status', 'semua');

            $query = DB::table('transaksi_penimbangan as transaksi')
                ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
                ->join('jenis_kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'jenis_kendaraan.id')
                ->leftJoin('detail_transaksi_barang as detail', 'transaksi.id', '=', 'detail.transaksi_id')
                ->select(
                    'transaksi.id',
                    'transaksi.kode_transaksi',
                    'transaksi.tanggal_transaksi',
                    'transaksi.status',
                    'pelanggan.nama_pelanggan',
                    'jenis_kendaraan.nama_kendaraan',
                    DB::raw('COUNT(detail.id) as jumlah_barang'),
                    DB::raw('COALESCE(SUM(detail.total_berat_bersih), 0) as total_berat_bersih')
                )
                ->where('transaksi.petugas_timbang_id', auth()->id())
                ->groupBy(
                    'transaksi.id',
                    'transaksi.kode_transaksi',
                    'transaksi.tanggal_transaksi',
                    'transaksi.status',
                    'pelanggan.nama_pelanggan',
                    'jenis_kendaraan.nama_kendaraan'
                )
                ->orderByDesc('transaksi.tanggal_transaksi');

            if ($status !== 'semua') {
                $query->where('transaksi.status', $status);
            }

            $transaksi = $query->paginate(8)->withQueryString();

            $summary = [
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

            return view('penimbang.transaksi.index', [
                'transaksi' => $transaksi,
                'summary' => $summary,
                'status' => $status,
            ]);
        })->name('transaksi.index');


        Route::get('/transaksi/create', function () {
            abort_unless(auth()->user()->role === 'penimbang', 403);

            $pelanggan = DB::table('pelanggan')
                ->where('status', 'aktif')
                ->orderBy('nama_pelanggan')
                ->get();

            $jenisKendaraan = DB::table('jenis_kendaraan')
                ->where('status', 'aktif')
                ->orderBy('nama_kendaraan')
                ->get();

            return view('penimbang.transaksi.create', [
                'pelanggan' => $pelanggan,
                'jenisKendaraan' => $jenisKendaraan,
            ]);
        })->name('transaksi.create');


        Route::post('/transaksi', function () {
            abort_unless(auth()->user()->role === 'penimbang', 403);

            request()->validate([
                'pelanggan_id' => ['required', 'exists:pelanggan,id'],
                'jenis_kendaraan_id' => ['required', 'exists:jenis_kendaraan,id'],
                'tanggal_transaksi' => ['required', 'date'],
                'catatan' => ['nullable', 'string'],
            ]);

            $tanggal = now()->format('Ymd');

            $urutanHariIni = DB::table('transaksi_penimbangan')
                ->whereDate('tanggal_transaksi', now()->toDateString())
                ->count() + 1;

            $kodeTransaksi = 'TRX-' . $tanggal . '-' . str_pad($urutanHariIni, 4, '0', STR_PAD_LEFT);

            DB::table('transaksi_penimbangan')->insert([
                'kode_transaksi' => $kodeTransaksi,
                'pelanggan_id' => request('pelanggan_id'),
                'jenis_kendaraan_id' => request('jenis_kendaraan_id'),
                'tanggal_transaksi' => request('tanggal_transaksi'),
                'status' => 'menunggu_qc',
                'petugas_timbang_id' => auth()->id(),
                'catatan' => request('catatan'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()
                ->route('penimbang.transaksi.index')
                ->with('success', 'Transaksi berhasil dibuat. Silakan lanjutkan input barang.');
        })->name('transaksi.store');

        Route::get('/pelanggan/{id}/timbangan-pertama', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $pelanggan = DB::table('pelanggan')
        ->where('id', $id)
        ->where('status', 'aktif')
        ->first();

    abort_if(!$pelanggan, 404);

    $jenisKendaraan = DB::table('jenis_kendaraan')
        ->where('status', 'aktif')
        ->orderBy('nama_kendaraan')
        ->get();

    $jenisKertasBekas = DB::table('jenis_kertas_bekas')
        ->where('status', 'aktif')
        ->orderBy('nama_barang')
        ->get();

    return view('penimbang.pelanggan.timbangan-pertama', [
        'pelanggan' => $pelanggan,
        'jenisKendaraan' => $jenisKendaraan,
        'jenisKertasBekas' => $jenisKertasBekas,
    ]);
})->name('pelanggan.timbangan-pertama');


Route::post('/pelanggan/{id}/timbangan-pertama', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $pelanggan = DB::table('pelanggan')
        ->where('id', $id)
        ->where('status', 'aktif')
        ->first();

    abort_if(!$pelanggan, 404);

    request()->validate([
        'jenis_kendaraan_id' => ['required', 'exists:jenis_kendaraan,id'],
        'plat_kendaraan' => ['nullable', 'string', 'max:30'],
        'tanggal_transaksi' => ['required', 'date'],
        'berat_timbang_pertama' => ['required', 'numeric', 'min:0.01'],
        'jenis_kertas_bekas_ids' => ['required', 'array', 'min:1'],
        'jenis_kertas_bekas_ids.*' => ['required', 'distinct', 'exists:jenis_kertas_bekas,id'],
        'catatan' => ['nullable', 'string'],
    ]);

    $tanggalKode = now()->format('Ymd');

    $urutanHariIni = DB::table('transaksi_penimbangan')
        ->whereDate('created_at', now()->toDateString())
        ->count() + 1;

    $kodeTransaksi = 'TRX-' . $tanggalKode . '-' . str_pad($urutanHariIni, 4, '0', STR_PAD_LEFT);

    DB::transaction(function () use ($kodeTransaksi, $pelanggan) {
        $transaksiId = DB::table('transaksi_penimbangan')->insertGetId([
            'kode_transaksi' => $kodeTransaksi,
            'pelanggan_id' => $pelanggan->id,
            'jenis_kendaraan_id' => request('jenis_kendaraan_id'),
            'plat_kendaraan' => request('plat_kendaraan'),
            'tanggal_transaksi' => \Carbon\Carbon::parse(request('tanggal_transaksi')),
            'berat_timbang_pertama' => request('berat_timbang_pertama'),
            'berat_timbang_kedua' => 0,
            'status' => 'draft_penimbangan',
            'petugas_timbang_id' => auth()->id(),
            'catatan' => request('catatan'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (request('jenis_kertas_bekas_ids') as $index => $jenisKertasBekasId) {
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

    return redirect()
        ->route('penimbang.transaksi.index')
        ->with('success', 'Timbangan pertama berhasil disimpan. Pelanggan sedang proses bongkar barang.');
        })->name('pelanggan.timbangan-pertama.store');


        Route::get('/transaksi/{id}/timbangan-kedua', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $transaksi = DB::table('transaksi_penimbangan as transaksi')
        ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
        ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
        ->select(
            'transaksi.*',
            'pelanggan.nama_pelanggan',
            'pelanggan.kode_pelanggan',
            'kendaraan.nama_kendaraan'
        )
        ->where('transaksi.id', $id)
        ->where('transaksi.petugas_timbang_id', auth()->id())
        ->first();

    abort_if(!$transaksi, 404);

    $detailBarang = DB::table('detail_transaksi_barang as detail')
        ->join('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
        ->select(
            'detail.id as detail_id',
            'detail.status_qc',
            'detail.total_berat_bersih',
            'kertas.kode_barang',
            'kertas.nama_barang'
        )
        ->where('detail.transaksi_id', $transaksi->id)
        ->orderBy('detail.urutan')
        ->get();

    $jumlahBelumQc = $detailBarang
        ->where('status_qc', 'belum_dinilai')
        ->count();

    return view('penimbang.transaksi.timbangan-kedua', [
        'transaksi' => $transaksi,
        'detailBarang' => $detailBarang,
        'jumlahBelumQc' => $jumlahBelumQc,
    ]);
    })->name('transaksi.timbangan-kedua-old');

   Route::get('/transaksi/{id}/timbangan-kedua', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $transaksi = DB::table('transaksi_penimbangan as transaksi')
        ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
        ->join('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
        ->select(
            'transaksi.*',
            'pelanggan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'kendaraan.nama_kendaraan'
        )
        ->where('transaksi.id', $id)
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

    return view('penimbang.transaksi.timbangan-kedua', [
        'transaksi' => $transaksi,
        'detailBarang' => $detailBarang,
        'detailBelumDitimbang' => $detailBelumDitimbang,
        'riwayatTimbang' => $riwayatTimbang,
        'beratTerakhir' => $beratTerakhir,
        'totalBeratBersih' => $totalBeratBersih,
    ]);
})->name('transaksi.timbangan-kedua');

    Route::post('/transaksi/{id}/timbang-bertahap', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $transaksi = DB::table('transaksi_penimbangan')
        ->where('id', $id)
        ->where('petugas_timbang_id', auth()->id())
        ->first();

    abort_if(!$transaksi, 404);

    request()->validate([
        'detail_transaksi_barang_id' => ['required', 'exists:detail_transaksi_barang,id'],
        'berat_barang_dibongkar' => ['required', 'numeric', 'min:0.01'],
        'catatan' => ['nullable', 'string'],
    ]);

    $detail = DB::table('detail_transaksi_barang')
        ->where('id', request('detail_transaksi_barang_id'))
        ->where('transaksi_id', $transaksi->id)
        ->first();

    abort_if(!$detail, 404);

    $sudahPernahDitimbang = DB::table('riwayat_penimbangan_barang')
        ->where('detail_transaksi_barang_id', $detail->id)
        ->exists();

    if ($sudahPernahDitimbang) {
        return back()
            ->withInput()
            ->withErrors([
                'detail_transaksi_barang_id' => 'Jenis kertas ini sudah pernah ditimbang bertahap.',
            ]);
    }

    $riwayatTerakhir = DB::table('riwayat_penimbangan_barang as riwayat')
        ->join('detail_transaksi_barang as detail', 'riwayat.detail_transaksi_barang_id', '=', 'detail.id')
        ->where('detail.transaksi_id', $transaksi->id)
        ->orderByDesc('riwayat.urutan_timbang')
        ->select('riwayat.*')
        ->first();

    $beratSebelumBongkar = $riwayatTerakhir
        ? (float) $riwayatTerakhir->tara
        : (float) $transaksi->berat_timbang_pertama;

    $beratBarangDibongkar = (float) request('berat_barang_dibongkar');

    if ($beratBarangDibongkar > $beratSebelumBongkar) {
        return back()
            ->withInput()
            ->withErrors([
                'berat_barang_dibongkar' => 'Berat barang yang dibongkar tidak boleh lebih besar dari berat terakhir.',
            ]);
    }

    $beratSetelahBongkar = round($beratSebelumBongkar - $beratBarangDibongkar, 2);

    $urutanTimbang = DB::table('riwayat_penimbangan_barang as riwayat')
        ->join('detail_transaksi_barang as detail', 'riwayat.detail_transaksi_barang_id', '=', 'detail.id')
        ->where('detail.transaksi_id', $transaksi->id)
        ->count() + 1;

    DB::transaction(function () use (
        $detail,
        $urutanTimbang,
        $beratSebelumBongkar,
        $beratSetelahBongkar,
        $beratBarangDibongkar
    ) {
        DB::table('riwayat_penimbangan_barang')->insert([
            'detail_transaksi_barang_id' => $detail->id,
            'urutan_timbang' => $urutanTimbang,

            // berat_kotor = berat sebelum barang dibongkar
            // tara = sisa berat setelah barang dibongkar
            // berat_bersih = berat barang yang dibongkar
            'berat_kotor' => $beratSebelumBongkar,
            'tara' => $beratSetelahBongkar,
            'berat_bersih' => $beratBarangDibongkar,

            'waktu_timbang' => now(),
            'petugas_timbang_id' => auth()->id(),
            'catatan' => request('catatan'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('detail_transaksi_barang')
            ->where('id', $detail->id)
            ->update([
                'total_berat_kotor' => $beratSebelumBongkar,
                'total_tara' => $beratSetelahBongkar,
                'total_berat_bersih' => $beratBarangDibongkar,

                'updated_at' => now(),
            ]);
    });

    return redirect()
        ->route('penimbang.transaksi.timbangan-kedua', $transaksi->id)
        ->with('success', 'Timbang bertahap berhasil disimpan.');
    })->name('transaksi.timbang-bertahap.store');

Route::post('/transaksi/{id}/selesai-penimbangan', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $transaksi = DB::table('transaksi_penimbangan')
        ->where('id', $id)
        ->where('petugas_timbang_id', auth()->id())
        ->first();

    abort_if(!$transaksi, 404);

    // Cek jika ada detail yang belum ditimbang
// Patokan: total_berat_bersih <= 0 berarti belum ditimbang
$adaDetailBelumDitimbang = DB::table('detail_transaksi_barang')
    ->where('transaksi_id', $transaksi->id)
    ->where('total_berat_bersih', '<=', 0)
    ->exists();

if ($adaDetailBelumDitimbang) {
    return back()
        ->withErrors(['selesai' => 'Semua jenis kertas harus sudah ditimbang sebelum menyelesaikan penimbangan.'])
        ->withInput();
};
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

    // Tentukan status transaksi
    if ($adaBarangMasukQc) {
        $statusBerikutnya = 'menunggu_qc';
        // Set status_qc = 'belum_dinilai' untuk barang > 100 kg
        DB::table('detail_transaksi_barang')
            ->where('transaksi_id', $transaksi->id)
            ->where('total_berat_bersih', '>', 100)
            ->update(['status_qc' => 'belum_dinilai']);
    } elseif ($adaBarangTidakMasukQc) {
        $statusBerikutnya = 'menunggu_pembayaran';
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

    // Hitung fuzzy jika ada barang masuk QC
    if ($adaBarangMasukQc) {
        app(\App\Services\FuzzyTsukamotoService::class)
            ->hitungTransaksiJikaSiap((int) $transaksi->id);
    }

    $pesan = $adaBarangMasukQc
        ? 'Penimbangan selesai. Barang dengan berat > 100 kg siap masuk QC.'
        : 'Penimbangan selesai. Semua barang siap masuk pembayaran.';

    return redirect()
        ->route('penimbang.transaksi.index')
        ->with('success', $pesan);
})->name('transaksi.selesai-penimbangan');

    Route::get('/transaksi/{id}/detail', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

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
        ->where('transaksi.id', $id)
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

    return view('penimbang.transaksi.show', [
        'transaksi' => $transaksi,
        'detailBarang' => $detailBarang,
        'riwayatTimbang' => $riwayatTimbang,
        'riwayatByDetail' => $riwayatByDetail,
        'summary' => $summary,
    ]);
    })->name('transaksi.show');

    Route::post('/transaksi/{id}/hitung-fuzzy', function ($id, \App\Services\FuzzyTsukamotoService $fuzzyService) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $transaksi = DB::table('transaksi_penimbangan')
        ->where('id', $id)
        ->where('petugas_timbang_id', auth()->id())
        ->first();

    abort_if(!$transaksi, 404);

    $detailBarang = DB::table('detail_transaksi_barang as detail')
        ->leftJoin('qc_penilaian as qc', 'qc.detail_transaksi_barang_id', '=', 'detail.id')
        ->leftJoin('fuzzy_hasil as fuzzy', 'fuzzy.detail_transaksi_barang_id', '=', 'detail.id')
        ->select(
            'detail.id',
            'detail.total_berat_bersih',
            'detail.status_qc',
            'qc.id as qc_id',
            'qc.nilai_kualitas_kertas',
            'fuzzy.id as fuzzy_id',
            'fuzzy.nilai_bobot_ketidaklayakan',
            'fuzzy.persentase_potongan',
            'fuzzy.potongan_berat',
            'fuzzy.berat_layak'
        )
        ->where('detail.transaksi_id', $transaksi->id)
        ->get();

    if ($detailBarang->count() === 0) {
        return back()->withErrors([
            'fuzzy' => 'Detail jenis kertas belum tersedia.',
        ]);
    }

    $belumSiap = $detailBarang->filter(function ($detail) {
        return $detail->total_berat_bersih <= 0
            || ! $detail->qc_id
            || $detail->nilai_kualitas_kertas <= 0
            || $detail->status_qc !== 'sudah_dinilai';
    });

    if ($belumSiap->count() > 0) {
        return back()->withErrors([
            'fuzzy' => 'Fuzzy belum bisa dihitung. Pastikan semua jenis kertas sudah ditimbang dan sudah dinilai QC.',
        ]);
    }

    $berhasil = 0;

    foreach ($detailBarang as $detail) {
        $fuzzyService->hitungDetail((int) $detail->id);
        $berhasil++;
    }
    

    return redirect()
        ->route('penimbang.transaksi.show', $transaksi->id)
        ->with('success', "Perhitungan fuzzy berhasil dilakukan untuk {$berhasil} jenis kertas.");
    })->name('transaksi.hitung-fuzzy');

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
    Route::get('/transaksi/{id}/print-antrian', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

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
        ->where('transaksi.id', $id)
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

    return view('penimbang.transaksi.print-antrian', [
        'transaksi' => $transaksi,
        'totalBeratBersih' => $totalBeratBersih,
        'nomorAntrian' => $nomorAntrian,
    ]);
})->name('transaksi.print-antrian');

    });

    Route::middleware(['auth'])
    ->prefix('qc')
    ->name('qc.')
    ->group(function () {

    Route::get('/riwayat', function () {
    abort_unless(auth()->user()->role === 'qc', 403);

    $keyword = request('q');

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

    $riwayatQc = $query->paginate(8)->withQueryString();

    return view('qc.riwayat.index', [
        'riwayatQc' => $riwayatQc,
        'keyword' => $keyword,
    ]);
})->name('riwayat.index');


Route::get('/riwayat/{qcId}/edit', function ($qcId) {
    abort_unless(auth()->user()->role === 'qc', 403);

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

    abort_if(!$qc, 404);

    return view('qc.riwayat.edit', [
        'qc' => $qc,
    ]);
})->name('riwayat.edit');


Route::put('/riwayat/{qcId}', function ($qcId) {
    abort_unless(auth()->user()->role === 'qc', 403);

    $qc = DB::table('qc_penilaian')
        ->where('id', $qcId)
        ->first();

    abort_if(!$qc, 404);

    request()->validate([
        'nilai_kualitas_kertas' => ['required', 'numeric', 'min:1', 'max:10'],
        'catatan' => ['nullable', 'string'],
    ]);

    DB::table('qc_penilaian')
        ->where('id', $qcId)
        ->update([
            'qc_user_id' => auth()->id(),
            'nilai_kualitas_kertas' => request('nilai_kualitas_kertas'),
            'catatan' => request('catatan'),
            'waktu_qc' => now(),
            'updated_at' => now(),
        ]);

        $qcTerbaru = DB::table('qc_penilaian')
    ->where('id', $qcId)
    ->first();

        if ($qcTerbaru) {
        app(\App\Services\FuzzyTsukamotoService::class)
        ->hitungDetailJikaSiap((int) $qcTerbaru->detail_transaksi_barang_id);
}

    return redirect()
        ->route('qc.riwayat.index')
        ->with('success', 'Riwayat penilaian QC berhasil diperbarui.');
        })->name('riwayat.update');

        Route::get('/penilaian/{detailId}/create', function ($detailId) {
    abort_unless(auth()->user()->role === 'qc', 403);

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

    abort_if(!$detail, 404);

    return view('qc.penilaian.create', [
        'detail' => $detail,
    ]);
})->name('penilaian.create');


Route::post('/penilaian/{detailId}', function ($detailId) {
    abort_unless(auth()->user()->role === 'qc', 403);

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

    abort_if(!$detail, 404);

    request()->validate([
        'nilai_kualitas_kertas' => ['required', 'numeric', 'min:1', 'max:10'],
        'catatan' => ['nullable', 'string'],
    ]);

    $namaKendaraan = strtoupper($detail->nama_kendaraan);

    $nilaiJenisKendaraan = match ($namaKendaraan) {
        'K1' => 1,
        'K2' => 2,
        'K3' => 3,
        default => 1,
    };

    DB::transaction(function () use ($detail, $nilaiJenisKendaraan) {
        DB::table('qc_penilaian')->updateOrInsert(
            [
                'detail_transaksi_barang_id' => $detail->detail_id,
            ],
            [
                'qc_user_id' => auth()->id(),
                'nilai_jenis_kendaraan' => $nilaiJenisKendaraan,
                'nilai_berat_kotor' => $detail->berat_timbang_pertama,
                'nilai_berat_bersih' => $detail->total_berat_bersih,
                'nilai_kualitas_kertas' => request('nilai_kualitas_kertas'),
                'catatan' => request('catatan'),
                'waktu_qc' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('detail_transaksi_barang')
            ->where('id', $detail->detail_id)
            ->update([
                'status_qc' => 'sudah_dinilai',
                'updated_at' => now(),
            ]);
    });
            app(\App\Services\FuzzyTsukamotoService::class)
            ->hitungDetailJikaSiap((int) $detailId);
            $detail = DB::table('detail_transaksi_barang')
                ->where('id', $detailId)
                ->first();

            $masihButuhQc = DB::table('detail_transaksi_barang as detail')
                ->leftJoin('fuzzy_hasil as fuzzy', 'detail.id', '=', 'fuzzy.detail_transaksi_barang_id')
                ->where('detail.transaksi_id', $detail->transaksi_id)
                ->where('detail.total_berat_bersih', '>', 100)
                ->whereNull('fuzzy.id')
                ->exists();

            if (! $masihButuhQc) {
                DB::table('transaksi_penimbangan')
                    ->where('id', $detail->transaksi_id)
                    ->update([
                        'status' => 'menunggu_pembayaran',
                        'updated_at' => now(),
                    ]);
            }

            return redirect()
                ->route('qc.penilaian.index')
                ->with('success', 'Penilaian QC berhasil disimpan.');
        })->name('penilaian.store');

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


        Route::get('/penilaian', function () {
            abort_unless(auth()->user()->role === 'qc', 403);

            $detailMenungguQc = DB::table('detail_transaksi_barang as detail')
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
                ->where('detail.total_berat_bersih', '>', 100)
                ->orderByDesc('transaksi.tanggal_transaksi')
                ->paginate(8)
                ->withQueryString();

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

            return view('qc.penilaian.index', [
                'detailMenungguQc' => $detailMenungguQc,
                'summary' => $summary,
            ]);
        })->name('penilaian.index');

        Route::get('/fuzzy', function () {
    abort_unless(auth()->user()->role === 'qc', 403);

    $keyword = request('q');

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

    $hasilFuzzy = $query->paginate(8)->withQueryString();

    return view('qc.fuzzy.index', [
        'hasilFuzzy' => $hasilFuzzy,
        'keyword' => $keyword,
    ]);
})->name('fuzzy.index');


Route::get('/fuzzy/{fuzzyId}', function ($fuzzyId) {
    abort_unless(auth()->user()->role === 'qc', 403);

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

    abort_if(!$hasil, 404);

    $perhitungan = json_decode($hasil->detail_perhitungan ?? '{}', true) ?: [];

    return view('qc.fuzzy.show', [
        'hasil' => $hasil,
        'perhitungan' => $perhitungan,
    ]);
    })->name('fuzzy.show');


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