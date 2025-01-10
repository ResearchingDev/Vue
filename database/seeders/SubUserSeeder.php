<?php
namespace Database\Seeders;

use App\Models\User; // Assuming you have the SubUser model
use Illuminate\Database\Seeder;

class SubUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // First, ensure that Clients and Roles are seeded
        $this->call(SubClientSeeder::class); // Seed clients
        $this->call(SubRoleSeeder::class); // Seed roles

        // Now, create 10 users with associated roles and clients
        User::factory()->count(10)->create();
    }
}
