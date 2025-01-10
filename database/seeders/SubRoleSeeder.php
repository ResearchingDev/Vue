<?php

namespace Database\Seeders;

use App\Models\SubUserRole; // Assuming you have the SubUserRole model
use Illuminate\Database\Seeder;

class SubRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample roles (you can add as many as required)
        SubUserRole::create([
            'role_name' => 'Admin',
            'role_unique_code' => 'admin',
            'web_access' => 'Yes',
            'mobile_access' => 'Yes',
            'primary_access' => 'Yes',
            'status' => 'Active',
        ]);

        SubUserRole::create([
            'role_name' => 'Editor',
            'role_unique_code' => 'editor',
            'web_access' => 'Yes',
            'mobile_access' => 'No',
            'primary_access' => 'No',
            'status' => 'Active',
        ]);
    }
}
