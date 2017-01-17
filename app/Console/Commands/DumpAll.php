<?php

namespace App\Console\Commands;

use App\Models\Novel;
use App\Services\NovelService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class DumpAll
 * @package App\Console\Commands
 */
class DumpAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dump_all {target_dir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'dump text to target directory';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $targetDir = $this->argument('target_dir');
        if (!file_exists($targetDir) || !is_dir($targetDir)) {
            $this->error("target dir not exists: $targetDir");
            return;
        }

        $novels = Novel::all();
        $this->info(count($novels) . " novels.");
        foreach ($novels as $novel) {
            if (!NovelService::canSaveToText($novel)) {
                $this->info("novel cannot dump: {$novel->url}");
                continue;
            }

            $title = "{$novel->author->name}『{$novel->title}』";
            $title = static::convertCharacterNoUseForFilename($title);
            $filename = "$targetDir/$title.txt";

            if (file_exists($filename)) {
                $modifiedAt = Carbon::createFromTimestampUTC(filemtime($filename));
                if ($modifiedAt > $novel->getLastCrawledAt()) {
                    $this->info("$filename is not modified.");
                    continue;
                }
            }

            $this->info("$filename saving.");
            NovelService::saveTextTo($novel, $filename);
            $this->info("$filename saved.");
        }
    }

    /**
     * ファイル名で使用できない文字を全角に変換
     * @param $str
     * @return mixed
     */
    public static function convertCharacterNoUseForFilename($str) {
        $targets = ["\\", "/", ":", "*", "?", '"', ">", "<", "|"];
        $replaces = ["＼", "／", "：", "＊", "？", "”", "＞", "＜", "｜"];
        return str_replace($targets, $replaces, $str);
    }
}
