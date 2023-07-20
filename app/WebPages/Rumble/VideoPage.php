<?php

namespace App\WebPages\Rumble;

use \DOMXpath;
use \DOMDocument;
use Illuminate\Support\Facades\Cache;

class VideoPage
{
    protected $url;
    protected $dom = [];

    public function __construct(string $url)
    {
        $doc = new DOMDocument();

        $cacheKey = 'HTML_' . md5($url);

        if (Cache::has($cacheKey)) {
            $html = Cache::get($cacheKey);

            @$doc->loadHTML($html);
        } else {
            @$doc->loadHTMLFile($url);
            $html = $doc->saveHTML();

            Cache::put($cacheKey, $html, now()->addHours(6));
        }

        $this->url = $url;
        $this->dom = [
            'doc' => $doc,
            'xpath' => new DOMXpath($doc)
        ];
    }

    public function id()
    {
        if (empty($this->url)) {
            throw new Exception('url is empty');
        }

        return str_replace('https://rumble.com/', '', $this->url);
    }

    public function channelName()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//div[@class="media-heading-name"]');

        return ($elements->length > 0) ? trim($elements->item(0)->textContent) : null;
    }
}