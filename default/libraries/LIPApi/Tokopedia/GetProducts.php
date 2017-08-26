<?php

class LIPApi_Tokopedia_GetProducts extends LIPApi_Method{
    
    
    public function execute() {
        $search_items = $this->process('SearchItems',null);
        
        
        
        $items = carr::get($search_items,'items');
        
        
        $data = array();
        $data['items']=$items;
        $this->data = $data;
    }
}

