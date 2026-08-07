<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('preferred_destinations')->nullable();
            $table->string('travel_dates')->nullable();
            $table->unsignedSmallInteger('group_size')->nullable();
            $table->string('budget_range')->nullable();
            $table->text('special_interests')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->string('source')->default('website');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
