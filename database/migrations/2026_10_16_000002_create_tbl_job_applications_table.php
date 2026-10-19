<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblJobApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_job_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_opening_id')->nullable();
            $table->string('position_title');
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('email', 150);
            $table->string('phone', 30);
            $table->string('city', 80);
            $table->string('linkedin_url')->nullable();
            $table->string('education_level', 40);
            $table->string('degree', 120);
            $table->string('institution', 150);
            $table->string('grad_year', 4);
            $table->boolean('has_experience')->default(false);
            $table->string('experience_years', 20)->nullable();
            $table->string('last_employer', 150)->nullable();
            $table->string('last_title', 150)->nullable();
            $table->unsignedInteger('previous_salary')->nullable(); // NULL = not given / N/A (e.g. unpaid)
            $table->unsignedInteger('expected_salary')->nullable();
            $table->longText('extra_answers')->nullable();          // role-specific answers (JSON)
            $table->string('cv_path');                               // private: storage/app/cvs
            $table->string('status', 20)->default('new');            // new | shortlisted | rejected
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_job_applications');
    }
}
