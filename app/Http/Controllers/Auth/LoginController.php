<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home'; // Изменено

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $tokenExpirationMinutes = env('TOKEN_EXPIRATION_MINUTES', 60);
                $refreshTokenExpirationDays = env('REFRESH_TOKEN_EXPIRATION_DAYS', 30);

                $token = auth()->user()->createToken('API Token', ['expires_in' => $tokenExpirationMinutes * 60]);

                $user = Auth::user();
                $resource = new AuthResourceDTO($user->name, $user->email); // Замените на ваш AuthResourceDTO

                return response()->json(['access_token' => $token->accessToken, 'user' => $resource], 200);
            }

            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed. ' . $e->getMessage()], 500);
        }
    }
}
