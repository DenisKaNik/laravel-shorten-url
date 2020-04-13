<?php

namespace App\Http\Controllers\Api;

use App\Components\ShortenUrl\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\{
    Request,
    JsonResponse
};
use Illuminate\Support\Facades\Http;

class ShortenUrlController extends Controller
{
    protected $client;

    function __construct(Client $client)
    {
        $this->client = $client;
    }

    function create(Request $request)
    {
        $this->validate($request, [
            'textUrl' => 'required|url|max:255',
        ]);

        $long_url = $request->get('textUrl');
        if (!$this->_linkCheck($long_url)) {
            return new JsonResponse([
                'errors' => [
                    'textUrl' => ['Resource not found.'],
                ]
            ], 422);
        }

        return $this->client->create($long_url);
    }

    /**
     * @return array
     */
    public function last()
    {
        return $this->client->lastUrls();
    }

    public function redirect($short_url)
    {
        $long_url = $this->client->getLongUrl($short_url);

        if (!$long_url || !$this->_linkCheck($long_url)) {
            return abort(404);
        }

        return redirect($long_url, 301);
    }

    /**
     * @param $long_url
     * @return bool
     */
    private function _linkCheck($long_url): bool
    {
        $response = Http::get($long_url);

        if ($response->status() !== 200) {
            return false;
        }

        $contentType = strtolower($response->header('Content-Type'));

        if (strpos($contentType, 'javascript') || strpos($contentType, 'css')) {
            return false;
        }

        return true;
    }
}
