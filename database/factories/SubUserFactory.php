<?php
namespace Database\Factories;

use App\Models\User;
use App\Models\SubClient;
use App\Models\SubUserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubUserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'client_id' => SubClient::factory(),
            'role_id' => SubUserRole::factory(),
            'username' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // Hashed password
            'secondary_password' => bcrypt('secondarypassword'),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone_number' => $this->faker->phoneNumber(),
            'alter_phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zipcode' => $this->faker->postcode(),
            'timezone' => $this->faker->timezone(),
            'user_type' => $this->faker->randomElement(['Super Admin', 'Client', 'User']),
            'can_login' => $this->faker->randomElement(['Yes', 'No']),
            'profile_picture' => $this->faker->imageUrl(),
            'remember_token' => Str::random(10),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'created_by' => $this->faker->randomNumber(),
            'updated_by' => $this->faker->randomNumber(),
            'deleted_at' => null, // For testing purposes, you can set it to null or use a Carbon instance to simulate soft deletion
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
