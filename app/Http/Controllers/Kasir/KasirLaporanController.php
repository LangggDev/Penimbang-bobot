<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Services\LaporanKasirService;
use Illuminate\Http\Request;

class KasirLaporanController extends Controller
{
    public function __construct(
        private LaporanKasirService $laporanService
    ) {}

    /**
     * Halaman index laporan pembayaran kasir.
     */
    public function index(Request $request)
    {
        abort_unless(strtolower(auth()->user()->role ?? '') === 'kasir', 403);

        $tanggalAwal  = $request->input('tanggal_awal', now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', now()->toDateString());

        $pembayaran = $this->laporanService->getPembayaran($tanggalAwal, $tanggalAkhir);
        $summary    = $this->laporanService->getSummaryLaporan($tanggalAwal, $tanggalAkhir);

        return view('kasir.laporan.index', [
            'pembayaran'   => $pembayaran,
            'summary'      => $summary,
            'tanggalAwal'  => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
        ]);
    }

    /**
     * Halaman detail pembayaran.
     */
    public function detail(int $id)
    {
        abort_unless(strtolower(auth()->user()->role ?? '') === 'kasir', 403);

        $pembayaran  = $this->laporanService->getDetailPembayaran($id);

        abort_if(!$pembayaran, 404);

        $detailBarang = $this->laporanService->getDetailBarangPembayaran($pembayaran->id);

        return view('kasir.laporan.detail', [
            'pembayaran'   => $pembayaran,
            'detailBarang' => $detailBarang,
        ]);
    }

    /**
     * Halaman print bukti pembayaran.
     */
    public function print(int $id)
    {
        abort_unless(strtolower(auth()->user()->role ?? '') === 'kasir', 403);

        $pembayaran = $this->laporanService->getDetailPembayaran($id);

        abort_if(!$pembayaran, 404);

        $detailBarang = $this->laporanService->getDetailBarangPembayaran($pembayaran->id);

        return view('kasir.laporan.print', [
            'pembayaran'   => $pembayaran,
            'detailBarang' => $detailBarang,
        ]);
    }
}
