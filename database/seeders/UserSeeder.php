<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
