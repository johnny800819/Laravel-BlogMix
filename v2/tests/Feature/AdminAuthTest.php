<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    // use RefreshDatabase; // Don't wipe DB, use existing or create temp

    public function test_admin_can_login()
    {
        $user = User::where('email', 'admin@example.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
        } else {
             $user->update([
                'password' => bcrypt('password'),
                'role' => 'admin'
             ]);
        }

        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
            'device_name' => 'test_device'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'user']);
    }
}
