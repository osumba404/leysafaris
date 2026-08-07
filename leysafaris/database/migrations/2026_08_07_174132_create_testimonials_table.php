<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name');
            $table->string('author_location')->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('source')->default('manual');
            $table->string('source_url')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
