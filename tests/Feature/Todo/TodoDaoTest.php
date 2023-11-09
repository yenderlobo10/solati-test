<?php

namespace Feature\Todo;

use App\Dao\TodoDao;
use App\Dao\TodoDaoImpl;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoDaoTest extends TestCase
{
    use RefreshDatabase;

    protected TodoDao $todoDao;

    protected function setUp(): void
    {
        parent::setUp();

        $this->todoDao = $this->app->make(TodoDaoImpl::class);
    }


    public function test_create_todo_in_db_successfully_when_model_is_valid(): void
    {
        $user = User::factory()->create();
        $response = $this->todoDao->create(
            userId: $user->id,
            title: '',
            description: ''
        );

        $this->assertModelExists($response);
        $this->assertInstanceOf(Todo::class, $response);
    }

    public function test_create_todo_in_db_failed_when_model_is_not_valid(): void
    {
        $this->expectException(QueryException::class);
        $this->expectException(\TypeError::class);

        $this->todoDao->create(
            userId: 1,
            title: null
        );
    }

    public function test_update_todo_in_db_successfully(): void
    {
        $todo = $this->createUserWithTodo();
        $response = $this->todoDao->update(
            id: $todo->id,
            title: 'new title',
            finished: !$todo->finished,
            description: $todo->description
        );

        $this->assertInstanceOf(Todo::class, $response);
        $this->assertDatabaseHas('todos', [
            'title' => 'new title'
        ]);
    }

    public function test_update_todo_in_db_failed_when_todo_id_not_found(): void
    {
        $this->expectException(\Error::class);

        $this->todoDao->update(
            id: 1,
            title: 'new title',
            finished: true
        );
    }

    public function test_delete_todo_in_db_successfully(): void
    {
        $todo = $this->createUserWithTodo();
        $response = $this->todoDao->delete(id: $todo->id);

        $this->assertModelMissing($todo);
        $this->assertEquals($todo->id, $response);
    }

    public function test_delete_todo_in_db_failed_when_not_found(): void
    {
        $response = $this->todoDao->delete(id: 1);

        $this->assertEquals(0, $response);
    }

    public function test_get_all_todos_by_user_in_db(): void
    {
        $users = User::factory(2)->create();
        $userId = $users->first()->id;
        Todo::factory(2)->create([
            'user_id' => 2
        ]);
        Todo::factory(3)->create([
            'user_id' => $userId
        ]);

        $response = $this->todoDao->allByUser($userId);

        $this->assertDatabaseCount('todos', 5);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    private function createUserWithTodo(): Todo
    {
        $user = User::factory()->create();
        return Todo::factory()->create([
            'user_id' => $user->id
        ]);
    }
}
