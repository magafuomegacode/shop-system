<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop category foreign key
            if (Schema::hasColumn('products', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }

            // Add store_id
            if (!Schema::hasColumn('products', 'store_id')) {
                $table->foreignId('store_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('stores')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'store_id')) {
                $table->dropForeign(['store_id']);
                $table->dropColumn('store_id');
            }

            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('categories')
                      ->nullOnDelete();
            }
        });
    }
};