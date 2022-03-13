<?php

namespace Tests\Feature;

use App\Helpers\RoleAndPermissionHelper;
use App\Models\Task;
use Tests\TestCase;


use function PHPUnit\Framework\assertCount;

class ProjectTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAssignTwoUsersToProject()
    {
        $project = \App\Models\Project::factory()->create();
        $user1 = \App\Models\User::factory()->create();
        $user1->assignRole(RoleAndPermissionHelper::TEAM_MEMBER_ROLE);

        $user2 = \App\Models\User::factory()->create();
        $user2->assignRole(RoleAndPermissionHelper::TEAM_MEMBER_ROLE);

        $task1 = \App\Models\Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user1->id
        ]);

        $task2 = \App\Models\Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user2->id
        ]);
        $userIds = Task::where('project_id', $project->id)->pluck('user_id')->toArray();
        assertCount(2, $userIds);
    }
}
