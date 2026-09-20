<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 50);          // product_added, sale_made, low_stock
            $table->string('title', 150);
            $table->text('message')->nullable();
            $table->string('link')->nullable();
            $table->string('icon', 50)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['shop_id', 'is_read']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};