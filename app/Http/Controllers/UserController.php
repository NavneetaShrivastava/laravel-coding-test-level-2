<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUser;
use App\Http\Requests\User\StoreUser;
use App\Http\Requests\User\PatchUser;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::orderBy('created_at', 'asc')->get();
    }

    public function store(StoreUser $form)
    {
        return response()->json($form->persist());
    }

    public function show(string $id)
    {
        try {
            $user = User::findorFail($id);
            return $user;
        } catch (\Exception $e) {
            return "User not Found";
        }
    }

    public function update(string $id, UpdateUser $form)
    {
        return response()->json($form->persist($id));
    }

    public function patch(string $id, PatchUser $form)
    {
        return response()->json($form->persist($id));
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findorFail($id);
            if ($user->delete()) {
                return response()->json([
                    'message' => 'User deleted successfully',
                    'statusCode' => 201
                ], 201);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' =>  'User not found',
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
