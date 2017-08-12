<?php

    /**
     *
     * @author Raymond Sugiarto
     * @since  Nov 1, 2014
     * @license http://piposystem.com Piposystem
     */
    class CAPIError implements ArrayAccess, Iterator, Countable{
        
        private $_errors;
        private $_current_row;
        private $_err_code;
        private $_err_message;
        private static $_instance;
        
        private $_list_error = array();
        
        private function __construct() {
            $this->_errors = array();
            $this->_current_row = 0;
            $this->_err_code = "0";
            
			$file_error = CF::get_file('data','api_error');
			
			
            $this->_list_error = include $file_error;
            
            // <editor-fold defaultstate="collapsed" desc="DEFAULT ERROR">
//            $this->_list_error[0] = "SUCCESS";
//            $this->_list_error[1000] = "Unknown Error.";
//            $this->_list_error[1001] = "Connection Error. No Response.";
//            $this->_list_error[1002] = "auth_id required.";
//            $this->_list_error[1003] = "IP is not registered.";
//            $this->_list_error[1004] = "DB.API TORS Error. Please contact administrator.";
//            $this->_list_error[1005] = "Parser Error.";
//            $this->_list_error[1006] = "Session Expired.";
//            $this->_list_error[1007] = "Login Failed.";
            // </editor-fold>
        }
        
        /**
         * 
         * @return CAPIError
         */
        public static function instance(){
            if (self::$_instance == NULL) {
                self::$_instance = new CAPIError();
            }
            return self::$_instance;
        }
        
        public function reset(){
            $this->_err_code = "0";
            $this->_err_message = '';
        }
        
        public function code(){
            return $this->_err_code;
        }
        public function set_err_code($err_code){
            $this->_err_code = $err_code; return $this;
        }
        
        public function set_err_message($err_message) {
            $this->_err_message = $err_message; return $this;
        }
        public function get_err_message() {
            return $this->_err_message;
        }
        
        /**
         * 
         * @param Int       $err_code           Error Code that listed at api_error.php
         * @param String    $custom_message     Error Message if you want add custom message at suffix default message.
         * @param Array     $replace            Array that contain key search and replace. <br/>
         *                                      - search is variable that listed at api_error.php <br/>
         *                                      - replace is new value variable
         * @example CAirlines.php
         * @return \CAPIError
         */
        public function add_default($err_code, $custom_message = "", $replace = NULL){
            $def_message = $this->_list_error[$err_code];
            if ($replace != NULL) {
                $def_message = str_replace(":" .$replace['search'], $replace['replace'], $def_message);
            }
            $this->add($def_message .$custom_message, $err_code);
            return $this;
        }
        
        /**
         * 
         * @param String $err_message   Custom new error message.
         * @param String $err_code      Error Code that listed at api_error.php
         * @return \CAPIError
         */
        public function add($err_message, $err_code = "9999"){
            $this->_err_code = $err_code;
            $this->_err_message = $err_message;
            if (is_array($err_message)){
                foreach ($err_message as $key => $value) {
                    $this->_errors[] = $value;
                }
            }
            else {
                $this->_errors[] = $err_message;
            }
            return $this;
        }
        
        public function get_errors(){
            return $this->_errors;
        }
        
        public function set($err_code) {
            $this->add($this->_list_error[$err_code], $err_code);
            return $this;
        }

        public function render() {
            $html = "";
            foreach ($this->_errors as $err) {
                if ($html != "")
                    $html .= PHP_EOL;
                $html .= "" . $err . "";
            }
            return $html;
        }
        
        
        
        /**
         * Countable count
         * 
         * @return int
         */
        public function count() {
            return count($this->_errors);
        }

        public function current() {
            return $this->offsetGet($this->_current_row);
        }

        public function key() {
            return $this->_current_row;
        }

        public function next() {
            ++$this->_current_row;
            return $this;
        }
        
        public function prev() {
            --$this->_current_row;
            return $this;
        }

        public function offsetExists($offset) {
            return isset($this->_errors[$offset]);
        }

        public function offsetGet($offset) {
            if ($this->offsetExists($offset))
                return $this->_errors[$offset];
            return FALSE;
        }

        public function offsetSet($offset, $value) {
            $this->_errors[$offset] = $value;
        }

        public function offsetUnset($offset) {
            unset($this->_errors[$offset]);
        }

        public function rewind() {
            $this->_current_row = 0;
            return $this;
        }

        public function valid() {
            return $this->offsetExists($this->_current_row);
        }

    }
    