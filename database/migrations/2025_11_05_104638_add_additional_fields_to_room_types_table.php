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
        Schema::table('transformed_room_types', function (Blueprint $table) {
            $table->string('code')->nullable()->after('external_id');
            $table->integer('member_count')->nullable()->after('max_occupancy'); // Number of units of this type
            $table->string('type')->nullable()->after('member_count'); // BedRoom, MeetingRoom, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transformed_room_types', function (Blueprint $table) {
            $table->dropColumn(['code', 'member_count', 'type']);
        });
    }
};
