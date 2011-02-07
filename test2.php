<?php

require 'Exchange.php';
require 'import/People.php';
require 'export/RSS2_Exporter.php';

// test.php
$exchange_obj = new Exchanger;

$exchange_obj->addImporter(new People(array('disable_cache' => false, 'TTL' => 3600)));

$exchange_obj->setExporter(new RSS2_Exporter);

$exchange_obj->setSorter(new TimeSorter);

$exchange_obj->process(Exchanger::DISPLAY_CONTENT); # RENDER_ONLY / DISPLAY_CONTENT(def)
