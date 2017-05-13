<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    protected $rules = [
        'title' => 'required|min:3'
    ];

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() {
        return response()->json(Todo::all(), 200);
    }

    public function show($id) {
        return response()->json(Todo::findOrFail($id), 200);
    }

    public function create() {
        $this->validate($this->request, $this->rules);

        return response()->json(Todo::create($this->request->all()), 201);
    }

    public function update($id) {
        $this->validate($this->request, $this->rules);

        $todo = Todo::findOrFail($id);
        $todo->update($this->request->all());
        $todo->save();

        return response()->json(Todo::create($this->request->all()), 200);
    }

    public function delete($id) {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return response()->json([], 204);
    }
}
