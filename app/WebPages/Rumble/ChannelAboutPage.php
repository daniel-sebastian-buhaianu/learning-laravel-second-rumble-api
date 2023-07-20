<?php

namespace App\WebPages\Rumble;

use \DOMXpath;
use \DOMDocument;
use Illuminate\Support\Facades\Cache;

class ChannelAboutPage
{
    protected $url;
    protected $dom = [];

    public function __construct(string $url)
    {
        $url = $url . '/about';

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

        $url = str_replace('/about', '', $this->url);

        return str_replace('https://rumble.com/c/', '', $url);
    }

    public function name()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//div[@class="channel-header--title"]/h1');

        return ($elements->length > 0) ? $elements->item(0)->textContent : null;
    }

    public function description()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//div[@class="channel-about--description"]/p');

        return ($elements->length > 0) ? $elements->item(0)->textContent : null;
    }

    public function banner()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//img[@class="channel-header--backsplash-img"]');

        return ($elements->length > 0) ? $elements->item(0)->getAttribute('src') : null;
    }

    public function avatar()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//img[@class="channel-header--thumb"]');

        return ($elements->length > 0) ? $elements->item(0)->getAttribute('src') : null;
    }

    public function followersCount()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//span[@class="channel-header--followers"]');

        if ($elements->length > 0) {
            $followersCountStringified = $elements->item(0)->textContent; // "1.5M Followers"
            return str_replace(' Followers', '', $followersCountStringified); // "1.5M"
        }

        return null;
    }

    public function videosCount()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//div[@class="channel-about-sidebar--inner"]/p[2]');

        if ($elements->length > 0) {
            $videosCountStringified = trim($elements->item(0)->textContent); // "306 videos"
            return str_replace(' videos', '', $videosCountStringified); // "306"
        }

        return null;
    }

    public function joiningDate()
    {
        $xpath = $this->dom['xpath'];

        if (empty($xpath)) {
            throw new Exception('xpath is empty');
        }

        $elements = $xpath->query('//div[@class="channel-about-sidebar--inner"]/p[1]');

        if ($elements->length > 0) {
            $dateStringified = trim($elements->item(0)->textContent); // "Joined Aug 19, 2022"
            return str_replace('Joined ', '', $dateStringified); // "Aug 19, 2022"
        }
        
        return null;
    }
}