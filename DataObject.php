<?php

class DataObject implements SeekableIterator
{
    protected $items = array();
    protected $item_id = 0;

    public function dump()
    {
        return $this->items;
    }

    public function addItemArray($array)
    {
        $this->addItem($array['title'], $array['pubDate'], $array['desc'], $array['link']);

        return true;
    }

    public function addItem($title, $pubDate, $desc, $link = '')
    {
        $item_id = $this->item_id;

        $this->items[$item_id] = array(
            'title' => $title,
            'pubDate' => $pubDate,
            'desc' => $desc,
            'link' => $link
        );

        $this->item_id++;

        return $item_id;
    }

    public function removeItem($item_id)
    {
        unset($this->items[$item_id]);

        return true;
    }

    #
    # 實作 FOR SeekableIterator
    #
    protected $current_seek_position = 0;

    public function current()
    {
        return $this->items[$this->current_seek_position];
    }

    public function key()
    {
        return $this->current_seek_position;
    }

    public function next()
    {
        ++$this->current_seek_position;
    }

    public function rewind()
    {
        $this->current_seek_position = 0;
    }

    public function valid()
    {
            return isset($this->items[$this->current_seek_position]);
    }

    public function seek($position)
    {
        $this->current_seek_position = $position;

        if(!$this->valid())
        {
            throw new OutOfBoundsException("invalid seek position ($position)");
        }
    }
}

