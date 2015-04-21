Asset Queue
================

Queue assets and manage dependencies.

    use MatilisLabs\AssetQueue\Queue;

    $css_collection = Queue::collection('css_collection');
    $css_collection->add('assets/css/reset.css', 'reset');
    $css_collection->add('assets/css/styles.css', 'styles')->dependsOn('reset');
    $css_collection->add('assets/css/foundation.css', 'foundation')->dependsOn('styles');
    $css_collection->add('assets/css/icons.css', 'icons');            
    $css_collection->add('assets/css/fontawesome/font-awesome.min.css?no_min=true', 'fontawesome');

The static method Queue::collection() is used to set and get collections. The following statement creates a collection with id 'css_collection' or gets the collection if it has already been created:

    $collection = Queue::collection('css_collection');


