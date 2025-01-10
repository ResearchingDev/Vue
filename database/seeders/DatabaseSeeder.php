<?php

namespace Database\Seeders;

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
        $this->call([
            SubClientSeeder::class,  // Seed SubClient first
            SubRoleSeeder::class, // Then seed SubUserRole
            SubUserSeeder::class,   // Finally, seed SubUser
        ]);
    }
}
