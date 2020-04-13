<?php

namespace App\Components\ShortenUrl;

use Illuminate\Support\Facades\Storage;

class FileStorage
{
    use ShortenUrlTrait;

    private static
        $folder = 'db',
        $linksDB = '/links.db',
        $hashUrlsDB = '/hash_urls.db',
        $shortenUrlsDB = '/shorten_urls.db',
        $lastShortenUrlDB = '/last_shorten_url.db';

    public function __construct() {}

    /**
     * @param $long_url
     * @return mixed
     */
    public function create($long_url)
    {
        $hash = md5($long_url . env('APP_KEY'));
        $directory = self::$folder . '/' . ($dir = "{$hash[1]}/{$hash[2]}/{$hash[3]}");

        $hash_urls = Storage::has(self::$folder . self::$hashUrlsDB)
            ? unserialize(Storage::get(self::$folder . self::$hashUrlsDB))
            : [];

        if (in_array($hash, array_keys($hash_urls))) {
            $directory = self::$folder . '/' . $hash_urls[$hash];

            $links = Storage::disk('local')->has($pathLinksDB = ($directory . self::$linksDB))
                ? unserialize(Storage::get($pathLinksDB))
                : null;

            $links = array_column($links, 'short_url', 'hash');

            return response()->json([
                'success' => true,
                'short_url' => url($links[$hash]),
            ], 200);
        }

        Storage::makeDirectory($directory);

        $links = Storage::disk('local')->has($pathLinksDB = ($directory . self::$linksDB))
            ? unserialize(Storage::get($pathLinksDB))
            : [];

        $links[] = [
            'hash' => $hash,
            'long_url' => $long_url,
            'short_url' => ($short_url = $this->generateShortUrl()),
        ];

        Storage::put($pathLinksDB, serialize($links));

        Storage::put(self::$folder . self::$hashUrlsDB, serialize(
            array_merge($hash_urls, [$hash => $dir])
        ));

        $this->_setShortenUrls($short_url, $dir);
        $this->_setLastShortenUrl($long_url, $short_url);

        return response()->json([
            'long_url' => $long_url,
            'short_url' => url($short_url),
        ], 200);
    }

    /**
     * @return array
     */
    public function lastUrls(): array
    {
        return Storage::has(self::$folder . self::$lastShortenUrlDB)
            ? unserialize(Storage::get(self::$folder . self::$lastShortenUrlDB))
            : [];
    }

    /**
     * @param $short_url
     * @return mixed
     */
    public function getLongUrl($short_url)
    {
        $shorten_urls = Storage::has(self::$folder . self::$shortenUrlsDB)
            ? unserialize(Storage::get(self::$folder . self::$shortenUrlsDB))
            : null;

        if ($shorten_urls && in_array($short_url, array_keys($shorten_urls))) {
            $directory = self::$folder . '/' . $shorten_urls[$short_url];

            $links = Storage::has($pathLinksDB = ($directory . self::$linksDB))
                ? unserialize(Storage::get($pathLinksDB))
                : null;

            $links = array_column($links, 'long_url', 'short_url');

            return $links[$short_url];
        } else {
            return abort(404);
        }
    }

    public function getShortenUrls()
    {
        return (Storage::has(self::$folder . self::$shortenUrlsDB))
            ? unserialize(Storage::get(self::$folder . self::$shortenUrlsDB))
            : [];
    }

    private function _setShortenUrls($short_url, $dir)
    {
        $shorten_urls = Storage::has(self::$folder . self::$shortenUrlsDB)
            ? unserialize(Storage::get(self::$folder . self::$shortenUrlsDB))
            : [];

        Storage::put(self::$folder . self::$shortenUrlsDB, serialize(
            array_merge($shorten_urls, [$short_url => $dir])
        ));
    }

    private function _setLastShortenUrl($long_url, $short_url)
    {
        $last_shorten_url = Storage::has(self::$folder . self::$lastShortenUrlDB)
            ? unserialize(Storage::get(self::$folder . self::$lastShortenUrlDB))
            : [];

        array_unshift($last_shorten_url, [
            'long_url' => $long_url,
            'short_url' => $short_url,
        ]);

        if (sizeof($last_shorten_url) > 5) {
            $last_shorten_url = array_slice($last_shorten_url, 0, 5);
        }

        Storage::put(self::$folder . self::$lastShortenUrlDB, serialize($last_shorten_url));
    }
}
