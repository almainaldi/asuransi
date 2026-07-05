<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Akun untuk Role User
        User::create([
            'name' => 'Budi Pemohon',
            'email' => 'user@mail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Akun untuk Role Verifier
        User::create([
            'name' => 'Vera Verifikator',
            'email' => 'verifier@mail.com',
            'password' => Hash::make('password'),
            'role' => 'verifier',
        ]);

        // Akun untuk Role Approver
        User::create([
            'name' => 'Agus Approver',
            'email' => 'approver@mail.com',
            'password' => Hash::make('password'),
            'role' => 'approver',
        ]);
    }
}
