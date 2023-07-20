<?php

namespace App\WebPages\Rumble;

use \DOMXpath;
use \DOMDocument;
use Illuminate\Support\Facades\Cache;
use App\Helpers\ConversionHelper as Convert;

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

    public function title()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//h1[@class="h1"]');

        return ($elements->length > 0) ? $elements->item(0)->textContent : null;
    }

    public function description()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        // Case 1: short description
        $elements = $xpath->query('//p[@class="media-description"]');

        if ($elements->length > 0) return $elements->item(0)->textContent;

        // Case 2: long description
        $elements = $xpath->query('//p[@class="media-description media-description--first"]');

        if ($elements->length > 0) {
            $description = $elements->item(0)->textContent;
            
            // Remove "Show more" suffix (the last 9 characters)
            $description = substr($description, 0, -9);

            // Add a white space at the end of each string
            $description .= ' ';

            $elements = $xpath->query('//p[@class="media-description media-description--more"]');

            foreach ($elements as $element) {
                $description .= $element->textContent . ' ';

                // Add a white space before "https://" if there is no white space before it already
                $description = preg_replace('/(?<!\s)https:\/\//', ' https://', $description);
            }

            return $description;
        }

        // Case 3: no description
        return null;
    }

    public function likesCount()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//span[@class="rumbles-up-votes"]');

        return ($elements->length > 0) ? $elements->item(0)->textContent : null;
    }

    public function dislikesCount()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//span[@class="rumbles-down-votes"]');

        return ($elements->length > 0) ? $elements->item(0)->textContent : null;
    }

    public function commentsCount()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//div[@class="video-counters--item video-item--comments"]');

        return ($elements->length > 0) ? trim($elements->item(0)->textContent) : null;
    }

    public function viewsCount()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//div[@class="video-counters--item video-item--views"]');

        return ($elements->length > 0) ? trim($elements->item(0)->textContent) : null;
    }

    public function uploadedAt()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        // Case 1: Normal video
        $elements = $xpath->query('//div[@class="media-published"]');

        if ($elements->length > 0) return $elements->item(0)->getAttribute('title');

        // Case 2: Livestream which has ended
        $elements = $xpath->query('//div[@class="streamed-on"]/time');

        if ($elements->length > 0) {
            $date = $elements->item(0)->getAttribute('datetime');

            return Convert::ISO8601ToDateString($date);
        }

        // Case 3: Livestream which has NOT ended, or other
        return null;
    }
}