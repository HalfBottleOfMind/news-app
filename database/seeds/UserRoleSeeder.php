<?php

declare(strict_types=1);

namespace Database\Seeds;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = User::all();
        $this->command->getOutput()->progressStart(count($users));
        foreach ($users as $user) {
            $user->roles()->detach();
            $user->roles()->attach(\App\Models\Role::all()->random());
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
    }
}
