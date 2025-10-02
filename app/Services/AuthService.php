<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthService
{
    public function loginViewService()
    {
        try {
            if (auth()->check()) {
                return redirect(env('NEXT_DASHBOARD_URL'));
            }
            return view('auth.login');

        } catch (Throwable $e) {
            report($e);
            throw new Exception('خطای سرور رخ داده است.');
        }

    }

    public function loginService(array $credentials)
    {
        try {
            if (! auth()->attempt($credentials)) {
                throw new Exception('ایمیل یا کلمه عبور اشتباه است.');
            }

            session()->regenerate();

            return $this->redirectService();

        } catch (Throwable $e) {
            report($e);
            throw new Exception('خطای سرور رخ داده است.');
        }
    }

    public function registerService(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

    }

    public function redirectService()
    {

        $code_challenge = session()->get('code_challenge');
        $code_challenge_method = session()->get('code_challenge_method');

        if ($code_challenge === null || $code_challenge_method === null) {
            return redirect('http://localhost:3000');
        }

        return redirect()->route('passport.authorizations.authorize', [
            'client_id' => env('PASSPORT_CLIENT_ID'),
            'redirect_uri' => env('PASSPORT_REDIRECT_URL'),
            'response_type' => 'code',
            'scope' => '',
            'code_challenge' => $code_challenge,
            'code_challenge_method' => $code_challenge_method,
        ]);
    }

    public function startPkceService($codeChallenge, $codeChallengeMethod)
    {
        try {
            if (! $codeChallenge) {
                throw new Exception('دوباره تلاش کنید.');
            }

            session()->put('code_challenge', $codeChallenge);
            session()->put('code_challenge_method', $codeChallengeMethod);

            return $this->redirectService();

        } catch (Throwable $e) {
            report($e);
            throw new Exception('خطای سرور در PKCE رخ داده است.');
        }
    }
}
