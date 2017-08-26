<?php

class LIPApi_Bukalapak_GetProducts extends LIPApi_Method{
    
    
    public function execute() {
        $categories = $this->process('Categories',null);
        $url=carr::get($this->request,'url');
        $category_id='';
        $page='';
        if(preg_match('#https://www.bukalapak.com(.+?)\?#ims',$url,$matches)) {
            $category_id=carr::get($categories,$matches[1]);
        }
        if(preg_match('#https://www.bukalapak.com/.+?\?from=category_home&page=(.+?)&#ims',$url,$matches)) {
            $page=$matches[1];
        }
        $options=array();
        $options['category_id']=$category_id;
        $options['page']=$page;
        $search_items = $this->process('SearchItems',$options);
        
        
        $data = array();
        $data['items']=$search_items;
        $this->data = $data;
    }
}

