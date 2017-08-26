<?php
class Blibli_Controller extends LIPAdminController {
    public function __construct() {
        parent::__construct();
    }
    
    public function login(){
        $session=Session::instance();
        $data=array();
        $data['username']='melon.cresenity@gmail.com';
        $data['password']='lemonad3';
        $json_data=json_encode($data);
        $json_data='{username: "melon.cresenity@gmail.com", password: "lemonad3"}';
        $url="https://www.blibli.com/ajax-login";
        $curl = CCurl::factory($url);
        $curl->set_opt(CURLOPT_RETURNTRANSFER,false);
        $curl->set_opt(CURLOPT_ENCODING,"");
        $curl->set_opt(CURLOPT_MAXREDIRS,10);
        $curl->set_opt(CURLOPT_TIMEOUT,30);
        $curl->set_opt(CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        $curl->set_opt(CURLOPT_CUSTOMREQUEST,"POST");
        $curl->set_raw_post($json_data);
        $header=array(
            "accept: application/json, text/plain, */*",
            "accept-encoding: gzip, deflate, br",
            "accept-language: en-US,en;q=0.8,id;q=0.6,ms;q=0.4,fr;q=0.2,pt;q=0.2",
            "cache-control: no-cache",
            "content-type: application/json;charset=UTF-8",
            "cookie: PAPVisitorId=9c5121d485c8eacf3717668c4dbNXs5M; __utmt=1; __bwa_account_id=blibli-Seoul; _dc_gtm_UA-21718848-13=1; _gat_UA-21718848-13=1; inLanding=https%3A%2F%2Fwww.blibli.com%2F; spUID=1478790345656909d899335.44794bea; first-permission-impression=1; RV=BEP-18907-01920; current-currency=; JSESSIONID=67421B2387CCBAE5D786342F677FC43D; TS01c21a08=018b7d26a7bc2b7965ed54ef9d59faf48daba5a4d6371502b461c50405b4da1acbc76d5f203bd684eab4b99f914ec6331a692ddaa82f0845ee8f3bc17ebe4627fbd9d44aa105b962b25f385312e099c8e1f43e41f03843c8ca1b16e987711025df367227a2; __utma=205442883.757946353.1480216585.1481619123.1481683128.5; __utmb=205442883.12.10.1481683128; __utmc=205442883; __utmz=205442883.1480314169.2.2.utmcsr=rapido|utmccn=PopAds_Desktop_November_2016_Homepage|utmcmd=popads_cpc; undefined=2361_undefined_rsb_0_rs_https%3A%2F%2Fwww.blibli.com%2F_rs_0_rs_0; __bwa_user_id=1916329177.U.1689906068494012.1480216588; __bwa_user_session_sequence=3; __bwa_session_id=1916329177.S.1052716316797249.1481683129; __bwa_session_referrer_data=direct; __bwa_session_search_engine_keyword_data=; __bwa_session_utm_campaign=; __bwa_session_utm_medium=; __bwa_session_utm_source=; __bwa_session_utm_content=; __bwa_session_utm_term=; __bwa_session_action_sequence=12; _ga=GA1.2.757946353.1480216585; scs=%7B%22t%22%3A1%7D; insdrSV=12; _vz=viz_57e4330ee8988; ins-gaSSId=3aa6c5c0-974b-d9ff-e30f-ee887fb97577_1481686732; rr_rcs=eF5jYSlN9kgyMU4yTjYx1U1NNDTTNUkxTdJNNjdI0zUxMzVNNkxNNTY2tuDKLSvJTBEwNDIw1TXUNQQAnoYOWA; _gali=gdn-submit-login; __utmli=gdn-submit-login;",
            "origin: https://www.blibli.com",
            "postman-token: f31b31fa-b890-26db-990d-99529a431c73",
            "referer: https://www.blibli.com/",
            "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36",
            "x-newrelic-id: VQQGWFRSDxABXVdRBwgDVw==",
            "x-ts-ajax-request: true"
        );
        $curl->set_opt(CURLOPT_HEADER,$header);
        $curl->set_ssl();
        $response = $curl->exec()->response();        
        if(strlen($response)>0){
            $response=  json_decode($response,true);
            cdbg::var_dump($response);
        }
    }
    
    public function login2(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.blibli.com/backend/common/users/_login",
            CURLOPT_ENCODING => "",
            CURLOPT_POSTFIELDS => "{\"username\":\"melon.cresenity@gmail.com\",\"password\":\"lemonad3\"}",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json, text/plain, */*",
                "accept-encoding: gzip, deflate, br",
                "accept-language: en-US,en;q=0.8,id;q=0.6,ms;q=0.4,fr;q=0.2,pt;q=0.2",
                "cache-control: no-cache",
                "content-type: application/json;charset=UTF-8",
                "origin: https://www.blibli.com",
                "referer: https://www.blibli.com/",
                "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36",
                "x-ts-ajax-request: true"
            ),
            CURLOPT_COOKIEJAR => 'bliblicookies.txt',
            CURLOPT_COOKIEFILE => 'bliblicookies.txt',        
        ));

        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }    
        $url = "https://www.blibli.com/";

        $curl = CCurl::factory($url);
        $cookies_file = DOCROOT . "bliblicookies.txt";
        $curl->set_cookies_file($cookies_file);
        $curl->set_ssl();

        $response = $curl->exec()->response();
        if (strlen($response) > 0) {
            echo $response;
        }
        
        
        
    }
    
}
