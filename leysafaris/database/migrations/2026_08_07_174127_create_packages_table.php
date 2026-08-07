<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->unsignedSmallInteger('duration_days');
            $table->decimal('starting_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('price_note')->nullable();
            $table->json('experience_types')->nullable();
            $table->json('traveler_types')->nullable();
            $table->string('departure_style')->default('private');
            $table->json('highlights')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('gallery')->nullable();
            $table->string('hero_image')->nullable();
            $table->text('pricing_notes')->nullable();
            $table->text('practical_info')->nullable();
            $table->string('route_map_image')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_template')->default(false);
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
