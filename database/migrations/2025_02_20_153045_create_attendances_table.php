<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->integer('date');
            $table->integer('check_in_time');
            $table->integer('check_out_time')->nullable();
            $table->string('photo_check_in');
            $table->string('photo_check_out')->nullable();
            $table->decimal('latitude_check_in', 10, 8);
            $table->decimal('longitude_check_in', 11, 8);
            $table->decimal('latitude_check_out', 10, 8)->nullable();
            $table->decimal('longitude_check_out', 11, 8)->nullable();
            $table->enum('check_in_status', ['On Time', 'Late', 'Absent']);
            $table->enum('check_out_status', ['On Time', 'Early Leave', 'Absent']);
            $table->boolean('checkin_outside_location')->default(false);
            $table->boolean('checkout_outside_location')->default(false);
            $table->unique(['user_id', 'date']);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
