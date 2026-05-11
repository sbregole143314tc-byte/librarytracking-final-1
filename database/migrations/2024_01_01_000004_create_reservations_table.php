<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('reservations');

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('member_id');
            $table->date('reservation_date');
            $table->date('expiry_date');
            $table->enum('status', ['pending', 'ready', 'fulfilled', 'cancelled', 'expired'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['book_id', 'status']);
            $table->index(['member_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
