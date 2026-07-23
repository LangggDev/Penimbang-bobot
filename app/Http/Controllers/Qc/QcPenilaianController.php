<?php

namespace App\Http\Controllers\Qc;

use App\Http\Controllers\Controller;
use App\Services\QcPenilaianService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * QcPenilaianController
 *
 * Controller QC bertugas:
 *  1. Menerima request dari route
 *  2. Memanggil QcPenilaianService untuk proses bisnis
 *  3. Mengirim data ke view
 *  4. Redirect setelah simpan/update dengan pesan sukses atau error
 *
 * Tidak ada query langsung di controller ini.
 * Tidak ada rumus fuzzy di controller ini.
 */
class QcPenilaianController extends Controller
{
    public function __construct(
        private QcPenilaianService $qcService
    ) {}

    // -----------------------------------------------------------------------
    // PENILAIAN
    // -----------------------------------------------------------------------

    /**
     * Menampilkan daftar barang yang menunggu penilaian QC.
     * Hanya barang dengan berat bersih > 100 kg yang muncul.
     *
     * Route: GET /qc/penilaian  (qc.penilaian.index)
     */
    public function index()
    {
        abort_unless(auth()->user()->role === 'qc', 403);

        $detailMenungguQc = $this->qcService->getDaftarMenungguQc();
        $summary = $this->qcService->getSummaryPenilaian();

        return view('qc.penilaian.index', [
            'detailMenungguQc' => $detailMenungguQc,
            'summary'          => $summary,
        ]);
    }

    /**
     * Menampilkan form penilaian QC untuk satu detail barang.
     *
     * Route: GET /qc/penilaian/{detailId}/create  (qc.penilaian.create)
     */
    public function create(int $detailId)
    {
        abort_unless(auth()->user()->role === 'qc', 403);

        $detail = $this->qcService->getDetailUntukPenilaian($detailId);

        return view('qc.penilaian.create', [
            'detail' => $detail,
        ]);
    }

    /**
     * Menyimpan penilaian QC.
     * Setelah simpan, fuzzy otomatis dijalankan oleh service.
     *
     * Route: POST /qc/penilaian/{detailId}  (qc.penilaian.store)
     */
    public function store(Request $request, int $detailId)
    {
        abort_unless(auth()->user()->role === 'qc', 403);

        $validated = $request->validate([
            'nilai_kualitas_kertas' => ['required', 'numeric', 'min:1', 'max:10'],
            'catatan'               => ['nullable', 'string'],
        ]);

        $this->qcService->simpanPenilaian($detailId, $validated);

        return redirect()
            ->route('qc.penilaian.index')
            ->with('success', 'Penilaian QC berhasil disimpan.');
    }

    // -----------------------------------------------------------------------
    // RIWAYAT PENILAIAN
    // -----------------------------------------------------------------------

    /**
     * Menampilkan daftar riwayat penilaian QC.
     *
     * Route: GET /qc/riwayat  (qc.riwayat.index)
     */
    public function riwayatIndex(Request $request)
    {
        abort_unless(auth()->user()->role === 'qc', 403);

        $keyword = $request->get('q');
        $riwayatQc = $this->qcService->getRiwayatPenilaian($keyword);

        return view('qc.riwayat.index', [
            'riwayatQc' => $riwayatQc,
            'keyword'   => $keyword,
        ]);
    }

    /**
     * Menampilkan form edit untuk riwayat penilaian QC.
     *
     * Route: GET /qc/riwayat/{qcId}/edit  (qc.riwayat.edit)
     */
    public function riwayatEdit(int $qcId)
    {
        abort_unless(auth()->user()->role === 'qc', 403);

        $qc = $this->qcService->getDetailRiwayatEdit($qcId);

        return view('qc.riwayat.edit', [
            'qc' => $qc,
        ]);
    }

    /**
     * Mengupdate penilaian QC dan menjalankan ulang fuzzy.
     *
     * Route: PUT /qc/riwayat/{qcId}  (qc.riwayat.update)
     */
    public function riwayatUpdate(Request $request, int $qcId)
    {
        abort_unless(auth()->user()->role === 'qc', 403);

        $validated = $request->validate([
            'nilai_kualitas_kertas' => ['required', 'numeric', 'min:1', 'max:10'],
            'catatan'               => ['nullable', 'string'],
        ]);

        $this->qcService->updatePenilaian($qcId, $validated);

        return redirect()
            ->route('qc.riwayat.index')
            ->with('success', 'Riwayat penilaian QC berhasil diperbarui.');
    }

    // -----------------------------------------------------------------------
    // FUZZY — Tampilan Hasil
    // -----------------------------------------------------------------------

    /**
     * Menampilkan daftar hasil fuzzy.
     * Kasir menggunakan data ini untuk melihat potongan — tidak menghitung ulang.
     *
     * Route: GET /qc/fuzzy  (qc.fuzzy.index)
     */
    public function fuzzyIndex(Request $request)
    {
        abort_unless(auth()->user()->role === 'qc', 403);

        $keyword = $request->get('q');
        $hasilFuzzy = $this->qcService->getDaftarHasilFuzzy($keyword);

        return view('qc.fuzzy.index', [
            'hasilFuzzy' => $hasilFuzzy,
            'keyword'    => $keyword,
        ]);
    }

    /**
     * Menampilkan detail lengkap satu hasil fuzzy beserta perhitungannya.
     *
     * Route: GET /qc/fuzzy/{fuzzyId}  (qc.fuzzy.show)
     */
    public function fuzzyShow(int $fuzzyId)
    {
        abort_unless(auth()->user()->role === 'qc', 403);

        $data = $this->qcService->getDetailHasilFuzzy($fuzzyId);

        return view('qc.fuzzy.show', [
            'hasil'       => $data['hasil'],
            'perhitungan' => $data['perhitungan'],
        ]);
    }

    // -----------------------------------------------------------------------
    // DASHBOARD
    // -----------------------------------------------------------------------

    /**
     * Menampilkan dashboard QC dengan ringkasan status penilaian berdasarkan filter tanggal.
     *
     * Route: GET /qc/dashboard  (qc.dashboard)
     */
    public function dashboard(Request $request)
    {
        abort_unless(auth()->user()->role === 'qc', 403);

        $tanggalMulai   = $request->get('tanggal_mulai', now()->toDateString());
        $tanggalSelesai = $request->get('tanggal_selesai', now()->toDateString());

        $summary = [
            'menunggu'      => 0,
            'sudah_dinilai' => 0,
            'revisi'        => 0,
        ];
        $detailTerbaru = collect();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('detail_transaksi_barang')) {
                if (\Illuminate\Support\Facades\Schema::hasTable('transaksi_penimbangan')) {
                    $summary['menunggu'] = DB::table('detail_transaksi_barang as detail')
                        ->join('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id')
                        ->whereIn('transaksi.status', ['menunggu_qc', 'proses_qc'])
                        ->where('detail.status_qc', 'belum_dinilai')
                        ->count();
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('penilaian_qc') || \Illuminate\Support\Facades\Schema::hasTable('qc_penilaian')) {
                    $qcTable = \Illuminate\Support\Facades\Schema::hasTable('qc_penilaian') ? 'qc_penilaian' : 'penilaian_qc';
                    $summary['sudah_dinilai'] = DB::table('detail_transaksi_barang as detail')
                        ->join("{$qcTable} as qc", 'detail.id', '=', 'qc.detail_transaksi_barang_id')
                        ->where('detail.status_qc', 'sudah_dinilai')
                        ->count();

                    $summary['revisi'] = DB::table('detail_transaksi_barang as detail')
                        ->join("{$qcTable} as qc", 'detail.id', '=', 'qc.detail_transaksi_barang_id')
                        ->where('detail.status_qc', 'revisi')
                        ->count();
                }

                $query = DB::table('detail_transaksi_barang as detail')
                    ->select('detail.*');

                if (\Illuminate\Support\Facades\Schema::hasTable('transaksi_penimbangan')) {
                    $query->leftJoin('transaksi_penimbangan as transaksi', 'detail.transaksi_id', '=', 'transaksi.id');

                    if (\Illuminate\Support\Facades\Schema::hasTable('pelanggan')) {
                        $query->leftJoin('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
                            ->addSelect('pelanggan.nama_pelanggan');
                    }
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('jenis_kertas_bekas')) {
                    $query->leftJoin('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
                        ->addSelect('kertas.nama_jenis as nama_kertas');
                }

                $detailTerbaru = $query->limit(5)->get();
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        return view('dashboard.qc', [
            'summary'        => $summary,
            'detailTerbaru'  => $detailTerbaru,
            'tanggalMulai'   => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);
    }
}
