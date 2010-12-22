<?php

namespace haFeed\Export;

use haFeed\DataObject as DataObject;

require_once 'Exporter.php';

class ArrayExporter extends Exporter
{
    public function export(DataObject $data)
    {
        return $data->dump();
    }
}
