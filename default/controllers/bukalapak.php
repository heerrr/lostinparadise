<?php
class Bukalapak_Controller extends LIPAdminController {
    public function __construct() {
        parent::__construct();
    }
    public function product(){
        $url="https://api.bukalapak.com/v2/authenticate.json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, 'heerrr:c123s3nt');
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($ch);               
        curl_close($ch);
        echo $response;
    }
    
    public function index() {
        $app = CApp::instance();
        $session=Session::instance();
        
        //home
        $url="https://www.bukalapak.com";
        $curl = CCurl::factory($url);
        $response = $curl->exec()->response();        
        if(preg_match("#<input type=\"hidden\" name=\"authenticity_token\" value=\"(.+?)\"#ims", $response,$matches)){
            if(isset($matches[1])){
                $auth_id=$matches[1];
            }
        }
        
        //login
        $data=array();
        $data['authenticity_token']=$auth_id;
        $data['user_session']['username']='erismelonade@msn.com';
        $data['user_session']['password']='lemonade';
        $data['user_session']['remember_me']='0';
        $data['commit']='login';
        $data['comeback']='%2F';
        $url="https://www.bukalapak.com/user_sessions";
        $curl = CCurl::factory($url);
        $curl->set_post($data);
        $curl->set_ssl();
        $response = $curl->exec()->response();        
        cdbg::var_dump($curl->has_error());
        cdbg::var_dump($curl->get_info());
     
//        if(preg_match("#session_id=(.+?);#ims", $response,$matches)){
//            cdbg::var_dump($matches);
//        }
        cdbg::var_dump($response);
        echo $response;
    }
    
    public function login(){
        $session=Session::instance();
        $data=array();
        $url="https://api.bukalapak.com/v2/authenticate.json";
        $curl = CCurl::factory($url);
        $curl->set_post($data);
        //$curl->set_opt(CURLOPT_HEADER,"Content-Type: application/x-www-form-urlencoded");        
        $curl->set_opt(CURLOPT_USERPWD, 'heerrr:c123s3nt');
        $curl->set_ssl();
        $response = $curl->exec()->response();        
        if(strlen($response)>0){
            $response=  json_decode($response,true);
            cdbg::var_dump($response);
        }
    }
}
