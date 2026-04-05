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
      Schema::create('post_translations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('post_id')->constrained()->onDelete('cascade');
        $table->string('locale', 2)->index(); // ka, en
        
        // თარგმნადი ველები გადმოვიდა აქ
        $table->string('title');
        $table->text('description');
        $table->string('image')->nullable(); 
        
        $table->timestamps();

        // ერთი პოსტისთვის ერთი ენა მხოლოდ ერთხელ
        $table->unique(['post_id', 'locale']); 
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_translations');
    }
};
