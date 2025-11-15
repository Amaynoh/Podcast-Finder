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
    Schema::create('episodes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('podcast_id');
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('audio_url');
        $table->integer('duration')->nullable();
        $table->timestamps();
        $table->foreign('podcast_id')->references('id')->on('podcasts')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};

