<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function loginView()
    {
        $code_challenge = session('code_challenge');
        $code_challenge_method = session('code_challenge_method');
        if ($code_challenge == null || $code_challenge_method == null)
            return redirect('http://localhost:3000');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // اعتبارسنجی ساده
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $code_challenge = session()->pull('code_challenge');
            $code_challenge_method = session()->pull('code_challenge_method');
            if ($code_challenge == null || $code_challenge_method == null)
                return redirect('http://localhost:3000');

            return redirect()->route('passport.authorizations.authorize', [
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'redirect_uri' => 'http://localhost:3000/auth/callback',
                'response_type' => 'code',
                'scope' => '',
                'code_challenge' => $code_challenge,
                'code_challenge_method' => $code_challenge_method
            ]);
        }

        return back()->withErrors([
            'email' => 'ایمیل یا رمز عبور اشتباه است',
        ]);
    }
    public function registerView()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->authService->registerService($request->validated());
        } catch (\Throwable $throwable) {
            dd($throwable);
        }
    }
    public function startPkce(Request $request)
    {
        $code_challenge = $request->query('code_challenge');
        $code_challenge_method = $request->query('code_challenge_method') ?? 'S256';

        if (!$code_challenge) {
            return response('Missing PKCE parameters', 400);
        }
        Session::put('code_challenge', $code_challenge);
        Session::put('code_challenge_method', $code_challenge_method);
        $clientId = env('PASSPORT_CLIENT_ID');
        $redirectUri = 'http://localhost:3000/auth/callback';
        $authorizeUrl = route('passport.authorizations.authorize', [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => '',
            'code_challenge' => $code_challenge,
            'code_challenge_method' => $code_challenge_method
        ]);

        return redirect($authorizeUrl);
    }
}
