<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $admin = User::create([
                'username' => 'Admin',
                'password' => 'adminPassword',
            ]);
            $admin->assignRole('Admin');
        });
    }
}
