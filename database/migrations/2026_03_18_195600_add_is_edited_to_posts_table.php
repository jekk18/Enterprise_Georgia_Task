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
        Schema::table('posts', function (Blueprint $table) {
            // ვამატებთ ბულეან ტიპის სვეტს, რომელიც ნაგულისხმევად იქნება false
            $table->boolean('is_edited')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
  public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // თუ მიგრაციას უკან დავაბრუნებთ, წავშალოთ ეს სვეტი
            $table->dropColumn('is_edited');
        });
    }
};
