<?php
// the path to the php class
require_once("classes/cssparser/cssParser.class.php");

//rootpath to the css folder with trailing slash
$root = '/(your system path to the css folder)/css/'; //such as home/ausername/public_html/ etc.

// set the base config, see the cssParser class for more options. It is merged automaticly.
$config = array(
    //rootpath to the cache folder with a trailing slash
    'cacheDir'=>'/(your system path to the cache folder)/cache/css/' 
);

// create and array with the variables you specified in the css file. More info in the css/reflection.test.css file
$vars = array(
    'textreflection'=>array( //the name you specify in the header link of the html page (see index.html ($_GET['name''])
        'reflected'=>array( /* see example usage in the file css/reflected.test.css */
            'content'=>'"This text should be horizontal reflected..."',
            'opacity'=>'.3',
            'color'=>'red',
            'background'=>'test'
        ),
        'setcolor'=>array(
            'color'=>'orange'
        ),
        'inserted'=>array(
             'insert'=> '.reflected {position: relative;}',
             'insert2'=>'.test {background-color: red;}'
        ),
        'setGradient'=>array(
             'attribute'=>'linear, left top, left center, from(rgba(255,255,255,0)), to(rgb(255,255,255))',
             'attribute2'=>'top, rgba(255,255,255,0), rgb(255,255,255)'
        )
    )
);

/* make an instantiation of the cssParser class and use the display method to invoke it */
if (isset($_GET['name']) && isset($_GET['css'])) {    
    $cssParser = new cssParser($root.$_GET['css'], $vars[trim($_GET['name'])], $config);    
    $cssParser->display();
}

/* check the css file reflected.test.css for example usage */
/* THAT'S IT - HAVE FUN !!!!'*/
?>