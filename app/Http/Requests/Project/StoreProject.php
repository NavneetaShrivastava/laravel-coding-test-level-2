<?php

namespace App\Http\Requests\Project;

use App\Models\Project as ModelProject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreProject extends FormRequest
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
            'name' => 'required|unique:projects|max:255',
        ];
    }

    public function persist()
    {
        try {
            $project = ModelProject::create(Request::all());
            return response()->json([
                'message' => 'Project created successfully',
                'statusCode' => 201,
                'data' => $project
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' =>  $e->getMessage(),
                'statusCode' => 417
            ], 417);
        }
    }
}
