<?php
require_once('(your path to)/autorun.php');
require_once('(your path to)/cssParser.testclass.php');

class TestOfCssParser extends UnitTestCase {
    function __construct() {
        parent::__construct('cssParser test');
    }

    function setUp() {
        //@unlink('../temp/test.txt');
    }



    function tearDown() {
        //@unlink('../temp/test.txt');
    }


    public function testParserVars(){
        //$this->dump($_SERVER);
        //$this->assertTrue(file_exists('../classes/cssParser.testclass.php'), 'php file exists');
        //$this->assertTrue(file_exists('/home/webdes64/public_html/projects/general/css/reflection.text.css'), 'css file exists');
        $vars = array(
            'reflected'=>array(
                'content'=>'"freelance </> developer"',
                'opacity'=>'.3',
                'color'=>'red',
                'background'=>'test'
            ),
            'inserted'=>array(
                 'insert'=> '.reflected {position: relative;}',
                 'insert2'=>'.test {background-color: red;}'
            ),
            'setGradient'=>array(
                 'attribute'=>'linear, left top, left center, from(rgba(255,255,255,0)), to(rgb(255,255,255))',
                 'attribute2'=>'top, rgba(255,255,255,0), rgb(255,255,255)'
            )
        );

        $config = array('cacheDir'=>'/(your system path to)/public_html/../cache/css');

        $parser = new cssParser('/(your system path to)/public_html/../css/reflection.test.css', $vars, $config);

        $result = $parser->parse();

        //$this->dump(get_object_vars($parser), 'Object variables:');
        $this->dump($result, 'Result after parsing:');
        $this->assertTrue(isset($result), 'Result is set?:');

    }

}

?>