<?php

namespace App\Console\Commands;

use App\Services\NovelService;
use Illuminate\Console\Command;
use Naloader\Naloader;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class RetrieveNovelUrl
 * @package App\Console\Commands
 */
class RetrieveNovelUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve_novel_url {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'retrieve novel url from web';

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
        $url = $this->argument('url');
        $this->info("retrieve from $url");

        $client = Naloader::getHttpClient($url);
        $crawler = $client->request('GET', $url);

        $crawler->filter('a')->each(function(Crawler $linkNode) {
            $novelUrl = $linkNode->attr('href');
            if (Naloader::isValidNovelUrl($novelUrl)) {
                if (!NovelService::isRegisteredNovelUrl($novelUrl)) {
                    NovelService::registerNovelUrl($novelUrl);
                    $this->info("add novel url: $novelUrl");
                }
            }
        });
    }
}
