<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoleUserPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table): void {
            $table->foreignId('role_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->comment('Associated Role ID.');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->comment('Associated User ID.');
            $table->primary(['role_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
}
