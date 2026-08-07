<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->unique();
            $table->string('title');
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('line_items')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('draft');
            $table->date('valid_until')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->string('pdf_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
