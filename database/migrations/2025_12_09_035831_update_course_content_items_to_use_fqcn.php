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
        // Update old 'article' to 'App\Models\Article'
        DB::table('course_content_items')
            ->where('content_type', 'article')
            ->update(['content_type' => 'App\\Models\\Article']);
        
        // Update old 'podcast' to 'App\Models\Podcast'
        DB::table('course_content_items')
            ->where('content_type', 'podcast')
            ->update(['content_type' => 'App\\Models\\Podcast']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'App\Models\Article' back to 'article'
        DB::table('course_content_items')
            ->where('content_type', 'App\\Models\\Article')
            ->update(['content_type' => 'article']);
        
        // Revert 'App\Models\Podcast' back to 'podcast'
        DB::table('course_content_items')
            ->where('content_type', 'App\\Models\\Podcast')
            ->update(['content_type' => 'podcast']);
    }
};
