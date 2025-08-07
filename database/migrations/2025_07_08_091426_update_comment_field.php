<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('comment');
            $table->text('check_in_comment')->nullable()->after('checkin_outside_location');
            $table->text('check_out_comment')->nullable()->after('checkout_outside_location');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['check_in_comment', 'check_out_comment']);
            $table->text('comment')->nullable()->after('checkout_outside_location');
        });
    }
};
