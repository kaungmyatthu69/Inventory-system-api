<?php

namespace App\Http\Controllers;

use App\Exceptions\MessageError;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\MessageResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): MessageResource
    {
        $user = User::create($request->validated());
        $token = $user->createToken('api-token')->plainTextToken;

        return new MessageResource([
            'status' => 201,
            'message' => 'Registration successful.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    public function login(LoginRequest $request): MessageResource
    {
        if (! Auth::attempt($request->validated())) {
            throw new MessageError(
                errorMessage: 'Invalid email or password.',
                statusCode: 401,
            );
        }

        $user = $request->user();
        $token = $user->createToken('api-token')->plainTextToken;

        return new MessageResource([
            'status' => 200,
            'message' => 'Login successful.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    public function logout(): MessageResource
    {
        $token = request()->user()->currentAccessToken();
        logger("user",$token);

        if (! $token) {
            throw new MessageError(
                errorMessage: 'User Already logged out.',
                statusCode: 401,
            );
        }

        if ($token) {
            $token->delete();
        }


        return new MessageResource([
            'status' => 200,
            'message' => 'Logout successful.',
            'data' => null,
        ]);
    }
}
