<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| The website already stores its settings (CONTACT_EMAIL, PHONE, ADDRESS ...) in tbl_settings.
| On your real database this table exists, so this migration does nothing.
| It only creates an empty table on a brand-new database (for example a fresh local copy).
*/

class CreateTblSettingsTableIfMissing extends Migration
{
    public function up()
    {
        if (Schema::hasTable('tbl_settings')) {
            return;
        }

        Schema::create('tbl_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key_name', 100)->unique();
            $table->text('key_value')->nullable();
        });
    }

    public function down()
    {
        // intentionally empty: this table belongs to the website, never drop it from here
    }
}
