<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Services\HutangPelangganService;
use App\Services\PembayaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirPembayaranController extends Controller
{
    public function __construct(
        private PembayaranService $pembayaranService,
        private HutangPelangganService $hutangService
    ) {}

    // =========================================================================
    // PEMBAYARAN
    // =========================================================================

    /**
     * Daftar transaksi yang siap dibayar.
     */
    public function index(Request $request)
    {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $keyword = $request->input('q');

        $query   = $this->pembayaranService->getDaftarTransaksiSiapBayar($keyword);
        $summary = $this->pembayaranService->getSummaryIndex(clone $query);

        $transaksi = $query->paginate(8)->withQueryString();

        return view('kasir.pembayaran.index', [
            'transaksi' => $transaksi,
            'summary'   => $summary,
            'keyword'   => $keyword,
        ]);
    }

    /**
     * Form pembayaran untuk transaksi tertentu.
     */
    public function show(int $transaksiId)
    {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $transaksi = $this->pembayaranService->getTransaksiUntukPembayaran($transaksiId);

        abort_if(!$transaksi, 404);

        if ($transaksi->pembayaran_id) {
            return redirect()
                ->route('kasir.pembayaran.index')
                ->withErrors(['pembayaran' => 'Transaksi ini sudah memiliki data pembayaran.']);
        }

        $detailBarang = $this->pembayaranService->getDetailBarangTransaksi($transaksi->id);
        $belumSiap    = $this->pembayaranService->filterBelumSiap($detailBarang);
        $siapBayar    = $detailBarang->count() > 0 && $belumSiap->count() === 0;
        $summary      = $this->pembayaranService->hitungSummaryDetailBarang($detailBarang);
        $hutangAktif  = $this->hutangService->getHutangAktif($transaksi->pelanggan_id);

        return view('kasir.pembayaran.show', [
            'transaksi'   => $transaksi,
            'detailBarang' => $detailBarang,
            'summary'     => $summary,
            'siapBayar'   => $siapBayar,
            'belumSiap'   => $belumSiap,
            'hutangAktif' => $hutangAktif,
        ]);
    }

    /**
     * Proses simpan pembayaran.
     */
    public function store(Request $request, int $transaksiId)
    {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $transaksi = \Illuminate\Support\Facades\DB::table('transaksi_penimbangan')
            ->where('id', $transaksiId)
            ->whereIn('status', [
                'draft_penimbangan',
                'menunggu_qc',
                'proses_qc',
                'menunggu_pembayaran',
            ])
            ->first();

        abort_if(!$transaksi, 404);

        if ($this->pembayaranService->sudahDibayar($transaksi->id)) {
            return back()->withErrors(['pembayaran' => 'Transaksi ini sudah dibayar.']);
        }

        $request->validate([
            'harga_per_kg'      => ['required', 'array'],
            'harga_per_kg.*'    => ['required', 'numeric', 'min:1'],
            'metode_pembayaran' => ['required', 'in:tunai,transfer'],
            'potongan_kasbon'   => ['nullable', 'numeric', 'min:0'],
            'catatan'           => ['nullable', 'string'],
        ]);

        $detailBarang = $this->pembayaranService->getDetailBarangTransaksi($transaksi->id);

        if ($detailBarang->count() === 0) {
            return back()->withErrors(['pembayaran' => 'Detail barang tidak ditemukan.']);
        }

        $belumSiap = $this->pembayaranService->filterBelumSiap($detailBarang);

        if ($belumSiap->count() > 0) {
            return back()->withErrors([
                'pembayaran' => 'Pembayaran belum bisa diproses. Pastikan semua barang dengan berat > 100 kg sudah memiliki hasil fuzzy dan penilaian QC.',
            ]);
        }

        $hargaInput = $request->input('harga_per_kg', []);

        foreach ($detailBarang as $detail) {
            if (!isset($hargaInput[$detail->detail_id]) || (float) $hargaInput[$detail->detail_id] <= 0) {
                return back()->withInput()->withErrors([
                    'harga_per_kg' => 'Semua harga per kg wajib diisi dan harus lebih dari 0.',
                ]);
            }
        }

        $rincian          = $this->pembayaranService->hitungRincianPembayaran($detailBarang, $hargaInput);
        $hutangAktif      = $this->hutangService->getHutangAktif($transaksi->pelanggan_id);
        $potonganKasbon   = (float) $request->input('potongan_kasbon', 0);
        $sisaHutangSebelum = $hutangAktif ? (float) $hutangAktif->sisa_hutang : 0;
        $totalTransaksi   = $rincian['total_transaksi'];

        if (!$hutangAktif && $potonganKasbon > 0) {
            return back()->withInput()->withErrors([
                'potongan_kasbon' => 'Pelanggan tidak memiliki kasbon aktif.',
            ]);
        }

        if ($potonganKasbon > $totalTransaksi) {
            return back()->withInput()->withErrors([
                'potongan_kasbon' => 'Potongan kasbon tidak boleh lebih besar dari total transaksi.',
            ]);
        }

        if ($potonganKasbon > $sisaHutangSebelum) {
            return back()->withInput()->withErrors([
                'potongan_kasbon' => 'Potongan kasbon tidak boleh lebih besar dari sisa kasbon pelanggan.',
            ]);
        }

        $totalDibayarKePelanggan = round($totalTransaksi - $potonganKasbon, 2);
        $sisaHutangSetelah       = round(max($sisaHutangSebelum - $potonganKasbon, 0), 2);
        $kodePembayaran          = $this->pembayaranService->generateKodePembayaran();

        $pembayaranId = $this->pembayaranService->simpanPembayaran(
            transaksi:               $transaksi,
            rincianBarang:           $rincian['rincian'],
            kodePembayaran:          $kodePembayaran,
            totalBeratBersih:        $rincian['total_berat_bersih'],
            totalPotonganBerat:      $rincian['total_potongan_berat'],
            totalBeratLayak:         $rincian['total_berat_layak'],
            totalTransaksi:          $totalTransaksi,
            potonganKasbon:          $potonganKasbon,
            sisaHutangSebelum:       $sisaHutangSebelum,
            sisaHutangSetelah:       $sisaHutangSetelah,
            totalDibayarKePelanggan: $totalDibayarKePelanggan,
            hutangAktif:             $hutangAktif,
            metodePembayaran:        $request->input('metode_pembayaran'),
            catatan:                 $request->input('catatan')
        );

        // Catat riwayat pembayaran hutang jika ada potongan kasbon
        if ($hutangAktif && $potonganKasbon > 0) {
            $this->hutangService->catatPembayaranHutang(
                hutangAktif:        $hutangAktif,
                pembayaranId:       $pembayaranId,
                potonganKasbon:     $potonganKasbon,
                sisaHutangSetelah:  $sisaHutangSetelah,
                kodePembayaran:     $kodePembayaran
            );
        }

        return redirect()
            ->route('kasir.pembayaran.index')
            ->with('success', 'Pembayaran berhasil disimpan. Transaksi selesai.');
    }

    // =========================================================================
    // KASBON / HUTANG PELANGGAN
    // =========================================================================

    /**
     * Daftar kasbon/hutang pelanggan.
     */
    public function kasbonIndex(Request $request)
    {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $keyword = $request->input('q');
        $status  = $request->input('status', 'semua');

        $hutang  = $this->hutangService->getDaftarKasbon($keyword, $status);
        $summary = $this->hutangService->getSummaryKasbon();

        return view('kasir.kasbon.index', [
            'hutang'  => $hutang,
            'summary' => $summary,
            'keyword' => $keyword,
            'status'  => $status,
        ]);
    }

    /**
     * Form tambah kasbon baru.
     */
    public function kasbonCreate()
    {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $pelanggan = \Illuminate\Support\Facades\DB::table('pelanggan')
            ->where('status', 'aktif')
            ->orderBy('nama_pelanggan')
            ->get();

        return view('kasir.kasbon.create', [
            'pelanggan' => $pelanggan,
        ]);
    }

    /**
     * Simpan kasbon baru.
     */
    public function kasbonStore(Request $request)
    {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $request->validate([
            'pelanggan_id'   => ['required', 'exists:pelanggan,id'],
            'tanggal_hutang' => ['required', 'date'],
            'total_hutang'   => ['required', 'numeric', 'min:1'],
            'keterangan'     => ['nullable', 'string'],
        ]);

        if ($this->hutangService->punyaHutangAktif((int) $request->input('pelanggan_id'))) {
            return back()->withInput()->withErrors([
                'pelanggan_id' => 'Pelanggan ini masih memiliki kasbon aktif. Lunasi atau edit kasbon lama terlebih dahulu.',
            ]);
        }

        $kodeHutang = $this->hutangService->generateKodeHutang();

        $this->hutangService->simpanKasbon($kodeHutang, [
            'pelanggan_id'   => $request->input('pelanggan_id'),
            'tanggal_hutang' => $request->input('tanggal_hutang'),
            'total_hutang'   => (float) $request->input('total_hutang'),
            'keterangan'     => $request->input('keterangan'),
        ]);

        return redirect()
            ->route('kasir.kasbon.index')
            ->with('success', 'Kasbon pelanggan berhasil ditambahkan.');
    }

    /**
     * Form edit kasbon.
     */
    public function kasbonEdit(int $id)
    {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $hutang = $this->hutangService->findKasbonDenganPelanggan($id);

        abort_if(!$hutang, 404);

        $sudahAdaPembayaran = $this->hutangService->sudahAdaPembayaran($hutang->id);

        return view('kasir.kasbon.edit', [
            'hutang'             => $hutang,
            'sudahAdaPembayaran' => $sudahAdaPembayaran,
        ]);
    }

    /**
     * Update kasbon.
     */
    public function kasbonUpdate(Request $request, int $id)
    {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $hutang = \Illuminate\Support\Facades\DB::table('hutang_pelanggan')
            ->where('id', $id)
            ->first();

        abort_if(!$hutang, 404);

        $request->validate([
            'tanggal_hutang' => ['required', 'date'],
            'total_hutang'   => ['required', 'numeric', 'min:1'],
            'keterangan'     => ['nullable', 'string'],
        ]);

        $sudahAdaPembayaran = $this->hutangService->sudahAdaPembayaran($hutang->id);

        $this->hutangService->updateKasbon($hutang, $sudahAdaPembayaran, [
            'tanggal_hutang' => $request->input('tanggal_hutang'),
            'total_hutang'   => (float) $request->input('total_hutang'),
            'keterangan'     => $request->input('keterangan'),
        ]);

        $pesan = $sudahAdaPembayaran
            ? 'Kasbon sudah memiliki riwayat pembayaran, sehingga hanya keterangan yang diperbarui.'
            : 'Kasbon pelanggan berhasil diperbarui.';

        return redirect()->route('kasir.kasbon.index')->with('success', $pesan);
    }

    /**
     * Hapus kasbon.
     */
    public function kasbonDestroy(int $id)
    {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $hutang = \Illuminate\Support\Facades\DB::table('hutang_pelanggan')
            ->where('id', $id)
            ->first();

        abort_if(!$hutang, 404);

        if ($this->hutangService->sudahAdaPembayaran($hutang->id)) {
            return back()->withErrors([
                'hapus' => 'Kasbon tidak bisa dihapus karena sudah memiliki riwayat pembayaran.',
            ]);
        }

        $this->hutangService->hapusKasbon($hutang->id);

        return redirect()
            ->route('kasir.kasbon.index')
            ->with('success', 'Kasbon pelanggan berhasil dihapus.');
    }

    // =========================================================================
    // DASHBOARD
    // =========================================================================

    /**
     * Menampilkan dashboard kasir dengan statistik pembayaran berdasarkan filter tanggal.
     *
     * Route: GET /kasir/dashboard  (kasir.dashboard)
     */
    public function dashboard(Request $request)
    {
        abort_unless(auth()->user()->role === 'kasir', 403);

        $tanggalMulai   = $request->get('tanggal_mulai', now()->toDateString());
        $tanggalSelesai = $request->get('tanggal_selesai', now()->toDateString());

        $totalPembayaran = 0;
        $totalDibayarKePelanggan = 0;
        $totalKasbon = 0;
        $totalPotonganKasbon = 0;
        $pembayaranTerbaru = collect();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('pembayaran')) {
                $totalPembayaran = DB::table('pembayaran')->count();
                $totalDibayarKePelanggan = DB::table('pembayaran')->sum('jumlah_bayar') ?? 0;

                $query = DB::table('pembayaran as bayar')
                    ->select('bayar.*');

                if (\Illuminate\Support\Facades\Schema::hasTable('transaksi_penimbangan')) {
                    $query->leftJoin('transaksi_penimbangan as transaksi', 'bayar.transaksi_id', '=', 'transaksi.id');
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('pelanggan')) {
                    $query->leftJoin('pelanggan', 'bayar.pelanggan_id', '=', 'pelanggan.id')
                        ->addSelect('pelanggan.nama_pelanggan');
                }

                $pembayaranTerbaru = $query->limit(5)->get();
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('hutang_pelanggan') || \Illuminate\Support\Facades\Schema::hasTable('kasbon_pelanggan')) {
                $kasbonTable = \Illuminate\Support\Facades\Schema::hasTable('hutang_pelanggan') ? 'hutang_pelanggan' : 'kasbon_pelanggan';
                $totalKasbon = DB::table($kasbonTable)->count();
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        return view('dashboard.kasir', [
            'totalPembayaran'         => $totalPembayaran,
            'totalDibayarKePelanggan' => $totalDibayarKePelanggan,
            'totalKasbon'             => $totalKasbon,
            'totalPotonganKasbon'     => $totalPotonganKasbon,
            'pembayaranTerbaru'       => $pembayaranTerbaru,
            'tanggalMulai'            => $tanggalMulai,
            'tanggalSelesai'          => $tanggalSelesai,
        ]);
    }
}
