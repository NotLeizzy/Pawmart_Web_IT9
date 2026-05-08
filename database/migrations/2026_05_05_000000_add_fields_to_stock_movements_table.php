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
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->after('id');
            $table->unsignedBigInteger('order_id')->nullable()->after('product_id');
            $table->enum('type', ['in', 'out'])->default('in')->after('order_id');
            $table->integer('quantity')->default(0)->after('type');
            $table->string('reference')->nullable()->after('quantity');
            $table->unsignedBigInteger('user_id')->nullable()->after('reference');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['order_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['product_id', 'order_id', 'type', 'quantity', 'reference', 'user_id']);
        });
    }
};
