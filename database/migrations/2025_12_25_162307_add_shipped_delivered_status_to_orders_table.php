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
        Schema::table('orders', function (Blueprint $table) {
            // Modify the order_status enum to include shipped and delivered
            $table->dropColumn('order_status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_status', [
                'pending', 
                'confirmed', 
                'processing', 
                'shipped', 
                'delivered', 
                'completed', 
                'cancelled'
            ])->default('pending')->after('payment_status');
            
            // Add delivery confirmation fields
            $table->timestamp('shipped_at')->nullable()->after('payment_proof');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->text('delivery_notes')->nullable()->after('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipped_at', 'delivered_at', 'delivery_notes']);
            $table->dropColumn('order_status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_status', ['pending', 'confirmed', 'processing', 'completed', 'cancelled'])->default('pending');
        });
    }
};