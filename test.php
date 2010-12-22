<?php
namespace haFeed;

require 'Exchange.php';
require 'import/cnWSJ.php';
require 'export/RSS_1_0.php';

// test.php

$exchange_obj = new Exchanger;

$exchange_obj->addImporter(new Import\cnWSJ(array('url' => 'http://chinese.wsj.com/big5/rssbch.xml', 'disable_cache' => false, 'TTL' => 3600)));

$exchange_obj->setExporter(new Export\RSS_1_0_Exporter);

$exchange_obj->setSorter(new TimeSorter);

$exchange_obj->process(Exchanger::RENDER_ONLY); # RENDER_ONLY / DISPLAY_CONTENT(def)
