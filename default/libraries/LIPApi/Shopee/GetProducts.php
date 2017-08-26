<?php

class LIPApi_Shopee_GetProducts extends LIPApi_Method{
    
    
    public function execute() {
        $search_items = $this->process('SearchItems',null);
        
        $items = carr::get($search_items,'items');
        
        $result_items = $this->process('GetItems',$items);
        $data = array();
        $data['a']='b';
        $this->data = $data;
    }
}

