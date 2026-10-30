<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('genre', 50);
            $table->smallInteger('release_year');
            $table->string('nudity_level')->default('none');
            $table->string('violence_level')->default('none');
            $table->string('language_level')->default('none');
            $table->boolean('has_clean_edit')->default(false);
            $table->text('clean_edit_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
