<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TasksController extends Controller
{
    public function index() {
        $tasks = auth()->user()->tasks();
        return view('tasks.index', compact('tasks'));
    }
    public function add() {
        return view('tasks.add');
    }

    public function create(Request $request) {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->user_id = auth()->user()->id;
        $task->save();
        return redirect('/dashboard');
    }

    public function edit(Task $task) {
        if (auth()->user()->id == $task->user_id) {
            return view('tasks.edit', compact('task'));
        }
        else {
            return redirect('/dashboard');
        }
    }

    public function update(Request $request, Task $task) {
        if(isset($_POST['delete'])) {
            $task->delete();
            return redirect('/dashboard');
        }
        else {
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required'
            ]);
            $task->title = $request->title;
            $task->description = $request->description;
            $task->save();
            return redirect('/dashboard');
        }
    }
}
