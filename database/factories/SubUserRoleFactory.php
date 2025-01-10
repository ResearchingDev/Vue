<?php
namespace Database\Factories;

use App\Models\SubUserRole;
use App\Models\SubClient;  // Assuming the SubUserRole has a relation with the Client model
use Illuminate\Database\Eloquent\Factories\Factory;

class SubUserRoleFactory extends Factory
{
    protected $model = SubUserRole::class;

    public function definition()
    {
        return [
            'client_id' => SubClient::factory(), // Assuming the client_id is related to the clients table
            'role_name' => $this->faker->word(), // Fake role name (e.g., Admin, User, etc.)
            'role_unique_code' => $this->faker->unique()->word(), // Fake unique code for the role
            'web_access' => $this->faker->randomElement(['Yes', 'No']),
            'mobile_access' => $this->faker->randomElement(['Yes', 'No']),
            'primary_access' => $this->faker->randomElement(['Yes', 'No']),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'created_by' => $this->faker->randomNumber(),
            'updated_by' => $this->faker->randomNumber(),
            'deleted_at' => null, // Use null for testing, or set it to a date if you want to test soft deletes
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
