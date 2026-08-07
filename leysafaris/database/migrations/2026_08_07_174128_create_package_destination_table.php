<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_destination', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->unique(['package_id', 'destination_id']);
        });

        Schema::create('experience_package', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->unique(['experience_id', 'package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experience_package');
        Schema::dropIfExists('package_destination');
    }
};
