<?php

namespace Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;


    public function test_todos_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/todos');

        $response->assertOk();
    }

    public function test_user_can_create_todos(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/todos', [
                'title' => 'test todo',
                'description' => null
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirectContains('todos');

        $this->assertDatabaseHas('todos', [
            'title' => 'test todo'
        ]);
    }

    public function test_api_get_all_todos_by_user(): void
    {
        $user = User::factory()->create();
        Todo::factory(3)->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->actingAs($user)
            ->get("/api/todos/$user->id");

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertJsonCount(3);
    }
}
