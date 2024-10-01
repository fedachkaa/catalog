<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalog_topics', function (Blueprint $table) {
            $table->dropColumn('topic');
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog_topics', function (Blueprint $table) {
            $table->string('topic', 256)->nullable();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers', 'user_id')->onUpdate('cascade')->onDelete('cascade'); // Restore 'teacher_id' column
        });
    }
};
