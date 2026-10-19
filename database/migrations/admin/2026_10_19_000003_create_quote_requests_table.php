<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteRequestsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('quote_requests')) {
            return;
        }

        Schema::create('quote_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference', 30)->nullable()->unique();

            // Catalogue choice (optional). The name is copied too, so the request
            // still reads correctly if the product/service is renamed or deleted later.
            $table->unsignedInteger('product_id')->nullable()->index();
            $table->string('product_label', 190)->nullable();
            $table->unsignedInteger('service_id')->nullable()->index();
            $table->string('service_label', 190)->nullable();

            // What the customer typed in "Product name"
            $table->string('item_name', 190);

            // Size
            $table->decimal('length', 10, 2);
            $table->decimal('width', 10, 2);
            $table->decimal('height', 10, 2);
            $table->string('unit', 20);

            // Specification
            $table->string('stock', 120);
            $table->string('coating', 120);
            $table->string('add_ons', 120);
            $table->string('colours', 120);
            $table->string('packing', 60)->nullable();
            $table->unsignedInteger('quantity_1');
            $table->unsignedInteger('quantity_2');

            // Attached artwork (kept in private storage)
            $table->string('file_path', 255)->nullable();
            $table->string('file_original_name', 255)->nullable();
            $table->unsignedInteger('file_size')->nullable();

            // Customer
            $table->string('full_name', 120);
            $table->string('phone', 40);
            $table->string('email', 150);
            $table->string('company', 150);
            $table->text('message')->nullable();

            // Inbox workflow
            $table->string('status', 20)->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('mail_sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quote_requests');
    }
}
