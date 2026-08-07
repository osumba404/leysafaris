<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('day_number');
            $table->string('title');
            $table->string('location')->nullable();
            $table->text('morning')->nullable();
            $table->text('afternoon')->nullable();
            $table->text('evening')->nullable();
            $table->text('narrative')->nullable();
            $table->json('meals')->nullable();
            $table->string('accommodation')->nullable();
            $table->text('accommodation_note')->nullable();
            $table->json('activities')->nullable();
            $table->text('travel_notes')->nullable();
            $table->text('wildlife_highlights')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_days');
    }
};
