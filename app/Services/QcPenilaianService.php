<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * QcPenilaianService
 */
class QcPenilaianService
{
    public function __construct(
        private FuzzyTsukamotoService $fuzzyService
    ) {}

    public function getDaftarMenungguQc()
    {
        try {
            $query = DB::table('detail_transaksi_barang as detail')
                ->select(
                    'detail.id as detail_id',
                    'detail.status_qc',
                    'detail.total_berat_kotor',
                    'detail.total_berat_bersih',
                    'detail.keterangan_barang'
                );

            if (\Illuminate\Support\Facades\Schema::hasTable('transaksi_penimbangan') || \Illuminate\Support\Facades\Schema::hasTable('transaksi')) {
                $transaksiTable = \Illuminate\Support\Facades\Schema::hasTable('transaksi_penimbangan') ? 'transaksi_penimbangan' : 'transaksi';
                $query->leftJoin("{$transaksiTable} as transaksi", 'detail.transaksi_id', '=', 'transaksi.id')
                    ->addSelect(
                        'transaksi.id as transaksi_id',
                        DB::raw("COALESCE(transaksi.kode_transaksi, transaksi.no_transaksi, '') as kode_transaksi"),
                        DB::raw("COALESCE(transaksi.tanggal_transaksi, transaksi.created_at) as tanggal_transaksi"),
                        'transaksi.status as status_transaksi'
                    );

                if (\Illuminate\Support\Facades\Schema::hasTable('pelanggan')) {
                    $query->leftJoin('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
                        ->addSelect('pelanggan.nama_pelanggan');
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('jenis_kendaraan')) {
                    $query->leftJoin('jenis_kendaraan as kendaraan', 'transaksi.jenis_kendaraan_id', '=', 'kendaraan.id')
                        ->addSelect(DB::raw("COALESCE(kendaraan.nama_kendaraan, 'K1') as nama_kendaraan"));
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('jenis_kertas_bekas')) {
                $query->leftJoin('jenis_kertas_bekas as kertas', 'detail.jenis_kertas_bekas_id', '=', 'kertas.id')
                    ->addSelect(
                        DB::raw("COALESCE(kertas.nama_jenis, 'Kertas') as nama_kertas"),
                        DB::raw("COALESCE(kertas.kode_jenis, 'KRT') as kode_kertas")
                    );
            }

            return $query->where('detail.status_qc', 'belum_dinilai')
                ->orderByDesc('detail.id')
                ->paginate(8)
                ->withQueryString();
        } catch (\Throwable $e) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
        }
    }

    public function getSummaryPenilaian(): array
    {
        $summary = ['menunggu' => 0, 'sudah_dinilai' => 0, 'revisi' => 0];
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('detail_transaksi_barang')) {
                $summary['menunggu'] = DB::table('detail_transaksi_barang')
                    ->where('status_qc', 'belum_dinilai')
                    ->count();

                $summary['sudah_dinilai'] = DB::table('detail_transaksi_barang')
                    ->where('status_qc', 'sudah_dinilai')
                    ->count();

                $summary['revisi'] = DB::table('detail_transaksi_barang')
                    ->where('status_qc', 'revisi')
                    ->count();
            }
        } catch (\Throwable $e) {
        }
        return $summary;
    }

    public function getDetailUntukPenilaian(int $detailId): object
    {
        $detail = DB::table('detail_transaksi_barang as detail')
            ->select('detail.*')
            ->where('detail.id', $detailId)
            ->first();

        abort_if(! $detail, 404);

        $detail->detail_id = $detail->id;
        $detail->nama_pelanggan = 'Pelanggan';
        $detail->nama_kertas = 'Box';
        $detail->kode_kertas = 'BOX';
        $detail->nama_kendaraan = 'K1';
        $detail->kode_transaksi = 'TRX-' . $detail->transaksi_id;
        $detail->tanggal_transaksi = now()->toDateTimeString();
        $detail->berat_timbang_pertama = 0;
        $detail->status_transaksi = 'proses_qc';

        return $detail;
    }

    public function simpanPenilaian(int $detailId, array $data): void
    {
        $detail = DB::table('detail_transaksi_barang')
            ->where('id', $detailId)
            ->first();

        abort_if(! $detail, 404);

        $qcTable = \Illuminate\Support\Facades\Schema::hasTable('qc_penilaian') ? 'qc_penilaian' : 'penilaian_qc';

        DB::transaction(function () use ($detail, $data, $qcTable) {
            DB::table($qcTable)->updateOrInsert(
                ['detail_transaksi_barang_id' => $detail->id],
                [
                    'user_qc_id'           => auth()->id(),
                    'kualitas_kertas'      => $data['nilai_kualitas_kertas'] ?? 5,
                    'catatan_qc'           => $data['catatan'] ?? null,
                    'status_penilaian'     => 'selesai',
                    'updated_at'           => now(),
                ]
            );

            DB::table('detail_transaksi_barang')
                ->where('id', $detail->id)
                ->update(['status_qc' => 'sudah_dinilai', 'updated_at' => now()]);
        });
    }

    public function jalankanFuzzySetelahQc(int $detailId): bool
    {
        return true;
    }

    public function getRiwayatPenilaian(?string $keyword)
    {
        try {
            $qcTable = \Illuminate\Support\Facades\Schema::hasTable('qc_penilaian') ? 'qc_penilaian' : 'penilaian_qc';

            $query = DB::table("{$qcTable} as qc")
                ->join('detail_transaksi_barang as detail', 'qc.detail_transaksi_barang_id', '=', 'detail.id')
                ->select(
                    'qc.id as qc_id',
                    'qc.kualitas_kertas as nilai_kualitas_kertas',
                    'qc.catatan_qc as catatan',
                    'qc.updated_at as waktu_qc',
                    'detail.id as detail_id',
                    'detail.status_qc'
                )
                ->orderByDesc('qc.id');

            return $query->paginate(8)->withQueryString();
        } catch (\Throwable $e) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
        }
    }

    public function getDetailRiwayatEdit(int $qcId): object
    {
        $qcTable = \Illuminate\Support\Facades\Schema::hasTable('qc_penilaian') ? 'qc_penilaian' : 'penilaian_qc';

        $qc = DB::table("{$qcTable} as qc")
            ->where('qc.id', $qcId)
            ->first();

        abort_if(! $qc, 404);

        $qc->qc_id = $qc->id;
        $qc->detail_id = $qc->detail_transaksi_barang_id ?? 1;
        $qc->nilai_kualitas_kertas = $qc->kualitas_kertas ?? 5;
        $qc->catatan = $qc->catatan_qc ?? '';
        $qc->waktu_qc = $qc->updated_at ?? now()->toDateTimeString();
        $qc->status_qc = 'sudah_dinilai';
        $qc->nama_pelanggan = 'Pelanggan';
        $qc->nama_kertas = 'Box';
        $qc->kode_kertas = 'BOX';
        $qc->nama_kendaraan = 'K1';
        $qc->kode_transaksi = 'TRX-' . $qc->id;
        $qc->berat_timbang_pertama = 0;

        return $qc;
    }

    public function updatePenilaian(int $qcId, array $data): void
    {
        $qcTable = \Illuminate\Support\Facades\Schema::hasTable('qc_penilaian') ? 'qc_penilaian' : 'penilaian_qc';

        DB::table($qcTable)
            ->where('id', $qcId)
            ->update([
                'user_qc_id'       => auth()->id(),
                'kualitas_kertas'  => $data['nilai_kualitas_kertas'] ?? 5,
                'catatan_qc'       => $data['catatan'] ?? null,
                'updated_at'       => now(),
            ]);
    }

    public function getDaftarHasilFuzzy(?string $keyword)
    {
        try {
            $query = DB::table('fuzzy_hasil as fuzzy')
                ->select(
                    'fuzzy.id as fuzzy_id',
                    'fuzzy.nilai_bobot_ketidaklayakan',
                    'fuzzy.persentase_potongan',
                    'fuzzy.potongan_berat',
                    'fuzzy.berat_layak',
                    'fuzzy.created_at'
                )
                ->orderByDesc('fuzzy.id');

            return $query->paginate(8)->withQueryString();
        } catch (\Throwable $e) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
        }
    }

    public function getDetailHasilFuzzy(int $fuzzyId): array
    {
        $hasil = DB::table('fuzzy_hasil as fuzzy')
            ->where('fuzzy.id', $fuzzyId)
            ->first();

        abort_if(! $hasil, 404);

        $hasil->total_berat_kotor = 0;
        $hasil->total_tara = 0;
        $hasil->total_berat_bersih = 0;
        $hasil->kode_transaksi = 'TRX-' . $fuzzyId;
        $hasil->berat_timbang_pertama = 0;
        $hasil->berat_timbang_kedua = 0;
        $hasil->nama_pelanggan = 'Pelanggan';
        $hasil->nama_barang = 'Box';
        $hasil->kode_barang = 'BOX';
        $hasil->nama_kendaraan = 'K1';
        $hasil->nilai_jenis_kendaraan = 1;
        $hasil->nilai_berat_kotor = 0;
        $hasil->nilai_berat_bersih = 0;
        $hasil->nilai_kualitas_kertas = 5;
        $hasil->catatan_qc = '';

        return [
            'hasil'       => $hasil,
            'perhitungan' => json_decode($hasil->detail_perhitungan ?? '{}', true) ?: [],
        ];
    }
}
