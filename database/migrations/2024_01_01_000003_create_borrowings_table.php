<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('borrowings');

        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 30)->unique();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('issued_by');
            $table->unsignedBigInteger('returned_to')->nullable();
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['active', 'returned', 'overdue', 'lost'])->default('active');
            $table->decimal('fine_amount', 8, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->date('fine_paid_date')->nullable();
            $table->enum('condition_on_return', ['good', 'damaged', 'lost'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('due_date');
            $table->index(['member_id', 'status']);
            $table->index('transaction_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
