<?php

require_once 'DataObject.php';
require_once 'Sorter.php';

class Exchanger
{
    const RENDER_ONLY = 1;
    const DISPLAY_CONTENT = 0;

    protected $importer = array();
    protected $export_obj = null;
    protected $sorter_obj = null;

    public function addImporter(Importer $import_obj)
    {
        $this->importer[$import_obj->getName()] = $import_obj;

        return true;
    }
        
    public function removeImporter(Importer $import_obj) 
    {
        unset($this->importer[$import_obj->getName()]); 

        return true; 
    }
        
    public function setExporter(Exporter $export_obj) 
    { 
        $this->exporter = $export_obj;

        return true;
    }

    public function checkIfRequirementWereFit()
    {
        if(sizeof($this->importer) == 0)
        {
            throw new Exception('haFeed->Exchange: No importer');
        }

        if(is_null($this->exporter))
        {
            throw new Exception('haFeed->Exchange: No exporter');
        }
        
        if(is_null($this->sorter_obj))
        {
            throw new Exception('haFeed->Exchange: No sorter');
        }
        
        return true;
    }

    public function process($output_control = DISPLAY_CONTENT)
    {
        # 檢查是否相依的變數都已準備好
        $this->checkIfRequirementWereFit();
        
        $data_collection = array();

        foreach($this->importer as $importer)
        {
            $data = $importer->import();

            $data_collection[] = $data;
        }

        $new_data_set = new DataObject;
        
        foreach($data_collection as $dataset)
        {
            foreach($dataset->dump() as $data_row)
            {
                $new_data_set->addItemArray($data_row);
            }
        }
        
        $final_data = $this->sorter_obj->sort($new_data_set);

        if($output_control == RENDER_ONLY)
        {
            $result = $this->exporter->render($final_data);

            return $result;
        }
        else
        {
            $this->exporter->export($final_data);

            return true;
        }

        return $result;
    }

    public function setSorter(Sorter $sorter_object)
    {
        $this->sorter_obj = $sorter_object;

        return true;
    }
}
