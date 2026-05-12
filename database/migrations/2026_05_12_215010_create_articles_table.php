<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
             $table->integer('category_id');
            $table->foreignId('author_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->string('rand_id');
            $table->text('body');
            $table->string('locale', 5)->default('en');
            $table->bigInteger('view_count')->nullable();
            $table->json('article_tags')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('image')->nullable();
            $table->boolean('is_highlight')->default(false);
            $table->datetime('published_at');
            $table->boolean('published')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
