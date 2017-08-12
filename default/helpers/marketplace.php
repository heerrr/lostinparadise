<?php

class marketplace {
    
    private static $marketplace_data=null;
    
    private static function _get_data() {
        $data_file = CF::get_file('data','marketplace');
        if(strlen($data_file)==0) {
            throw LIPException('Data marketplace not found');
        }
        self::$marketplace_data = include $data_file;
        return self::$marketplace_data;
    }
    
    public static function get_list() {
        $list = array();
        foreach (self::get_data() as $k=>$v) {
            $list[$k]=carr::get($v,'name');
        }
        return $list;
    }
    
    public static function get_data() {
        if(self::$marketplace_data==null) {
            self::_get_data();
        }
        return self::$marketplace_data;
    }
    
    public static function get($code) {
        $data = self::get_data();
        return carr::get($data,$code);
    }
}