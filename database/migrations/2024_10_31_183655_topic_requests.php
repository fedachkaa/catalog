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
        Schema::table('topic_requests', function (Blueprint $table) {
            $table->foreignId('catalog_id');

            $table->foreign('catalog_id')
                ->references('id')->on('catalogs')
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
        Schema::table('topic_requests', function (Blueprint $table) {
            $table->dropForeign(['catalog_id']);
            $table->dropColumn('catalog_id');
        });
    }
};
