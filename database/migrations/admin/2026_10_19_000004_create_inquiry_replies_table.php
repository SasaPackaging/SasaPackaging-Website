<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiryRepliesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('inquiry_replies')) {
            return;
        }

        // One table keeps the replies for BOTH contact messages and quote requests.
        Schema::create('inquiry_replies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inquiry_type', 40);            // see Relation::morphMap in AppServiceProvider
            $table->unsignedInteger('inquiry_id');
            $table->unsignedInteger('admin_id')->nullable();
            $table->string('to_email', 150);
            $table->string('subject', 190);
            $table->text('body');
            $table->timestamps();

            $table->index(['inquiry_type', 'inquiry_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inquiry_replies');
    }
}
