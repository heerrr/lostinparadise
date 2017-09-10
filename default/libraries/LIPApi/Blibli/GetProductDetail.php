<?php

class LIPApi_Blibli_GetProductDetail extends LIPApi_Method{
    
    
    public function execute() {
        $request=$this->request();
        $result = $this->process('ProductDetail',$request);
        $data=array();
        $data=$result;
        $this->data = $data;
    }
}

