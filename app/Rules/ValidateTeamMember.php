<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;
use App\Helpers\RoleAndPermissionHelper;
use App\Models\User as ModelUser;

class ValidateTeamMember implements ImplicitRule
{

    public function __construct()
    {
    }

    public function passes($attribute, $values)
    {
        $this->values = $values;

        $teamMemberUserIds = ModelUser::whereHas('roles', function ($q) {
            $q->where('name', RoleAndPermissionHelper::TEAM_MEMBER_ROLE);
        })->pluck('id');
        return $teamMemberUserIds->contains($this->values);
    }

    public function message()
    {
        return 'Please choose a ' . RoleAndPermissionHelper::TEAM_MEMBER_ROLE . ' to assign the Task.';
    }
}
