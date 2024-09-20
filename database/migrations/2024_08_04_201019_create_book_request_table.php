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
        Schema::create('book_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('User')->onDelete("cascade");
            $table->string('name');
            $table->string('book_id');
            $table->string('email');
            $table->string('title');
            $table->string('author');
            $table->string('isbn');
            $table->string('publisher');
            $table->text('recommendation');
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Delivered'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_request');
    }
};
