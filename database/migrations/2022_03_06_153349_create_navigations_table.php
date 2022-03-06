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
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('identifier human readable');
            $table->enum('location', ['sidebar', 'topbar', 'footer', 'settings', 'profile'])->default('SideBar');
            $table->text('blade');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigations');
    }
};
