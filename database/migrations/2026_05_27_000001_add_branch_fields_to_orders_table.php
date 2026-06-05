<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null')->after('user_id');
            $table->string('customer_name')->nullable()->after('branch_id');
            $table->string('food_item')->nullable()->after('customer_name');
            $table->integer('quantity')->default(1)->after('food_item');
            $table->string('notes')->nullable()->after('quantity');
            $table->timestamp('order_date')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id', 'customer_name', 'food_item', 'quantity', 'notes', 'order_date']);
        });
    }
};
