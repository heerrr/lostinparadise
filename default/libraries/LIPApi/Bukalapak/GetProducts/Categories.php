<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LIPApi_Bukalapak_GetProducts_Categories extends LIPApi_Parser {

    

    public function request_parser($request) {
        $this->url = 'https://api.bukalapak.com/v2/categories.json';
    }

    public function response_parser($response) {
        $data=array();
        $categories=json_decode($response,true);
        $categories=carr::get($categories,'categories');
        $data=$this->make_simple_categories($categories);
        return $data;
    }

    public function make_simple_categories($categories,&$data=array()){
        foreach($categories as $cat){
            $id=carr::get($cat,'id');
            $url_key=carr::get($cat,'url');
            if(strlen($url_key)>0){
                $data[$url_key]=$id;
            }
            $children=carr::get($cat,'children');
            if($children!=null){
                $this->make_simple_categories($children,$data);
            }
        }
        return $data;
    }
}
