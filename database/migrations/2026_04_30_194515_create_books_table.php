<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn', 20)->unique();
            $table->string('publisher')->nullable();
            $table->year('publication_year')->nullable();
            $table->integer('pages')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->boolean('is_available')->default(true);
            $table->string('location')->nullable();
            $table->decimal('replacement_price', 10, 0)->default(5000);
            $table->timestamps();
            
            // Index simples au lieu d'index composite
            $table->index('title');
            $table->index('author');
            $table->index('isbn');
            $table->index('is_available');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};