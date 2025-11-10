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
        Schema::create('mews_resource_feature_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('resource_id'); // Foreign key to mews_resources
            $table->string('resource_feature_id'); // Foreign key to mews_resource_features
            
            // Import tracking
            $table->timestamp('last_imported_at')->nullable();
            $table->timestamps();
            
            $table->index(['resource_id']);
            $table->index(['resource_feature_id']);
            $table->unique(['resource_id', 'resource_feature_id'], 'mews_resource_feature_unique');
            
            $table->foreign('resource_id')->references('mews_id')->on('mews_resources')->onDelete('cascade');
            $table->foreign('resource_feature_id')->references('mews_id')->on('mews_resource_features')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mews_resource_feature_assignments');
    }
};
