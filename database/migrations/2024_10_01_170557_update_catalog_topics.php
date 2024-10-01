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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers', 'user_id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('topic', 256);
            $table->unsignedSmallInteger('is_ai_generated')->default(0);
            $table->timestamps();
        });

        Schema::table('catalog_topics', function (Blueprint $table) {
            $table->foreignId('topic_id')
                ->nullable()
                ->constrained('topics')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
            $table->dropForeign(['topic_id']);
            $table->dropColumn('topic_id');
        });

        Schema::dropIfExists('topics');
    }
};
