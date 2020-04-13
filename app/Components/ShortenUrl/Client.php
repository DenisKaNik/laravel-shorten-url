<?php

namespace App\Components\ShortenUrl;

use Illuminate\Support\Str;

class Client
{
    /* @var DbStorage $driver */
    protected $driver;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $classname = '\\App\\Components\\ShortenUrl\\' . ucfirst(strtolower(env('SHORTEN_URL_STORAGE_DRIVER', 'file'))) . 'Storage';
        if (class_exists($classname)) {
            $this->driver = app($classname);
        } else {
            abort(500, 'Driver not found.');
        }
    }

    /**
     * @param $long_url
     * @return mixed
     */
    public function create($long_url)
    {
        return $this->driver->create($long_url);
    }

    /**
     * @return array
     */
    public function lastUrls(): array
    {
        return array_map(function ($link) {
            return [
                'long_url' => Str::limit($link['long_url'], 50),
                'short_url' => url($link['short_url']),
            ];
        }, $this->driver->lastUrls());
    }

    /**
     * @param $short_url
     * @return string
     */
    public function getLongUrl($short_url): string
    {
        return $this->driver->getLongUrl($short_url);
    }
}
