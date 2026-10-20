<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| Adds a "role" to every admin (see config/admin.php > roles).
| Admins that already exist become super admins, so nobody is locked out.
*/
class AddRoleToAdminsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('admins') || Schema::hasColumn('admins', 'role')) {
            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            $table->string('role', 30)->default(config('admin.super_role'))->after('password');
        });
    }

    public function down()
    {
        if (Schema::hasColumn('admins', 'role')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
}
