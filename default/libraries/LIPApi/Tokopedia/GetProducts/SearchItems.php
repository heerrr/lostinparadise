<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LIPApi_Tokopedia_GetProducts_SearchItems extends LIPApi_Parser {

    

    public function request_parser($request) {
        $this->url = carr::get($request,'url');
        
        
    }

    public function response_parser($response) {
        $data=array();
        $html_list_product='';
        if(preg_match('#<div ng-if="product_loaded == 1" class="product-content" ng-cloak>(.+?)<div class="ta-inventory child bottom"#ims', $response,$matches)){
            if(isset($matches[1])){
                $html_list_product=$matches[1];
            }
        }
        if(preg_match('',$html_list_product,$matches)){
            
        }
        
        
        
        return $data;
    }

}
