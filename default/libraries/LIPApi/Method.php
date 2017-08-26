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

        $curl = $this->curl($parser_object, $request_parser);
        
        $response = $curl->exec()->response();

        
        $response_parser = $parser_object->response_parser($response);

        return $response_parser;

        
    }
    
    protected function request_callback(&$curl, $parser_object, $data = NULL) {
        
    }
    
    protected function curl($parser_object, $data) {
        
        $curl = CCurl::factory($parser_object->url());
        $curl->set_timeout(40000);
        $curl->set_useragent('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0');

        if ($data != NULL) {
            if (is_array($data)) {
                $curl->set_post($data);
            } else {
                $curl->set_raw_post($data);
            }
        }
        $curl->set_opt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->set_opt(CURLOPT_SSL_VERIFYHOST, 2);
        $curl->set_opt(CURLOPT_ENCODING, 'gzip, deflate');


        $this->request_callback($curl, $parser_object, $data);
        CFBenchmark::start($parser_object->engine() . '_' . $parser_object->parser() . '_request');
        
        return $curl;
    }

}


