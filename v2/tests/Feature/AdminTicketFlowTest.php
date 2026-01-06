<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ServiceTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTicketFlowTest extends TestCase
{
    // use RefreshDatabase; 

    public function test_admin_can_manage_tickets()
    {
        // 1. Setup Admin
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
        }
        $adminToken = $admin->createToken('admin-test')->plainTextToken;
        $adminHeaders = ['Authorization' => "Bearer $adminToken"];

        // 2. Setup User & Ticket
        $category = Category::factory()->create(['type' => 'ticket']);
        $user = User::factory()->create();
        $ticket = ServiceTicket::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subject' => 'Issue for Admin',
            'content' => 'Please fix'
        ]);

        // 3. Admin List Tickets
        $this->getJson('/api/admin/tickets', $adminHeaders)
             ->assertStatus(200)
             ->assertJsonFragment(['subject' => 'Issue for Admin']);

        // 4. Admin View Ticket
        $this->getJson("/api/admin/tickets/{$ticket->id}", $adminHeaders)
             ->assertStatus(200)
             ->assertJson(['id' => $ticket->id]);

        // 5. Admin Reply
        $this->putJson("/api/admin/tickets/{$ticket->id}/reply", [
            'reply_content' => 'Admin Reply Content',
            'status' => 'replied'
        ], $adminHeaders)
             ->assertStatus(200)
             ->assertJson(['status' => 'replied', 'reply_content' => 'Admin Reply Content']);

        // 6. Admin Close
        $this->patchJson("/api/admin/tickets/{$ticket->id}/close", [], $adminHeaders)
             ->assertStatus(200)
             ->assertJson(['status' => 'closed']);
    }
}
