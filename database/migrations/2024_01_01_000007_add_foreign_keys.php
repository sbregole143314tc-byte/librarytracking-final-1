<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // users.member_id -> members
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('member_id')->references('id')->on('members')->nullOnDelete();
        });

        // members.user_id -> users
        Schema::table('members', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        // borrowings FKs
        Schema::table('borrowings', function (Blueprint $table) {
            $table->foreign('book_id')->references('id')->on('books')->onDelete('restrict');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('restrict');
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('returned_to')->references('id')->on('users')->onDelete('set null');
        });

        // reservations FKs
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });

        // renewals FKs
        Schema::table('renewals', function (Blueprint $table) {
            $table->foreign('borrowing_id')->references('id')->on('borrowings')->onDelete('cascade');
            $table->foreign('renewed_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('renewals', function (Blueprint $table) {
            $table->dropForeign(['borrowing_id']);
            $table->dropForeign(['renewed_by']);
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropForeign(['member_id']);
        });
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropForeign(['member_id']);
            $table->dropForeign(['issued_by']);
            $table->dropForeign(['returned_to']);
        });
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
        });
    }
};
