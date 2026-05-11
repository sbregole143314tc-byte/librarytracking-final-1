<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('members');

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('member_id', 20)->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('membership_type', ['student', 'faculty', 'staff', 'public'])->default('public');
            $table->date('membership_start');
            $table->date('membership_expiry');
            $table->enum('status', ['active', 'suspended', 'expired', 'blacklisted'])->default('active');
            $table->decimal('outstanding_fines', 8, 2)->default(0);
            $table->integer('max_books_allowed')->default(4);
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('membership_type');
            $table->index('membership_expiry');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
