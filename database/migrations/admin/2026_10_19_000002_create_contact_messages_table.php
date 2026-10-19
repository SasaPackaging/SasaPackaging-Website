<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactMessagesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('contact_messages')) {
            return;
        }

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 120);
            $table->string('email', 150);
            $table->string('phone', 40);
            $table->string('subject', 180);
            $table->text('message');
            $table->string('status', 20)->index();   // values come from config/admin.php > inbox.statuses
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('mail_sent_at')->nullable();  // when the notification email went out
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_messages');
    }
}
