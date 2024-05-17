<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 25);
            $table->string('lastname', 50);
            $table->string('slug', 255)->unique();
            $table->string('email', 320)->unique();
            $table->string('password');
            $table->foreignId('role_id')->default(4)
                ->constrained('roles', 'id');
            $table->foreignId('type_id')->default(1)
                ->constrained('account_types', 'id');
            $table->foreignId('licences_id')->default(1)
                ->constrained('licences', 'id');
            $table->boolean('active')->default(1);
            $table->boolean('canShare')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }
};
