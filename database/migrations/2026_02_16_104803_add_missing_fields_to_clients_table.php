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
            $table->string('contact_person1_name')->nullable()->after('address');
            $table->string('contact_person1_phone')->nullable()->after('contact_person1_name');
            $table->string('contact_person2_name')->nullable()->after('contact_person1_phone');
            $table->string('contact_person2_phone')->nullable()->after('contact_person2_name');
            $table->string('business_type')->default('product')->after('contact_person2_phone');
            $table->string('project_title')->nullable()->after('project_id');
            $table->text('project_description')->nullable()->after('project_title');
            $table->string('attachment')->nullable()->after('project_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person1_name',
                'contact_person1_phone',
                'contact_person2_name',
                'contact_person2_phone',
                'business_type',
                'project_title',
                'project_description',
                'attachment'
            ]);
        });
    }
};
