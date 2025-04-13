<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Ensure the class name matches the migration file name convention
// (e.g., RemoveElectionFromVotes) if you are using class-based migrations
return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // 1. Drop the foreign key constraint FIRST.
            // Use the conventional name or the specific name if you defined it differently.
            // $table->dropForeign('votes_election_id_foreign');
            // If the above doesn't work, try passing the column name in an array:
            $table->dropForeign(['election_id']);

            // 2. Drop the column itself.
            $table->dropColumn('election_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Target the CORRECT table: 'votes'
        Schema::table('votes', function (Blueprint $table) {
            // 1. Add the column back.
            // Make sure the type (unsignedBigInteger is common for foreignId) and constraints
            // (like nullable) match how it was originally defined.
            // Add ->nullable() if the column could be null.
            // Add ->after('some_other_column') if you need specific column order.
            $table->foreignId('election_id')
                  ->nullable() // Add or remove based on original definition
                  ->constrained('elections') // Creates unsignedBigInteger and adds foreign key
                  ->onDelete('cascade'); // Or 'set null', 'restrict' etc. match original

            // If you didn't use constrained() originally, you might need:
            // $table->unsignedBigInteger('election_id')->nullable(); // Add column
            // $table->foreign('election_id') // Add constraint separately
            //       ->references('id')
            //       ->on('elections')
            //       ->onDelete('cascade');
        });
    }
};