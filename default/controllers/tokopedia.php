<?php
class Tokopedia_Controller extends LIPAdminController {
    public function __construct() {
        parent::__construct();
    }
 
    public function login(){
        $session=Session::instance();
        $data=array();
        $url="https://google.com/recaptcha/api2/userverify?k=6Lf5phwUAAAAAK4rbyFIEsgK77ysuT5a3i4H7FZn";
        $curl = CCurl::factory($url);
        $curl->set_post($data);
        $curl->set_ssl();
        $response = $curl->exec()->response();        
        if(strlen($response)>0){
            $response=  json_decode($response,true);
            cdbg::var_dump($response);
        }
    }
}
