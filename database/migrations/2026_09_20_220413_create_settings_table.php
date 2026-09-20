<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('shop_id')->index();
                $table->string('key', 100)->index();
                $table->text('value')->nullable();
                $table->timestamps();

                $table->unique(['shop_id', 'key']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};