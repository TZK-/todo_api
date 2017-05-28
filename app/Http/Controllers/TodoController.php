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
        $todos = $this->request->user()->todos();

        if($this->request->get('filter_type') && $this->request->get('filter_value')) {
            $todos = $todos->where(
                $this->request->get('filter_type'),
                'LIKE',
                "%{$this->request->get('filter_value')}%"
            );
        }

        return response()->json(["todos" => $todos->get()], 200);
    }

    public function show($id) {
        $todo = $this->request->user()->todos()
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($todo, 200);
    }

    public function create() {
        $this->validate($this->request, $this->rules);

        return response()->json($this->request->user()->todos()->create($this->request->all()), 201);
    }

    public function update($id) {
        $this->validate($this->request, $this->rules);

        $todo = Todo::findOrFail($id);
        $todo->update($this->request->all());
        $todo->save();

        return response()->json($todo, 200);
    }

    public function delete($id) {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return response()->json([], 204);
    }
}
