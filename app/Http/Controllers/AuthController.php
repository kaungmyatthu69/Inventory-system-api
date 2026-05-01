<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatus;
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
        $token = $user->createToken('auth-token')->plainTextToken;

        return new MessageResource([
            'status' => HttpStatus::CREATED->value,
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
                statusCode: HttpStatus::UNAUTHORIZED->value,
            );
        }

        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;

        return new MessageResource([
            'status' => HttpStatus::OK->value,
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

        if (! $token) {
            throw new MessageError(
                errorMessage: 'User already logged out.',
                statusCode: HttpStatus::UNAUTHORIZED->value,
            );
        }

        $token->delete();

        return new MessageResource([
            'status' => HttpStatus::OK->value,
            'message' => 'Logout successful.',
            'data' => null,
        ]);
    }
}
