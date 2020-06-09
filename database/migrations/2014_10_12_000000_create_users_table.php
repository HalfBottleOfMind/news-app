<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login', 20)->unique()->comment('Login for authentication.');
            $table->string('password')->comment('User\'s password.');
            $table->string('first_name')->nullable()->comment('User\'s first name.');
            $table->string('middle_name')->nullable()->comment('User\'s middle name or patronymic.');
            $table->string('last_name')->nullable()->comment('User\'s last name.');
            $table->rememberToken()->comment('This field prevents cookie hijacking.');
            $table->timestamp('created_at')->useCurrent()->comment('Determine when this user was initially created.');
            $table->softDeletes()->comment('Determine whether this user was soft deleted.');
        });

        if (! app()->runningUnitTests()) {
            DB::statement('ALTER TABLE users ADD CONSTRAINT chk_users_login CHECK (login ~ \'^[A-Za-z0-9._-]+$\');');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
