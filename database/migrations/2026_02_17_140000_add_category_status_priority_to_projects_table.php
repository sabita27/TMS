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
        Schema::table('projects', function (Blueprint $table) {
            // Add category and subcategory foreign keys
            $table->foreignId('category_id')->nullable()->after('name')->constrained('product_categories')->onDelete('set null');
            $table->foreignId('subcategory_id')->nullable()->after('category_id')->constrained('product_sub_categories')->onDelete('set null');
            
            // Add status and priority foreign keys
            $table->foreignId('status_id')->nullable()->after('subcategory_id')->constrained('ticket_statuses')->onDelete('set null');
            $table->foreignId('priority_id')->nullable()->after('status_id')->constrained('ticket_priorities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['subcategory_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['priority_id']);
            
            $table->dropColumn(['category_id', 'subcategory_id', 'status_id', 'priority_id']);
        });
    }
};
