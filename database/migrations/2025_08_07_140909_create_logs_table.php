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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('User');
            $table->string('object_type'); // e.g., User, Book, etc.
            $table->unsignedBigInteger('object_id'); // ID of the affected object
            $table->string('action'); // create, update, delete, restore, login, etc.
            $table->text('remarks')->nullable(); // Reason for action (optional)

            $table->string('ip_address')->nullable(); // Optional, useful for auth actions
            $table->timestamps(); // created_at = log time
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
