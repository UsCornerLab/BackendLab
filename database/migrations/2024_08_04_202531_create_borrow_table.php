<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * user_id
     * copy_id
     * status
     */
    public function up(): void
    {
        Schema::create('Borrow', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("User");
            $table->foreignId('copy_id')->constrained("Book");
            $table->enum("status", ["Borrowed", "Returned", "Missed"])->default("Borrowed");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Borrow');
    }
};
