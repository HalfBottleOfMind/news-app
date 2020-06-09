<?php

declare(strict_types=1);

namespace Database\Seeds;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Database\Seeds\Traits\ConsoleBarTrait;

class UserSeeder extends Seeder
{
    use ConsoleBarTrait;
    
    /**
     * Total rows to seed
     *
     * @var int
     */
    protected int $total = 500;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedAndOutput(User::class);
    }
}
