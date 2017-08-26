<?php

class LIPApi {
    protected $engine = null;
    protected static $instance = null;
    protected $org_id = null;
    protected $marketplace_code = null;
    protected $marketplace_name = null;
    protected $engine_name = null;
    protected function __construct($marketplace_code,$org_id) {
       
        $this->org_id = $org_id;
        $this->marketplace_code = $org_id;
        $marketplace = marketplace::get($marketplace_code);
        $this->engine = carr::get($marketplace,'engine');
        $this->marketplace_name = carr::get($marketplace,'name');
        

    }
    
    public static function instance($marketplace_code,$org_id=null) {
        if($org_id==null) {
            $org_id = CF::org_id();
        }
        if(self::$instance==null) {
            self::$instance=array();
        }
        if(!isset(self::$instance[$org_id])) {
            self::$instance[$org_id] = array();
        }
        if(!isset(self::$instance[$org_id][$marketplace_code])) {
            self::$instance[$org_id][$marketplace_code] = new LIPApi($marketplace_code,$org_id);
        }
        return self::$instance[$org_id][$marketplace_code];
    }
    
    public function exec($method,$request) {
        
        
        //locate file method
        $response = array();
        $class_name = 'LIPApi'.'_' .$this->engine.'_'. $method;
        if (class_exists($class_name)) {
            $method_object = new $class_name($this->engine,$method,$this->org_id,$request);
            $method_object->execute();
            $response = $method_object->result();
        } else {
            $response = array(
                'err_code' => '11',
                'err_message' => 'Class '.$class_name.' not found',
            );
        }




        return $response;

    }
    
}