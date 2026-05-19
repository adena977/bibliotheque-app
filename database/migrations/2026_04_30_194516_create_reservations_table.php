<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->date('reserved_at');
            $table->date('expires_at');
            $table->enum('status', ['pending', 'active', 'cancelled', 'expired', 'converted'])->default('pending');
            $table->integer('position')->default(1);
            $table->boolean('notified')->default(false);
            $table->timestamps();
            
            // Index simplifiés
            $table->index('user_id');
            $table->index('book_id');
            $table->index('status');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};