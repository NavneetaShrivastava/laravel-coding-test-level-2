<?php

namespace App\Http\Requests\User;

use App\Models\User as ModelUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;


class UpdateUser extends FormRequest
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
            'username' => ['required', Rule::unique('users')->ignore($this->username), 'regex:/^\S*$/u'],
            'password' => 'required', Password::min(8),
            'roles' => 'required|exists:roles,name'
        ];
    }

    public function persist($id)
    {
        DB::beginTransaction();
        try {
            $user = ModelUser::findOrFail($id);
            $user->update(Request::except('roles'));
            if (Request::has('roles')) {
                $user->syncRoles($this->roles);
            }
            DB::commit();

            return response()->json([
                'message' => 'User updated successfully',
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
