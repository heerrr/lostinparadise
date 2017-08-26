<?php

class LIPApi_Session {

    protected static $instance;
    protected $session_id;
    protected $session_path;
    protected $session_file;
    protected $data;
    protected $is_direct = false;
    

    
    public static function get_session_path($session_id) {
        $session_path = DOCROOT . "application/supermall/default/sessions/";
        $strYmd = substr($session_id,0,8);
        $strH = substr($session_id,8,2);
        if(!is_dir($session_path)) {
            @mkdir($session_path);
        }
        $session_path.="SMApi"."/";
        if(!is_dir($session_path)) {
            @mkdir($session_path);
        }
        $session_path.=$strYmd."/";
        if(!is_dir($session_path)) {
            @mkdir($session_path);
        }
        $session_path.=$strH."/";
        if(!is_dir($session_path)) {
            @mkdir($session_path);
        }
        return $session_path;
    }
    public function __construct($session_id, $is_new_session) {
        $include_paths = CF::include_paths();
        $this->session_id = $session_id;
        //create session path
        $this->session_path = $this->get_session_path($this->session_id);
        
        $this->session_file = $this->session_path.$this->session_id.".php";
        
        $this->data = array();
        if ($is_new_session) {
            $this->data['session_id'] = $this->session_id;
            $this->save(null);
        } 
        //load data session
        $this->load();

    }
    
    
    public static function instance($session_id = null,$is_direct=false) {
        $is_new_session = false;
        if ($session_id == null) {
            //generate session_id baru
            $prefix = date("YmdHis");
            $session_id = uniqid($prefix);
            $is_new_session = true;
        }
        
        if (self::$instance == null) {
            self::$instance = array();
        }
        if (!isset(self::$instance[$session_id])) {
            self::$instance[$session_id] = new LIPApi_Session($session_id, $is_new_session);
        }
        return self::$instance[$session_id];
    }

    public function get($key) {
        return carr::get($this->data,$key);
    }
    
    public function data() {
        return $this->data;
    }
    
    public function set($key,$val) {
        $this->data[$key]=$val;
        $this->save();
        return $this;
    }
    public function save() {
        cphp::save_value($this->data, $this->session_file);
        return $this;
    }
    public function load() {
        
        $this->data = cphp::load_value($this->session_file);
        return $this;
    }

    public function session_id() {
        return $this->session_id;
    }
    
    public static function exists($session_id) {
        $session_path = self::get_session_path($session_id);
        $session_file = $session_path.$session_id.".php";
        return file_exists($session_file);
    }

}
