<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| The Careers form saves an "is_unpaid" answer, but the first migration never created that column,
| so saving an application could fail. This adds it ONLY if it is missing (safe to run on the live
| database: it never changes or removes existing data).
*/
class AddIsUnpaidToTblJobApplications extends Migration
{
    public function up()
    {
        if (Schema::hasTable('tbl_job_applications') && !Schema::hasColumn('tbl_job_applications', 'is_unpaid')) {
            Schema::table('tbl_job_applications', function (Blueprint $table) {
                $table->boolean('is_unpaid')->default(false)->after('previous_salary');
            });
        }
    }

    public function down()
    {
        // intentionally empty: never drop application data from a migration
    }
}
