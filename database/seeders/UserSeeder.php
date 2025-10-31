<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'q',
            'email' => 'q@q.hu',
            'password' => 'q',
            'admin' => true,
        ]);

        // Test User
        User::create([
            'name' => 't',
            'email' => 't@t.hu',
            'password' => 't',
            'admin' => false,
        ]);

        User::factory()->count(5)->create();
    }
}
