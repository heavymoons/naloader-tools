<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNovels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('novels', function (Blueprint $table) {
            $table->increments('id')->comment('小説ID');
            $table->string('url')->comment('小説トップページURL');
            $table->unique('url');
            $table->string('title')->nullable()->comment('小説タイトル');
            $table->string('text_download_url')->nullable()->comment('テキストダウンロードURL');
            $table->unsignedInteger('author_id')->nullable()->comment('作家ID');
            $table->index('author_id');
            $table->datetime('crawled_at')->nullable()->comment('情報を取得日時');
            $table->boolean('is_completed')->comment('完結しているか');
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
        Schema::dropIfExists('novels');
    }
}
