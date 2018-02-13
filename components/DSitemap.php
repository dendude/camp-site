<?php

namespace app\components;

use Yii;

class DSitemap extends \yii\base\Component
{
    const ALWAYS = 'always';
    const HOURLY = 'hourly';
    const DAILY = 'daily';
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY = 'yearly';
    const NEVER = 'never';
 
    protected $items = array();

    public function addUrl($full_url, $changeFreq = self::DAILY, $priority = 0.5, $lastMod = 0) {

        $item = ['loc' => $full_url,
          'changefreq' => $changeFreq,
            'priority' => $priority];

        if ($lastMod) $item['lastmod'] = $this->dateToW3C($lastMod);
 
        $this->items[] = $item;
    }
 
    /**
     * @return string XML code
     */
    public function render()
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
        foreach($this->items as $item)
        {
            $url = $dom->createElement('url');
 
            foreach ($item as $key=>$value)
            {
                $elem = $dom->createElement($key);
                $elem->appendChild($dom->createTextNode($value));
                $url->appendChild($elem);
            }
 
            $urlset->appendChild($url);
        }
        $dom->appendChild($urlset);
 
        return $dom->saveXML();
    }
 
    protected function dateToW3C($date)
    {
        if (is_numeric($date))
            return date(DATE_W3C, $date);
        else
            return date(DATE_W3C, strtotime($date));
    }
}