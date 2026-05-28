<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::transaction(function () {
            // SuperAdmin
            User::create([
                'name' => 'Super Admin Utama',
                'email' => 'superadmin@school.com',
                'password' => bcrypt('password123'),
                'role' => 'superadmin',
                'nohp' => '08999999999',
                'alamat' => 'Kantor Pusat Sekolah, Jakarta',
            ]);

            // Guru
            $guru1 = User::create([
                'name' => 'Bu Siti Nurhaliza',
                'email' => 'guru1@school.com',
                'password' => bcrypt('password123'),
'role' => 'admin',
                'nohp' => '08123456789',
                'alamat' => 'Jl. Pendidikan No. 1, Jakarta',
            ]);

            // Kelas
            $kelas1 = ClassModel::create([
                'name' => 'X RPL 1',
                'admin_id' => $guru1->id,
            ]);

            // Subject sample
            Subject::create(['name' => 'Pemrograman Web', 'admin_id' => $guru1->id]);
        });
    }

    public function down()
    {
        User::whereIn('email', ['superadmin@school.com', 'guru1@school.com'])->delete();
        ClassModel::where('name', 'X RPL 1')->delete();
    }
};

