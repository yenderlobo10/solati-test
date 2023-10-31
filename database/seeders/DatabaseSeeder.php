<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        User::create([
            'name' => 'Yender',
            'email' => 'yender@mail.io',
            'email_verified_at' => now(),
            'password' => '12345678', // password
            'remember_token' => Str::random(10),
        ]);
        \App\Models\Todo::factory(20)->create();
    }
}
