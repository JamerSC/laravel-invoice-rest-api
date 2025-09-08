<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // find user by id
        $user = User::find($id);

        // check if user is exist
        if ($user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // response display
        return response()->json([
            new UserResource($user),
        ], 200  );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'message'=> 'User not found!',
            ],404);
        }

        $user->delete();

        return response()->json([
            'message'=> 'Deleted user successfully!',
            'user' => $user,
        ], 204);
    }
}
