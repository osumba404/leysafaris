<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('country')->default('Kenya');
            $table->string('region')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->string('best_time')->nullable();
            $table->text('signature_wildlife')->nullable();
            $table->string('hero_image')->nullable();
            $table->json('gallery')->nullable();
            $table->json('facts')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
