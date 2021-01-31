<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('report_issues', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('report_id');
            $table->string('severity');
            $table->string('problem', 500);
            $table->string('description', 500)->nullable();
            $table->string('solution', 500);
            $table->string('uid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('report_issues');
    }
}
