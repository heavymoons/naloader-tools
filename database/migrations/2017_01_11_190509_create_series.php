<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->increments('id')->comment('シリーズID');
            $table->string('url')->comment('シリーズURL');
            $table->unique('url');
            $table->string('title')->nullable()->comment('シリーズ名');
            $table->unsignedInteger('author_id')->nullable()->nullable()->comment('作者ID');
            $table->unsignedInteger('novel_id')->nullable()->comment('小説ID');
            $table->unique(['author_id', 'novel_id']);
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
        Schema::dropIfExists('series');
    }
}
