<?php

use App\Todo;

class TodoTest extends TestCase
{

    public function test_get_existing_todo_returns_its_information()
    {
        $todo = factory(Todo::class)->create([
            'user_id' => $this->user->id
        ]);

        $this->json('GET', '/todos/' . $todo->id, [])
            ->seeStatusCode(200)
            ->seeJson([
                'title' => $todo->title,
                'description' => $todo->description,
                'user_id' => "{$this->user->id}"
            ]);
    }

    public function test_get_non_existing_todo_returns_error()
    {
        $this->json('GET', '/todos/azeaze', [])
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

        $this->seeInDatabase('todos', array_merge($data, ['user_id' => $this->user->id]));
    }

    public function test_update_todo()
    {
        $todo = factory(Todo::class)->create();

        $this->json('PUT', '/todos/' . $todo->id, ['title' => 'Updated'])
            ->seeStatusCode(200);

        $updatedTodo = Todo::find($todo->id);

        $this->assertNotEquals($todo->title, $updatedTodo->title);
        $this->assertEquals($todo->id, $updatedTodo->id);
    }

    public function test_delete_todo()
    {
        $todo = factory(Todo::class)->create();

        $this->json('DELETE', '/todos/' . $todo->id, [])
            ->seeStatusCode(204);

        $this->notSeeInDatabase('todos', ['id' => $todo->id]);
    }

}
