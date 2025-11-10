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
        Schema::table('mews_resources', function (Blueprint $table) {
            $table->string('category_id')->nullable()->after('parent_resource_id');
            $table->string('building_number')->nullable()->after('floor_number');
            $table->string('external_identifier')->nullable()->after('location_notes');
            $table->text('description')->nullable()->after('external_identifier');
            
            $table->index(['category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mews_resources', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropColumn(['category_id', 'building_number', 'external_identifier', 'description']);
        });
    }
};
