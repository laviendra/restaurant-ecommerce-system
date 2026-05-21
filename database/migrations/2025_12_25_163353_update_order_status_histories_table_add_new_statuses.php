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
        Schema::table('order_status_histories', function (Blueprint $table) {
            // Drop the existing status column
            $table->dropColumn('status');
        });
        
        Schema::table('order_status_histories', function (Blueprint $table) {
            // Add the new status column with updated enum values
            $table->enum('status', [
                'pending', 
                'confirmed', 
                'processing', 
                'shipped', 
                'delivered', 
                'completed', 
                'cancelled'
            ])->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'processing', 'completed', 'cancelled'])->after('order_id');
        });
    }
};