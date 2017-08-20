<?php

class LIPShopeeEngine extends LIPEngine {

    public function __construct() {
        $this->_engine_name = 'LIPShopee';
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
        if(preg_match('#https://shopee.co.id/.+?\.(.*)#ims',$this->_url,$matches)) {
            $product_category_id = $matches[1];
            $this->_url = 'https://shopee.co.id/api/v1/search_items/?by=pop&order=desc&newest=0&limit=50&skip_price_adjust=false&categoryids='.$product_category_id.'&is_official_shop=false';
        }
        $response_parse = $this->_processing_request_response("SearchItem",$options);
        $this->_url = 'https://shopee.co.id/api/v1/items/';
        $response_parse = $this->_processing_request_response("Item",$options);
        
    }
    
    public function login($options) {
        $this->_url = carr::get($options,'url');
        $response_parse = $this->_processing_request_response("Login",$options);

    }

}
