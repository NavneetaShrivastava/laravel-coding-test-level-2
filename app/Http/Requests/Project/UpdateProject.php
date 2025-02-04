<?php

namespace App\Http\Requests\Project;

use App\Models\Project as ModelProject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class UpdateProject extends FormRequest
{
    public function __construct(
        Request $request
    ) {
        parent::__construct();
        $this->request = $request;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('projects')->ignore($this->name)],
        ];
    }

    public function persist($id)
    {
        try {
            $project = ModelProject::findOrFail($id);
            $project->update(Request::all());
            return response()->json([
                'message' => 'Project updated successfully',
                'statusCode' => 201,
                'data' => $project
            ], 201);
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
