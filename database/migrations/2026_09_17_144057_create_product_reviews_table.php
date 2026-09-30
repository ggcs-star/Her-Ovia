<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();

            $table->unsignedTinyInteger('rating')->nullable();

            $table->string('title')->nullable();

            $table->text('review');

            $table->string('size', 100)->nullable();

            $table->string('color', 100)->nullable();

            $table->json('images')->nullable();

            $table->string('video')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['product_id', 'status']);
            $table->index(['user_id', 'product_id']);
            $table->index(['order_id', 'product_id']);
            $table->index(['rating']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};