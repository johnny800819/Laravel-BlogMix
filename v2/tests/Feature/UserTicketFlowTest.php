<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ServiceTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTicketFlowTest extends TestCase
{
    // use RefreshDatabase; // Use existing DB to avoid wiping

    public function test_user_can_create_and_manage_tickets()
    {
        // 1. Create User
        $user = User::where('email', 'ticket_user@example.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Ticket User',
                'email' => 'ticket_user@example.com',
                'password' => bcrypt('password'),
                'role' => 'user'
            ]);
        }

        // 2. Create Category
        $category = Category::firstOrCreate(
            ['slug' => 'test-support'],
            ['name' => 'Test Support', 'type' => 'ticket']
        );

        // 3. Login
        $token = $user->createToken('test')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        // 4. Create Ticket
        $response = $this->postJson('/api/tickets', [
            'subject' => 'Help me please', 
            'content' => 'I have an issue.',
            'category_id' => $category->id
        ], $headers);
        
        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'subject']); // verify basic structure
        
        // 5. List Tickets
        $this->getJson('/api/tickets', $headers)
             ->assertStatus(200)
             ->assertJsonFragment(['subject' => 'Help me please']);
             
        // 6. View Ticket
        $ticketId = $response->json('id');
        $this->getJson("/api/tickets/$ticketId", $headers)
             ->assertStatus(200)
             ->assertJson(['id' => $ticketId]);

        // 7. Reply
        $this->postJson("/api/tickets/$ticketId/replies", [
            'message' => 'User Reply'
        ], $headers)->assertStatus(201);
    }
}
