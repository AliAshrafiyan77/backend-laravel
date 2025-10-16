<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Throwable;

class UserController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function user(Request $request)
    {
        try {
            $user = $this->userService->getUserService();
            return response()->json($user);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function store(StoreUserRequest $request) {
        return response()->json(['tesxt'=>$request->all()]);
    }
}
