<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'aessaputra@yahoo.com'],
            [
                'name' => 'Admin Catering',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone' => '085155090800',
            ]
        );
    }
}
