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
        
        if($this->request->get('status') !== null) {
            $todos->where('ended', $this->request->get('status')); 
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

        return response()->json(
            $this->request->user()->todos()->create($this->request->all()), 
            201
        );
    }

    public function update($id) {

        $todo = Todo::findOrFail($id);
        $todo->update(['ended' => '1']);

        return response()->json($todo, 200);
    }

    public function delete($id) {
        $todo = Todo::findOrFail($id);

        return response()->json(['success' => $todo->delete()], 200);
    }
}
