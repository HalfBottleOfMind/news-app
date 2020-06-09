<?php

declare(strict_types=1);

namespace Database\Seeds;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Models\Email\Email;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate([
            'id' => 1,
        ], [
            'login' => 'test_user',
            'password' => Hash::make('password123'),
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'remember_token' => '7Ow2qGgdGv',
            'created_at' => '2020-01-01 00:00:00',
        ]);
        $role = Role::updateOrCreate([
            'id' => 1,
        ], [
            'name' => 'Administrator'
        ]);
        $permissions = Permission::all();
        $role->permissions()->detach();
        $role->permissions()->attach($permissions);
        $user->roles()->detach();
        $user->roles()->attach($role);
    }
}
