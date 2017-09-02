<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Hery_Controller extends LIPAdminController {

    public function blibli() {
        $options = array();
        $options['url']='https://www.blibli.com/tablet-lainnya/54593';
        $response = LIPApi::instance('blibli')->exec('GetProducts',$options);
        cdbg::var_dump($response);
    }
    
    public function phantom() {
        $payload = '
            {
                "url": "https://shopee.co.id/",
                "renderType": "jpeg",
                "scripts": {
                    "domReady": [
                        "https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js",  
                        "document.cookie"
                    ]
                }
}
            
            ';
        
        $url = 'http://PhantomJScloud.com/api/browser/v2/ak-e0fab-at6md-3byaz-wchzd-5cwre/';
        
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => $payload
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */ }
        var_dump($result);
        
    }
    
    public function api() {
        $options = array();
        $options['url']='https://shopee.co.id/Handphone-Aksesoris-cat.40';
        $response = LIPApi::instance('shopee')->exec('GetProducts',$options);
        cdbg::var_dump($response);
    }
    
    public function test() {
        $session = Session::instance();
        $cookies_file = DOCROOT . "test.cookies";
        /*
        $url = "https://www.blibli.com/";

        $curl = CCurl::factory($url);
        
        $curl->set_cookies_file($cookies_file);
        $curl->set_ssl();

        $response = $curl->exec()->response();
        if (strlen($response) == 0) {
            die('error on home');
        }
        */
        $data = array();
        $data['username'] = 'melon.cresenity@gmail.com';
        $data['password'] = 'lemonad3';
        $json_data = json_encode($data);
        //$json_data = '{"username": "melon.cresenity@gmail.com", "password": "lemonad3"}';
        $url = "https://www.blibli.com//backend/common/users/_login";
        //$url = "https://www.blibli.com/ajax-login";
        $curl = CCurl::factory($url);
        //$curl->set_cookies_file($cookies_file);
        $headers = array(
            "accept: application/json, text/plain, */*",
            "accept-encoding: gzip, deflate, br",
            "accept-language: en-US,en;q=0.8,id;q=0.6,ms;q=0.4,fr;q=0.2,pt;q=0.2",
            "cache-control: no-cache",
            "content-type: application/json;charset=UTF-8",
            "origin: https://www.blibli.com",
            "referer: https://www.blibli.com/",
            "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36",
            "x-ts-ajax-request: true"
        );

        $curl->set_raw_post($json_data);
        $curl->set_opt(CURLOPT_HTTPHEADER, $headers);
        $curl->set_opt(CURLOPT_ENCODING, "");
        $curl->set_timeout(40000);
        $curl->set_cookies_file($cookies_file);
        $response = $curl->exec()->response();
        //$response = $curl->exec()->response();
        if (strlen($response) > 0) {
            $response = json_decode($response, true);
            cdbg::var_dump($response);
        } else {
            cdbg::var_dump($curl->get_status());
        }
        cdbg::var_dump($response);
    }

    public function bl() {
        $session = Session::instance();
        $url = "https://api.bukalapak.com/v2/authenticate.json";
        $curl = CCurl::factory($url);

        $headers = array(
            "User-Agent: curl/7.46.0",
            "Authorization: Basic YWxvdWV0dGVfbWVsb246bGVtb25hZGU=",
            "Accept: */*",
        );
        $curl->set_opt(CURLOPT_ENCODING, 'gzip, deflate');

        $curl->set_opt(CURLOPT_POST, 1);
        $curl->set_opt(CURLOPT_HEADER, $headers);
        $curl->set_http_user_agent("curl/7.46.0");
        $curl->set_opt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->set_opt(CURLOPT_SSL_VERIFYHOST, 2);

        $response = $curl->exec()->response();
        if (strlen($response) > 0) {
            //$response=  json_decode($response,true);
        } else {
            cdbg::var_dump($curl->get_status());
        }
        cdbg::var_dump($response);
    }

}
