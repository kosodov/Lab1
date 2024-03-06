<?php

namespace App\Http\Controllers\Auth;

use App\DTO\RegisterResourceDTO;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->create($request->all());

        $maxActiveTokens = env('MAX_ACTIVE_TOKENS', 5);

        if (Auth::check()) {
            $activeTokensCount = Auth::user()->tokens->count();

            if ($activeTokensCount > $maxActiveTokens) {
                return response()->json(['error' => 'Превышен лимит активных токенов'], 403);
            }
        }

        $resource = new RegisterResourceDTO($user->name, $user->email);

        return response()->json($resource, 201);
    }
}
