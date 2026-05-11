<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('fine_settings');
        Schema::create('fine_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('daily_fine_rate', 8, 2)->default(5.00);
            $table->decimal('lost_book_multiplier', 5, 2)->default(10.00);
            $table->integer('grace_period_days')->default(0);
            $table->integer('max_borrow_days')->default(14);
            $table->integer('renewal_limit')->default(2);
            $table->timestamps();
        });

        // Insert default settings
        DB::table('fine_settings')->insert([
            'daily_fine_rate'       => 5.00,
            'lost_book_multiplier'  => 10.00,
            'grace_period_days'     => 0,
            'max_borrow_days'       => 14,
            'renewal_limit'         => 2,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('fine_settings');
    }
};
