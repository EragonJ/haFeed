<?php

require 'phpQuery.php';

require_once 'Importer.php';

class cnWSJ extends Importer
{
    protected $feed_url = '';
    protected $import_name = 'cnWSJ';
    protected $max_count = 5;
    protected $cacheFilepath = '/tmp/haFeed_Import_cnWSJ.cache.php';

    public function assignParameter($parameter)
    {
        if(isset($parameter['url']))
        {
            $this->setFeedURL($parameter['url']);
        }

        if(isset($parameter['disable_cache']))
        {
            $this->disable_cache = $parameter['disable_cache'];
        }

        if(isset($parameter['TTL']))
        {
            $this->TTL = $parameter['TTL'];
        }
    }

    public function reterieveFromSource()
    {
        $data_obj = new DataObject;
        
        // retreieve rss
        $rss = file_get_contents($this->feed_url);

        phpQuery::newDocumentXML($rss , 'big5');

        $i = 0;
        foreach(pq("item") as $item)
        {
            $title = pq($item)->children("title")->text();
            $pubDate = pq($item)->children("pubDate")->text();
            $link = pq($item)->children("link")->text();

            $html = file_get_contents($link);
            phpQuery::newDocumentHTML($html, 'big5');

            $desc = pq("#A")->text();
            $news_text = mb_strstr($desc, '（本文版權', true);

            if ($news_text === FALSE)
            {
                $news_text = mb_strstr($desc, '本文由道', true);
            }
            
            if ($news_text === FALSE)
            {
                $news_text = mb_strstr($desc, "<div id='bottomlink'>", true);
            }

            $data_obj->addItem($title, strtotime($pubDate), $news_text, $link);

            $i++;

            if($i > 10)
            {
                break;
            }
        }

        return $data_obj;
    }

    protected function setFeedURL($feed_url = null, $max = 5)
    {
        if(is_null($feed_url))
        {
            throw new Exception('haFeed::Import->Importer: Feed URL not given.');
        }

        $this->max_count = $max;
        $this->feed_url = $feed_url;

        return true;
    }
}
