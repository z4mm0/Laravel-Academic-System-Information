<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WaliKelasSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Bu Wali Kelas 1',
            'email' => 'wali1@school.com',
            'password' => Hash::make('password123'),
            'role' => 'wali_kelas',
            'nohp' => '08111111111',
            'alamat' => 'Jl. Wali Kelas No.1',
        ]);

        echo "✅ Wali Kelas demo created: wali1@school.com / password123\n";
    }
}

