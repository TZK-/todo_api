<?php

use App\Todo;

class UserTest extends TestCase
{
    public function test_deleting_user_deletes_its_todos()
    {
        $todoCount = $this->user->todos()->count();

        factory(Todo::class, 3)->create(['user_id' => $this->user->id]);

        $this->assertEquals($todoCount + 3, $this->user->todos()->count());

        $this->assertTrue($this->user->delete());

        $this->notSeeInDatabase('users', ['id' => $this->user->id]);
        $this->notSeeInDatabase('todos', ['id' => $this->user->id]);
    }
}
