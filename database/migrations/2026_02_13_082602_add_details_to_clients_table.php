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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('contact_person_name')->nullable()->after('phone');
            $table->string('contact_person_phone')->nullable()->after('contact_person_name');
            $table->json('project_name')->nullable()->after('contact_person_phone');
            $table->date('project_start_date')->nullable()->after('project_name');
            $table->date('project_end_date')->nullable()->after('project_start_date');
            $table->text('remarks')->nullable()->after('project_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person_name',
                'contact_person_phone',
                'project_name',
                'project_start_date',
                'project_end_date',
                'remarks'
            ]);
        });
    }
};
