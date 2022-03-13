<?php

namespace Tests\Feature;

use App\Helpers\RoleAndPermissionHelper;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testChangeStatus()
    {
        $project = \App\Models\Project::factory()->create();
        $user = \App\Models\User::factory()->create();

        Sanctum::actingAs(
            $user,
            [RoleAndPermissionHelper::PARTIAL_ACCESS_TASK_API_ABILITY]
        );

        $task = \App\Models\Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);
        $changeStatusData = [
            "status" => Task::IN_PROGRESS,
        ];

        $this->json('PATCH', 'api/v1/tasks/' . $task->id, $changeStatusData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
