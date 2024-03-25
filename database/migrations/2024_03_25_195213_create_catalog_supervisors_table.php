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
        Schema::create('catalog_supervisors', function (Blueprint $table) {
            $table->foreignId('catalog_id')->constrained('catalogs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers', 'user_id')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['catalog_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_supervisors');
    }
};
