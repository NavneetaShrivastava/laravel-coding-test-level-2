<?php

namespace Tests\Feature;

use App\Helpers\RoleAndPermissionHelper;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreatingUserViaApiCallErrors()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            [RoleAndPermissionHelper::ACCESS_ALL_USER_API_ABILITY]
        );

        $this->json('POST', 'api/v1/users', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "username" => ["The username field is required."],
                    "password" => ["The password field is required."],
                    "roles" => ["The roles field is required."],
                ]
            ]);
    }

    public function testCreatingUserViaApiCall()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            [RoleAndPermissionHelper::ACCESS_ALL_USER_API_ABILITY]
        );


        $userDataToCreate = [
            "username" => "JohnDoe",
            "password" => "JohnDoe1234",
            "roles" =>  RoleAndPermissionHelper::PRODUCT_OWNER_ROLE
        ];

        $this->json('POST', 'api/v1/users', $userDataToCreate, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "headers",
                "original" => [
                    "message",
                    "statusCode",
                    "data" => [
                        "username",
                        "password",
                        "id",
                        "updated_at",
                        "created_at",
                        "assignedRole" => []
                    ]
                ],
                "exception"
            ]);
    }
}
