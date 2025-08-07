<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableFieldsInAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->bigInteger('check_in_time')->nullable()->change();
            $table->bigInteger('check_out_time')->nullable()->change();
            $table->string('photo_check_in')->nullable()->change();
            $table->string('photo_check_out')->nullable()->change();
            $table->decimal('latitude_check_in', 10, 7)->nullable()->change();
            $table->decimal('longitude_check_in', 10, 7)->nullable()->change();
            $table->decimal('latitude_check_out', 10, 7)->nullable()->change();
            $table->decimal('longitude_check_out', 10, 7)->nullable()->change();
            $table->unsignedBigInteger('schedule_id')->nullable()->change();
            $table->text('comment')->nullable()->change();
        });
    }

    public function down()
    {

    }
}
