<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase; 
    
    /** @test */
    public function a_user_can_retrieve_their_own_profile()
    {
        // 1. Arrange: create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user); // authenticating user

        // 2. Act: make an API request to the profile endpoint
        $response = $this->getJson('/api/v1/profile'); // getJson(): This helper method makes a GET request and automatically sets the 

        // 3. Assert: Make an API request to the profile endpoint
        $response->assertStatus(200)
                 ->assertJson([
                    'name'  => $user->name,
                    'email' => $user->email
                 ]);
    }
    
    /** @test */
    public function a_user_can_update_their_profile()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        $updateData = ['name' => 'Jane Doe', 'email' => 'jane@mail.com'];

        // Act
        $response = $this->putJson('/api/v1/profile', $updateData); // getJson(): This helper method makes a GET request and automatically sets the 

        //Assert
        $response->assertStatus(200)
                 ->assertJson([
                    'message' => 'Profile updated successfully!'
                 ]);


        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'Jane Doe',
            'email' => 'jane@mail.comx'
        ]); //
    }

    /** @test */
    public function a_user_can_soft_delete_their_account()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act
        $response = $this->deleteJson('/api/v1/profile'); // deleteJson(): Used to make a DELETE request.

        // Assert
        $response->assertStatus(200)
                 ->assertJson([
                    'message' => 'Account soft-deleted successfully!'
                 ]);

        $this->assertSoftDeleted(
            'users', ['id' => $user->id]
        ); // assertSoftDeleted(): A specific assertion for checking if a model has been soft-deleted.
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
