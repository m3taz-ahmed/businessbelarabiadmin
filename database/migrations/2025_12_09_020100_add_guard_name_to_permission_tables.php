<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add guard_name to permissions table if it doesn't exist
        if (Schema::hasTable('permissions') && !Schema::hasColumn('permissions', 'guard_name')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('guard_name', 125)->default('admin')->after('name');
            });

            // Update existing records to have 'admin' guard
            DB::table('permissions')->update(['guard_name' => 'admin']);

            // Add unique constraint for name and guard_name
            Schema::table('permissions', function (Blueprint $table) {
                $table->unique(['name', 'guard_name']);
            });
        }

        // Add guard_name to roles table if it doesn't exist
        if (Schema::hasTable('roles') && !Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('guard_name', 125)->default('admin')->after('name');
            });

            // Update existing records to have 'admin' guard
            DB::table('roles')->update(['guard_name' => 'admin']);

            // Add unique constraint for name and guard_name
            Schema::table('roles', function (Blueprint $table) {
                $table->unique(['name', 'guard_name']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('permissions') && Schema::hasColumn('permissions', 'guard_name')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropUnique(['name', 'guard_name']);
                $table->dropColumn('guard_name');
            });
        }

        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropUnique(['name', 'guard_name']);
                $table->dropColumn('guard_name');
            });
        }
    }
};
