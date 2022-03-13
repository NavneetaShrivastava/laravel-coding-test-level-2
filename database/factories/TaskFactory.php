<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'status' => Task::NOT_STARTED,
            'project_id' => $this->faker->uuid(),
            'user_id' => $this->faker->uuid(),
        ];
    }
}
