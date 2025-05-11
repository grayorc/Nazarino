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
            $table->unsignedBigInteger('sub_feature_id');
            $table->unsignedBigInteger('subscription_tier_id');

            $table->foreign('sub_feature_id')
                ->references('id')
                ->on('sub_features')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('subscription_tier_id')
                ->references('id')
                ->on('subscription_tiers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->primary(['sub_feature_id', 'subscription_tier_id']);
            $table->timestamps();
        });


        Schema::create('subscription_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained()
                ->onDelete('cascade');
            $table->foreignId('subscription_tier_id')
            ->constrained('subscription_tiers')
                ->onDelete('cascade');
            $table->string('status')->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('subscription_user_id')
                ->nullable()
                ->constrained('subscription_users')
                ->nullOnDelete();
            $table->decimal('total', 10, 2);
            $table->string('currency')->default('تومان');
            $table->string('payment_method')->nullable();
            $table->string('payment_status');
            $table->json('meta_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('receipt_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('receipt_id')
                ->constrained('receipts')
                ->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });



    }


    public function down(): void
    {
        Schema::dropIfExists('receipt_users');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('subscription_users');
        Schema::dropIfExists('subscription_tier_permissions');
        Schema::dropIfExists('subscription_tiers');
    }
};
