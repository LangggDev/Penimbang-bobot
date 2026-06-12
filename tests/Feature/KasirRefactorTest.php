<?php

use App\Services\PembayaranService;
use App\Services\HutangPelangganService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Models\User;

beforeEach(function () {
    // 1. Create the required tables in SQLite memory DB dynamically
    Schema::create('pelanggan', function (Blueprint $table) {
        $table->id();
        $table->string('kode_pelanggan');
        $table->string('nama_pelanggan');
        $table->string('no_hp')->nullable();
        $table->text('alamat')->nullable();
        $table->string('status');
        $table->timestamps();
    });

    Schema::create('jenis_kendaraan', function (Blueprint $table) {
        $table->id();
        $table->string('nama_kendaraan');
    });

    Schema::create('jenis_kertas_bekas', function (Blueprint $table) {
        $table->id();
        $table->string('kode_barang');
        $table->string('nama_barang');
    });

    Schema::create('transaksi_penimbangan', function (Blueprint $table) {
        $table->id();
        $table->string('kode_transaksi');
        $table->unsignedBigInteger('pelanggan_id');
        $table->unsignedBigInteger('jenis_kendaraan_id');
        $table->string('plat_kendaraan');
        $table->dateTime('tanggal_transaksi');
        $table->float('berat_timbang_pertama')->nullable();
        $table->float('berat_timbang_kedua')->nullable();
        $table->string('status');
        $table->unsignedBigInteger('petugas_timbang_id');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });

    Schema::create('detail_transaksi_barang', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('transaksi_id');
        $table->unsignedBigInteger('jenis_kertas_bekas_id');
        $table->text('keterangan_barang')->nullable();
        $table->float('total_berat_kotor');
        $table->float('total_tara');
        $table->float('total_berat_bersih');
        $table->string('status_qc')->nullable();
        $table->integer('urutan');
        $table->timestamps();
    });

    Schema::create('fuzzy_hasil', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('qc_penilaian_id')->nullable();
        $table->unsignedBigInteger('detail_transaksi_barang_id');
        $table->float('nilai_bobot_ketidaklayakan');
        $table->float('persentase_potongan');
        $table->float('potongan_berat');
        $table->float('berat_layak');
        $table->text('detail_perhitungan')->nullable();
        $table->timestamps();
    });

    Schema::create('pembayaran', function (Blueprint $table) {
        $table->id();
        $table->string('kode_pembayaran');
        $table->unsignedBigInteger('transaksi_id');
        $table->unsignedBigInteger('pelanggan_id');
        $table->dateTime('tanggal_bayar');
        $table->float('total_berat_bersih');
        $table->float('total_potongan_berat');
        $table->float('total_berat_layak');
        $table->float('total_transaksi');
        $table->float('sisa_hutang_sebelum');
        $table->float('potongan_kasbon');
        $table->float('total_dibayar_ke_pelanggan');
        $table->float('sisa_hutang_setelah');
        $table->string('metode_pembayaran');
        $table->string('status_pembayaran');
        $table->unsignedBigInteger('kasir_id');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });

    Schema::create('detail_pembayaran_barang', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('pembayaran_id');
        $table->unsignedBigInteger('detail_transaksi_barang_id');
        $table->unsignedBigInteger('fuzzy_hasil_id')->nullable();
        $table->string('nama_barang_snapshot');
        $table->float('berat_bersih');
        $table->float('persentase_potongan');
        $table->float('potongan_berat');
        $table->float('berat_layak');
        $table->float('harga_per_kg');
        $table->float('subtotal');
        $table->integer('urutan');
        $table->timestamps();
    });

    Schema::create('hutang_pelanggan', function (Blueprint $table) {
        $table->id();
        $table->string('kode_hutang');
        $table->unsignedBigInteger('pelanggan_id');
        $table->dateTime('tanggal_hutang');
        $table->float('total_hutang');
        $table->float('total_terbayar');
        $table->float('sisa_hutang');
        $table->string('status');
        $table->text('keterangan')->nullable();
        $table->unsignedBigInteger('created_by');
        $table->timestamps();
    });

    Schema::create('pembayaran_hutang', function (Blueprint $table) {
        $table->id();
        $table->string('kode_pembayaran_hutang');
        $table->unsignedBigInteger('hutang_pelanggan_id');
        $table->unsignedBigInteger('pembayaran_id');
        $table->integer('nomor_potongan');
        $table->float('nominal_bayar');
        $table->string('jenis_pembayaran');
        $table->dateTime('tanggal_bayar');
        $table->unsignedBigInteger('kasir_id');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });

    // 2. Set up services
    $this->pembayaranService = new PembayaranService();
    $this->hutangService = new HutangPelangganService();

    $user = User::factory()->create([
        'id' => 99,
        'name' => 'Kasir Test',
        'role' => 'kasir',
        'status' => 'aktif',
    ]);
    $this->actingAs($user);
});

test('pembayaran service can get daftar transaksi siap bayar', function () {
    // Seed test data
    $pelangganId = DB::table('pelanggan')->insertGetId([
        'kode_pelanggan' => 'PLG-01',
        'nama_pelanggan' => 'John Doe',
        'status' => 'aktif',
    ]);

    $kendaraanId = DB::table('jenis_kendaraan')->insertGetId([
        'nama_kendaraan' => 'Truk',
    ]);

    $barangId = DB::table('jenis_kertas_bekas')->insertGetId([
        'kode_barang' => 'BRG-01',
        'nama_barang' => 'Koran Bekas',
    ]);

    $transaksiId = DB::table('transaksi_penimbangan')->insertGetId([
        'kode_transaksi' => 'TRX-01',
        'pelanggan_id' => $pelangganId,
        'jenis_kendaraan_id' => $kendaraanId,
        'plat_kendaraan' => 'B 1234 CD',
        'tanggal_transaksi' => now(),
        'status' => 'menunggu_pembayaran',
        'petugas_timbang_id' => 1,
    ]);

    // Detail barang 1: <= 100 kg (bypass fuzzy)
    DB::table('detail_transaksi_barang')->insert([
        'transaksi_id' => $transaksiId,
        'jenis_kertas_bekas_id' => $barangId,
        'total_berat_kotor' => 120,
        'total_tara' => 40,
        'total_berat_bersih' => 80, // <= 100 kg
        'urutan' => 1,
    ]);

    $transaksiSiap = $this->pembayaranService->getDaftarTransaksiSiapBayar(null)->get();
    expect($transaksiSiap)->toHaveCount(1);
    expect($transaksiSiap[0]->id)->toBe($transaksiId);
});

test('hutang service can check and manage hutang aktif', function () {
    $pelangganId = 1;

    expect($this->hutangService->punyaHutangAktif($pelangganId))->toBeFalse();

    $this->hutangService->simpanKasbon('KSB-01', [
        'pelanggan_id' => $pelangganId,
        'tanggal_hutang' => now()->toDateString(),
        'total_hutang' => 50000,
        'keterangan' => 'Kasbon awal',
    ]);

    expect($this->hutangService->punyaHutangAktif($pelangganId))->toBeTrue();

    $hutangAktif = $this->hutangService->getHutangAktif($pelangganId);
    expect($hutangAktif->total_hutang)->toBe(50000.0);
    expect($hutangAktif->sisa_hutang)->toBe(50000.0);
});

test('simpan pembayaran can record payment and reduce hutang', function () {
    $pelangganId = DB::table('pelanggan')->insertGetId([
        'kode_pelanggan' => 'PLG-02',
        'nama_pelanggan' => 'Jane Doe',
        'status' => 'aktif',
    ]);

    $kendaraanId = DB::table('jenis_kendaraan')->insertGetId([
        'nama_kendaraan' => 'Motor',
    ]);

    $barangId = DB::table('jenis_kertas_bekas')->insertGetId([
        'kode_barang' => 'BRG-02',
        'nama_barang' => 'Kardus Bekas',
    ]);

    $transaksiId = DB::table('transaksi_penimbangan')->insertGetId([
        'kode_transaksi' => 'TRX-02',
        'pelanggan_id' => $pelangganId,
        'jenis_kendaraan_id' => $kendaraanId,
        'plat_kendaraan' => 'B 5678 EF',
        'tanggal_transaksi' => now(),
        'status' => 'menunggu_pembayaran',
        'petugas_timbang_id' => 99,
    ]);

    $detailId = DB::table('detail_transaksi_barang')->insertGetId([
        'transaksi_id' => $transaksiId,
        'jenis_kertas_bekas_id' => $barangId,
        'total_berat_kotor' => 150,
        'total_tara' => 50,
        'total_berat_bersih' => 100, // <= 100 kg
        'urutan' => 1,
    ]);

    // Create a kasbon for this customer
    $hutangId = DB::table('hutang_pelanggan')->insertGetId([
        'kode_hutang' => 'KSB-99',
        'pelanggan_id' => $pelangganId,
        'tanggal_hutang' => now(),
        'total_hutang' => 30000,
        'total_terbayar' => 0,
        'sisa_hutang' => 30000,
        'status' => 'belum_lunas',
        'created_by' => 99,
    ]);

    $hutangAktif = $this->hutangService->getHutangAktif($pelangganId);
    expect($hutangAktif->id)->toBe($hutangId);

    // Call service to get detail barang
    $detailBarang = $this->pembayaranService->getDetailBarangTransaksi($transaksiId);

    // Hitung rincian with price 1000 per kg -> total transaksi = 100 * 1000 = 100000
    $rincian = $this->pembayaranService->hitungRincianPembayaran($detailBarang, [$detailId => 1000]);

    // Process payment with potongan kasbon 20000
    $potonganKasbon = 20000.0;
    $sisaHutangSebelum = (float) $hutangAktif->sisa_hutang;
    $sisaHutangSetelah = $sisaHutangSebelum - $potonganKasbon;
    $totalDibayarKePelanggan = $rincian['total_transaksi'] - $potonganKasbon;
    $kodePembayaran = 'PAY-TEST-01';

    $pembayaranId = $this->pembayaranService->simpanPembayaran(
        transaksi: (object)['id' => $transaksiId, 'pelanggan_id' => $pelangganId],
        rincianBarang: $rincian['rincian'],
        kodePembayaran: $kodePembayaran,
        totalBeratBersih: $rincian['total_berat_bersih'],
        totalPotonganBerat: $rincian['total_potongan_berat'],
        totalBeratLayak: $rincian['total_berat_layak'],
        totalTransaksi: $rincian['total_transaksi'],
        potonganKasbon: $potonganKasbon,
        sisaHutangSebelum: $sisaHutangSebelum,
        sisaHutangSetelah: $sisaHutangSetelah,
        totalDibayarKePelanggan: $totalDibayarKePelanggan,
        hutangAktif: $hutangAktif,
        metodePembayaran: 'tunai',
        catatan: 'Test catatan'
    );

    // Catat riwayat
    $this->hutangService->catatPembayaranHutang(
        hutangAktif: $hutangAktif,
        pembayaranId: $pembayaranId,
        potonganKasbon: $potonganKasbon,
        sisaHutangSetelah: $sisaHutangSetelah,
        kodePembayaran: $kodePembayaran
    );

    // Verify pembayaran recorded
    $pembayaranRecord = DB::table('pembayaran')->where('id', $pembayaranId)->first();
    expect($pembayaranRecord)->not->toBeNull();
    expect($pembayaranRecord->potongan_kasbon)->toBe(20000.0);
    expect($pembayaranRecord->total_dibayar_ke_pelanggan)->toBe(80000.0);

    // Verify detail pembayaran recorded
    $detailPembayaran = DB::table('detail_pembayaran_barang')->where('pembayaran_id', $pembayaranId)->first();
    expect($detailPembayaran)->not->toBeNull();
    expect($detailPembayaran->harga_per_kg)->toBe(1000.0);
    expect($detailPembayaran->subtotal)->toBe(100000.0);

    // Verify hutang_pelanggan updated
    $hutangUpdated = DB::table('hutang_pelanggan')->where('id', $hutangId)->first();
    expect($hutangUpdated->sisa_hutang)->toBe(10000.0);
    expect($hutangUpdated->total_terbayar)->toBe(20000.0);
    expect($hutangUpdated->status)->toBe('belum_lunas');

    // Verify pembayaran_hutang logged
    $riwayatHutang = DB::table('pembayaran_hutang')->where('hutang_pelanggan_id', $hutangId)->first();
    expect($riwayatHutang)->not->toBeNull();
    expect($riwayatHutang->nominal_bayar)->toBe(20000.0);
});
