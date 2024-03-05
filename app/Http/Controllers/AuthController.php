<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\DTO\AuthResourceDTO;
use App\DTO\RegisterResourceDTO;
use App\DTO\UserResourceDTO;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('authenticate.register');
    }

    public function showLoginForm()
    {
        return view('authenticate.login');
    }

    public function register(RegisterRequest $request)
    {
        // Реализация метода регистрации
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Проверка количества активных токенов
        $user = Auth::user();
        $activeTokensCount = $user->tokens->count();
        $maxActiveTokens = env('MAX_ACTIVE_TOKENS', 5);

        if ($activeTokensCount > $maxActiveTokens) {
            return response()->json(['error' => 'Превышен лимит активных токенов'], 403);
        }
        $resource = new RegisterResourceDTO($user->name, $user->email);

        return response()->json($resource, 201);
    }

        public function login(LoginRequest $request)
    {
        // Реализация метода авторизации
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $tokenExpirationMinutes = env('TOKEN_EXPIRATION_MINUTES', 60);
                $refreshTokenExpirationDays = env('REFRESH_TOKEN_EXPIRATION_DAYS', 30);

                $token = auth()->user()->createToken('API Token', ['expires_in' => $tokenExpirationMinutes * 60]);

                $user = Auth::user();
                $resource = new AuthResourceDTO($user->name, $user->email); // Используем AuthResourceDTO

                return response()->json(['access_token' => $token->accessToken, 'user' => $resource], 200);
            }

            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed. ' . $e->getMessage()], 500);
        }
    }

    public function getUser()
    {
        // Реализация метода получения пользователя
        $user = Auth::user();

        if ($user) {
            $resource = new UserResourceDTO($user->name, $user->email);

            return response()->json($resource, 200);
        } else {
            return response()->json(['message' => 'Неавторизованный запрос'], 401);
        }
    }

    public function logout()
    {
        // Реализация метода разлогирования
        $user = Auth::user();
        $user->tokens()->revoke();

        return response()->json(['message' => 'Успешный выход'], 200);
    }

    public function getTokens()
    {
        // Реализация метода получения токенов
        $user = Auth::user();
        $tokens = $user->tokens;

        return response()->json($tokens, 200);
    }

    public function revokeTokens()
    {
        // Реализация метода отзыва токенов
        $user = Auth::user();
        $user->tokens()->revoke();

        return response()->json(['message' => 'Все токены успешно отозваны'], 200);
    }
}
