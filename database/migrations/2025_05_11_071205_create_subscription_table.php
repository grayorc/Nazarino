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
        Schema::create('subscription_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('price', 10 , 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sub_features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('key')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subscription_tier_sub_features', function (Blueprint $table) {
            $table->foreignId('sub_feature_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('subscription_tier_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->primary(['sub_feature_id', 'subscription_tier_id']);
            $table->timestamps();
        });

        Schema::create('subscription_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('subscription_tier_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('status')->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('subscription_user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('تومان');
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('status');
            $table->index('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('subscription_users');
        Schema::dropIfExists('subscription_tier_permissions');
        Schema::dropIfExists('subscription_tiers');
    }
};
