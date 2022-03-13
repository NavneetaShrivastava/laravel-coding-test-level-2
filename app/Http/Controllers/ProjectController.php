<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\UpdateProject;
use App\Http\Requests\Project\StoreProject;
use App\Http\Requests\Project\PatchProject;
use App\Http\Requests\Project\IndexProject;
use App\Models\Project;
use Exception;

class ProjectController extends Controller
{
    public function index(IndexProject $form)
    {
        return response()->json($form->data());
    }

    public function store(StoreProject $form)
    {
        return response()->json($form->persist());
    }

    public function show(string $id)
    {
        try {
            $project = Project::findorFail($id);
            return $project;
        } catch (Exception $e) {
            return 'Project Not Found';
        }
    }

    public function update(string $id, UpdateProject $form)
    {
        return response()->json($form->persist($id));
    }

    public function patch(string $id, PatchProject $form)
    {
        return response()->json($form->persist($id));
    }

    public function destroy(string $id)
    {
        try {
            $Project = Project::findorFail($id);
            if ($Project->delete()) {
                return response()->json([
                    'message' => 'Project deleted successfully',
                    'statusCode' => 201
                ], 201);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' =>  'Project not found',
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
