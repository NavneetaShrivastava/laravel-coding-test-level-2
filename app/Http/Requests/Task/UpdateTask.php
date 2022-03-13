<?php

namespace App\Http\Requests\Task;

use App\Models\Task as ModelTask;
use App\Helpers\RoleAndPermissionHelper;
use App\Rules\ValidateTeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateTask extends FormRequest
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
            'status' => [
                'sometimes',
                Rule::in([ModelTask::NOT_STARTED, ModelTask::IN_PROGRESS, ModelTask::READY_FOR_TEST, ModelTask::COMPLETED]),
            ],
            'project_id' => 'required|exists:projects,id',
            'user_id'=> ['sometimes','exists:users,id',new ValidateTeamMember],
        ];
    }

    public function persist($id)
    {
        try {
            $task = ModelTask::findOrFail($id);
            if( Request::has('user_id') && Auth()->user()->tokenCan(RoleAndPermissionHelper::PARTIAL_ACCESS_TASK_API_ABILITY)){
                return response()->json([
                    'error' =>  'Only Product Owner can do the Task assignment to Team Member',
                    'statusCode' => 401,
                ], 401);
            }

            if (
                Request::has('status') &&
                Auth()->user()->tokenCan(RoleAndPermissionHelper::PARTIAL_ACCESS_TASK_API_ABILITY) &&
                $task->user_id == Auth()->user()->id
            ) {
                $task->update(Request::all());
                return response()->json([
                    'message' => 'Task updated successfully',
                    'statusCode' => 201,
                    'data' => $task
                ], 201);
            }

            if(Auth()->user()->tokenCan(RoleAndPermissionHelper::ACCESS_ALL_TASK_API_ABILITY)){
                $task->update(Request::except('status'));
                return response()->json([
                    'message' => 'Task updated successfully, Except Status',
                    'statusCode' => 201,
                    'data' => $task
                ], 201);
            }
            return response()->json([
                'error' =>  'Unauthorised Action',
                'statusCode' => 401,
            ], 401);
        
        } 
        catch (\Exception $e) {
            return response()->json([
                'error' =>  $e->getMessage(),
                'statusCode' => 400,
            ], 400);
        }
    }
}
