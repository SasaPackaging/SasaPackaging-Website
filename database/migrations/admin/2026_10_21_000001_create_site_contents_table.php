<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Edited website wording. One row per changed setting; no row = the default from config/landing.php or config/site.php.
 */
class CreateSiteContentsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('site_contents')) {
            return;
        }

        Schema::create('site_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('config_key', 150)->unique(); // e.g. landing.values.title
            $table->longText('value')->nullable();       // JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_contents');
    }
}
