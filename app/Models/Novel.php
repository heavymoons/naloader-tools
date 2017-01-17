<?php
namespace App\Models;

/**
 * Class Novel
 *
 * @package App\Models
 * @property int $id
 * @property string $url
 * @property string $title
 * @property int $author_id
 * @property \Carbon\Carbon $crawled_at
 * @property bool $is_completed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Author $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Chapter[] $chapters
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Novel whereAuthorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Novel whereCrawledAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Novel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Novel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Novel whereIsCompleted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Novel whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Novel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Novel whereUrl($value)
 * @mixin \Eloquent
 * @property string $text_download_url
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Novel whereTextDownloadUrl($value)
 */
class Novel extends \Eloquent
{
    /**
     * @var array
     */
    protected $dates = ['crawled_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author() {
        return $this->belongsTo('App\Models\Author');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chapters() {
        return $this->hasMany('App\Models\Chapter');
    }

    /**
     * @param \Naloader\Novel $novel
     * @param Author $author
     */
    public function updateFromNaloaderNovel(\Naloader\Novel $novel, Author $author) {
        $this->url = $novel->url;
        $this->title = $novel->title;
        $this->author_id = $author->id;
    }

    /**
     * @return \Carbon\Carbon|string
     */
    public function getLastCrawledAt() {
        $lastCrawledAt = null;
        foreach ($this->chapters as $chapter) {
            if ($lastCrawledAt == null || $chapter->crawled_at > $lastCrawledAt) {
                $lastCrawledAt = $chapter->crawled_at;
            }
        }
        return $lastCrawledAt;
    }
}