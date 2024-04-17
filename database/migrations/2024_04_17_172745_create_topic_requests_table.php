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
        Schema::create('topic_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('catalog_topics')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students', 'user_id')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('status', ['sent', 'approved', 'rejected'])->default('sent');
            $table->unique(['topic_id', 'student_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_requests');
    }
};
