<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'nama' => 'Super Admin',
            'jabatan' => 'Super Admin',
            'status' => '',
            'status_kerja' => '',
            'nik' => '',
            'umur' => '20',
            'telepon' => '0812343710',
            'alamat' => 'Jl. Sukamaju',
            'username' => 'sa',
            'password' => bcrypt('123'),
        ]);

        $jabatan = [
            ['nama_jabatan' => 'Mining Engineer'],
            ['nama_jabatan' => 'Geologist'],
            ['nama_jabatan' => 'Safety Officer'],
            ['nama_jabatan' => 'Blasting Engineer']
        ];
        foreach ($jabatan as $j) {
            Jabatan::create($j);
        }
        User::create([
            'nama' => 'Karyawan 1',
            'jabatan' => 'Karyawan',
            'divisi_id' => 2,
            'status' => '',
            'status_kerja' => '',
            'nik' => '',
            'umur' => '20',
            'telepon' => '0812343710',
            'alamat' => 'Jl. Sukamaju',
            'username' => 'karyawan',
            'password' => bcrypt('123'),
        ]);
    }
}
