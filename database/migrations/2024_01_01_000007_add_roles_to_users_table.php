<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add role + member_id to users (only if columns don't already exist)
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'user'])->default('user')->after('email');
            }
            if (! Schema::hasColumn('users', 'member_id')) {
                $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete()->after('role');
            }
        });

        // Add user_id FK to members (only if not already there)
        Schema::table('members', function (Blueprint $table) {
            if (! Schema::hasColumn('members', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'member_id')) {
                $table->dropForeign(['member_id']);
                $table->dropColumn(['role', 'member_id']);
            }
        });
    }
};
