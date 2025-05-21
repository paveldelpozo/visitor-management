<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VisitorControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_create_visitor(): void
    {
        $user = User::factory()->create()->assignRole('admin');

        $this->actingAs($user)
            ->postJson('/api/visitors', [
                'name' => 'Pavel',
                'surname' => 'Tester',
                'phone' => '123456789',
                'headphones' => 1
            ])
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Pavel']);
    }
}
