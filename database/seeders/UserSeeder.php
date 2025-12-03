<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'nama' => 'admin',
            'alamat' => 'jalan jalan',
            'telepon' => '08123456789',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin@123'),
            'jenis' => 'admin'
        ]);
    }
}
