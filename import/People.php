<?php

require 'phpQuery.php';

require_once 'Importer.php';

class People extends Importer
{
    protected $feed_url = 'http://ins.finance.people.com.cn/GB/141391/index.html';
    protected $import_name = 'People';
    protected $max_count = 5;
    protected $cacheFilepath = '/tmp/haFeed_Import_People.cache.php';

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
        $page = file_get_contents($this->feed_url);

        phpQuery::newDocument($page);

        $i = 0;
        foreach(pq(".d2j_05 a") as $item)
        {
            $title = pq($item)->text();
            $link = pq($item)->attr('href');

            if(strpos($link,'http://') === FALSE)
            {
                $link = 'http://ins.finance.people.com.cn' . $link;
            }

            $html = file_get_contents($link);

            # 取得 link
            phpQuery::newDocument($html);

            $time = pq("#p_publishtime")->text();
            $time = str_replace('年','-',$time);
            $time = str_replace('月','-',$time);
            $time = str_replace('日',' ',$time);

            $text = trim($this->strip_html_tags(iconv("euc-cn","utf8",pq("#p_content")->html())));

            $data_obj->addItem($title, strtotime($time), $text, $link);

            $i++;

            if($i > 10)
            {
                break;
            }
        }

        return $data_obj;
    }

    protected function strip_html_tags( $text )
    {
        $text = preg_replace(
            array(
              // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
              // Add line breaks before and after blocks
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
            ),
            $text );
        return $text;
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
