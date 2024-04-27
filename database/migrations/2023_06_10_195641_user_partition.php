<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userPartition', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
            $table->foreignId('partition_id')
                ->constrained('partitions', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('permission_id')
                ->nullable()
                ->constrained('permissions', 'id');
            $table->boolean('accepted')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userPartition');
    }
};
