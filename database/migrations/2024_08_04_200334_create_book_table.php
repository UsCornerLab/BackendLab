<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * title
     * author_id
     * ISBN
     * publisher
     * publication_date
     * genre_id
     * category_id
     */
    public function up(): void
    {
        Schema::create('Book', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            // $table->foreignId('author_id')->constrained('Author_Book')->onDelete("set null");
            $table->string('ISBN')->unique()->nullable();
            $table->string('publisher');
            $table->date('publication_date');
            $table->string('cover_image_path')->unique()->nullable();
            $table->integer("accession_number")->unique();
            $table->integer("copies")->default(1);
            $table->integer("available_copies")->default(1);
            // $table->foreignId('genre_id')->constrained('Genre')->onDelete("set null");
            $table->foreignId('category_id')->constrained('Category')->onDelete("cascade");
            $table->foreignId("added_by")->constrained('User');
            $table->enum("status", ["Available", "Reserved", "Borrowed"])->default("Available");
            $table->foreignId("from")->constrained('Origin_from');
            $table->boolean("active")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Book');
    }
};
