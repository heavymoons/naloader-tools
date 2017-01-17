<?php

namespace App\Console\Commands;

use App\Services\AuthorService;
use Illuminate\Console\Command;

/**
 * Class RegisterAuthorUrl
 * @package App\Console\Commands
 */
class RegisterAuthorUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register_author_url {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'register author URL';

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

        if (AuthorService::isRegisteredAuthorUrl($url)) {
            $this->info("already registered url: $url");
            $author = AuthorService::findByAuthorUrl($url);
        } else {
            $author = AuthorService::registerAuthorUrl($url);
        }

        AuthorService::crawl($author);
    }
}
