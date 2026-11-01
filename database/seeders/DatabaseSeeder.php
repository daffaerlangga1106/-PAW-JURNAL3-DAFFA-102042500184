<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Dosen
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'nomor_induk' => '198001012005011001',
            ]
        );

        // Akun Mahasiswa
        $mahasiswa = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Pramandhika Daffa Erlangga',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '102042500184',
            ]
        );

        // Mata kuliah contoh milik dosen
        $course = MataKuliah::updateOrCreate(
            ['kode_mk' => 'PAW'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mempelajari pengembangan aplikasi web berbasis framework Laravel.',
                'sks' => 3,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // Mahasiswa langsung berstatus approved pada mata kuliah contoh
        $course->students()->syncWithoutDetaching([
            $mahasiswa->id => ['status' => 'approved'],
        ]);

        // Tugas contoh
        $assignment = Assignment::updateOrCreate(
            ['course_id' => $course->id, 'judul' => 'Tugas 01 - Bikin Sistem Kasir pake Laravel'],
            [
                'deskripsi' => 'Buatlah sistem kasir sederhana menggunakan Laravel lengkap dengan CRUD produk.',
                'deadline' => '2026-10-22 23:59:00',
            ]
        );

        // Pengumpulan tugas contoh dari mahasiswa
        Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => $mahasiswa->id],
            [
                'jawaban' => 'Sistem kasir sudah dibuat dan kode dapat diakses pada repository GitHub saya.',
                'submitted_at' => '2026-10-20 10:00:00',
            ]
        );
    }
}