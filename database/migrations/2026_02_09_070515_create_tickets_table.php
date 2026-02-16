<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->foreignId('sub_category_id')->nullable()->constrained('product_sub_categories')->onDelete('set null');
            $table->string('subject');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['open', 'in-progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('forwarded_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
