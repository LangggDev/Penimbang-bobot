<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // cache_locks
        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        // kategori_kertas
        if (!Schema::hasTable('kategori_kertas')) {
            Schema::create('kategori_kertas', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori');
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        // jenis_kertas_bekas
        if (!Schema::hasTable('jenis_kertas_bekas')) {
            Schema::create('jenis_kertas_bekas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_kertas_id')->nullable();
                $table->string('kode_jenis')->nullable();
                $table->string('nama_jenis');
                $table->decimal('harga_per_kg', 15, 2)->default(0);
                $table->text('deskripsi')->nullable();
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();
            });
        }

        // pelanggan
        if (!Schema::hasTable('pelanggan')) {
            Schema::create('pelanggan', function (Blueprint $table) {
                $table->id();
                $table->string('kode_pelanggan')->nullable();
                $table->string('nama_pelanggan');
                $table->string('no_hp', 50)->nullable();
                $table->text('alamat')->nullable();
                $table->enum('kategori', ['setia', 'biasa', 'baru'])->default('biasa');
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();
            });
        }

        // mitra_pengepul
        if (!Schema::hasTable('mitra_pengepul')) {
            Schema::create('mitra_pengepul', function (Blueprint $table) {
                $table->id();
                $table->string('nama_mitra');
                $table->string('no_hp', 50)->nullable();
                $table->text('alamat')->nullable();
                $table->timestamps();
            });
        }

        // pemilik
        if (!Schema::hasTable('pemilik')) {
            Schema::create('pemilik', function (Blueprint $table) {
                $table->id();
                $table->string('nama_pemilik');
                $table->string('no_hp', 50)->nullable();
                $table->text('alamat')->nullable();
                $table->timestamps();
            });
        }

        // transaksi & transaksi_penimbangan
        if (!Schema::hasTable('transaksi')) {
            Schema::create('transaksi', function (Blueprint $table) {
                $table->id();
                $table->string('no_transaksi')->nullable();
                $table->foreignId('pelanggan_id')->nullable();
                $table->foreignId('user_penimbang_id')->nullable();
                $table->foreignId('petugas_timbang_id')->nullable();
                $table->enum('jenis_kendaraan', ['K1', 'K2', 'K3'])->default('K1');
                $table->integer('bobot_kendaraan_id')->default(1);
                $table->string('status')->default('timbang_1');
                $table->string('status_transaksi')->default('timbang_1');
                $table->string('status_pembayaran')->default('belum_dibayar');
                $table->timestamp('tanggal_transaksi')->useCurrent();
                $table->timestamp('waktu_masuk')->nullable();
                $table->timestamp('waktu_keluar')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('transaksi_penimbangan')) {
            Schema::create('transaksi_penimbangan', function (Blueprint $table) {
                $table->id();
                $table->string('no_transaksi')->nullable();
                $table->string('kode_transaksi')->nullable();
                $table->foreignId('pelanggan_id')->nullable();
                $table->foreignId('petugas_timbang_id')->nullable();
                $table->foreignId('user_penimbang_id')->nullable();
                $table->enum('jenis_kendaraan', ['K1', 'K2', 'K3'])->default('K1');
                $table->integer('bobot_kendaraan_id')->default(1);
                $table->string('status')->default('draft_penimbangan');
                $table->string('status_transaksi')->default('draft_penimbangan');
                $table->string('status_pembayaran')->default('belum_dibayar');
                $table->timestamp('tanggal_transaksi')->useCurrent();
                $table->timestamp('waktu_masuk')->nullable();
                $table->timestamp('waktu_keluar')->nullable();
                $table->timestamps();
            });
        }

        // detail_transaksi_barang
        if (!Schema::hasTable('detail_transaksi_barang')) {
            Schema::create('detail_transaksi_barang', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transaksi_id');
                $table->foreignId('jenis_kertas_bekas_id');
                $table->string('keterangan_barang')->nullable();
                $table->decimal('total_berat_kotor', 12, 2)->default(0);
                $table->decimal('total_tara', 12, 2)->default(0);
                $table->decimal('total_berat_bersih', 12, 2)->default(0);
                $table->enum('status_qc', ['belum_dinilai', 'sudah_dinilai', 'revisi'])->default('belum_dinilai');
                $table->unsignedInteger('urutan')->default(1);
                $table->timestamps();
            });
        }

        // timbangan_detail
        if (!Schema::hasTable('timbangan_detail')) {
            Schema::create('timbangan_detail', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transaksi_id');
                $table->foreignId('detail_transaksi_barang_id')->nullable();
                $table->enum('tipe_timbangan', ['timbang_1_bruto', 'timbang_bertahap_tara', 'timbang_2_tara_akhir']);
                $table->decimal('berat', 12, 2)->default(0);
                $table->timestamp('waktu_timbang')->nullable();
                $table->unsignedInteger('urutan_timbang')->default(1);
                $table->timestamps();
            });
        }

        // penilaian_qc
        if (!Schema::hasTable('penilaian_qc')) {
            Schema::create('penilaian_qc', function (Blueprint $table) {
                $table->id();
                $table->foreignId('detail_transaksi_barang_id');
                $table->foreignId('user_qc_id')->nullable();
                $table->decimal('kualitas_kertas', 5, 2)->default(0);
                $table->text('catatan_qc')->nullable();
                $table->enum('status_penilaian', ['draft', 'selesai'])->default('selesai');
                $table->timestamps();
            });
        }

        // fuzzy_hasil
        if (!Schema::hasTable('fuzzy_hasil')) {
            Schema::create('fuzzy_hasil', function (Blueprint $table) {
                $table->id();
                $table->foreignId('qc_penilaian_id');
                $table->foreignId('detail_transaksi_barang_id');
                $table->decimal('nilai_bobot_ketidaklayakan', 8, 2)->default(0);
                $table->decimal('persentase_potongan', 8, 2)->default(0);
                $table->decimal('potongan_berat', 12, 2)->default(0);
                $table->decimal('berat_layak', 12, 2)->default(0);
                $table->longText('detail_perhitungan')->nullable();
                $table->timestamps();
            });
        }

        // pembayaran
        if (!Schema::hasTable('pembayaran')) {
            Schema::create('pembayaran', function (Blueprint $table) {
                $table->id();
                $table->string('no_pembayaran')->nullable();
                $table->foreignId('transaksi_id');
                $table->foreignId('user_kasir_id')->nullable();
                $table->decimal('total_berat_bersih', 12, 2)->default(0);
                $table->decimal('total_potongan_berat', 12, 2)->default(0);
                $table->decimal('total_berat_layak', 12, 2)->default(0);
                $table->decimal('total_pembayaran', 15, 2)->default(0);
                $table->decimal('jumlah_bayar', 15, 2)->default(0);
                $table->decimal('kembalian', 15, 2)->default(0);
                $table->enum('metode_pembayaran', ['tunai', 'transfer'])->default('tunai');
                $table->enum('status_pembayaran', ['lunas', 'kasbon', 'batal'])->default('lunas');
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }

        // detail_pembayaran_barang
        if (!Schema::hasTable('detail_pembayaran_barang')) {
            Schema::create('detail_pembayaran_barang', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pembayaran_id');
                $table->foreignId('detail_transaksi_barang_id');
                $table->foreignId('fuzzy_hasil_id')->nullable();
                $table->string('nama_barang_snapshot');
                $table->decimal('berat_bersih', 12, 2)->default(0);
                $table->decimal('persentase_potongan', 8, 2)->default(0);
                $table->decimal('potongan_berat', 12, 2)->default(0);
                $table->decimal('berat_layak', 12, 2)->default(0);
                $table->decimal('harga_per_kg', 15, 2)->default(0);
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->unsignedInteger('urutan')->default(1);
                $table->timestamps();
            });
        }

        // kasbon_pelanggan & hutang_pelanggan
        if (!Schema::hasTable('kasbon_pelanggan')) {
            Schema::create('kasbon_pelanggan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pelanggan_id');
                $table->foreignId('pembayaran_id')->nullable();
                $table->decimal('jumlah_kasbon', 15, 2)->default(0);
                $table->decimal('sisa_kasbon', 15, 2)->default(0);
                $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('hutang_pelanggan')) {
            Schema::create('hutang_pelanggan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pelanggan_id');
                $table->foreignId('pembayaran_id')->nullable();
                $table->decimal('jumlah_kasbon', 15, 2)->default(0);
                $table->decimal('sisa_kasbon', 15, 2)->default(0);
                $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutang_pelanggan');
        Schema::dropIfExists('kasbon_pelanggan');
        Schema::dropIfExists('detail_pembayaran_barang');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('fuzzy_hasil');
        Schema::dropIfExists('penilaian_qc');
        Schema::dropIfExists('timbangan_detail');
        Schema::dropIfExists('detail_transaksi_barang');
        Schema::dropIfExists('transaksi_penimbangan');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('pemilik');
        Schema::dropIfExists('mitra_pengepul');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('jenis_kertas_bekas');
        Schema::dropIfExists('kategori_kertas');
        Schema::dropIfExists('cache_locks');
    }
};
