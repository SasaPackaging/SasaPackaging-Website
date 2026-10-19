<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTblJobOpeningsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_job_openings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('department', 80)->nullable();
            $table->string('location', 80)->default('Lahore');
            $table->string('job_type', 40)->default('Full-time');
            $table->string('profile', 30)->default('general'); // general | creative | production | sales (see config/careers.php)
            $table->text('summary')->nullable();
            $table->enum('status', ['Yes', 'No'])->default('Yes');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // SAMPLE openings so the page is not empty. Edit/delete them from the new admin later.
        $now = date('Y-m-d H:i:s');
        $rows = [
            ['Graphic Designer', 'Design', 'creative', 'Create dielines, box artwork and print-ready files for packaging clients.'],
            ['Machine Operator - Die Cutting', 'Production', 'production', 'Set up and run die cutting machines for exact dimensions and clean edges.'],
            ['Sales Executive - Export', 'Sales', 'sales', 'Grow packaging orders with local and international clients.'],
            ['Accounts Officer', 'Finance', 'general', 'Handle invoicing, payments and monthly reconciliations.'],
        ];
        foreach ($rows as $i => $r) {
            DB::table('tbl_job_openings')->insert([
                'title' => $r[0],
                'department' => $r[1],
                'profile' => $r[2],
                'summary' => $r[3],
                'sort_order' => $i + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('tbl_job_openings');
    }
}
