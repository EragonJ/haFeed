<?php

require 'Exchange.php';
require 'import/cnWSJ.php';
require 'export/RSS_2_0.php';

// test.php

$exchange_obj = new Exchanger;

$exchange_obj->addImporter(new cnWSJ(array('url' => 'http://chinese.wsj.com/big5/rssbch.xml', 'disable_cache' => false, 'TTL' => 3600)));

$exchange_obj->setExporter(new RSS_2_0_Exporter);

$exchange_obj->setSorter(new TimeSorter);

$exchange_obj->process(Exchanger::DISPLAY_CONTENT); # RENDER_ONLY / DISPLAY_CONTENT(def)
