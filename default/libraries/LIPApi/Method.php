<?php



class LIPApi_Method {

    protected $engine;
    protected $method;
    protected $session;
    protected $err_code = 0;
    protected $err_message = "";
    protected $data = array();
    protected $org_id = null;
    protected $debug = false;
    
    protected $request = null;

    public function __construct($engine,$method, $org_id, $request = null) {
        $this->engine = $engine;
        $this->method = $method;
        $this->org_id = $org_id;
        $this->request = $request;
        
    }

    public function request() {
        if ($this->request == null) {
            return array_merge($_GET, $_POST);
        }
        return $this->request;
    }

    public function result() {
        $return = array(
            'err_code' => $this->err_code,
            'err_message' => $this->err_message,
            'data' => $this->data,
        );
        return $return;
    }
    
    protected function process($parser, $request = NULL, $debug = FALSE) {
        
        if($request==null) {
            $request = $this->request;
        }
        //locate file method
        $response = array();
        $class_name = 'LIPApi'.'_' .$this->engine.'_'. $this->method.'_'.$parser;
        $parser_object = null;
        if (class_exists($class_name)) {
            $parser_object = new $class_name($this->engine,$this->method,$parser,$this->org_id,$request);
        }
        if($parser_object==null) {
            throw new LIPApi_Exception('Class '.$class_name.' not found',12);
        }
        

        $request_parser = $parser_object->request_parser($request);

        $curl = $parser_object->curl($parser_object, $request_parser);
        
        $response = $curl->exec()->response();

        
        $response_parser = $parser_object->response_parser($response);

        return $response_parser;

        
    }
    
    

}


