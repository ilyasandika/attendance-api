<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->onDelete('cascade');

            $table->bigInteger('date');

            //history
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration')->nullable(); //minutes
            $table->integer('late_minutes')->nullable();
            $table->integer('early_leave_minutes')->nullable();
            $table->integer('overtime_minutes')->nullable();

            // Check in
            $table->bigInteger('check_in_time')->nullable();
            $table->string('check_in_photo')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->text('check_in_address')->nullable();
            $table->enum('check_in_status', ['on time', 'late', 'absent'])->nullable();
            $table->text('check_in_comment')->nullable();
            $table->boolean('check_in_outside_location')->default(false);

            // Check out
            $table->bigInteger('check_out_time')->nullable();
            $table->string('check_out_photo')->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->text('check_out_address')->nullable();
            $table->enum('check_out_status', ['on time', 'early leave', 'absent'])->nullable();
            $table->text('check_out_comment')->nullable();
            $table->boolean('check_out_outside_location')->default(false);


            $table->boolean('auto_checkout')->default(false);

            $table->unique(['user_id', 'date']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
