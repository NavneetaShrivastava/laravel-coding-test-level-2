<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\UpdateTask;
use App\Http\Requests\Task\StoreTask;
use App\Http\Requests\Task\PatchTask;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        return Task::orderBy('created_at', 'asc')->get();
    }

    public function store(StoreTask $form)
    {
        return response()->json($form->persist());
    }

    public function show(string $id)
    {
        try {
            $task = Task::findorFail($id);
            return $task;
        } catch (\Exception $e) {
            return "Task Not Found";
        }
    }

    public function update(string $id, UpdateTask $form)
    {
        return response()->json($form->persist($id));
    }

    public function patch(string $id, PatchTask $form)
    {
        return response()->json($form->persist($id));
    }

    public function destroy(string $id)
    {
        try {
            $Task = Task::findorFail($id);
            if ($Task->delete()) {
                return response()->json([
                    'message' => 'Task deleted successfully',
                    'statusCode' => 201
                ], 201);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' =>  'Task not found',
                'statusCode' => 417
            ], 417);
        } catch (\Exception $e) {
            return response()->json([
                'error' =>  $e->getMessage(),
                'statusCode' => 417
            ], 417);
        }
    }
}
