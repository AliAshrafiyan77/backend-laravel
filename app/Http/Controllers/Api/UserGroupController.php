<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserGroup\StoreUserGroupRequest;
use App\Http\Requests\UserGroup\UpdateUserGroupRequest;
use App\Models\UserGroup;
use App\Services\UserGroupService;
use Illuminate\Http\JsonResponse;
use Throwable;

class UserGroupController extends Controller
{
    private UserGroupService $userGroupService;

    public function __construct(UserGroupService $userGroupService)
    {
        $this->userGroupService = $userGroupService;
    }

    public function index(): JsonResponse
    {
        try {
            $userGroups = UserGroup::latest()->paginate(20);

            return response()->json(['userGroup' => $userGroups]);
        } catch (Throwable $e) {

        }
    }

    public function store(StoreUserGroupRequest $request): JsonResponse
    {
        try {
            $this->userGroupService->storeService($request->validated());

            return response()->json(['success' => 'گروه جدید کاربران با موفقیت ایجاد شد.']);
        } catch (Throwable $e) {
            return response()->json($e->getMessage());
        }

    }

    public function update(UserGroup $userGroup, UpdateUserGroupRequest $request): JsonResponse
    {
        try {
            $this->userGroupService->updateService($userGroup, $request->validated());
            return response()->json(['گروه کاربران با موفقیت بروز رسانی شد.']);
        } catch (Throwable $e) {
            return response()->json($e->getMessage());
        }
    }
}
