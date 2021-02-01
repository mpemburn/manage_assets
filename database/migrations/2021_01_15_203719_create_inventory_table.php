<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('device_type');
            $table->string('primary_users')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->string('room')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('device_model')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('computer_name')->nullable();
            $table->string('drive_info')->nullable();
            $table->string('ram')->nullable();
            $table->string('processor')->nullable();
            $table->integer('monitor_count')->default(0);
            $table->string('operating_system')->nullable();
            $table->string('screen_lock_time')->nullable();
            $table->string('antivirus')->nullable();
            $table->string('antivirus_status')->nullable();
            $table->string('comment')->nullable();
            $table->date('date_purchased')->nullable();
            $table->boolean('is_os_current')->default(0);
            $table->boolean('is_hd_encrypted')->default(0);
            $table->boolean('has_user_profiles')->default(0);
            $table->boolean('requires_password')->default(0);
            $table->boolean('has_complex_password')->default(0);
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
        Schema::dropIfExists('inventory');
    }
}
