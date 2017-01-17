<?php
namespace App\Services;

use App\Exceptions\InvalidUrlException;
use App\Models\Author;
use Naloader\Naloader;

/**
 * Class AuthorService
 * @package App\Services
 */
class AuthorService
{
    /**
     * @param $url
     * @return bool
     */
    public static function isRegisteredAuthorUrl($url) {
        return Author::whereUrl($url)->first() !== null;
    }

    /**
     * @param $url
     * @param null $name
     * @return Author
     * @throws InvalidUrlException
     * @throws \Exception
     */
    public static function registerAuthorUrl($url, $name = null) {
        if (!Naloader::isValidAuthorUrl($url)) {
            throw new InvalidUrlException("invalid author url: $url");
        }
        if (static::isRegisteredAuthorUrl($url)) {
            throw new \Exception("already registered author url: $url");
        }
        $row = new Author();
        $row->url = $url;
        $row->name = $name;
        $row->save();
        return $row;
    }

    /**
     * @param $url
     * @return Author
     */
    public static function findByAuthorUrl($url) {
        return Author::whereUrl($url)->firstOrFail();
    }
}