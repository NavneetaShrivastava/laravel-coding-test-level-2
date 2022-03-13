<?php

namespace App\Http\Requests\User;

use App\Models\User as ModelUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class StoreUser extends FormRequest
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
            'username' => 'required|unique:users|max:255|regex:/^\S*$/u',
            'password' => ['required', Password::min(8), 'alpha_dash'],
            'roles' =>  'required|exists:roles,name'
        ];
    }

    public function persist()
    {
        DB::beginTransaction();
        try {
            $user = ModelUser::create(Request::except('roles'));
            $user->assignRole($this->roles);
            $user = $user->makeHidden(['roles']);
            $user->assignedRole = $user->getRoleNames();
            DB::commit();
            return response()->json([
                'message' => 'User created successfully',
                'statusCode' => 201,
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' =>  $e->getMessage(),
                'statusCode' => 417
            ], 417);
        }
    }
}
