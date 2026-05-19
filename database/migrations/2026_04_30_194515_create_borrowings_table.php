<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->date('borrowed_at');
            $table->date('due_date');
            $table->date('returned_at')->nullable();
            $table->enum('status', ['ongoing', 'returned', 'overdue', 'lost'])->default('ongoing');
            $table->decimal('fine', 10, 0)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->text('notes')->nullable();
            $table->foreignId('borrowed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('returned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Index simples au lieu d'index composites
            $table->index('user_id');
            $table->index('status');
            $table->index('due_date');
            $table->index('borrowed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};