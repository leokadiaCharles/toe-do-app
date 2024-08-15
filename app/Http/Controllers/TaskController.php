<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Log; // Import the Log model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('completed')->get();
        $users = User::all();
        return view('tasks.index', compact('tasks', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id', // Ensure all user_ids are valid
        ]);
        $task = Task::create($request->only('title', 'description', 'deadline') + [
            'user_id' => auth()->id() // Set user_id to the authenticated user's ID
        ]);

        // Attach users to the task with the deadline from the task
        if ($request->has('user_ids')) {
            foreach ($request->input('user_ids') as $user_id) {
                \DB::table('user_tasks')->insert([
                    'task_id' => $task->id,
                    'user_id' => $user_id,
                    'deadline' => $task->deadline, // Populate deadline from task
                    'supervisor' => $request->input('supervisor', null), // Optional supervisor field
                    'status' => 'open', // Default status
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('tasks.index');
    }

    public function update(Request $request, Task $task)
    {
        $user = auth()->user(); // Get the current user

        if ($request->has('modal_form')) {
            $task->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'deadline' => $request->input('deadline'),
                'completed' => $request->has('completed'),
            ]);

            // Sync users with the updated task and update the deadline in the pivot table
            if ($request->has('user_ids')) {
                foreach ($request->input('user_ids') as $user_id) {
                    \DB::table('user_tasks')
                        ->updateOrInsert(
                            ['task_id' => $task->id, 'user_id' => $user_id],
                            [
                                'deadline' => $task->deadline,
                                'status' => $task->completed ? 'closed' : 'open', // Update status based on completion
                                'updated_at' => now()
                            ]
                        );
                }
            }

            
        } else {
            $task->update([
                'completed' => $request->has('completed'),
            ]);

            // Update the status in the pivot table based on the task's completion status
            if ($task->completed) {
                \DB::table('user_tasks')
                    ->where('task_id', $task->id)
                    ->update(['status' => 'closed', 'updated_at' => now()]);
            } else {
                \DB::table('user_tasks')
                    ->where('task_id', $task->id)
                    ->where('deadline', '>=', now()) // Ensure the deadline is not reached
                    ->update(['status' => 'open', 'updated_at' => now()]);
            }

            

            if ($request->has('modal_form')) {
                $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'deadline' => 'nullable|date',
                ]);
        
                $task->update([
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'deadline' => $request->input('deadline'),
                    'completed' => $request->has('completed'),
                ]);
        
                return redirect()->route('tasks.index');
            }
        }

        return redirect()->route('tasks.index');
    }

   
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

