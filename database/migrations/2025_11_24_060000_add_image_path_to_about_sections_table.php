<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_sections', function (Blueprint $table) {
            if (! Schema::hasColumn('about_sections', 'image_path')) {
                $table->string('image_path')->nullable()->after('content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('about_sections', function (Blueprint $table) {
            if (Schema::hasColumn('about_sections', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};
