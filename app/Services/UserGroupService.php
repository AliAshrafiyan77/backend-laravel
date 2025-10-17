<?php

namespace App\Services;

use App\Models\UserGroup;
use App\Support\ServiceLogger;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Throwable;

class UserGroupService
{
    public function indexService(): LengthAwarePaginator
    {
        try {
            return UserGroup::latest()->paginate(10);
        } catch (Throwable $e) {
            ServiceLogger::error($e, 'UserGroupService@indexService');
            throw new Exception('خطای سرور رخ داده است، لطفاً چند دقیقه دیگر تلاش فرمایید!');
        }
    }

    public function storeService(array $data): void
    {
        try {
            UserGroup::create([
                'name' => $data['name'],
                'display_name' => $data['display_name'],
            ]);
        } catch (Throwable $e) {
            ServiceLogger::error($e, 'UserGroupService@storeService');
            throw new Exception('خطای سرور رخ داده است، لطفاً چند دقیقه دیگر تلاش فرمایید!');
        }
    }

    public function updateService(UserGroup $userGroup, array $data): void
    {
        try {
            $userGroup->update([
                'name' => $data['name'],
                'display_name' => $data['display_name'],
            ]);
        } catch (Throwable $e) {
            ServiceLogger::error($e, 'UserGroupService@updateService');
            throw new Exception('خطای سرور رخ داده است، لطفاً چند دقیقه دیگر تلاش فرمایید!');
        }
    }
}
