<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Database\QueryException;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::where("email", $validated["email"])->first();

            if (
                !Auth::attempt([
                    "email" => $validated["email"],
                    "password" => $validated["password"],
                ])
            ) {
                throw new ModelNotFoundException("Email or password incorrect");
            }

            $token = $user->createToken("api-token", [
                "read:home, read:posts, write:posts",
            ]);

            return response()->json(
                [
                    "data" => [
                        "name" =>
                            $user["first_name"] . " " . $user["last_name"],
                        "email" => $user["email"],
                        "api-token" => $token->plainTextToken,
                    ],
                ],
                200,
            );
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            return response()->json(["message" => $e->getMessage()], 401);
        }
    }

    public function create(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                "id" => Str::uuid(),
                "first_name" => $validated["first_name"],
                "last_name" => $validated["last_name"],
                "email" => $validated["email"],
                "password" => Hash::make($validated["password"]),
            ]);

            $user->save();

            $token = $user->createToken("api-token", [
                "read:home",
                "read:posts",
                "write:posts",
            ]);

            return response()->json(
                [
                    "data" => [
                        "name" =>
                            $user["first_name"] . " " . $user["last_name"],
                        "email" => $user["email"],
                        "api-token" => $token->plainTextToken,
                    ],
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                ["data" => ["error" => $e->getMessage()]],
                401,
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                throw new \Exception("Token is required");
            }

            $accessToken = PersonalAccessToken::findToken($token);

            if (!$accessToken) {
                throw new ModelNotFoundException("Invalid Token");
            }

            $accessToken->delete();

            return response()->json(["data" => ["status" => "ok"]], 204);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            return response()->json(["message" => $e->getMessage()], 404);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(["message" => $e->getMessage()], 401);
        }
    }
}
