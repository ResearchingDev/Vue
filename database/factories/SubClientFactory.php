<?php 
namespace Database\Factories;

use App\Models\SubClient;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubClientFactory extends Factory
{
    protected $model = SubClient::class;

    public function definition()
    {
        return [
            'client_name' => $this->faker->company(), // Fake company name as client name
            'email' => $this->faker->unique()->companyEmail(), // Fake unique email
            'phone_number' => $this->faker->phoneNumber(), // Fake phone number
            'logo' => $this->faker->imageUrl(), // Fake logo URL
            'address' => $this->faker->address(), // Fake client address
            'city' => $this->faker->city(), // Fake city name
            'state' => $this->faker->state(), // Fake state name
            'zipcode' => $this->faker->postcode(), // Fake postal code
            'timezone' => $this->faker->timezone(), // Fake timezone
            'status' => $this->faker->randomElement(['Active', 'Inactive']), // Random status
            'created_by' => $this->faker->randomNumber(), // Fake created_by ID
            'updated_by' => $this->faker->randomNumber(), // Fake updated_by ID
            'deleted_at' => null, // Set to null (soft delete)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
