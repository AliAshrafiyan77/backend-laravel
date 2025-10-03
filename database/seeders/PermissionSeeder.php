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
                'teacher' => [
                    ['name' => 'edit-teacher', 'display_name' => 'ویرایش اساتید', 'guard_name' => 'api'],
                    ['name' => 'delete-teacher', 'display_name' => 'حذف اساتید', 'guard_name' => 'api'],
                    ['name' => 'create-teacher', 'display_name' => 'ایجاد اساتید', 'guard_name' => 'api'],
                    ['name' => 'read-teacher', 'display_name' => 'خواندن اساتید', 'guard_name' => 'api'],
                ],
                'student' => [
                    ['name' => 'edit-student', 'display_name' => 'ویرایش دانشجویان', 'guard_name' => 'api'],
                    ['name' => 'delete-student', 'display_name' => 'حذف دانشجویان', 'guard_name' => 'api'],
                    ['name' => 'create-student', 'display_name' => 'ایجاد دانشجویان', 'guard_name' => 'api'],
                    ['name' => 'read-student', 'display_name' => 'خواندن دانشجویان', 'guard_name' => 'api'],
                ],
                'admin' => [
                    ['name' => 'edit-admin', 'display_name' => 'ویرایش مدیران', 'guard_name' => 'api'],
                    ['name' => 'delete-admin', 'display_name' => 'حذف مدیران', 'guard_name' => 'api'],
                    ['name' => 'create-admin', 'display_name' => 'ایجاد مدیران', 'guard_name' => 'api'],
                    ['name' => 'read-admin', 'display_name' => 'خواندن مدیران', 'guard_name' => 'api'],
                ],
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

            foreach ($permissions as $group => $groupPermissions) {
                foreach ($groupPermissions as $permission) {
                    $newPermission = Permission::updateOrCreate(
                        ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                        ['display_name' => $permission['display_name']]
                    );
                    $superAdmin->givePermissionTo($newPermission);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            $this->command->error('خطا در ایجاد نقش‌ها و مجوزها: '.$e->getMessage());
            throw $e;
        }
    }
}
