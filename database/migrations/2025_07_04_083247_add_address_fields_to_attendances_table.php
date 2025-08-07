<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->text('check_in_address')->nullable()->after('longitude_check_in');
            $table->text('check_out_address')->nullable()->after('longitude_check_out');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('check_in_address');
            $table->dropColumn('check_out_address');
        });
    }
};
