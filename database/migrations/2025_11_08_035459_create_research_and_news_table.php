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
        Schema::create('research_and_news', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['medical_research', 'scientific_news', 'medical_news'])->default('medical_research');
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('content_ar');
            $table->text('content_en')->nullable();
            $table->string('image')->nullable();
            $table->string('source')->nullable();
            $table->string('author')->nullable();
            $table->date('published_date')->nullable();
            $table->text('summary_ar')->nullable();
            $table->text('summary_en')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_and_news');
    }
};
