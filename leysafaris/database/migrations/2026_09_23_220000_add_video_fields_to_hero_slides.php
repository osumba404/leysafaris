<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hero_slides')) {
            return;
        }

        Schema::table('hero_slides', function (Blueprint $table) {
            if (! Schema::hasColumn('hero_slides', 'media_type')) {
                $table->string('media_type', 20)->default('image')->after('id');
            }
            if (! Schema::hasColumn('hero_slides', 'video_path')) {
                $table->string('video_path')->nullable()->after('image');
            }
            if (! Schema::hasColumn('hero_slides', 'video_url')) {
                $table->string('video_url', 500)->nullable()->after('video_path');
            }
        });

        if (Schema::hasColumn('hero_slides', 'image')) {
            Schema::table('hero_slides', function (Blueprint $table) {
                $table->string('image')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('hero_slides')) {
            return;
        }

        Schema::table('hero_slides', function (Blueprint $table) {
            if (Schema::hasColumn('hero_slides', 'video_url')) {
                $table->dropColumn('video_url');
            }
            if (Schema::hasColumn('hero_slides', 'video_path')) {
                $table->dropColumn('video_path');
            }
            if (Schema::hasColumn('hero_slides', 'media_type')) {
                $table->dropColumn('media_type');
            }
        });
    }
};
