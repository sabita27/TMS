<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person_name',
                'contact_person_phone'
            ]);
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
        });
    }
};
