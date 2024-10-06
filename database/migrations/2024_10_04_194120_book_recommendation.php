<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('book_recommendations')) {
            Schema::create('book_recommendations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained("User");
                $table->string('book_title');
                $table->string('author');
                $table->text('reason');
                $table->string('status');
                $table->string('publisher')->nullable();
                $table->timestamps();

                
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_recommendations');
    }
};
