<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSecurityQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('service_security_questions', function (Blueprint $table) {
		$table->id();
		$table->bigInteger('service _id')->unsigned();
		$table->string('question',100);
		$table->string('answer',100);
		$table->foreign('service _id')->references('id')->on('services');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_security_questions');
    }
}
