<?php

use App\Todo;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TodoTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    public function test_get_existing_todo_returns_its_informations()
    {
        $todo = factory(Todo::class)->create();

        $this->json('GET', '/todos/' . $todo->id)
            ->seeStatusCode(200)
            ->seeJson([
                'title' => $todo->title,
                'description' => $todo->description
            ]);
    }

    public function test_get_non_existing_todo_returns_error()
    {
        $this->json('GET', '/todos/azeaze')
            ->seeStatusCode(404);
    }

    public function test_create_todo()
    {
        $data = [
            'title' => 'New todo',
            'description' => 'My super todo'
        ];

        $this->json('POST', '/todos', $data)
            ->seeStatusCode(201)
            ->seeJson($data);

        $this->seeInDatabase('todos', $data);
    }

    public function test_update_todo()
    {
        $todo = factory(Todo::class)->create();

        $this->json('PUT', '/todos/' . $todo->id, ['title' => 'Updated'])
            ->seeStatusCode(200);

        $updatedTodo = Todo::find($todo->id);

        $this->assertNotEquals($todo->title, $updatedTodo->title);
    }

    public function test_delete_todo()
    {
        $todo = factory(Todo::class)->create();

        $this->json('DELETE', '/todos/' . $todo->id)
            ->seeStatusCode(204);

        $this->notSeeInDatabase('todos', ['id' => $todo->id]);
    }
}
