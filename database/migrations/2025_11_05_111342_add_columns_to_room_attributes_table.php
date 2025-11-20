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
        Schema::table('transformed_room_attributes', function (Blueprint $table) {
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('external_id')->nullable(); // Apaleo attribute ID
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable(); // text, number, boolean, etc.
            $table->text('value')->nullable();
            $table->json('metadata')->nullable(); // Additional Apaleo data

            $table->index(['room_id', 'code']);
            $table->unique(['room_id', 'external_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transformed_room_attributes', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropUnique(['room_id', 'external_id']);
            $table->dropIndex(['room_id', 'code']);
            $table->dropColumn([
                'room_id',
                'external_id',
                'name',
                'code',
                'description',
                'type',
                'value',
                'metadata'
            ]);
        });
    }
};
