<?php

require 'Exchange.php';
require 'import/GenericRSS2.php';
require 'export/RSS2_Exporter.php';

// test.php
$exchange_obj = new Exchanger;

$exchange_obj->addImporter(new GenericRSS2(array('url' => 'http://bu.idv.tw/blog/feed/','disable_cache' => false, 'TTL' => 3600)));

$exchange_obj->setExporter(new RSS2_Exporter);

$exchange_obj->setSorter(new TimeSorter);

$exchange_obj->process(Exchanger::DISPLAY_CONTENT); # RENDER_ONLY / DISPLAY_CONTENT(def)
