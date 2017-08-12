<?php

class LIP {
    protected $engine = null;
    
    
    protected function __construct($marketplace_code) {
        $marketplace = marketplace::get($marketplace_code);
        $engine = carr::get($marketplace,'engine');
        $marketplace_name = carr::get($marketplace,'name');
        $folder = dirname(__FILE__) .'/LIPEngine/'.$engine."/";
        $engine_name = $engine.'Engine';
        $engine_filename = $engine_name.'.php';
        $full_engine_filename = $folder.$engine_filename;
        if(!file_exists($full_engine_filename)) {
            throw new LIPException("File engine ".$marketplace_name.' ['.$full_engine_filename.'] Not Found');
        }
        require_once $full_engine_filename;
        $this->engine = new $engine_name();
    }
    
    public static function factory($marketplace_code) {
        return new LIP($marketplace_code);
    }
    
    
    public function get_products($options) {
        $result = array();
        $data = $this->engine->get_products($options);
        
        $result['err_code']=0;
        $result['err_message']='';
        $result['data']=$data;
        
        
        return $result;
    }
}