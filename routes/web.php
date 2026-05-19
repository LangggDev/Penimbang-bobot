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
    })->name('transaksi.timbangan-kedua');

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
})->name('transaksi.timbangan-kedua');


Route::put('/transaksi/{id}/timbangan-kedua', function ($id) {
    abort_unless(auth()->user()->role === 'penimbang', 403);

    $transaksi = DB::table('transaksi_penimbangan')
        ->where('id', $id)
        ->where('petugas_timbang_id', auth()->id())
        ->first();

    abort_if(!$transaksi, 404);

    $detailBarang = DB::table('detail_transaksi_barang')
        ->where('transaksi_id', $transaksi->id)
        ->get();

    $jumlahBelumQc = $detailBarang
        ->where('status_qc', 'belum_dinilai')
        ->count();

    if ($jumlahBelumQc > 0) {
        return back()
            ->withInput()
            ->withErrors([
                'qc' => 'Timbangan kedua belum bisa disimpan karena masih ada jenis kertas yang belum dinilai QC.',
            ]);
    }

    request()->validate([
        'berat_timbang_kedua' => ['required', 'numeric', 'min:0'],
        'berat_bersih_detail' => ['required', 'array'],
        'berat_bersih_detail.*' => ['required', 'numeric', 'min:0'],
    ]);

    $beratTimbangPertama = (float) $transaksi->berat_timbang_pertama;
    $beratTimbangKedua = (float) request('berat_timbang_kedua');

    if ($beratTimbangKedua >= $beratTimbangPertama) {
        return back()
            ->withInput()
            ->withErrors([
                'berat_timbang_kedua' => 'Berat timbangan kedua harus lebih kecil dari berat timbangan pertama.',
            ]);
    }

    $totalBeratBersihTransaksi = round($beratTimbangPertama - $beratTimbangKedua, 2);

    $inputDetail = request('berat_bersih_detail', []);

    $detailIds = $detailBarang
        ->pluck('id')
        ->map(fn ($id) => (string) $id)
        ->toArray();

    foreach ($inputDetail as $detailId => $berat) {
        if (! in_array((string) $detailId, $detailIds, true)) {
            abort(403);
        }
    }

    $totalInputDetail = round(array_sum(array_map('floatval', $inputDetail)), 2);

    if (abs($totalInputDetail - $totalBeratBersihTransaksi) > 0.05) {
        return back()
            ->withInput()
            ->withErrors([
                'berat_bersih_detail' => 'Total berat bersih per jenis kertas harus sama dengan total berat bersih transaksi. Total transaksi: '
                    . number_format($totalBeratBersihTransaksi, 2, ',', '.')
                    . ' kg, total input: '
                    . number_format($totalInputDetail, 2, ',', '.')
                    . ' kg.',
            ]);
    }

    DB::transaction(function () use ($transaksi, $beratTimbangKedua, $inputDetail) {
        DB::table('transaksi_penimbangan')
            ->where('id', $transaksi->id)
            ->update([
                'berat_timbang_kedua' => $beratTimbangKedua,
                'status' => 'menunggu_pembayaran',
                'updated_at' => now(),
            ]);

        foreach ($inputDetail as $detailId => $beratBersih) {
            DB::table('detail_transaksi_barang')
                ->where('id', $detailId)
                ->where('transaksi_id', $transaksi->id)
                ->update([
                    'total_berat_bersih' => $beratBersih,
                    'updated_at' => now(),
                ]);

            DB::table('qc_penilaian')
                ->where('detail_transaksi_barang_id', $detailId)
                ->update([
                    'nilai_berat_bersih' => $beratBersih,
                    'updated_at' => now(),
                ]);
        }
    });

    return redirect()
        ->route('penimbang.transaksi.index')
        ->with('success', 'Timbangan kedua berhasil disimpan. Transaksi masuk ke tahap menunggu pembayaran.');
    })->name('transaksi.timbangan-kedua.update');


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

    return redirect()
        ->route('qc.penilaian.index')
        ->with('success', 'Penilaian QC berhasil disimpan.');
})->name('penilaian.store');

       Route::get('/dashboard', function () {
            abort_unless(auth()->user()->role === 'qc', 403);

            $summary = [
                'menunggu' => DB::table('detail_transaksi_barang as detail')
                    ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
                    ->whereIn('transaksi.status', [
                        'draft_penimbangan',
                        'menunggu_qc',
                        'proses_qc',
                    ])
                    ->where('detail.status_qc', 'belum_dinilai')
                    ->count(),

                'sudah_dinilai' => DB::table('detail_transaksi_barang')
                    ->where('status_qc', 'sudah_dinilai')
                    ->count(),

                'revisi' => DB::table('detail_transaksi_barang')
                    ->where('status_qc', 'revisi')
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
                    'draft_penimbangan',
                    'menunggu_qc',
                    'proses_qc',
                ])
                ->where('detail.status_qc', 'belum_dinilai')
                ->orderByDesc('transaksi.tanggal_transaksi')
                ->limit(5)
                ->get();

            return view('dashboard.qc', [
                'summary' => $summary,
                'detailTerbaru' => $detailTerbaru,
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
                    'kendaraan.nama_kendaraan'
                )
                ->whereIn('transaksi.status', [
                    'draft_penimbangan',
                    'menunggu_qc',
                    'proses_qc',
                ])
                ->where('detail.status_qc', 'belum_dinilai')
                ->orderByDesc('transaksi.tanggal_transaksi')
                ->paginate(8)
                ->withQueryString();

            $summary = [
                'menunggu' => DB::table('detail_transaksi_barang as detail')
                    ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
                    ->whereIn('transaksi.status', [
                        'draft_penimbangan',
                        'menunggu_qc',
                        'proses_qc',
                    ])
                    ->where('detail.status_qc', 'belum_dinilai')
                    ->count(),

                'sudah_dinilai' => DB::table('detail_transaksi_barang')
                    ->where('status_qc', 'sudah_dinilai')
                    ->count(),

                'revisi' => DB::table('detail_transaksi_barang')
                    ->where('status_qc', 'revisi')
                    ->count(),
            ];

            return view('qc.penilaian.index', [
                'detailMenungguQc' => $detailMenungguQc,
                'summary' => $summary,
            ]);
        })->name('penilaian.index');

    });