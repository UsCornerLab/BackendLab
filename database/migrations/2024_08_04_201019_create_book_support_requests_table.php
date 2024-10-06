<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_support_requests', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->unique(); 
            $table->string('phone_number', 15)->nullable();
            $table->json('requested_book_titles')->nullable(); 
            $table->integer('number_of_books')->nullable(); 
            $table->string('request_letter'); 
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('admin_comments')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_support_requests');
    }
};
