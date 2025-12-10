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
        Schema::table('permissions', function (Blueprint $table) {
            // Make permission_type_id nullable with default null
            if (Schema::hasColumn('permissions', 'permission_type_id')) {
                $table->unsignedBigInteger('permission_type_id')->nullable()->default(null)->change();
            }
        });

        Schema::table('roles', function (Blueprint $table) {
            // Make permission_type_id nullable with default null if exists
            if (Schema::hasColumn('roles', 'permission_type_id')) {
                $table->unsignedBigInteger('permission_type_id')->nullable()->default(null)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'permission_type_id')) {
                $table->unsignedBigInteger('permission_type_id')->nullable(false)->change();
            }
        });

        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'permission_type_id')) {
                $table->unsignedBigInteger('permission_type_id')->nullable(false)->change();
            }
        });
    }
};
