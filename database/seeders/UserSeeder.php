<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin 2',
            'email' => 'gym@prasasti.my.id',
            'password' => bcrypt('prasasti2021'),
            'email_verified_at' => now(),
        ]);
    }
}
