<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreUserRequest;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use G4T\Swagger\Attributes\SwaggerSection;
use Illuminate\Http\Request;

#[SwaggerSection('User Controller')]
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
    public function store(StoreUserRequest $request)
    {
        $fields = $request->validated();

        $user = User::create($fields);

        // dispatch the job (queue)
        SendWelcomeEmail::dispatch($user);

        return response()->json([
            'message' => 'User Created! Email will be sent in background.',
            'user'    => $user,
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // find user by id
        $user = User::find($id);

        // check if user is exist
        if (!$user) {
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
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found!',
            ],404);
        }

        $user->update($request->only([
            'name',
            'email',
            'password',
        ]));

        return response()->json([
            'message' => 'Updated user details successfully!',
            'user'    => new UserResource($user),
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found!',
            ],404);
        }

        $user->delete();

        return response()->json([
            'message' => 'Deleted user successfully!',
            'user'    => $user,
        ], 204);
    }
}
