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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->float('price', 18, 6)->default(0);
            $table->float('setup_price', 18, 6)->default(0)->comment('The minimum amount of credits needed for this configuration');
            $table->float('minimum_required_credits', 18, 6)->default(0)->comment('The minimum amount of credits needed for this configuration');
            $table->unsignedInteger('memory');
            $table->unsignedInteger('cpu');
            $table->unsignedInteger('swap');
            $table->unsignedInteger('disk');
            $table->unsignedInteger('io');
            $table->unsignedInteger('databases');
            $table->unsignedInteger('backups');
            $table->unsignedInteger('allocations');
            $table->boolean('disabled')->default(false);
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
        Schema::dropIfExists('configurations');
    }
};
