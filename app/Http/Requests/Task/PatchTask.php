<?php

namespace App\Http\Requests\Task;

use App\Models\Task as ModelTask;
use App\Helpers\RoleAndPermissionHelper;
use App\Rules\ValidateTeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PatchTask extends FormRequest
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
            'title' => 'sometimes|max:255',
            'description' => 'sometimes|max:255',
            'status' => 'sometimes',
            'project_id' => 'sometimes|exists:projects,id',
            'user_id' => ['sometimes', 'exists:users,id', new ValidateTeamMember],
        ];
    }

    public function persist($id)
    {
        try {
            $task = ModelTask::findOrFail($id);

            if( Request::has('user_id') && Auth()->user()->tokenCan(RoleAndPermissionHelper::PARTIAL_ACCESS_TASK_API_ABILITY)){
                return response()->json([
                    'error' =>  'Team Member can not assign tasks to anyone',
                    'statusCode' => 401,
                ], 401);
            }

            if (
                Request::has('status') &&
                Auth()->user()->tokenCan(RoleAndPermissionHelper::PARTIAL_ACCESS_TASK_API_ABILITY) &&
                $task->user_id == Auth()->user()->id
            ) {
                $task->update(Request::only('title', 'description', 'status', 'project_id', 'user_id'));
                return response()->json([
                    'message' => 'Task patched successfully',
                    'statusCode' => 201,
                    'data' => $task
                ], 201);
            }
            if (Auth()->user()->tokenCan(RoleAndPermissionHelper::ACCESS_ALL_TASK_API_ABILITY)) {
                $task->update(Request::only('title', 'description', 'project_id', 'user_id'));
                return response()->json([
                    'message' => 'Task patched successfully, without Status',
                    'statusCode' => 201,
                    'data' => $task
                ], 201);
            }
            return response()->json([
                'error' =>  'Unauthorised Action',
                'statusCode' => 417,
            ], 417);
        } catch (Exception $e) {
            return response()->json([
                'error' =>  $e->getMessage(),
                'statusCode' => 417
            ], 417);
        }
    }
}
