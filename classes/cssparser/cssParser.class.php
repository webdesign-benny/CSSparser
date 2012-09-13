<?php
/*
    CSS PARSER
    css parser for using dynamic fields in your css files.
    See ... for documentation.
    
    Author: Fret Benny
    Email: info@webdesign-benny.be
    Version: 1.0
    Release date: 12/09/2012
    License: free
*/
class cssParser {
    public $values;
    public $cssFile;
    public $cssVars;
    public $config = array(
        'cacheDir'=>'',
        'tag'=>'~',
        'compress'=>false,   //not yet supported in this version
        'log'=>false     //not yet supported in this version
    );
    
    public function __construct($cssFile, $cssVars, $config=null) {
        if (!file_exists($cssFile)) {  
            header('HTTP/1.0 404 Not Found');  
            exit;  
        }
        $this->config = array_merge($this->config, $config);      
        $this->cssFile = $cssFile; 
        $this->cssVars = $cssVars;
        
        $modified = filemtime($cssFile);  
        header('Last-Modified: '.gmdate("D, d M Y H:i:s", $modified).' GMT');  
      
        if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {  
            if (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $modified) {  
               header('HTTP/1.1 304 Not Modified');  
               exit();  
            }  
        }
    }
    
    public function parse(){
        $lines = file($this->cssFile);
        return $this->parsePart($lines);
    }
    
    public function parsePart($lines){
        $part = '';
        $flag = false;
        $sectionName = '';
        $linesArrObj = new ArrayObject($lines);
        $totalLines = $linesArrObj->count();
        $it = $linesArrObj->getIterator();
        while($it->valid()){
            if(strpos(trim($it->current()), '/*') === 0 && strpos($it->current() , $this->config['tag']) > 0){
                if(!$flag){
                    $sectionName = $this->getSectionName(&$it);
                    $flag = true;
                } else {
                    $sectionEnd = $this->getSectionName(&$it);
                    if($sectionEnd == $sectionName){
                       $flag = false;
                    } else {
                        $sectionName = $sectionEnd;
                    }
                }
            } else {
                 if(strpos(trim($it->current()), '$') === 0 || strpos(trim($it->current()), '$') > 0){
                    $part .= $this->transformCssVars(&$it, $this->getCssVars($sectionName));   
                } else {
                    $part .= $it->current();
                }
            }
            $it->next();
        }
        return $part;
    }
    
    public function getSectionName(&$it){
        return trim(str_replace(array(' ','/','~','*',$this->config['tag']), '', $it->current()));
    }
    
    public function getCssVars($sectionName){
        if($vars = $this->cssVars[$sectionName]){
            return $vars;
        } else {
            return false;
            /* error log will be supported in next version */
        }
    }
       
    public function transformCssVars(&$line, $vars) {
        preg_match_all('/\s*\\$([A-Za-z1-9_\-]+)(\s*:\s*(.*?);)?\s*/', $line->current(), $matches); 
        $varNames  = $matches[1];
        $part = '';
        if($vars != false){
            foreach($varNames as $varName){
                switch($varName){
                    case (strpos($varName, 'insert')===0):
                        $part .= ' '.$vars[$varName];        
                        break;
                    case (strpos($varName, 'attribute')===0):
                        $part .=  ' '.str_replace('$'.$varName.';', $vars[$varName], $line->current());
                        break;
                    default: 
                        $part .= ' '.$varName.': '.$vars[$varName].';';
                }
            }
        }
        return $part;
    }
    
    public function display() { 
        header('Content-type: text/css'); 
        echo $this->cache($this->parse());   
    }
    
    private function cache($part = false) {
        $cacheFile = $this->config['cacheDir'].urlencode($this->cssFile);
        if(file_exists($cacheFile) && filemtime($cacheFile) > filemtime($this->cssFile)){
            return file_get_contents($cacheFile);
        } else if ($part) {
            file_put_contents($cacheFile, $part);
        }
        return $part;
    }

    /* will be supported in next version */
    public function compress(){
        /* comming next */
    }
    
    public function decompress(){
        /* comming next */
    }
}    
?>