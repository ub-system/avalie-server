<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(){
        return UserResource::collection(User::all());
    }

    public function register(Request $request){
        $request -> validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'is_admin' => 'nullable|boolean',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->is_admin,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;
        $user->token = $token;

        $resource = new UserResource($user);
        return $resource->response()->setStatusCode(201);
    }

    public function login(Request $request){
        $request->validate([
            "email"=>"required|email",
            "password"=>"required|string",
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response(['error'=>'O e-mail informado não está cadastrado'], 401);
        }

        if($user && Hash::check($request->password, $user->password)){
            $token = $user->createToken('auth-token')->plainTextToken;
            $user->token = $token;

            return new UserResource($user);
        }

        return response(['error'=>'A senha informada está incorreta'], 401);
    }
}
