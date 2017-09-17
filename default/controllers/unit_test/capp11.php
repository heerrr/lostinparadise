<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Capp11_Controller extends CController {
    
    public function test_db() {
        $db = CDatabase::instance();
        $r = $db->query('select * from product_type');
        foreach($r as $row) {
            cdbg::var_dump($row);
        }
    }
    
    public function test_deprecated() {
        cdbg::deprecated('a');
    }
    
}
