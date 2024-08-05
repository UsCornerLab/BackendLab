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
        Schema::create('Genre_Book', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->constrained("Genre")->onDelete('cascade');
            $table->foreignId('book_id')->constrained("Book")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Genre_Book');
    }
};
