<?php

namespace Database\Seeders;
use app\Models\User
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administator',
            'email' => 'Admin@kantor.com',
            'password' => Hash::make('Admin123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Karyawan',
            'email' => 'user@kantor.com',
            'password' => Hash::make('12345'),
            'role' => 'user'
        ]);
    }
    
}
