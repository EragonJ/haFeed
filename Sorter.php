<?php

abstract class Sorter
{
    abstract public function compare($a, $b);
    
    protected function exportArray(DataObject $dataset)
    {
        $dataArray = $dataset->dump();

        return $dataArray;
    }

    protected function buildDataObject($data_array)
    {
        $dataObject = new DataObject;

        foreach($data_array as $data)
        {
            $dataObject->addItemArray($data);
        }

        return $dataObject;
    }

    public function sort(DataObject $data_collection)
    {
        $data = $this->exportArray($data_collection);

        usort($data, array($this, "compare"));
        
        $sorted_data_object = $this->buildDataObject($data);

        return $sorted_data_object;
    }

 
}

class DummySorter extends Sorter
{
    public function compare($a, $b)
    {
        return 0;
    }

}

class TimeSorter extends Sorter
{
    public function compare($a, $b)
    {
        if($a['pubDate'] == $b['pubDate'])
        {
            return 0;
        }

        return ($a['pubDate'] < $b['pubDate']) ? -1 : 1;
    }
}

class TitleSorter extends Sorter
{
   public function compare($a, $b)
    {
        return strcmp($a['title'],$b['title']);
    }
}

class LinkSorter extends Sorter
{
   public function compare($a, $b)
    {
        return strcmp($a['link'],$b['link']);
    }
}
