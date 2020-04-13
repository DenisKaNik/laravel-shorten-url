<?php

namespace App\Components\ShortenUrl;

trait ShortenUrlTrait
{
    function generateShortUrl($length = 6)
    {
        $chars = [
            range('a', 'z'),
            range('A', 'Z'),
            range(0, 9),
        ];

        for ($i = 0; $i < $length; $i++) {
            $symbols = array_diff(
                $chars[($i % sizeof($chars))],
                $symbols ?? []
            );
            $items[] = $symbols[array_rand($symbols)];
        }

        shuffle($items);

        return $this->_checkUniqueShortUrl(
            implode('', $items)
        );
    }

    private function _checkUniqueShortUrl($short_url, $shorten_urls = null, $repeat = 0)
    {
        if ($repeat >= 6) {
            return $this->generateShortUrl();
        }

        if (!$shorten_urls) {
            $shorten_urls = $this->getShortenUrls();
        }

        if (in_array($short_url, array_keys($shorten_urls))) {
            $repeat++;

            return $this->_checkUniqueShortUrl(
                str_shuffle($short_url),
                $shorten_urls,
                $repeat
            );
        } else {
            return $short_url;
        }
    }
}
