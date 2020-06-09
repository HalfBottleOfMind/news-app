<?php

declare(strict_types=1);

use Database\Seeds\RoleSeeder;
use Database\Seeds\UserSeeder;
use Illuminate\Database\Seeder;
use Database\Seeds\TestDataSeeder;
use Database\Seeds\UserRoleSeeder;
use Illuminate\Database\Eloquent\Model;
use Database\Seeds\Required\PermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $testEnv = app()->runningUnitTests();
        Model::unguard();
        
        if ($this->command->confirm('Clear DB before seeding?', false)) {
            $this->command->call('migrate:fresh');
            $this->command->info("Database cleared.");
        }

        $this->call(PermissionSeeder::class);

        if ($this->command->confirm('Seed Dummy data?', false)) {
            $this->command->info("Ok, dummy data will be seeded now...");
            $this->call(RoleSeeder::class);
            $this->call(UserSeeder::class);
            $this->call(UserRoleSeeder::class);
            // Override required with static data
            $this->call(TestDataSeeder::class);
        }
        
        Model::reguard();
    }
}
