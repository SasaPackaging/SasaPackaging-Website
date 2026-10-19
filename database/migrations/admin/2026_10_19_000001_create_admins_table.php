<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('admins')) {
            return; // never touch a table that already exists
        }

        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 120);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
