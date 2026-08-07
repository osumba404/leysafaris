<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('wildlife');
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('duration_hours')->nullable();
            $table->decimal('starting_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
