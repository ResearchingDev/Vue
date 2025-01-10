<?php
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a successful login attempt.
     *
     * @return void
     */
    public function test_successful_login()
    {
        User::factory()->create([
            "email"=> "test@example.com",
            "password"=> Hash::make('12332342')
        ]);
        // Act: Attempt to log in with correct credentials
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => '12332342',
            'remember_me' => false,
            
        ]);
        $jsonResponse = $response->json();  // Returns the response content as an array
        $statusMessage = $jsonResponse['status'];  // Returns the response content as an array
        $responseMessage = $jsonResponse['message'];  // Returns the response content as an array
        $this->assertEquals("success", $statusMessage);
        // Assert: Check that the user is authenticated and a token is returned
        $response->assertStatus(200);
        $this->assertStringContainsString('successful', $responseMessage);
    }

    /**
     * Test failed login with incorrect credentials.
     *
     * @return void
     */
    public function test_failed_login_with_empty_credentials()
    {
        // Act: Attempt to log in with incorrect password
        $response = $this->postJson('/api/login', [
            'email' => '',
            'password' => '',
        ]);
        // Assert: Check that login fails with a 401 Unauthorized status
        $response->assertStatus(422);
        $jsonResponse = $response->json();  // Returns the response content as an array
        $statusMessage = $jsonResponse['status'];  // Returns the response content as an array
        $this->assertEquals('error', $statusMessage);
    }
}
