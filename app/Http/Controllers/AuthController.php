<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show login view or redirect if already authenticated.
     */
    public function loginView(Request $request)
    {
        try {
            return $this->authService->loginViewService();
        } catch (Throwable $e) {
            return back()->withErrors([
                'login' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle user login request.
     */
    public function login(LoginRequest $request)
    {
        try {
            return $this->authService->loginService($request->validated());
        } catch (Throwable $e) {
            return back()->withErrors([
                'login' => $e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Show registration view.
     */
    public function registerView()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration request.
     */
    public function register(RegisterRequest $request)
    {
        try {
            return $this->authService->registerService($request->validated());
        } catch (Throwable $e) {
            return redirect()->back()->withErrors([
                'register' => $e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Start PKCE flow for OAuth2 login.
     */
    public function startPkce(Request $request)
    {
        $codeChallenge = $request->query('code_challenge');
        $codeChallengeMethod = $request->query('code_challenge_method') ?? 'S256';

        try {
            return $this->authService->startPkceService($codeChallenge, $codeChallengeMethod);
        } catch (Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
