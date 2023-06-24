<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::orderBy('id', 'desc')->get();

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'no data found'], 204);
        }

        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'deadline' => 'required|date',
        ]);

        $task = Task::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'deadline' => $validated['deadline'],
            'is_finished' => 0,
            'user_id' => $request->user()->id,
        ]);

        if ($task) {
            return response()->json($task, 201);
        }

        return response()->json(['message' => 'Bad request'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task = Task::find($task->id);

        if (!$task) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'deadline' => 'required|date',
        ]);

        /**
         * @TODO 1 turn this into a gate to verify if user is authorized to update or delete task.
         */
        if ($request->user()->id != $task->user_id) {
            return response()->json(['message' => 'Not owner of the task'], 401);
        }

        $task->name = $validated['name'];
        $task->description = $validated['description'];
        $task->deadline = $validated['deadline'];

        $task->save();

        return response()->json(['message' => "task $task->id updated succesfully"], 200);
    }

    public function finish(Task $task)
    {

        /**
         * @TODO 1 turn this into a gate to verify if user is authorized to update or delete task.
         */
        if (Auth::user()->id != $task->user_id) {
            return response()->json(['message' => 'Not owner of the task'], 401);
        }

        $task->is_finished = 1;
        $task->save();


        if (date('Y-m-d') > $task->deadline) {
            return response()->json(['message' => "task $task->id finished, but you missed the deadline"], 200);
        } else {
            return response()->json(['message' => "task $task->id finished succesfully within Deadline"], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {

        $task = Task::find($task->id);

        /**
         * @TODO 1 turn this into a gate to verify if user is authorized to update or delete task.
         */
        if (Auth::user()->id != $task->user_id) {
            return response()->json(['message' => 'Not owner of the task'], 401);
        }

        $task->delete();

        return response()->json(['message' => "task $task->id deleted successfully."], 200);
    }
}
