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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pterodactyl_id')->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('identifier')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->nullable();
            $table->boolean('suspended')->default(false);
            $table->unsignedInteger('memory');
            $table->unsignedInteger('cpu');
            $table->unsignedInteger('swap');
            $table->unsignedInteger('disk');
            $table->unsignedInteger('io');
            $table->unsignedInteger('databases');
            $table->unsignedInteger('backups');
            $table->unsignedInteger('allocations');
            $table->unsignedInteger('threads')->nullable();
            $table->boolean('oom_disabled')->default(true);
            $table->unsignedInteger('node');
            $table->unsignedInteger('allocation');
            $table->unsignedInteger('nest');
            $table->unsignedInteger('egg');
            $table->float('price', 18, 6)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
};
