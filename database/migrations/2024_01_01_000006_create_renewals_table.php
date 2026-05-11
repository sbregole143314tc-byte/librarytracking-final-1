<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('renewals');

        Schema::create('renewals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrowing_id');
            $table->unsignedBigInteger('renewed_by');
            $table->date('previous_due_date');
            $table->date('new_due_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('renewals');
    }
};
