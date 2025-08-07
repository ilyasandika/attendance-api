<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultToRolesAndDepartmentsTables extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('default')->default(false)->after('name');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->boolean('default')->default(false)->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('default');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('default');
        });
    }
}
