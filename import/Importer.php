<?php

namespace haFeed\Import;

use haFeed\DataObject as DataObject;

abstract class Importer
{    
    protected $import_name = 'unnamed importer'; 
    protected $disable_cache = false;
    protected $TTL = 3600;

    abstract public function reterieveFromSource();
    abstract public function assignParameter($parameter);

    public function __construct($parameter = null)
    {
        $parameter = ( is_null($parameter) ) ? array() : $parameter;

        $this->assignParameter($parameter);
    }

    public function getName()
    {
        return $this->import_name;
    }

    protected function checkIfCacheExpired()
    {
        if(!file_exists($this->cacheFilepath))
        {
            return true;
        }

        if((filemtime($this->cacheFilepath) + $this->TTL) <= time())
        {
            return true;
        }

        return false;
    }

    protected function writeCache(DataObject $data_obj)
    {
        file_put_contents($this->cacheFilepath, serialize($data_obj));

        return true;
    }

    protected function loadCache()
    {
        $serialized_data = file_get_contents($this->cacheFilepath);

        $data_obj = unserialize($serialized_data);

        return $data_obj;
    }

    public function import()
    {
        if($this->disable_cache || $this->checkIfCacheExpired())
        {
            $data = $this->reterieveFromSource();

            $this->writeCache($data);
        }
        else
        {
            $data = $this->loadCache();
        }
       
        return $data;
    }
}
