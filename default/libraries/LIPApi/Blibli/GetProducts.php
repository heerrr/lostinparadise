<?php

class LIPApi_Blibli_GetProducts extends LIPApi_Method{
    
    
    public function execute() {
        $request=$this->request();
        $result = $this->process('CategoryPage',$request);
        $data=array();
        $data['items']=$result;
        $this->data = $data;
    }
}

