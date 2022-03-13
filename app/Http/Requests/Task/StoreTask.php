<?php

namespace App\Http\Requests\Task;

use App\Models\Task as ModelTask;
use App\Rules\ValidateTeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreTask extends FormRequest
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
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'project_id' => 'required|exists:projects,id',
            'user_id' => ['required', 'exists:users,id', new ValidateTeamMember],
        ];
    }

    public function persist()
    {
        try {
            $task = ModelTask::create(Request::except('status'));
            return response()->json([
                'message' => 'Task created successfully, With Status: ' . ModelTask::NOT_STARTED,
                'statusCode' => 201,
                'data' => $task
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' =>  $e->getMessage(),
                'statusCode' => 400
            ], 400);
        }
    }
}
