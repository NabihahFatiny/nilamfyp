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
        Schema::table('summaries', function (Blueprint $table) {
            $table->foreignId('id_book')->nullable()->after('id_student')->constrained('books', 'id_book')->onDelete('cascade');
            $table->text('comment')->nullable()->after('summary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->dropForeign(['id_book']);
            $table->dropColumn(['id_book', 'comment']);
        });
    }
};
