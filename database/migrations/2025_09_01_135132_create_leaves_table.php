<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', [
                'annual',
                'sick',
                'unpaid',
                'maternity',
                'emergency',
                'other'
            ]);
            $table->integer('start_date');
            $table->integer('end_date');
            $table->integer('total_days');
            $table->text('reason')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('Pending');
            $table->text('comment')->nullable();
            $table->foreignId('approver_id')->nullable()->constrained('users');
            $table->integer('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
