<?php

namespace App\Http\Controllers\Penimbang;

use App\Http\Controllers\Controller;
use App\Services\PenimbangTransaksiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * PenimbangTransaksiController
 *
 * Mengontrol alur request untuk penimbangan, termasuk:
 *  - Form timbangan pertama & kedua.
 *  - Input timbang bertahap.
 *  - Penyelesaian timbang dan print antrian.
 */
class PenimbangTransaksiController extends Controller
{
    public function __construct(
        private PenimbangTransaksiService $transaksiService
    ) {}

    /**
     * Menampilkan daftar transaksi penimbangan.
     */
    public function index(Request $request)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $status = $request->get('status', 'semua');
        $transaksi = $this->transaksiService->getDaftarTransaksi($status);
        $summary = $this->transaksiService->getSummaryTransaksi();

        return view('penimbang.transaksi.index', [
            'transaksi' => $transaksi,
            'summary' => $summary,
            'status' => $status,
        ]);
    }

    /**
     * Menampilkan form pembuatan transaksi manual.
     */
    public function create()
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $pelanggan = $this->transaksiService->getPelangganAktif();
        $jenisKendaraan = $this->transaksiService->getJenisKendaraanAktif();

        return view('penimbang.transaksi.create', [
            'pelanggan' => $pelanggan,
            'jenisKendaraan' => $jenisKendaraan,
        ]);
    }

    /**
     * Menyimpan transaksi baru manual.
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $validated = $request->validate([
            'pelanggan_id' => ['required', 'exists:pelanggan,id'],
            'jenis_kendaraan_id' => ['required', 'exists:jenis_kendaraan,id'],
            'tanggal_transaksi' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
        ]);

        $this->transaksiService->simpanTransaksi($validated);

        return redirect()
            ->route('penimbang.transaksi.index')
            ->with('success', 'Transaksi berhasil dibuat. Silakan lanjutkan input barang.');
    }

    /**
     * Menampilkan form input timbangan pertama untuk pelanggan tertentu.
     */
    public function timbanganPertama(int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $pelanggan = $this->transaksiService->getPelangganUntukTimbanganPertama($id);
        $jenisKendaraan = $this->transaksiService->getJenisKendaraanAktif();
        $jenisKertasBekas = $this->transaksiService->getJenisKertasBekasAktif();

        return view('penimbang.pelanggan.timbangan-pertama', [
            'pelanggan' => $pelanggan,
            'jenisKendaraan' => $jenisKendaraan,
            'jenisKertasBekas' => $jenisKertasBekas,
        ]);
    }

    /**
     * Menyimpan data timbangan pertama.
     */
    public function simpanTimbanganPertama(Request $request, int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $validated = $request->validate([
            'jenis_kendaraan_id' => ['required', 'exists:jenis_kendaraan,id'],
            'plat_kendaraan' => ['nullable', 'string', 'max:30'],
            'tanggal_transaksi' => ['required', 'date'],
            'berat_timbang_pertama' => ['required', 'numeric', 'min:0.01'],
            'jenis_kertas_bekas_ids' => ['required', 'array', 'min:1'],
            'jenis_kertas_bekas_ids.*' => ['required', 'distinct', 'exists:jenis_kertas_bekas,id'],
            'catatan' => ['nullable', 'string'],
        ]);

        $this->transaksiService->simpanTimbanganPertama($id, $validated);

        return redirect()
            ->route('penimbang.transaksi.index')
            ->with('success', 'Timbangan pertama berhasil disimpan. Pelanggan sedang proses bongkar barang.');
    }

    /**
     * Menampilkan form timbangan kedua / timbang bertahap.
     */
    public function timbanganKedua(int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $data = $this->transaksiService->getDetailTimbanganKedua($id);

        return view('penimbang.transaksi.timbangan-kedua', $data);
    }

    /**
     * Menyimpan timbang bertahap per jenis kertas bekas.
     *
     * Mode ditentukan dari jumlah detail barang transaksi:
     *  - Single item (1 detail): validasi berat_kendaraan_akhir
     *  - Multi item (>1 detail): validasi berat_barang_dibongkar
     */
    public function simpanTimbangBertahap(Request $request, int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $dataTimbangan = $this->transaksiService->getDetailTimbanganKedua($id);
        $isSingleItem  = $dataTimbangan['detailBarang']->count() === 1;
        $beratSebelumnya = $dataTimbangan['beratTerakhir'];
        $beratTimbangPertama = (float) $dataTimbangan['transaksi']->berat_timbang_pertama;

        if ($isSingleItem) {
            $validated = $request->validate([
                'detail_transaksi_barang_id' => ['required', 'exists:detail_transaksi_barang,id'],
                'berat_kendaraan_akhir' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    function ($attribute, $value, $fail) use ($beratTimbangPertama) {
                        if ($value >= $beratTimbangPertama) {
                            $fail("Berat kendaraan akhir harus lebih kecil dari berat timbang pertama ({$beratTimbangPertama} kg).");
                        }
                    },
                ],
                'catatan' => ['nullable', 'string'],
            ]);
        } else {
            $validated = $request->validate([
                'detail_transaksi_barang_id' => ['required', 'exists:detail_transaksi_barang,id'],
                'berat_barang_dibongkar' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    function ($attribute, $value, $fail) use ($beratSebelumnya) {
                        if ($value > $beratSebelumnya) {
                            $fail("Berat bersih barang yang dibongkar tidak boleh lebih besar dari berat sebelumnya ({$beratSebelumnya} kg).");
                        }
                    },
                ],
                'catatan' => ['nullable', 'string'],
            ]);
        }

        $this->transaksiService->simpanTimbangBertahap($id, $validated);

        return redirect()
            ->route('penimbang.transaksi.timbangan-kedua', $id)
            ->with('success', 'Timbang bertahap berhasil disimpan.');
    }

    /**
     * Menyelesaikan seluruh proses penimbangan.
     */
    public function selesaiPenimbangan(int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $pesan = $this->transaksiService->selesaiPenimbangan($id);

        return redirect()
            ->route('penimbang.transaksi.index')
            ->with('success', $pesan);
    }

    /**
     * Menampilkan detail lengkap transaksi penimbangan.
     */
    public function detail(int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $data = $this->transaksiService->getDetailTransaksi($id);

        return view('penimbang.transaksi.show', $data);
    }

    /**
     * Mencetak nomor antrian.
     */
    public function printAntrian(int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $data = $this->transaksiService->getPrintAntrian($id);

        return view('penimbang.transaksi.print-antrian', $data);
    }

    /**
     * Menampilkan dashboard penimbang dengan statistik transaksi berdasarkan filter tanggal.
     *
     * Route: GET /penimbang/dashboard  (penimbang.dashboard)
     */
    public function dashboard(Request $request)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $tanggalMulai   = $request->get('tanggal_mulai', now()->toDateString());
        $tanggalSelesai = $request->get('tanggal_selesai', now()->toDateString());

        $totalTransaksiHariIni = 0;
        $totalBeratBersihHariIni = 0;
        $totalDraft = 0;
        $totalMenungguQc = 0;
        $transaksiTerbaru = collect();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('transaksi_penimbangan')) {
                $totalTransaksiHariIni = DB::table('transaksi_penimbangan')
                    ->where('petugas_timbang_id', auth()->id())
                    ->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$tanggalMulai, $tanggalSelesai])
                    ->count();

                $totalDraft = DB::table('transaksi_penimbangan')
                    ->where('petugas_timbang_id', auth()->id())
                    ->where('status', 'draft_penimbangan')
                    ->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$tanggalMulai, $tanggalSelesai])
                    ->count();

                $totalMenungguQc = DB::table('transaksi_penimbangan')
                    ->where('petugas_timbang_id', auth()->id())
                    ->where('status', 'menunggu_qc')
                    ->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$tanggalMulai, $tanggalSelesai])
                    ->count();

                if (\Illuminate\Support\Facades\Schema::hasTable('detail_transaksi_barang')) {
                    $totalBeratBersihHariIni = DB::table('detail_transaksi_barang as detail')
                        ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
                        ->where('transaksi.petugas_timbang_id', auth()->id())
                        ->whereBetween(DB::raw('DATE(transaksi.tanggal_transaksi)'), [$tanggalMulai, $tanggalSelesai])
                        ->sum('detail.total_berat_bersih') ?? 0;
                }

                $query = DB::table('transaksi_penimbangan as transaksi')
                    ->select('transaksi.*');

                if (\Illuminate\Support\Facades\Schema::hasTable('pelanggan')) {
                    $query->leftJoin('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
                        ->addSelect('pelanggan.nama_pelanggan');
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('jenis_kendaraan')) {
                    $query->leftJoin('jenis_kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'jenis_kendaraan.id')
                        ->addSelect('jenis_kendaraan.nama_kendaraan');
                }

                $transaksiTerbaru = $query->where('transaksi.petugas_timbang_id', auth()->id())
                    ->whereBetween(DB::raw('DATE(transaksi.tanggal_transaksi)'), [$tanggalMulai, $tanggalSelesai])
                    ->orderByDesc('transaksi.tanggal_transaksi')
                    ->limit(5)
                    ->get();
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        return view('dashboard.penimbang', [
            'totalTransaksiHariIni'   => $totalTransaksiHariIni,
            'totalBeratBersihHariIni' => $totalBeratBersihHariIni,
            'totalDraft'              => $totalDraft,
            'totalMenungguQc'         => $totalMenungguQc,
            'transaksiTerbaru'        => $transaksiTerbaru,
            'tanggalMulai'            => $tanggalMulai,
            'tanggalSelesai'          => $tanggalSelesai,
        ]);
    }
}
