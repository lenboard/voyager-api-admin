<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHtmlBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('html_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->notNull();
            $table->string('uri', 255)->notNull()->unique();
            $table->text('content');
            $table->smallInteger('active')->default(0);
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('html_blocks');
    }
}
