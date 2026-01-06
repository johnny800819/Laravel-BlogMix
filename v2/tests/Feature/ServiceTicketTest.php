<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ServiceTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_their_tickets()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        ServiceTicket::factory()->count(3)->create(['user_id' => $user->id]);
        ServiceTicket::factory()->count(2)->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->getJson('/api/tickets');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'user_id', 'subject', 'content', 'status', 'created_at']
                ]
            ]);
    }

    public function test_user_can_create_ticket()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['type' => 'ticket']);

        $data = [
            'subject' => 'Help me',
            'content' => 'I have a problem',
            'category_id' => $category->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/tickets', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('service_tickets', [
            'user_id' => $user->id,
            'subject' => 'Help me',
        ]);
    }

    public function test_admin_can_reply_to_ticket()
    {
        $user = User::factory()->create(['role' => 'admin']); 
        $token = $user->createToken('admin-test')->plainTextToken;
        $ticket = ServiceTicket::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->putJson("/api/admin/tickets/{$ticket->id}/reply", [
                'reply_content' => 'We are working on it.',
                'status' => 'replied',
             ]);

        $response->assertStatus(200)
            ->assertJson([
                'reply_content' => 'We are working on it.',
                'status' => 'replied',
            ]);
        
        $this->assertDatabaseHas('service_tickets', [
            'id' => $ticket->id,
            'reply_content' => 'We are working on it.',
             'status' => 'replied',
        ]);
    }
}
