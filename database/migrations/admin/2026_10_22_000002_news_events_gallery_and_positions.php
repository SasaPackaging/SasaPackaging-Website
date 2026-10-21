<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * News & events: many pictures per item (images), and the job openings a "Hiring" item points to.
 * The old single "image" column is copied into "images" and then left alone (never deleted from here).
 */
class NewsEventsGalleryAndPositions extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('news_events')) {
            return;
        }

        if (!Schema::hasColumn('news_events', 'images')) {
            Schema::table('news_events', function (Blueprint $table) {
                $table->text('images')->nullable();          // JSON list of file names, first = cover
            });

            if (Schema::hasColumn('news_events', 'image')) {
                foreach (DB::table('news_events')->whereNotNull('image')->where('image', '!=', '')->get(['id', 'image']) as $row) {
                    DB::table('news_events')->where('id', $row->id)->update(['images' => json_encode([$row->image])]);
                }
            }
        }

        if (!Schema::hasColumn('news_events', 'job_opening_ids')) {
            Schema::table('news_events', function (Blueprint $table) {
                $table->text('job_opening_ids')->nullable(); // JSON list of tbl_job_openings ids (Hiring items)
            });
        }
    }

    public function down()
    {
        // intentionally empty
    }
}
