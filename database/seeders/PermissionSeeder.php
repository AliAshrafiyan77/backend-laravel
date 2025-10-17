<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds to create roles and permissions.
     *
     * @throws Exception
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $permissions = [

                // users
                ['name' => 'update-user', 'display_name' => 'ویرایش کاربر', 'guard_name' => 'api'],
                ['name' => 'delete-user', 'display_name' => 'حذف کاربر', 'guard_name' => 'api'],
                ['name' => 'store-user', 'display_name' => 'ایجاد کاربر', 'guard_name' => 'api'],
                ['name' => 'show-user', 'display_name' => 'خواندن کاربر', 'guard_name' => 'api'],
                ['name' => 'index-users', 'display_name' => 'خواندن همه کاربران', 'guard_name' => 'api'],

                // user_group
                ['name' => 'update-user-group', 'display_name' => 'ویرایش گروه‍', 'guard_name' => 'api'],
                ['name' => 'delete-user-group', 'display_name' => 'حذف گروه', 'guard_name' => 'api'],
                ['name' => 'store-user-group', 'display_name' => 'ایجاد گروه', 'guard_name' => 'api'],
                ['name' => 'show-user-group', 'display_name' => 'خواندن گروه', 'guard_name' => 'api'],
                ['name' => 'index-user-groups', 'display_name' => 'خواندن همه گروه ها', 'guard_name' => 'api'],

                // roles
                ['name' => 'update-role', 'display_name' => 'ویرایش نقش', 'guard_name' => 'api'],
                ['name' => 'delete-role', 'display_name' => 'حذف نقش', 'guard_name' => 'api'],
                ['name' => 'store-role', 'display_name' => 'ایجاد نقش', 'guard_name' => 'api'],
                ['name' => 'show-role', 'display_name' => 'خواندن نقش', 'guard_name' => 'api'],
                ['name' => 'attach-role', 'display_name' => 'تخصیص نقش به کابران', 'guard_name' => 'api'],
                ['name' => 'detach-role', 'display_name' => 'حذف نقش کاربران', 'guard_name' => 'api'],
                ['name' => 'index-roles', 'display_name' => 'خواندن همه نقش ها', 'guard_name' => 'api'],

                // permissions
                ['name' => 'update-permission', 'display_name' => 'ویرایش دسترسی', 'guard_name' => 'api'],
                ['name' => 'delete-permission', 'display_name' => 'حذف دسترسی', 'guard_name' => 'api'],
                ['name' => 'store-permission', 'display_name' => 'ایجاد دسترسی', 'guard_name' => 'api'],
                ['name' => 'show-permission', 'display_name' => 'خواندن دسترسی', 'guard_name' => 'api'],
                ['name' => 'attach-permission', 'display_name' => 'تخصیص دسترسی به نقش ها', 'guard_name' => 'api'],
                ['name' => 'detach-permission', 'display_name' => 'حذف دسترسی نقش ها', 'guard_name' => 'api'],
                ['name' => 'index-permissions', 'display_name' => 'خواندن همه دسترسی ها', 'guard_name' => 'api'],
            ];

            $role = [
                'name' => 'super-admin',
                'display_name' => 'مدیرکل',
                'guard_name' => 'api',
            ];

            $user = User::find(1);
            if (! $user) {
                throw new Exception('کاربر با ID 1 یافت نشد. لطفاً ابتدا یک کاربر ایجاد کنید.');
            }

            $superAdmin = Role::updateOrCreate(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']],
                ['display_name' => $role['display_name']]
            );

            $user->syncRoles([$superAdmin->name]);

            foreach ($permissions as $permission) {
                $newPermission = Permission::updateOrCreate(
                    ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                    ['display_name' => $permission['display_name']]
                );
                $superAdmin->givePermissionTo($newPermission);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            $this->command->error('خطا در ایجاد نقش‌ها و مجوزها: '.$e->getMessage());
            throw $e;
        }
    }
}
