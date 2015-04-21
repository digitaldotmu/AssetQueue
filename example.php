<?php

$css_collection = Queue::collection('front_css');
$css_collection->add('assets/css/reset.css', 'reset');
$css_collection->add('assets/css/styles.css', 'styles')->dependsOn('reset');
$css_collection->add('assets/css/foundation.css', 'foundation')->dependsOn('styles');
$css_collection->add('assets/css/icons.css', 'icons');			
$css_collection->add('assets/css/fontawesome/font-awesome.min.css?no_min=true', 'fontawesome');