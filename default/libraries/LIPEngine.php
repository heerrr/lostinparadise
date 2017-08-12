<?php

abstract class LIPEngine {

    /**
     *
     * @var CAPISession 
     */
    protected $_engine_name;
    protected $_auth;
    protected $_url;

    public function __construct() {
        
    }

    public function get_cookies_filename($account_name = null) {
        $org_code = LIPAdmin::org()->code;

        $folder = DOCROOT . "application/lostinparadise/default/cookies/";
        if (!is_dir($folder)) {
            @mkdir($folder);
        }
        $folder.=$org_code . "/";
        if (!is_dir($folder)) {
            @mkdir($folder);
        }

        $folder.=$this->_engine_name . "/";
        if (!is_dir($folder)) {
            @mkdir($folder);
        }
        $filename = $org_code . "_" . $this->_engine_name;
        if (strlen($account_name) > 0) {
            $account_name = cstr::sanitize($account_name, true);
            $filename .= $org_code . "_" . $this->_engine_name . "_" . $account_name;
        }
        $filename.=".cookies";
        return $folder . $filename;
    }

    public function load_parser($service, $auth = NULL) {

        require_once dirname(__FILE__) . '/LIPEngine/' . $this->_engine_name .'/' .$this->_engine_name."Parser.php";
        require_once dirname(__FILE__) . '/LIPEngine/' . $this->_engine_name .'/Parser/' .$this->_engine_name.$service."Parser.php";
        $classname = $this->_engine_name.$service."Parser";
        return new $classname($auth);
    }

    protected function _processing_request_response($service, $request = NULL, $debug = FALSE) {
        
        $parser = $this->load_parser($service, $this->_auth);
        $this->_debug = $debug;

        
        $xml_request = $parser->request_parser($request);
        if ($debug == TRUE) {
            echo '<h2> Request: </h2>';
            if (is_array($xml_request)) {
                echo "<pre>";
                print_r($xml_request);
                echo "</pre>";
            } else
                echo "<pre>" . htmlspecialchars($xml_request) . "</pre>";
        }
        

        $curl = $this->__request($service, $xml_request);
        if ($debug == TRUE) {
            echo '<h2> Response: </h2>';
            echo $this->_last_response;
//                    echo "<pre>" .htmlspecialchars($this->_last_response) ."</pre>";
        }
        

        
        $response_parse = $parser->response_parser($this->_last_response);

        return $response_parse;

        return false;
    }

    public function __request($service_name, $data = NULL) {


        $curl = CCurl::factory(NULL);
        $curl->set_timeout(40000);
        $curl->set_useragent('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0');

        if ($data != NULL) {
            if (is_array($data)) {
                $curl->set_post($data);
            } else {
                $curl->set_raw_post($data);
            }
        }
        $curl->set_opt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->set_opt(CURLOPT_SSL_VERIFYHOST, 2);
        $curl->set_opt(CURLOPT_ENCODING, 'gzip, deflate');


        $this->request_callback($curl, $service_name, $data);
        $curl->set_url($this->_url);

//            if($service_name=='CaptchaCommit') {
//                $this->error()->add(cdbg::var_dump($data,true));
//            }
        //$this->_session->set('before_' .$service_name, date("Y-m-d H:i:s"));
        CFBenchmark::start($this->_engine_name . '_' . $service_name . '_request');
        $curl->exec();

        //$this->_session->set('after_' .$service_name, date("Y-m-d H:i:s"));
        $has_error = $curl->has_error();

        $response = $curl->response();

        $this->_last_response = $response;

        $http_response_code = $curl->get_http_code();
        $benchmark = CFBenchmark::get($this->_engine_name . '_' . $service_name . '_api_request');
        $execution_time = 0;
        if (isset($benchmark['time'])) {
            $execution_time = $benchmark['time'];
        }
        //log request
    
        $url = $this->_url;
        $org_code = LIPAdmin::org()->code;
        /**
         * Make Log Directory 
         */
        $log_path = DOCROOT."application/lostinparadise/default/logs/";
        if(!is_dir($log_path)) {
            @mkdir($log_path);
        }
        $log_path.=$org_code;
        if(!is_dir($log_path)) {
            @mkdir($log_path);
        }
        $log_path.=$this->_engine_name;
        if(!is_dir($log_path)) {
            @mkdir($log_path);
        }
        $date = date("Ymd");
        $log_path.=$date;
        if(!is_dir($log_path)) {
            @mkdir($log_path);
        }
        $time = date("His");
        $hour = substr($time, 0,2);
        $log_path.=$hour;
        if(!is_dir($log_path)) {
            @mkdir($log_path);
        }

        $filename_request = $this->_engine_name . "_" . $date.$time . "_" . $service_name . "_rq" . ".log";
        $filename_response = $this->_engine_name . "_" . $date.$time . "_" . $service_name . "_rq" . ".log";


        // check if URL contain GET Parameter
        
        if (is_array($data)) {
            $data['_url'] = $this->_url;
            $data = json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data .= "\nUrl:\n" . $this->_url;
        }

        // remove html special character
        //$response = html_entity_decode($response);

        @file_put_contents($log_path . $filename_request, $data);
        @file_put_contents($log_path . $filename_response, $response);

//        $data = array(
//         
//            "request_date" => date("Y-m-d H:i:s"),
//            "org_id" => $org_id,
//            "auth" => $auth,
//            "url" => $url,
//            "service_name" => $service_name,
//            "session_id" => $session_id,
//            "request" => $filename_request,
//            "response" => $filename_response,
//            "http_response_code" => $http_response_code,
//            "execution_time" => $execution_time,
//        );
        //CDatabase::instance()->insert("api_log_request", $data);

        if ($this->_debug == TRUE) {
            echo '===== <br/> <h2> Response Raw CURL: </h2>';
            echo '<textarea>' . $this->_last_response . '</textarea> <br/>';
            echo 'Has Error: ';
            var_dump($has_error);
            echo '<br/>';
            echo 'HTTP Response Code: ';
            var_dump($http_response_code);
            echo '<br/>';
        }
        if (isset($has_error) && $has_error == TRUE) {
            throw new LIPException($has_error);
            return FALSE;
        }

        switch ($http_response_code) {
            case '404':
                $param = array(
                    'search' => 'code',
                    'replace' => $http_response_code
                );
                $this->error()->add_default(1012, '', $param);
                break;
        }

        if ($this->error()->code() != '0') {
            return FALSE;
        }
        return $this->_last_response;
    }

    protected function request_callback(&$curl, $service_name, $data = NULL) {
        
    }

    public static function obj_parser($product_category_name, $product_name, $parser_name, $session) {
        //$path = $product_category_name .DS .$product_name .DS .'Parser' .DS .$parser_name .'Parser';
        $parser = $product_name . 'Parser';

        require_once dirname(__FILE__) . DS . $product_category_name . DS . $product_name . DS . 'Parser' . DS . $parser . EXT;
        require_once dirname(__FILE__) . DS . $product_category_name . DS . $product_name . DS . 'Parser' . DS . $parser_name . EXT;

        $auth = $session->get('auth_engine');

        return new $parser_name($session, $auth);
    }

}
