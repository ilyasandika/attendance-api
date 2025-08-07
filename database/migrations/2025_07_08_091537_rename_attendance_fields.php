<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->renameColumn('photo_check_in', 'check_in_photo');
            $table->renameColumn('photo_check_out', 'check_out_photo');
            $table->renameColumn('latitude_check_in', 'check_in_latitude');
            $table->renameColumn('longitude_check_in', 'check_in_longitude');
            $table->renameColumn('latitude_check_out', 'check_out_latitude');
            $table->renameColumn('longitude_check_out', 'check_out_longitude');
            $table->renameColumn('checkin_outside_location', 'check_in_outside_location');
            $table->renameColumn('checkout_outside_location', 'check_out_outside_location');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->renameColumn('check_in_photo', 'photo_check_in');
            $table->renameColumn('check_out_photo', 'photo_check_out');
            $table->renameColumn('check_in_latitude', 'latitude_check_in');
            $table->renameColumn('check_in_longitude', 'longitude_check_in');
            $table->renameColumn('check_out_latitude', 'latitude_check_out');
            $table->renameColumn('check_out_longitude', 'longitude_check_out');
            $table->renameColumn('check_in_outside_location', 'checkin_outside_location');
            $table->renameColumn('check_out_outside_location', 'checkout_outside_location');

        });
    }
};
