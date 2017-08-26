<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LIPApi_Bukalapak_GetProducts_SearchItems extends LIPApi_Parser {

    

    public function request_parser($request) {
        $category_id=carr::get($request,'category_id');
        $page=carr::get($request,'page');
        $param="";
        if(strlen($category_id)>0){
            $param.="category_id=".$category_id.'&';
        }
        if(strlen($page)>0){
            $param.="page=".$page.'&';
        }
        if(strlen($param)>0){
            $param='?'.$param;
        }
        $this->url="https://api.bukalapak.com/v2/products.json".$param;
    }

    public function response_parser($response) {
        $data=array();
        $products=json_decode($response,true);
        $products=carr::get($products,'products',array());
        $data=$this->format_product($products);
        return $data;
    }
    
    public function format_product($products){
        $data=array();
        foreach($products as $p){
            $product['product_id']=carr::get($p,'id');
            $product['name']=carr::get($p,'name');
            $product['condition']=carr::get($p,'condition');
            $product['image_url']=$images=carr::get($p,'images');
            $product['sell_price']=carr::get($p,'price');
            $product['stock']=carr::get($p,'stock');
            $product['sku']=carr::get($p,'product_sku');
            $product['weight']=carr::get($p,'weight');
            $product['description']=carr::get($p,'desc');
            $product['specification']='';
            $product['url']=carr::get($p,'url');
            $data[]=$product;
        }
        return $data;
    }
    

}
