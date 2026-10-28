<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Machines / Automation page: the machines shown on /machines.html, managed from the admin panel.
 * Only a NEW table is created (skipped when it already exists). Nothing existing is touched.
 */
class CreateMachinesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('machines')) {
            return;
        }

        Schema::create('machines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 160);
            $table->text('description')->nullable();
            $table->string('image', 190)->nullable();        // file name inside public/upload/machines
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('machines');
    }
}
