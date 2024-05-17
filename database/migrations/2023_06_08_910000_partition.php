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
        Schema::create('partitions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
            $table->string('icon', 255)
                ->default('mdi-text-long');
            $table->string('slug')
                ->unique();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('partitions');
    }
};
