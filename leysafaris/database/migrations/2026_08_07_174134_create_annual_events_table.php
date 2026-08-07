<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annual_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->date('event_date');
            $table->date('early_bird_deadline')->nullable();
            $table->decimal('early_bird_price', 10, 2)->nullable();
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('hero_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annual_events');
    }
};
