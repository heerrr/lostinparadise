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
        parent::request_callback($curl, $service_name, $data = NULL);
        
    }

    public function get_products($options) {
        $response_parse = $this->_processing_request_response("GetProducts",$options);
    }

}
