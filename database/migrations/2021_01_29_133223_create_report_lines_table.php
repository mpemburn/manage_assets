<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('report_lines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('report_id');
            $table->foreignId('report_issue_id')->constrained('report_issues');
            $table->string('data');
            $table->string('mac_addresses');
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
        Schema::dropIfExists('report_lines');
    }
}
