<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGifTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'gif_tag',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('gif_id');
                $table->unsignedBigInteger('tag_id');

                $table->foreign('gif_id')->references('id')->on('gifs');
                $table->foreign('tag_id')->references('id')->on('tags');
                $table->unique(['gif_id', 'tag_id']);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gif_tag');
    }
}
