<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE attendances
            MODIFY COLUMN user_id BIGINT(20) UNSIGNED NOT NULL AFTER id,
            MODIFY COLUMN schedule_id BIGINT(20) UNSIGNED NULL AFTER user_id,
            MODIFY COLUMN date INT(11) NOT NULL AFTER schedule_id,

            MODIFY COLUMN check_in_time BIGINT(20) NULL AFTER date,
            MODIFY COLUMN check_in_photo VARCHAR(255) NULL AFTER check_in_time,
            MODIFY COLUMN check_in_latitude DECIMAL(10,7) NULL AFTER check_in_photo,
            MODIFY COLUMN check_in_longitude DECIMAL(10,7) NULL AFTER check_in_latitude,
            MODIFY COLUMN check_in_address TEXT NULL AFTER check_in_longitude,
            MODIFY COLUMN check_in_status ENUM('On Time','Late','Absent') NOT NULL AFTER check_in_address,
            MODIFY COLUMN check_in_comment TEXT NULL AFTER check_in_status,
            MODIFY COLUMN check_in_outside_location TINYINT(1) NOT NULL DEFAULT 0 AFTER check_in_comment,

            MODIFY COLUMN check_out_time BIGINT(20) NULL AFTER check_in_outside_location,
            MODIFY COLUMN check_out_photo VARCHAR(255) NULL AFTER check_out_time,
            MODIFY COLUMN check_out_latitude DECIMAL(10,7) NULL AFTER check_out_photo,
            MODIFY COLUMN check_out_longitude DECIMAL(10,7) NULL AFTER check_out_latitude,
            MODIFY COLUMN check_out_address TEXT NULL AFTER check_out_longitude,
            MODIFY COLUMN check_out_status ENUM('On Time','Early Leave','Absent') NOT NULL AFTER check_out_address,
            MODIFY COLUMN check_out_comment TEXT NULL AFTER check_out_status,
            MODIFY COLUMN check_out_outside_location TINYINT(1) NOT NULL DEFAULT 0 AFTER check_out_comment,

            MODIFY COLUMN auto_checkout TINYINT(1) NOT NULL DEFAULT 0 AFTER check_out_outside_location,
            MODIFY COLUMN created_at TIMESTAMP NULL AFTER auto_checkout,
            MODIFY COLUMN updated_at TIMESTAMP NULL AFTER created_at
        ");
    }

    public function down(): void
    {

    }
};
