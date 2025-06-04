<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\User;
use App\Services\Contracts\Auth\AuthServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    /**
     * AuthController constructor.
     */
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login the user and return a token.
     *
     * Authenticate the user using their email and password, and return a token upon successful login.
     *
     * @group Authentication
     *
     * @bodyParam email string required The email of the user. Example: user@example.com
     * @bodyParam password string required The password of the user. Example: Secret123**
     *
     * @response 200 {
     *   "token": "your-generated-token"
     * }
     * @response 401 {
     *   "error": "Unauthorized"
     * }
     * @response 422 {
     *   "error": {
     *     "email": ["The email field is required."],
     *     "password": ["The password field is required."]
     *   }
     * }
     * @response 500 {
     *   "error": "An internal error occurred"
     * }
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            /** @var array<string, string> $credentials */
            $credentials = $request->only('email', 'password');

            /** @var string|null $token */
            $token = $this->authService->login($credentials);

            if (! $token) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json(['token' => $token], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'An internal error occurred'], 500);
        }
    }

    /**
     * Logout the user and revoke the token.
     *
     * Revoke the token that was used to authenticate the user.
     *
     * @group Authentication
     * 
     * @header Authorization Bearer your-token
     *
     * @response 200 {
     *   "message": "Logged out successfully"
     * }
     * @response 500 {
     *   "error": "An internal error occurred"
     * }
     */
    public function logout(): JsonResponse
    {
        try {

            /** @var bool $logout */
            $logout = $this->authService->logout();

            // Check if the user is authenticated
            if (! $logout) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An internal error occurred'], 500);
        }
    }

    /**
     * Get the authenticated user.
     *
     * Return the authenticated user's information.
     *
     * @group Authentication
     * 
     * @header Authorization Bearer your-token
     *
     * @response 200 {
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "johndoe@example.com",
     *     "created_at": "2023-01-01T00:00:00.000000Z",
     *     "updated_at": "2023-01-01T00:00:00.000000Z"
     *   }
     * }
     * @response 401 {
     *   "error": "Unauthorized"
     * }
     * @response 500 {
     *   "error": "An internal error occurred"
     * }
     */
    public function user(): JsonResponse
    {
        try {

            /** @var User|null $user */
            $user = $this->authService->user();

            // Check if the user is authenticated
            if (! $user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Return the authenticated user
            return response()->json(['user' => $user], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An internal error occurred'], 500);
        }
    }

    /**
     * Refresh the token.
     *
     * Generate a new token for the authenticated user.
     *
     * @group Authentication
     * 
     * @header Authorization Bearer your-token
     *
     * @response 200 {
     *   "token": "your-new-generated-token"
     * }
     * @response 401 {
     *   "error": "Unauthorized"
     * }
     * @response 500 {
     *   "error": "An internal error occurred"
     * }
     */
    public function refresh(): JsonResponse
    {
        try {
            /** @var string|null $token */
            $token = $this->authService->refresh();

            // Check if the user is authenticated
            if (! $token) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json(['token' => $token], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An internal error occurred'], 500);
        }
    }

    /**
     * Register a new user.
     *
     * Create a new user and return a token.
     *
     * @group Authentication
     *
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: user@example.com
     * @bodyParam password string required The password of the user. Example: Secret123**
     * @bodyParam password_confirmation string required The confirmation of the password. Example: Secret123**
     *
     * @response 201 {
     *   "token": "your-generated-token"
     * }
     * @response 422 {
     *   "error": {
     *     "name": ["The name field is required."],
     *     "email": ["The email field is required."],
     *     "password": ["The password field is required."],
     *     "password_confirmation": ["The password confirmation field is required."]
     *   }
     * }
     * @response 500 {
     *   "error": "An internal error occurred"
     * }
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        try {
            /** @var array<string, string> $data */
            $data = $request->only('name', 'email', 'password');

            // Create the user
            /** @var string|null $token */
            $token = $this->authService->register($data);

            if (! $token) {
                return response()->json(['error' => 'Unable to create user'], 422);
            }

            return response()->json(['token' => $token], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'An internal error occurred'], 500);
        }
    }
}
