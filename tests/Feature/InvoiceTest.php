<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

        public function test_user_can_create_invoice()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');
        
        // create a customer belonging to this user
        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $response = $this->postJson('/api/v1/invoices', [
            'amount' => 1000.00,
            'status' => 'billed',
            'billed_date' => now()->toDateTimeString(),
            'paid_date' => now()->toDateTimeString(),
            'customer_id' => $customer->id,
        ]);

        $response->dump(); // 👈 shows the response in the test output

        // $response->assertStatus(200)
        //          ->assertJson([
        //              'message' => 'Create invoice successfully!'
        // ]);
    }
}
