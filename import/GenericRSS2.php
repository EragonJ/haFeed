<?php

require_once 'Importer.php';

class GenericRSS2 extends Importer
{
    protected $feed_url = '';
    protected $import_name = 'GenericRSS2';
    protected $cacheFilepath = '/tmp/haFeed_Import_GenericRSS2.cache.php';

    public function reterieveFromSource()
    {
        $data_obj = new DataObject;

        $rawFeed = file_get_contents($this->feed_url);
        $xml = new SimpleXmlElement($rawFeed);

        foreach($xml->channel->item as $item)
        {
            $data_obj->addItem(
                (string) $item->title,
                (int) strtotime( (string) $item->pubDate),
                (string) trim($item->description),
                (string) $item->link);
        }

        return $data_obj;
    }

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

    protected function setFeedURL($feed_url = null)
    {
        if(is_null($feed_url))
        {
            throw new Exception('haFeed::Import->Importer: Feed URL not given.');
        }

        $this->feed_url = $feed_url;

        return true;
    }

}
