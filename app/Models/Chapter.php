<?php
namespace App\Models;

/**
 * Class Chapter
 *
 * @package App\Models
 * @property int $id
 * @property int $novel_id
 * @property int $number
 * @property string $title
 * @property string $content
 * @property string $created_on
 * @property string $updated_on
 * @property string $crawled_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Novel $novel
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereChapterNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereCrawledAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereCreatedOn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereNovelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereUpdatedOn($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Chapter whereNumber($value)
 */
class Chapter extends \Eloquent
{
    /**
     * @var array
     */
    protected $dates = ['created_on', 'updated_on', 'crawled_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function novel() {
        return $this->belongsTo('App\Models\Novel');
    }
}