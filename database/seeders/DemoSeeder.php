<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ========== CREATE SUPERADMIN ==========
        $superadmin = User::create([
            'name' => 'Super Admin Utama',
            'email' => 'superadmin@school.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'nohp' => '08999999999',
            'alamat' => 'Kantor Pusat Sekolah, Jakarta',
        ]);

        // ========== CREATE TEACHERS/ADMIN ==========
        $guru1 = User::create([

'name' => 'Bu Siti Nurhaliza',
            'email' => 'guru1@school.com',
            'password' => Hash::make('password123'),
            'role' => 'guru_mapel',
            'nohp' => '08123456789',
            'alamat' => 'Jl. Pendidikan No. 1, Jakarta',
        ]);


        $guru2 = User::create([

'name' => 'Pak Budi Santoso',
            'email' => 'guru2@school.com',
            'password' => Hash::make('password123'),
            'role' => 'guru_mapel',
            'nohp' => '08234567890',
            'alamat' => 'Jl. Muda No. 5, Jakarta',
        ]);


        $guru3 = User::create([
            'name' => 'Ibu Rina Wijaya',
            'email' => 'guru3@school.com',
            'password' => Hash::make('password123'),
            'role' => 'guru_mapel',
            'nohp' => '08345678901',
            'alamat' => 'Jl. Reformasi No. 12, Jakarta',
        ]);

        // ========== CREATE STUDENTS ==========
        // Kelas 1: X RPL 1 (Guru 1) - 2 siswa
        $siswa1 = User::create([
            'name' => 'Ahmad Rizki',
            'email' => 'siswa1@school.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'nohp' => '08312345678',
            'alamat' => 'Jl. Jenderal No. 10',
        ]);

        $siswa2 = User::create([
            'name' => 'Siti Fatimah',
            'email' => 'siswa2@school.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'nohp' => '08412345678',
            'alamat' => 'Jl. Diponegoro No. 15',
        ]);

        // Kelas 2: X RPL 2 (Guru 2) - 2 siswa
        $siswa3 = User::create([
            'name' => 'Rudi Hermawan',
            'email' => 'siswa3@school.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'nohp' => '08512345678',
            'alamat' => 'Jl. Gatot Subroto No. 20',
        ]);

        $siswa4 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'siswa4@school.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'nohp' => '08612345678',
            'alamat' => 'Jl. Sultan Iskandar No. 8',
        ]);

        // Kelas 3: XI RPL 1 (Guru 3) - 2 siswa
        $siswa5 = User::create([
            'name' => 'Yoga Pratama',
            'email' => 'siswa5@school.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'nohp' => '08712345678',
            'alamat' => 'Jl. Sudirman No. 25',
        ]);

        $siswa6 = User::create([
            'name' => 'Ani Kusuma',
            'email' => 'siswa6@school.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'nohp' => '08812345678',
            'alamat' => 'Jl. Tanjungsari No. 18',
        ]);

        // ========== CREATE CLASSES ==========
        $kelas1 = ClassModel::create([
            'name' => 'X RPL 1',
            'admin_id' => $guru1->id,
        ]);

        $kelas2 = ClassModel::create([
            'name' => 'X RPL 2',
            'admin_id' => $guru2->id,
        ]);

        $kelas3 = ClassModel::create([
            'name' => 'XI RPL 1',
            'admin_id' => $guru3->id,
        ]);

        // ========== ASSIGN STUDENTS TO CLASSES ==========
        $kelas1->students()->attach([$siswa1->id, $siswa2->id]);
        $kelas2->students()->attach([$siswa3->id, $siswa4->id]);
        $kelas3->students()->attach([$siswa5->id, $siswa6->id]);

        // ========== CREATE SUBJECTS ==========
        Subject::create(['name' => 'Pemrograman Web', 'admin_id' => $guru1->id]);
        Subject::create(['name' => 'Basis Data', 'admin_id' => $guru1->id]);
        Subject::create(['name' => 'Jaringan Komputer', 'admin_id' => $guru2->id]);
        Subject::create(['name' => 'Sistem Operasi', 'admin_id' => $guru2->id]);
        Subject::create(['name' => 'Cloud Computing', 'admin_id' => $guru3->id]);
        Subject::create(['name' => 'Mobile Programming', 'admin_id' => $guru3->id]);

        // ========== CREATE GRADES (sample) ==========
        Grade::create(['student_id' => $siswa1->id, 'subject_id' => 1, 'admin_id' => $guru1->id, 'nilai' => 85]);
        Grade::create(['student_id' => $siswa2->id, 'subject_id' => 1, 'admin_id' => $guru1->id, 'nilai' => 92]);
        // ... more grades omitted for brevity

        echo "\n✅ Demo data seeding selesai! (plaintext passwords)\n";
        echo "\n🔑 SUPERADMIN:\n";
        echo "  superadmin@school.com / password123\n\n";
        echo "  GURU:\n";
        echo "    guru1@school.com / password123\n";
        echo "    guru2@school.com / password123\n";
        echo "    guru3@school.com / password123\n\n";
        echo "  SISWA:\n";
        echo "    siswa1@school.com / password123\n";
  
        }   }
        // ... list all
