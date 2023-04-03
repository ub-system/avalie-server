<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(){
        return UserResource::collection(User::all());
    }

    public function register(Request $request){
        $request -> validate([
            'name' => 'required | string',
            'email' => 'required | email | unique:users,email',
            'is_admin' => 'nullable | boolean',
            'password' => 'required | string | confirmed',
        ]);

        $user = User::create([
            'name' => $request -> name,
            'email' => $request -> email,
            'is_admin' => $request -> is_admin,
            'password' => bcrypt($request -> password),
        ]);

        $token = $user -> createToken('auth-token')-> plainTextToken;
        $user->token = $token;

        $resource = new UserResource($user);
        return $resource -> response()->json(['message' => 'Operação bem sucedida'], 201);
    }
}
