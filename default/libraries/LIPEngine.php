<?php

    /**
     *
     * @author Raymond Sugiarto
     * @since  Nov 3, 2014
     * @license http://piposystem.com Piposystem
     */
    abstract class CAPIEngine {

        /**
         *
         * @var CAPISession 
         */
        protected $_session;
        public $_last_response;
        protected $_url = "";
        protected $_level = "DEVEL";
        protected $_debug = FALSE;
        protected $_auth;

        public function __construct($session) {
            $this->_session = $session;
        }

        public function state($state = NULL) {
            if ($state == NULL) {
                return $this->_session->get('state');
            }
            $this->_session->set("state", $state);
            return $this;
        }

        public function login($request) {
            $return = array();
            $session_id = "";
            if ($this->error()->code() == 0) {
                $return['err_code'] = $this->error()->code();
                $return['session_id'] = $this->_session->get("session_id");
                return $return;
            }
            return NULL;
        }

        public function error() {
            return CAPIError::instance();
        }

        protected function __get_cookies_file() {
            return $this->_session->get('cookies_file');
        }

        public function __request($service_name, $data = NULL) {
            $curr_state = $this->_session->get('state');
//            $parser = array(
//                $curr_state => array(
//                    $service_name
//                    )
//            );
//            $this->_session->set_parser($parser);
            $this->_session->set('auth_engine', $this->_auth);

           
            $curl = CCurl::factory(NULL);
            $curl->set_timeout(40000);
            $curl->set_useragent('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0');

            if ($data != NULL) {
                if (is_array($data)) {
                    $curl->set_post($data);
                }
                else {
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
            CFBenchmark::start($this->_session->get_session_id() . '_' . $service_name . '_api_request');
            $curl->exec();

            //$this->_session->set('after_' .$service_name, date("Y-m-d H:i:s"));
            $has_error = $curl->has_error();

            $response = $curl->response();

            $this->_last_response = $response;
            
            $http_response_code = $curl->get_http_code();
            $benchmark = CFBenchmark::get($this->_session->get_session_id() . '_' . $service_name . '_api_request');
            $execution_time = 0;
            if (isset($benchmark['time'])) {
                $execution_time = $benchmark['time'];
            }
            //log request
            $product_category_code = $this->_session->get_product_category_code();
            $product_code = $this->_session->get_product_code();
            $session_id = $this->_session->get_session_id();
            $auth = json_encode($this->_session->get('vendor_auth'));

            $org_id = $this->_session->get('org_id');
            $url = $this->_url;

            /**
             * Make Log Directory based on product category, product and session_id
             */
            $log_path = api_log::make_dir($product_category_code, $product_code, $session_id, $service_name, $this->_session->get('org_code', 'Default'));

            $filename_request = 'API_' . $session_id . "_" . date("His") . "_" . $service_name . "_rq" . ".log";
            $filename_response = 'API_' . $session_id . "_" . date("His") . "_" . $service_name . "_rs" . ".log";

            // set session list parser
            $arr_filename = explode('_', $filename_request);
            $parse_service_name = $arr_filename[2] . '_' . $arr_filename[3];
            $parser = array(
                $curr_state => array(
                    $parse_service_name
                )
            );
            $this->_session->set_parser($parser);

            // check if URL contain GET Parameter
            $get_array = array();
            $get_param = explode('?', $url);
            if (isset($get_param[1])) {
                parse_str($get_param[1], $get_array);
            }
            if (is_array($data)) {
                $data['GET_param'] = $get_array;
                $data = json_encode($data, JSON_PRETTY_PRINT);
            }
            else {
                $data .= "\nGET Parameter\n" . json_encode($get_array, JSON_PRETTY_PRINT);
            }

            // remove html special character
            //$response = html_entity_decode($response);

            @file_put_contents($log_path . $filename_request, $data);
            @file_put_contents($log_path . $filename_response, $response);

            $data = array(
                "product_code" => $product_code,
                "product_category_code" => $product_category_code,
                "request_date" => date("Y-m-d H:i:s"),
                "org_id" => $org_id,
                "auth" => $auth,
                "url" => $url,
                "service_name" => $service_name,
                "session_id" => $session_id,
                "request" => $filename_request,
                "response" => $filename_response,
                "http_response_code" => $http_response_code,
                "execution_time" => $execution_time,
            );
            CDatabase::instance()->insert("api_log_request", $data);

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
                $this->error()->add($has_error, 1010);
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

        /**
         * 
         * @param string $status_trans  PREBOOK / BOOKED / PREISSUED / ISSUED
         * @return array
         */
        protected function __transaction($status_trans) {

            $d_trans = array();
            $d_trans['org_id'] = $this->_session->get('org_id');
            $d_trans['org_vendor_id'] = $this->_session->get('org_vendor_id');
            $d_trans['transaction_group_id'] = $this->_session->get('transaction_group_id');
            $d_trans['product_category_code'] = $this->_session->get_product_category_code();
            $d_trans['product_category_name'] = $this->_session->get('product_category_name');
            $d_trans['product_code'] = $this->_session->get_product_code();
            $d_trans['product_name'] = $this->_session->get('product_name');
            $d_trans['transaction_date'] = date("YmdHis");
            $d_trans['code'] = generate_code::get_next_transaction_code($d_trans['product_category_code']);
            $d_trans['booking_code'] = $this->_session->get('booking_code');

            $data_price = $this->_session->get('data_price');

            $d_trans['currency_code'] = $this->_session->get('currency_code', 'IDR');
            $d_trans['currency_rate'] = $this->_session->get('currency_rate', 1);
            $d_trans['basic_fare'] = $this->_session->get('basic_fare', 0);
            $d_trans['tax_total'] = $this->_session->get('tax_total', 0);
            $d_trans['discount_total'] = $this->_session->get('discount_total', 0);
            $d_trans['fee_total'] = $this->_session->get('fee_total', 0);
            $d_trans['ssr_total'] = $this->_session->get('ssr_total', 0);
            $d_trans['insurance_total'] = $this->_session->get('insurance_total', 0);
            $d_trans['vendor_sell_price'] = $this->_session->get('vendor_sell_price', 0);
            $d_trans['vendor_commission_value'] = carr::get($data_price, 'vendor_commission_value', 0);
            $d_trans['vendor_commission_percent'] = carr::get($data_price, 'vendor_commission_percent', 0);
            $d_trans['vendor_nta'] = carr::get($data_price, 'vendor_nta', 0);
            $d_trans['upselling'] = carr::get($data_price, 'upselling', 0);
            $d_trans['admin_agent_total'] = $d_trans['upselling'];
            $d_trans['channel_sell_price'] = carr::get($data_price, 'channel_sell_price', 0);
            $d_trans['channel_commission_ho'] = carr::get($data_price, 'channel_commission_ho', 0);
            $d_trans['channel_commission_share'] = carr::get($data_price, 'channel_commission_share', 0);
            $d_trans['channel_commission_full'] = carr::get($data_price, 'channel_commission_full', 0);
            $d_trans['channel_commission_value'] = $this->_session->get('channel_commission_value', 0);
            $d_trans['channel_commission_percent'] = $this->_session->get('channel_commission_percent', 0);

            // BEFORE
            $d_trans['currency_code_before'] = $this->_session->get('currency_code', 'IDR');
            $d_trans['basic_fare_before'] = $this->_session->get('basic_fare', 0);
            $d_trans['tax_total_before'] = $this->_session->get('tax_total', 0);
            $d_trans['discount_total_before'] = $this->_session->get('discount_total', 0);
            $d_trans['fee_total_before'] = $this->_session->get('fee_total', 0);
            $d_trans['ssr_total_before'] = $this->_session->get('ssr_total', 0);
            $d_trans['insurance_total_before'] = $this->_session->get('insurance_total', 0);
            $d_trans['admin_agent_total_before'] = $d_trans['upselling'];

            $d_trans['api_session_id'] = $this->_session->get_session_id();
            $d_trans['total_adult'] = $this->_session->get('adult', 0);
            $d_trans['total_child'] = $this->_session->get('child', 0);
            $d_trans['total_infant'] = $this->_session->get('infant', 0);
            $d_trans['total_pax'] = $d_trans['total_adult'] + $d_trans['total_child'] + $d_trans['total_infant'];
            $d_trans['status_transaction'] = $status_trans;
            $d_trans['user_booked'] = $this->_session->get('user_booked', '');
            $d_trans['user_issued'] = $this->_session->get('user_issued', '');
            $d_trans['product_balance_id'] = $this->_session->get('product_balance_id');

            $have_booking = '0';
            if ($status_trans == 'PREBOOK') $have_booking = '1';

            $d_trans['have_booking'] = $have_booking;
            $d_trans['have_request'] = $this->_session->get('have_request', 0);
            $d_trans['is_posted'] = '0';
            $this->__default_db($d_trans);

            $this->_session->set('transaction_code', $d_trans['code']);
            return $d_trans;
        }

        protected function __default_db(&$default) {
            $default['created'] = date("YmdHis");
            $default['createdby'] = 'system';
            $default['updated'] = date("YmdHis");
            $default['updatedby'] = 'system';
            $default['status'] = '1';
            return $default;
        }

        public static function obj_parser($product_category_name, $product_name, $parser_name, $session) {
            //$path = $product_category_name .DS .$product_name .DS .'Parser' .DS .$parser_name .'Parser';
            $parser = $product_name . 'Parser';

            require_once dirname(__FILE__) . DS . $product_category_name . DS . $product_name . DS . 'Parser' . DS . $parser . EXT;
            require_once dirname(__FILE__) . DS . $product_category_name . DS . $product_name . DS . 'Parser' . DS . $parser_name . EXT;

            $auth = $session->get('auth_engine');

            return new $parser_name($session, $auth);
        }

        protected function get_transaction_by_booking_code($booking_code) {
            $db = CDatabase::instance();
            $q = "SELECT *
                FROM transaction t
                WHERE t.booking_code = " . $db->escape($booking_code) . " AND t.status > 0
                ";
            return cdbutils::get_row($q);
        }

    }
    