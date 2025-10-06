<?php

namespace App\Services;

use App\Models\User;
use App\Support\ServiceLogger;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthService
{
    /**
     * Display the login view or redirect if already authenticated.
     */
    public function loginViewService()
    {
        try {
            if (auth()->check()) {
                return redirect(env('NEXT_DASHBOARD_URL'));
            }

            return view('auth.login');

        } catch (Throwable $e) {
            ServiceLogger::error($e, 'AuthService@loginViewService');
            throw new Exception('خطای سرور رخ داده است.');
        }
    }

    /**
     * Handle user login.
     */
    public function loginService(array $userData)
    {
        try {
            $user = User::where('email', $userData['email'])->first();

            if (! auth()->guard('web')->attempt($userData)) {
                throw new Exception('ایمیل یا کلمه عبور اشتباه است.');
            }

            session()->regenerate();

            return $this->redirectService();

        } catch (Throwable $e) {
            ServiceLogger::error($e, 'AuthService@loginService');
            throw new Exception('خطای سرویس رخ داده است لطفا بعدا تلاش فرمایید.');
        }
    }

    /**
     * Redirect user based on PKCE or default URL.
     */
    private function redirectService()
    {
        try {
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

        } catch (Throwable $e) {
            ServiceLogger::error($e, 'AuthService@redirectService');
            throw new Exception('خطای سرور در فرآیند ریدایرکت رخ داده است.');
        }
    }

    /**
     * Register a new user.
     */
    public function registerService(array $data)
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            auth()->guard('web')->login($user);
            DB::commit();
            return $this->redirectService();

        } catch (Throwable $e) {
            DB::rollBack();
            ServiceLogger::error($e, 'AuthService@registerService');
            throw new Exception('خطای سرور رخ داده است، لطفاً چند دقیقه دیگر تلاش فرمایید!');
        }
    }

    /**
     * Start PKCE flow by storing code challenge in session.
     */
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
            ServiceLogger::error($e, 'AuthService@startPkceService');
            throw new Exception('خطای سرور در PKCE رخ داده است.');
        }
    }
}
