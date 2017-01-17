<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChapters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->increments('id')->comment('パートID');
            $table->unsignedInteger('novel_id')->comment('小説ID');
            $table->unsignedInteger('number')->comment('パート番号');
            $table->unique(['novel_id', 'number']);
            $table->string('title')->nullable()->comment('パートタイトル');
            $table->string('content')->nullable()->comment('コンテンツ');
            $table->date('created_on')->nullable()->comment('公開日');
            $table->date('updated_on')->nullable()->comment('更新日');
            $table->index('updated_on');
            $table->datetime('crawled_at')->nullable()->comment('データ取得日');
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
        Schema::dropIfExists('chapters');
    }
}
