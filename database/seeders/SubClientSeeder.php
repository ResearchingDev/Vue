<?php

namespace Database\Seeders;

use App\Models\SubClient; // Assuming you have the SubClient model
use Illuminate\Database\Seeder;

class SubClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 sample clients
        SubClient::factory()->count(10)->create();
    }
}
