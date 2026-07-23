<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $users = [
            ['name' => 'QC User', 'email' => 'qc@gmail.com', 'role' => 'qc'],
            ['name' => 'qc user', 'email' => 'qc2@gmail.com', 'role' => 'qc'],
            ['name' => 'Penimbang User', 'email' => 'penimbang@gmail.com', 'role' => 'penimbang'],
            ['name' => 'penimbang user', 'email' => 'penimbang2@gmail.com', 'role' => 'penimbang'],
            ['name' => 'Kasir User', 'email' => 'kasir@gmail.com', 'role' => 'kasir'],
            ['name' => 'kasir user', 'email' => 'kasir2@gmail.com', 'role' => 'kasir'],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['name' => $userData['name']],
                [
                    'email' => $userData['email'],
                    'password' => Hash::make('password123'),
                    'role' => $userData['role'],
                    'status' => 'aktif',
                ]
            );
        }

        // 2. Seed Kategori Kertas
        $kategori = [
            ['id' => 1, 'nama_kategori' => 'Kertas Industri', 'deskripsi' => 'Kertas bekas jenis industri seperti kardus, dupleks'],
            ['id' => 2, 'nama_kategori' => 'Kertas Kantor', 'deskripsi' => 'Kertas bekas jenis kantor seperti HVS, SWL, buku'],
        ];
        foreach ($kategori as $kat) {
            DB::table('kategori_kertas')->updateOrInsert(['id' => $kat['id']], $kat);
        }

        // 3. Seed Jenis Kertas Bekas
        $jenisKertas = [
            ['id' => 1, 'kategori_kertas_id' => 1, 'kode_jenis' => 'BOX', 'nama_jenis' => 'Box', 'harga_per_kg' => 2200.00, 'deskripsi' => 'Kardus bekas', 'status' => 'aktif'],
            ['id' => 2, 'kategori_kertas_id' => 1, 'kode_jenis' => 'DPLX', 'nama_jenis' => 'Duplex', 'harga_per_kg' => 1100.00, 'deskripsi' => 'Kertas dupleks', 'status' => 'aktif'],
            ['id' => 3, 'kategori_kertas_id' => 2, 'kode_jenis' => 'SWL', 'nama_jenis' => 'Swl', 'harga_per_kg' => 2500.00, 'deskripsi' => 'Small White Paper / HVS', 'status' => 'aktif'],
            ['id' => 4, 'kategori_kertas_id' => 2, 'kode_jenis' => 'BK', 'nama_jenis' => 'Buku', 'harga_per_kg' => 1800.00, 'deskripsi' => 'Buku bekas', 'status' => 'aktif'],
            ['id' => 5, 'kategori_kertas_id' => 2, 'kode_jenis' => 'KORAN', 'nama_jenis' => 'Koran', 'harga_per_kg' => 2000.00, 'deskripsi' => 'Koran bekas', 'status' => 'aktif'],
        ];
        foreach ($jenisKertas as $jk) {
            DB::table('jenis_kertas_bekas')->updateOrInsert(['id' => $jk['id']], $jk);
        }

        // 4. Seed Pelanggan
        $pelanggan = [
            ['id' => 1, 'kode_pelanggan' => 'PEL-001', 'nama_pelanggan' => 'Pak Budi', 'no_hp' => '081234567890', 'alamat' => 'Jl. Merdeka No. 12', 'kategori' => 'setia'],
            ['id' => 2, 'kode_pelanggan' => 'PEL-002', 'nama_pelanggan' => 'Ibu Siti', 'no_hp' => '089876543210', 'alamat' => 'Jl. Mawar No. 5', 'kategori' => 'biasa'],
            ['id' => 3, 'kode_pelanggan' => 'PEL-003', 'nama_pelanggan' => 'Mas Agus', 'no_hp' => '085512345678', 'alamat' => 'Jl. Anggrek No. 8', 'kategori' => 'baru'],
        ];
        foreach ($pelanggan as $p) {
            DB::table('pelanggan')->updateOrInsert(['id' => $p['id']], $p);
        }

        // 5. Seed Mitra Pengepul
        DB::table('mitra_pengepul')->updateOrInsert(
            ['id' => 1],
            ['nama_mitra' => 'CV Kertas Jaya', 'no_hp' => '081122334455', 'alamat' => 'Jl. Industri No. 45']
        );

        // 6. Seed Pemilik
        DB::table('pemilik')->updateOrInsert(
            ['id' => 1],
            ['nama_pemilik' => 'H. Slamet', 'no_hp' => '081299887766', 'alamat' => 'Jl. Utama No. 1']
        );
    }
}