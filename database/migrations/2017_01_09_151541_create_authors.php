<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->increments('id')->comment('作家ID');
            $table->string('url')->comment('作家トップURL');
            $table->unique('url');
            $table->string('name')->nullable()->comment('作家名');
            $table->datetime('crawled_at')->nullable()->comment('データ取得日時');
            $table->index('crawled_at');
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
        Schema::dropIfExists('authors');
    }
}
