<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * News and events shown on the home page and the News & events pages.
 */
class CreateNewsEventsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('news_events')) {
            return;
        }

        Schema::create('news_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 20)->default('news');      // news | event (config/news.php)
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->string('summary', 400)->nullable();       // short text on cards
            $table->longText('body')->nullable();             // full story (simple HTML)
            $table->string('image', 190)->nullable();         // file name inside public/upload/news
            $table->string('video_url', 300)->nullable();     // optional YouTube / Vimeo / mp4 link
            $table->date('event_date')->nullable();           // events only
            $table->string('location', 150)->nullable();      // events only
            $table->date('published_at');                     // date shown on the card
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('news_events');
    }
}
