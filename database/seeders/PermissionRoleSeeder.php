<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Helpers\RoleAndPermissionHelper;

class PermissionRoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['guard_name' => 'api', 'name' => RoleAndPermissionHelper::ACCESS_ALL_USER_API_ABILITY]);
        Permission::create(['guard_name' => 'api', 'name' => RoleAndPermissionHelper::ACCESS_ALL_TASK_API_ABILITY]);
        Permission::create(['guard_name' => 'api', 'name' => RoleAndPermissionHelper::ACCESS_ALL_PROJECT_API_ABILITY]);
        Permission::create(['guard_name' => 'api', 'name' => RoleAndPermissionHelper::PARTIAL_ACCESS_TASK_API_ABILITY]);

        // create roles and assign existing permissions
        $productOwnerRole = Role::create(['guard_name' => 'api', 'name' => RoleAndPermissionHelper::PRODUCT_OWNER_ROLE]);
        $productOwnerRole->givePermissionTo(RoleAndPermissionHelper::ACCESS_ALL_TASK_API_ABILITY);
        $productOwnerRole->givePermissionTo(RoleAndPermissionHelper::ACCESS_ALL_PROJECT_API_ABILITY);

        $teamMemberRole = Role::create(['guard_name' => 'api', 'name' => RoleAndPermissionHelper::TEAM_MEMBER_ROLE]);
        $teamMemberRole->givePermissionTo(RoleAndPermissionHelper::PARTIAL_ACCESS_TASK_API_ABILITY);

        $adminRole = Role::create(['guard_name' => 'api', 'name' => RoleAndPermissionHelper::ADMIN_ROLE]);
        $adminRole->givePermissionTo(RoleAndPermissionHelper::ACCESS_ALL_USER_API_ABILITY);
    }
}
