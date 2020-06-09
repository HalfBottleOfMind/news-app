<?php

declare(strict_types=1);

namespace Database\Seeds\Required;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{

    /**
     * Predefined permissions.
     *
     * @var array
     */
    protected $permissions = [
        // User
        ['name' => 'Просмотр пользователей', 'slug' => 'read_users'],
        ['name' => 'Создание пользователей', 'slug' => 'create_users'],
        ['name' => 'Редактирование пользователей', 'slug' => 'update_users'],
        ['name' => 'Удаление пользователей', 'slug' => 'delete_users'],
        // Role
        ['name' => 'Просмотр ролей', 'slug' => 'read_roles'],
        ['name' => 'Создание ролей', 'slug' => 'create_roles'],
        ['name' => 'Редактирование ролей', 'slug' => 'update_roles'],
        ['name' => 'Удаление ролей', 'slug' => 'delete_roles'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::updateOrCreate($permission);
        }
    }
}
