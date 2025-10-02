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

    public function login(LoginRequest $request)
    {
        try {
            return $this->authService->loginService($request->validated());
        } catch (Throwable $e) {
            return back()->withErrors([
                'login' => $e->getMessage(),
            ]);
        }

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
        $codeChallenge = $request->query('code_challenge');
        $codeChallengeMethod = $request->query('code_challenge_method') ?? 'S256';
        try {
            return $this->authService->startPkceService($codeChallenge, $codeChallengeMethod);
        } catch (Throwable $e) {
            return response($e->getMessage(), 400);
        }
    }
}
