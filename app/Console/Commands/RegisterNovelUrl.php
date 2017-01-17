<?php

namespace App\Console\Commands;

use App\Services\NovelService;
use Illuminate\Console\Command;

class RegisterNovelUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register_novel_url {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register Novel URL';

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
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $url = $this->argument('url');
        if (NovelService::isRegisteredNovelUrl($url)) {
            $this->info("already registered url: $url");
            return;
        }
        NovelService::registerNovelUrl($url);

    }
}
