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
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('end_date');
            $table->boolean('has_comment');
            $table->boolean('is_revocable');
            $table->timestamps();
        });
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->bigInteger('election_id')->unsigned();
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
        });
        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('election_id')->unsigned();
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
        Schema::dropIfExists('invites');
        Schema::dropIfExists('elections');
    }
};
