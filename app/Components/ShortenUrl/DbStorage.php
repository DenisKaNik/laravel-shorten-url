<?php

namespace App\Components\ShortenUrl;

use App\Link;
use Illuminate\Http\JsonResponse;

class DbStorage
{
    use ShortenUrlTrait;

    public function __construct() {}

    public function create($long_url)
    {
        $hash = md5($long_url . env('APP_KEY'));

        if ($link = Link::whereHash($hash)->first()) {
            return response()->json([
                'success' => true,
                'short_url' => url($link->short_url),
            ], 200);
        } else {
            $link = new Link();
            $link->hash = $hash;
            $link->long_url = $long_url;
            $link->short_url = ($short_url = $this->generateShortUrl());

            if ($link->save()) {
                return response()->json([
                    'long_url' => $long_url,
                    'short_url' => url($short_url),
                ], 200);
            }
        }

        return new JsonResponse([
            'errors' => [
                'textUrl' => ['Resource no saved.'],
            ]
        ], 422);
    }

    /**
     * @return array
     */
    public function lastUrls(): array
    {
        return Link::select('long_url', 'short_url')
            ->orderBy('id', 'DESC')
            ->take(5)
            ->get()
            ->toArray();
    }

    /**
     * @param $short_url
     * @return string|null
     */
    public function getLongUrl($short_url)
    {
        $link = Link::whereShortUrl($short_url)->firstOrFail();
        return $link->long_url ?? null;
    }

    public function getShortenUrls()
    {
        return Link::all()->pluck('id', 'short_url')->toArray();
    }
}
