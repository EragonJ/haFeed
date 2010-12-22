<?php

namespace haFeed\Export;

use haFeed\DataObject as DataObject;

abstract class Exporter
{
    abstract public function export(DataObject $data);
}
