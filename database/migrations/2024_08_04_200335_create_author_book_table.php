<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * author_id
     * book_id
     */
    public function up(): void
    {
        Schema::create('Author_Book', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained("Authors")->onDelete('set null');
            $table->foreignId('book_id')->constrained("Book")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Author_Book');
    }
};
