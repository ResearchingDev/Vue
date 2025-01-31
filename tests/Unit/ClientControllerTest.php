<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\SubUserRole;
use App\Models\UserPermission;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_a_new_client_and_user()
    {
        // Prepare the test environment
        Storage::fake('public');

        // Create necessary roles and permissions
        $roles = SubUserRole::factory()->count(3)->create([
            'role_unique_code' => 'client',
        ]);

        UserPermission::factory()->create([
            'role_id' => $roles->first()->id,
        ]);

        // Prepare input data
        $data = [
            'client_name' => 'Test Client',
            'email' => 'client@example.com',
            'phone_number' => '1234567890',
            'alternate_phone_number' => '0987654321',
            'address' => '123 Test Street',
            'status' => 'Active',
            'username' => 'testclient',
            'password' => 'password123',
            'profile_picture' => UploadedFile::fake()->image('profile.jpg'),
        ];

        // Make the request
        $response = $this->postJson(route('api.admin.client.store'), $data);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Client and User created successfully',
                'data' => [
                    'sub_client' => [
                        'client_name' => $data['client_name'],
                        'email' => $data['email'],
                    ],
                ],
            ]);

        // Assert the database has the records
        $this->assertDatabaseHas('sub_clients', [
            'client_name' => $data['client_name'],
            'email' => $data['email'],
        ]);

        $this->assertDatabaseHas('users', [
            'username' => $data['username'],
            'email' => $data['email'],
        ]);

        // Assert the file was uploaded
        Storage::disk('public')->assertExists('profile_pictures/' . $data['profile_picture']->hashName());
    }
}
