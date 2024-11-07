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
        Schema::create('g_wordle', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique();

            $table->string('word');

            $table->foreignId('created_by')->nullable()
                ->constrained('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->softDeletes();
        });

        Schema::create('g_wordle_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('attempt');
            $table->timestamp('guessed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g_wordle_attempts');
        Schema::dropIfExists('g_wordle');
    }
};
