# Refactor Penimbang & Timbang Bertahap — Tahap 4 (REVISED)

## Ringkasan

Memindahkan routing logic (closure) pada alur transaksi dan penimbangan dengan hak akses **Penimbang** di `routes/web.php` ke dalam Controller dan Service yang terpisah. 

Sesuai instruksi revisi:
- **Pelanggan CRUD** dan **Dashboard Penimbang** TIDAK direfaktor dan dibiarkan sebagai closure di `routes/web.php`.
- **Tidak ada pemanggilan `FuzzyTsukamotoService`** dari modul Penimbang.
- Route `penimbang.transaksi.hitung-fuzzy` **dihapus/ditiadakan**.
- Status transaksi ditentukan murni di Penimbang:
  - Jika ada barang dengan `total_berat_bersih > 100`, status transaksi menjadi `menunggu_qc`.
  - Jika semua barang dengan `total_berat_bersih <= 100`, status transaksi menjadi `menunggu_pembayaran`.
- Tidak ada modifikasi pada modul QC/Fuzzy, database schema, migration, `.env`, kasir, pembayaran, atau kasbon.
- Indikator barang sudah ditimbang menggunakan data `total_berat_bersih > 0` (bukan kolom `status` pada `detail_transaksi_barang`).
- Rumus timbang bertahap dipertahankan: `berat_bersih = berat_kotor (berat sebelum bongkar) - tara (sisa berat setelah bongkar)`.

---

## Rencana Perubahan

### 1. File Baru: `PenimbangTransaksiService`

#### [NEW] [PenimbangTransaksiService.php](file:///c:/Users/fairuz/Herd/penilaian-bobot-clean-code/app/Services/PenimbangTransaksiService.php)
Service yang mengelola alur transaksi penimbangan, timbangan pertama/kedua, timbang bertahap, dan print antrian.
Method:
* `getDaftarTransaksi(string $status = 'semua')` - Menampilkan daftar transaksi dengan pagination.
* `getSummaryTransaksi()` - Mengambil jumlah statistik transaksi (total, draft, menunggu_qc, selesai) untuk filter.
* `getPelangganAktif()` - Mengambil semua pelanggan dengan status aktif untuk form create transaksi.
* `getJenisKendaraanAktif()` - Mengambil semua jenis kendaraan aktif untuk form.
* `getJenisKertasBekasAktif()` - Mengambil semua jenis kertas bekas aktif untuk form timbangan pertama.
* `simpanTransaksi(array $data)` - Membuat transaksi baru secara manual.
* `getPelangganUntukTimbanganPertama(int $id)` - Mengambil data pelanggan untuk form timbangan pertama.
* `simpanTimbanganPertama(int $id, array $data)` - Menyimpan timbangan pertama, membuat transaksi (status `draft_penimbangan`), dan membuat data detail transaksi kertas bekas secara transactional.
* `getDetailTimbanganKedua(int $transaksiId)` - Mengambil data transaksi, detail barang, riwayat penimbangan bertahap, sisa berat terakhir, dan total berat bersih.
* `simpanTimbangBertahap(int $transaksiId, array $data)` - Menyimpan timbang bertahap per item barang (timbang kotor, tara, hitung bersih) secara transactional. Menggunakan rumus: `berat_bersih = berat_kotor - tara`.
* `selesaiPenimbangan(int $transaksiId)` - Menyelesaikan penimbangan:
  - Validasi semua barang harus sudah ditimbang (`total_berat_bersih > 0`). Jika belum, lempar `ValidationException`.
  - Tentukan status berikutnya (`menunggu_qc` jika berat bersih barang > 100 kg, `menunggu_pembayaran` jika semua <= 100 kg).
  - Update berat timbang kedua transaksi ke tara terakhir.
* `getDetailTransaksi(int $transaksiId)` - Mengambil detail transaksi lengkap beserta status QC dan detail hasil fuzzy.
* `getPrintAntrian(int $transaksiId)` - Mengambil detail transaksi untuk cetak nomor antrian.

---

### 2. File Baru: `PenimbangTransaksiController`

#### [NEW] [PenimbangTransaksiController.php](file:///c:/Users/fairuz/Herd/penilaian-bobot-clean-code/app/Http/Controllers/Penimbang/PenimbangTransaksiController.php)
Controller untuk delegasi route transaksi, timbang bertahap, dan print antrian.
Method:
* `index(Request $request)`
* `create()`
* `store(Request $request)`
* `timbanganPertama(int $id)`
* `simpanTimbanganPertama(Request $request, int $id)`
* `timbanganKedua(int $id)`
* `simpanTimbangBertahap(Request $request, int $id)`
* `selesaiPenimbangan(int $id)`
* `detail(int $id)`
* `printAntrian(int $id)`

---

### 3. File Diubah: `routes/web.php`

#### [MODIFY] [web.php](file:///c:/Users/fairuz/Herd/penilaian-bobot-clean-code/routes/web.php)
Mengubah closure route transaksi dan timbangan penimbang menjadi mengarah ke Controller baru.
*Route Pelanggan CRUD, Dashboard, dan Kasir/QC dibiarkan utuh tanpa perubahan.*

```diff
-        Route::get('/transaksi', function () {
-            ...
-        })->name('transaksi.index');
+        Route::get('/transaksi', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'index'])
+            ->name('transaksi.index');

-        Route::get('/transaksi/create', function () {
-            ...
-        })->name('transaksi.create');
+        Route::get('/transaksi/create', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'create'])
+            ->name('transaksi.create');

-        Route::post('/transaksi', function () {
-            ...
-        })->name('transaksi.store');
+        Route::post('/transaksi', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'store'])
+            ->name('transaksi.store');

-        Route::get('/pelanggan/{id}/timbangan-pertama', function ($id) {
-            ...
-        })->name('pelanggan.timbangan-pertama');
+        Route::get('/pelanggan/{id}/timbangan-pertama', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'timbanganPertama'])
+            ->name('pelanggan.timbangan-pertama');

-        Route::post('/pelanggan/{id}/timbangan-pertama', function ($id) {
-            ...
-        })->name('pelanggan.timbangan-pertama.store');
+        Route::post('/pelanggan/{id}/timbangan-pertama', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'simpanTimbanganPertama'])
+            ->name('pelanggan.timbangan-pertama.store');

-        Route::get('/transaksi/{id}/timbangan-kedua', function ($id) {
-            ...
-        })->name('transaksi.timbangan-kedua');
+        Route::get('/transaksi/{id}/timbangan-kedua', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'timbanganKedua'])
+            ->name('transaksi.timbangan-kedua');

-        Route::post('/transaksi/{id}/timbang-bertahap', function ($id) {
-            ...
-        })->name('transaksi.timbang-bertahap.store');
+        Route::post('/transaksi/{id}/timbang-bertahap', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'simpanTimbangBertahap'])
+            ->name('transaksi.timbang-bertahap.store');

-        Route::post('/transaksi/{id}/selesai-penimbangan', function ($id) {
-            ...
-        })->name('transaksi.selesai-penimbangan');
+        Route::post('/transaksi/{id}/selesai-penimbangan', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'selesaiPenimbangan'])
+            ->name('transaksi.selesai-penimbangan');

-        Route::get('/transaksi/{id}/detail', function ($id) {
-            ...
-        })->name('transaksi.show');
+        Route::get('/transaksi/{id}/detail', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'detail'])
+            ->name('transaksi.show');

-        Route::get('/transaksi/{id}/print-antrian', function ($id) {
-            ...
-        })->name('transaksi.print-antrian');
+        Route::get('/transaksi/{id}/print-antrian', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'printAntrian'])
+            ->name('transaksi.print-antrian');

-        Route::post('/transaksi/{id}/hitung-fuzzy', function ($id, ...) {
-            ...
-        })->name('transaksi.hitung-fuzzy');
+        // Route hitung-fuzzy dihapus sepenuhnya
```

---

## Rencana Verifikasi

### Automated Verification
```bash
php -l app/Http/Controllers/Penimbang/PenimbangTransaksiController.php
php -l app/Services/PenimbangTransaksiService.php
php -l routes/web.php
php artisan route:list --name=penimbang
php artisan optimize:clear
```

### Manual Verification
1. Login sebagai Penimbang.
2. Buka halaman Pelanggan, klik "Timbangan Pertama", input data dan simpan. Pastikan status transaksi menjadi `draft_penimbangan`.
3. Cetak nomor antrian dari transaksi tersebut, pastikan format nomor antrian benar.
4. Pada transaksi `draft_penimbangan`, klik "Timbangan Kedua" / "Timbang Bertahap".
5. Lakukan timbang bertahap per item kertas bekas. Pastikan sisa tara dan berat bersih terhitung dengan rumus `berat_bersih = berat_kotor - tara`.
6. Klik "Selesai Penimbangan":
   - Kasus A (Ada barang > 100 kg): Status transaksi berubah ke `menunggu_qc`. Fuzzy tidak boleh terpanggil dari modul Penimbang.
   - Kasus B (Semua barang <= 100 kg): Status transaksi langsung ke `menunggu_pembayaran`.
7. Pastikan detail transaksi menampilkan data timbangan secara akurat.
