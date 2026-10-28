<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reviews & ratings.
 *  review_invites : one private, single-use link per invited person (partner / collaborator)
 *  reviews        : the feedback itself; the admin decides which ones show on the website (is_visible)
 * Only NEW tables are created (each is skipped when it already exists).
 */
class CreateReviewsTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('review_invites')) {
            Schema::create('review_invites', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120);
                $table->string('email', 150);
                $table->string('company', 150)->nullable();
                $table->string('token', 64)->unique();          // the secret part of /feedback/{token}
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('sent_at')->nullable();       // last time the email was sent
                $table->timestamp('used_at')->nullable();       // feedback received
                $table->timestamp('revoked_at')->nullable();
                $table->timestamps();

                $table->index('email');
            });
        }

        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('invite_id')->nullable()->unique();   // one review per invite
                $table->string('name', 120);
                $table->string('company_role', 150)->nullable();
                $table->unsignedTinyInteger('rating');                         // 1 to 5
                $table->text('comment');
                $table->boolean('is_visible')->default(false);                 // false = backend only
                $table->unsignedInteger('sort_order')->default(0);
                $table->string('source', 10)->default('invite');               // invite | admin
                $table->timestamps();

                $table->index(['is_visible', 'sort_order']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('review_invites');
    }
}
