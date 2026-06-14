<?php

namespace App\Http\Controllers\Penimbang;

use App\Http\Controllers\Controller;
use App\Services\PenimbangTransaksiService;
use Illuminate\Http\Request;

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
     */
    public function simpanTimbangBertahap(Request $request, int $id)
    {
        abort_unless(auth()->user()->role === 'penimbang', 403);

        $validated = $request->validate([
            'detail_transaksi_barang_id' => ['required', 'exists:detail_transaksi_barang,id'],
            'berat_barang_dibongkar' => ['required', 'numeric', 'min:0.01'],
            'catatan' => ['nullable', 'string'],
        ]);

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
}
