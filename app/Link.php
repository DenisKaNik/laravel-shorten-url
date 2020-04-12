<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Link
 *
 * @property integer $id
 * @property string $hash
 * @property string $long_url
 * @property string $short_url
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereShortUrl($value)
 * @package App
 */

class Link extends Model
{
    public $timestamps = false;

    public function rules()
    {
        return [
            'hash' => 'required|string|max:32',
            'long_url' => 'required|url|max:255',
            'short_url' => 'required|string|max:6',
        ];
    }
}
