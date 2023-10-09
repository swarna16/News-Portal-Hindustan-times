<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library feed reader
 *
 * Copyright (c) 2016 YUZURU SUZUKI
 *
 * MIT License
 */

require APPPATH . "third_party/rss-parser/Feed.php";
require APPPATH . "third_party/rss-parser/embed/autoloader.php";

// Load all required Feed classes
use YuzuruS\Rss\Feed;

class Rss_parser
{
    /**
     * @return  feed
     **/
    public function get_feeds($url)
    {
        try {
            $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36';
            return Feed::load($url, $ua, true);
        } catch (Exception $e) {
            return false;
        }
    }

    //get post image
    public function get_image($item)
    {
        //return og image
        $image_url = $this->get_image_from_og($item['link']);
        if (!empty($image_url) && (strpos($image_url, 'http') !== false)) {
            return $image_url;
        } else {
            //return enclosure image
            if (!empty($item['image']) && (strpos($item['image'], 'http') !== false)) {
                return $item['image'];
            } else {
                //return text image
                $images = $this->get_image_from_text($item['description']);
                if (!empty($images)) {
                    return @$images[0];
                } else {
                    //return embed og image
                    return $this->get_image_from_embed_og($item['link']);
                }
            }
        }
    }

    //get post image from og tag
    public function get_image_from_og($url)
    {
        $meta_og_img = null;
        $response = Feed::httpRequest($url, NULL, NULL, NULL);
        if (!empty($response)) {
            $html = new DOMDocument();
            @$html->loadHTML($response);
            foreach ($html->getElementsByTagName('meta') as $meta) {
                if ($meta->getAttribute('property') == 'og:image') {
                    $meta_og_img = $meta->getAttribute('content');
                }
            }
        }
        return $meta_og_img;
    }

    //get post image from og tag embed
    public function get_image_from_embed_og($url)
    {
        try {
            return Feed::getImgFromOg($url);
        } catch (Exception $e) {
            return false;
        }
    }

    //get post image from description
    public function get_image_from_text($text)
    {
        try {
            return Feed::getImgFromText($text);
        } catch (Exception $e) {
            return false;
        }
    }

}
