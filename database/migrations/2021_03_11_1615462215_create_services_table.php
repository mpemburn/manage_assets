<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable()->default('NULL');
            $table->string('url', 500)->default('NULL');
            $table->string('username', 100)->default('NULL');
            $table->string('password', 100)->default('NULL');
            $table->text('notes')->nullable()->default('NULL');
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
}
