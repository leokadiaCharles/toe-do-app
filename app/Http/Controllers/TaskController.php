<?php

namespace App\Http\Controllers;
use App\Models\Task;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
         $tasks = Task::orderBy('completed')->get();

        // $tasks = Task::where('completed', '1')->get();

        return view('tasks.index', compact('tasks'));
        
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        Task::create($request->only('title'));
        return redirect()->route('tasks.index');
    }

    // `update function
    public function update(Request $request, Task $task)
    {
        if ($request->has('title')) {
            // This update is coming from the modal form

            $request->validate(['title' => 'required|string|max:255']);
    
            $task->update([
                'title' => $request->input('title'),
                'completed' => $request->has('completed'),
            ]);
        } else {
            // This update is coming from the checkbox
            $task->update(['completed' => $request->has('completed')]);
        }
    
        return redirect()->route('tasks.index');
    }

    // `delete `funcion

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }

    public function edit(Task $task)
{
    return view('tasks.edit', compact('task'));
}


}