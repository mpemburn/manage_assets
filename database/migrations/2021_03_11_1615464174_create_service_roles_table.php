<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceRolesTable extends Migration
{
    public function up()
    {
        Schema::create('service_roles', function (Blueprint $table) {

		$table->id();
		$table->bigInteger('service_id')->unsigned();
		$table->bigInteger('role_id')->unsigned();
		$table->foreign('role_id')->references('id')->on('roles');
		$table->foreign('service_id')->references('id')->on('services');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_roles');
    }
}
