<?php

namespace App\Http\Controllers;

use App\Helpers\RoleAndPermissionHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = User::where('username', $request->username)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                $abilities = $this->getAbilities($user);
                $token = $user->createToken('Laravel Password Grant Client', $abilities)->plainTextToken;
                $response = ['token' => $token];

                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    private function getAbilities($user)
    {
        $abilities = [];
        if (isset($user->getRoleNames()[0]) && $user->getRoleNames()[0] == RoleAndPermissionHelper::ADMIN_ROLE) {
            $abilityToBeAssigned = [RoleAndPermissionHelper::ACCESS_ALL_USER_API_ABILITY];
            return array_merge($abilities, $abilityToBeAssigned);
        }
        if (isset($user->getRoleNames()[0]) && $user->getRoleNames()[0] == RoleAndPermissionHelper::PRODUCT_OWNER_ROLE) {
            $abilityToBeAssigned = [RoleAndPermissionHelper::ACCESS_ALL_TASK_API_ABILITY, RoleAndPermissionHelper::ACCESS_ALL_PROJECT_API_ABILITY];
            return array_merge($abilities, $abilityToBeAssigned);
        }
        if (isset($user->getRoleNames()[0]) && $user->getRoleNames()[0] == RoleAndPermissionHelper::TEAM_MEMBER_ROLE) {
            $abilityToBeAssigned = [RoleAndPermissionHelper::PARTIAL_ACCESS_TASK_API_ABILITY];
            return array_merge($abilities, $abilityToBeAssigned);
        }
    }
}
