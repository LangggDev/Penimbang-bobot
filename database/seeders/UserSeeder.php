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
        User::updateOrCreate(
            ['name' => 'QC User'],
            [
                'password' => Hash::make('password123'),
                'role' => 'qc',
                'status' => 'aktif',
            ]
        );

        User::updateOrCreate(
            ['name' => 'Penimbang User'],
            [
                'password' => Hash::make('password123'),
                'role' => 'penimbang',
                'status' => 'aktif',
            ]
        );

        User::updateOrCreate(
            ['name' => 'Kasir User'],
            [
                'password' => Hash::make('password123'),
                'role' => 'kasir',
                'status' => 'aktif',
            ]
        );
    }
}
