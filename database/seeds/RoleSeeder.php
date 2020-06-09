<?php

declare(strict_types=1);

namespace Database\Seeds;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Database\Seeds\Traits\ConsoleBarTrait;

class RoleSeeder extends Seeder
{
    use ConsoleBarTrait;
        
    /**
     * Total rows to seed
     *
     * @var int
     */
    protected int $total = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->seedAndOutput(Role::class);
    }
}
