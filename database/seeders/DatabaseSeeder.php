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
            'username' => 'admin',
            'password' => bcrypt('123'),
            'nama' => 'Admin',
            'role' => 'Admin',
        ]);

        $jabatan = [
            [
                'nama_jabatan' => 'Software Engineer'
            ],
            [
                'nama_jabatan' => 'Project Manager'
            ],
            [
                'nama_jabatan' => 'Quality Assurance'
            ],
            [
                'nama_jabatan' => 'UI/UX Designer'
            ],
            [
                'nama_jabatan' => 'DevOps Engineer'
            ],
        ];
        foreach ($jabatan as $j) {
            Jabatan::create($j);
        }
    }
}
