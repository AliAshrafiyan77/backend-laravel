<?php

namespace App\Services;

use App\Support\ServiceLogger;
use Exception;
use Throwable;

class UserService
{
    public function __construct() {}

    public function getUserService()
    {
        try {
            $user = auth()->user();
            if (! $user) {
                throw new Exception('عدم احراز هویت');
            }

            $roles = $user->roles->map(function ($role) {
                return [
                    'name' => $role->name,
                    'display_name' => $role->display_name,
                ];
            });
            
            $userData = $user->toArray();
            $userData['roles'] = $roles;

            return $userData;
            
        } catch (Throwable $e) {
            ServiceLogger::error($e, 'UserService@getUserService');
            throw new Exception('خطلا سرور');
        }
    }

    public function storeService() {}
}
