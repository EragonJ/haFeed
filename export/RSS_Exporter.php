<?php

require_once 'Exporter.php';

class RSS_Exporter extends Exporter
{
    public function export(DataObject $data)
    {
        header("Content-Type: application/rdf+xml; charset=UTF-8");
    
        echo $this->render($data);

        exit;
    }

    public function render(DataObject $data)
    {
         $rss_source .= '<channel rdf:about="http://hafeed.hax4.in/"><title>haFeed Generated</title><items><rdf:Seq>';

        foreach($data->dump() as $data)
        {
            $rss_source .= '<rdf:li resource="'.$data['link'].'" />';
        }

        $rss_source .= '</rdf:Seq></items><link>http://hafeed.hax4.in/</link><description>This feed were created by haFeed.</description></channel>' . "\n"; # RDF RSS Channel Description

        foreach($data->dump() as $data)
        {
            $rss_source .= '<item rdf:about="'.$data['link'].'"><title>'.$data['title'].'</title><link>'.$data['link'].'</link><description><[CDATA['.$data['desc'].']]></description></item>' . "\n"; # RDF RSS Item
        }

        $rss_source .= '</rdf:RDF>'; # RSS RDF END

        return $rss_source;
    }
}
