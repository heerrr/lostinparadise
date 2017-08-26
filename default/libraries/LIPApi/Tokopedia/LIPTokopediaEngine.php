<?php

class LIPTokopediaEngine extends LIPEngine {

    public function __construct() {
        $this->_engine_name = 'LIPTokopedia';
        parent::__construct();
    }
    
    protected function _processing_request_response($service, $request = NULL, $debug = FALSE) {


        return parent::_processing_request_response($service, $request, $debug);
    }

    public function request_callback(&$curl, $service_name, $data = NULL) {
        if($service_name=='GetProducts') {
        }
        parent::request_callback($curl, $service_name, $data = NULL);
        
    }

    public function get_products($options) {
        $this->_url = carr::get($options,'url');
        $response_parse = $this->_processing_request_response("GetProducts",$options);
    }
    
    public function login($options) {
        $this->_url = carr::get($options,'url');
        $response_parse = $this->_processing_request_response("Login",$options);

    }

}
