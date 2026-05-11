<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('books');

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('isbn', 20)->unique();
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->smallInteger('published_year')->unsigned()->nullable(); // SMALLINT supports 0–65535
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->decimal('price', 8, 2)->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['active', 'damaged', 'lost', 'archived'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['title', 'author']);
            $table->index('category');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
