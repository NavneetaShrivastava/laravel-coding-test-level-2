<?php

namespace App\Helpers;

class RoleAndPermissionHelper
{
    const ACCESS_ALL_USER_API_ABILITY = 'access-all-user-apis';
    const ACCESS_ALL_TASK_API_ABILITY = 'access-all-task-apis-except-updating-status';
    const ACCESS_ALL_PROJECT_API_ABILITY = 'access-all-project-apis';
    const PARTIAL_ACCESS_TASK_API_ABILITY = 'partial-access-task-apis';

    const ADMIN_ROLE = 'Admin';
    const PRODUCT_OWNER_ROLE = 'Product Owner';
    const TEAM_MEMBER_ROLE = 'Team Member';
}
