<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hero_slides')) {
            Schema::create('hero_slides', function (Blueprint $table) {
                $table->id();
                $table->string('image');
                $table->string('eyebrow')->nullable();
                $table->string('title');
                $table->text('subtitle')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('nav_items')) {
            Schema::create('nav_items', function (Blueprint $table) {
                $table->id();
                $table->string('label');
                $table->string('route_name')->nullable();
                $table->string('url')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->boolean('is_highlight')->default(false);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('footer_links')) {
            Schema::create('footer_links', function (Blueprint $table) {
                $table->id();
                $table->string('group')->default('explore');
                $table->string('label');
                $table->string('route_name')->nullable();
                $table->string('url')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_links');
        Schema::dropIfExists('nav_items');
        Schema::dropIfExists('hero_slides');
    }
};
