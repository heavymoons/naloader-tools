<?php
namespace App\Services;

use App\Exceptions\InvalidUrlException;
use App\Models\Author;
use App\Models\Chapter;
use App\Models\Novel;
use Carbon\Carbon;
use Naloader\Naloader;

/**
 * Class NovelService
 * @package App\Services
 */
class NovelService
{

    /**
     * @param $url
     * @return bool
     */
    public static function isRegisteredNovelUrl($url) {
        return Novel::whereUrl($url)->first() !== null;
    }

    /**
     * @param $url
     * @return Novel
     * @throws \Exception
     */
    public static function registerNovelUrl($url) {
        if (!Naloader::isValidNovelUrl($url)) {
            throw new InvalidUrlException("invalid novel url: $url");
        }
        if (static::isRegisteredNovelUrl($url)) {
            throw new \Exception("already registered novel url: $url");
        }
        $row = new Novel();
        $row->url = $url;
        $row->is_completed = false;
        $row->save();
        return $row;
    }

    /**
     * @param $url
     * @return Novel
     */
    public static function findByNovelUrl($url) {
        return Novel::whereUrl($url)->firstOrFail();
    }

    /**
     * @param Novel $novel
     */
    public static function crawl(Novel $novel) {
        $novelCrawler = new \Naloader\Novel($novel->url);
        $novelCrawler->crawl();

        $authorCrawler = $novelCrawler->author;
        if (!AuthorService::isRegisteredAuthorUrl($authorCrawler->url)) {
            $author = AuthorService::registerAuthorUrl($authorCrawler->url, $authorCrawler->name);
        } else {
            $author = AuthorService::findByAuthorUrl($authorCrawler->url);
        }

        $novel->url = $novelCrawler->url;
        $novel->title = $novelCrawler->title;
        $novel->text_download_url = $novelCrawler->getTextDownloadUrl();
        $novel->crawled_at = Carbon::now();
        $novel->author_id = $author->id;
        $novel->save();

        $chapters = [];
        foreach ($novel->chapters as $chapter) {
            $chapters[$chapter->number] = $chapter;
        }
        foreach ($novelCrawler->chapters as $chapterCrawler) {
            $chapterNumber = $chapterCrawler->number;
            if (!isset($chapters[$chapterNumber])) {
                $chapter = new Chapter();
                $chapter->novel_id = $novel->id;
                $chapter->number = $chapterNumber;
            } else {
                $chapter = $chapters[$chapterNumber];
            }
            $chapter->title = $chapterCrawler->title;
            $chapter->created_on = $chapterCrawler->created_on;
            $chapter->updated_on = $chapterCrawler->updated_on;
            $chapter->save();
        }
    }

    /**
     * @param Novel $novel
     * @param $filename
     */
    public static function saveTextTo(Novel $novel, $filename) {
        $fp = fopen($filename, 'w');

        static::writeLine($fp, $novel->title);
        static::writeLine($fp, "作者: " . $novel->author->name);
        static::writeLine($fp, "URL: " . $novel->url);
        static::writeLine($fp, "最終更新: " . $novel->getLastCrawledAt());
        static::writeLine($fp);

        $chapters = $novel->chapters->sortBy('number');
        foreach ($chapters as $chapter) {
            $chapterTitle = "#{$chapter->number}";
            if ($chapter->number != $chapter->title) {
                $chapterTitle .= " {$chapter->title}";
            }
            $chapterTitle .=  ' ' . $chapter->created_on->toDateString() . '投稿';
            if ($chapter->updated_on) {
                $chapterTitle .= '（' . $chapter->updated_on->toDateString() . '更新)';
            }
            static::writeLine($fp, $chapterTitle);
            static::writeLine($fp);
            static::writeLine($fp, $chapter->content);
        }

        static::writeLine($fp);
        static::writeLine($fp, "最終更新: " . $novel->getLastCrawledAt());

        fclose($fp);
    }

    /**
     * @param $fp
     * @param $text
     */
    private static function writeLine($fp, $text = null) {
        fwrite($fp, $text . "\n");
    }
}