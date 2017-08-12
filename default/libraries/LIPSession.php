<?php

    /**
     *
     * @author Raymond Sugiarto
     * @since  Oct 31, 2014
     * @license http://piposystem.com Piposystem
     */
    class CAPISession {

        const __ExpiredSession = 30;

        private $_have_cookies = FALSE;
        private $_session_id;
        private $_data;
        private $_product_code;
        private $_product_category_code;
        private $_session_path = NULL;
        private $_cookies_path = NULL;

        /**
         *
         * @var CAPISession 
         */
        private static $_instance;

        private function __construct($product_category_code, $product_code, $session_id = "", $have_cookies = FALSE) {
            $this->_product_code = $product_code;
            $this->_product_category_code = $product_category_code;
            $this->_session_id = $session_id;
            $this->_have_cookies = $have_cookies;
            $this->_data = array();

            if ($this->_session_id != NULL && strlen($this->_session_id) > 0) {
                $dir_1 = substr($session_id, 4, 8);
                $dir_2 = substr($session_id, 12, 2);
                $this->_session_path = CF::get_dir('sessions');
                $this->_cookies_path = CF::get_dir('cookies');

                $this->_session_path .= $this->_product_category_code . DS
                        . $this->_product_code . DS . $dir_1 . DS . $dir_2 . DS;
                $this->_cookies_path .= $this->_product_category_code . DS
                        . $this->_product_code . DS . $dir_1 . DS . $dir_2 . DS;
                $this->load();
            }
            else {
                $this->_session_path = CF::get_dir('sessions');
                $this->_cookies_path = CF::get_dir('cookies');

                $this->_session_path .= $this->_product_category_code . DS
                        . $this->_product_code . DS . date("Ymd") . DS . date("H") . DS;
                $this->_cookies_path .= $this->_product_category_code . DS
                        . $this->_product_code . DS . date("Ymd") . DS . date("H") . DS;
                $this->init();
            }
        }

        public function reset_cookies() {
            $this->_cookies_path = CAPPPATH . "cookies" . DS . $this->_product_category_code . DS
                    . $this->_product_code . DS . date("Ymd") . DS . date("H") . DS;
            $this->_data['cookies_file'] = $this->_cookies_path . $this->_session_id;
            $this->save();
        }

        /**
         * 
         * @param string $product_category_code
         * @param string $product_code
         * @param string $session_id
         * @return CAPISession
         */
        public static function factory($product_category_code, $product_code, $session_id = "") {
            $product = products::get_product_by_code($product_category_code, $product_code);

            $have_cookies = FALSE;
            if (isset($product['have_cookies'])) {
                $have_cookies = $product['have_cookies'];
            }

            return new CAPISession($product_category_code, $product_code, $session_id, $have_cookies);
        }

        /**
         * 
         * @param type $product_category_code
         * @param type $product_code
         * @param type $session_id
         * @return CAPISession
         */
        public static function instance($product_category_code, $product_code, $session_id = "") {
            if (self::$_instance == NULL) {
                self::$_instance = array();
            }
            if (!isset(self::$_instance[$product_category_code])) {
                self::$_instance[$product_category_code] = array();
            }
            if (!isset(self::$_instance[$product_category_code][$product_code])) {
                self::$_instance[$product_category_code][$product_code] = CAPISession::factory($product_category_code, $product_code, $session_id);
            }
            //if (self::$_instance == NULL) {
            //self::$_instance = CAPISession::factory($product_category_code, $product_code, $session_id);
            //}

            return self::$_instance[$product_category_code][$product_code];
        }

        public function save($data = NULL) {
            if ($data != NULL) $this->_data = $data;
            if (!is_array($this->_data)) {
                $this->_data = array();
            }
            $filename = $this->_session_path . $this->_session_id . EXT;

//            if ($this->error()->code() == 0)
            cphp::save_value($this->_data, $filename);
            return $this;
        }

        public function load() {
            $filename = $this->_session_path . $this->_session_id . EXT;

            if (!file_exists($filename)) {
                $this->error()->add_default(1006);
            }

//            if ($this->error()->code() == 0)
            $this->_data = cphp::load_value($filename);
            if (!is_array($this->_data)) {
                $this->_data = array();
            }
            return $this;
        }

        public function set($key, $val, $session_id = NULL, $save = true) {
            if (!isset($this->_data[$key]) || $this->_data[$key] != $val) {
                $this->_data[$key] = $val;
                if ($save) {
                    $this->save();
                }
            }
            return $this;
        }

        public function get($key, $default = NULL) {
            if (isset($this->_data[$key])) return $this->_data[$key];
            return $default;
        }

        public function is_expired() {
            $session_path = "sessions" . DS . $this->_product_category_code . DS
                    . $this->_product_code . DS . date("Ymd") . DS . date("H") . DS;
            $file = CAPPPATH . $session_path;
            $date_modified = filemtime($file);
            $difference = (time() - $date_modified) / 60;
            if (floor($difference) >= self::__ExpiredSession) {
                touch($file);
                return TRUE;
            }
            return FALSE;
        }

        /**
         * AISJYmdHis.uniqid()
         */
        public function init() {
            $time_now = date("YmdHis");
            $prefix = $this->_product_category_code . $this->_product_code . $time_now;
            $this->_session_id = uniqid($prefix) . mt_rand(100, 999);

            $session_path = "sessions" . DS . $this->_product_category_code . DS
                    . $this->_product_code . DS . date("Ymd") . DS . date("H");
            $this->make_dir($session_path);

            if ($this->_have_cookies == TRUE) {
                $cookies_path = "cookies" . DS . $this->_product_category_code . DS
                        . $this->_product_code . DS . date("Ymd") . DS . date("H");
                $this->make_dir($cookies_path);
                $this->_data['cookies_file'] = $this->_cookies_path . $this->_session_id;
            }
            $this->_data['session_id'] = $this->_session_id;
            $this->save();
            return $this;
        }

        private function make_dir($path) {
            $tmp_path = explode(DS, $path);

            $directory = CF::get_dir();
            foreach ($tmp_path as $key => $t) {
                $directory .= $t . DS;
                if ((!is_dir($directory)) && !file_exists($directory))
                        @mkdir($directory);
            }
        }

        public function set_session_id($_session_id) {
            $this->_session_id = $_session_id;
            return $this;
        }

        public function get_session_id() {
            return $this->_session_id;
        }

        public function error() {
            return CAPIError::instance();
        }

        public function get_product_code() {
            return $this->_product_code;
        }

        public function get_product_category_code() {
            return $this->_product_category_code;
        }

        public function set_parser($parser) {
            $list_parser = $this->get('list_parser', array());
            foreach ($parser as $parser_k => $parser_v) {
                foreach ($parser_v as $k => $v) {
                    $list_parser[$parser_k][] = $v;
                }
            }
            $this->set('list_parser', $list_parser);
            return $this;
        }

    }
    