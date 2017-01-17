<?php
namespace App\Models;

/**
 * Class Author
 *
 * @package App\Models
 * @property int $id
 * @property string $url
 * @property string $name
 * @property string $crawled_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Novel[] $novels
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereCrawledAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereUrl($value)
 * @mixin \Eloquent
 */
class Author extends \Eloquent
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function novels() {
        return $this->hasMany('App\Models\Novel');
    }

}