<?php

require_once 'Exporter.php';

class RSS_2_0_Exporter extends Exporter
{
    public function export(DataObject $data)
    {
        header("Content-Type: text/xml; charset=UTF-8");
    
        echo $this->render($data);

        exit;
    }

    public function render(DataObject $data)
    {
        $rss_source = '<?xml version="1.0" encoding="utf-8" ?><rss version="2.0"><channel><title>haFeed</title><link>http://hafeed.hax4.in</link><description>this feed were created by hafeed</description><language>zh</language><pubDate>'.date(DATE_RFC822).'</pubDate><lastBuildDate>'.date(DATE_RFC822).'</lastBuildDate>' . "\n";

        foreach($data->dump() as $data_item)
        {
            $rss_source .= '<item><title>'.$data_item['title'].'</title><link>'.$data_item['link'].'</link><description>'.$data_item['desc'].'</description><pubDate>'.date(DATE_RFC822,$data_item['pubDate']).'</pubDate><guid>'.$data_item['link'].'</guid></item>'. "\n";
        }

        $rss_source .= '</channel></rss>';

        return $rss_source;
    }
}
