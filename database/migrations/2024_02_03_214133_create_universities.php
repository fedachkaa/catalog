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
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('name', 150);
            $table->string('city', 70);
            $table->string('address', 128);
            $table->string('phone_number', 13);
            $table->string('email')->unique();
            $table->enum('accreditation_level', ['I', 'II', 'III', 'IV'])->default('I');
            $table->timestamp('founded_at');
            $table->string('website', 128)->nullable();
            $table->string('activated_at')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('universities');
    }
};
