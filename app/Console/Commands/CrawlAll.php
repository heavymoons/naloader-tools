<?php

namespace App\Console\Commands;

use App\Models\Novel;
use App\Services\ChapterService;
use App\Services\NovelService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class CrawlAll
 * @package App\Console\Commands
 */
class CrawlAll extends Command
{
    const CRAWL_INTERVAL_SECONDS = 4; // 連続アクセスを避けるためのインターバル（秒）
    const CRAWL_INTERVAL_MINUTES = 60 * 24; // 同じ作品の情報を連続して取得しないためのインターバル（分）

    /**
     * @param Novel $novel
     * @return bool
     */
    public static function canCrawl(Novel $novel) {
        return
            empty($novel->crawled_at)
            || Carbon::now()->diff($novel->crawled_at)->i > static::CRAWL_INTERVAL_MINUTES;

    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl_all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl all registered novels.';

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
        \DB::disableQueryLog();

        $novels = Novel::all();
        $this->info(count($novels) . " novels remaining.");
        foreach ($novels as $novel) {
            $this->info("target novel: {$novel->url}");
            if ($novel->is_completed) {
                $this->info("the novel is completed.");
                continue;
            }
            if (!static::canCrawl($novel)) {
                $this->info("the novel is just crawled.");
                continue;
            }
            $this->info("start crawling.");
            NovelService::crawl($novel);
            $this->info("finish crawling.");
            $this->info("wait seconds...");
            sleep(static::CRAWL_INTERVAL_SECONDS);
        }
        $this->info(count($novels) . " novels crawled.");

        $chapters = ChapterService::getListToCrawl();
        $this->info(count($chapters) . " chapters remaining.");
        foreach ($chapters as $chapter) {
            $this->info("chapter {$chapter->number} downloading.");
            ChapterService::download($chapter);
            $this->info("chapter {$chapter->number} downloaded.");
            $this->info("wait seconds...");
            sleep(static::CRAWL_INTERVAL_SECONDS);
        }
        $this->info(count($chapters) . " chapters downloaded.");
    }
}
