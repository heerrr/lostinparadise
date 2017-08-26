<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Melon_Controller extends LIPAdminController {

    public function bukalapak() {
        $options = array();
        $options['url']='https://www.bukalapak.com/c/perawatan-kecantikan?from=category_home&page=2&search%5Bkeywords%5D=';
        $response = LIPApi::instance('bukalapak')->exec('GetProducts',$options);
        cdbg::var_dump($response);
    }
    
    public function tokopedia() {
        $options = array();
        $options['url']='https://tokopedia.com/hot/sporty-girl?page=2';
        $response = LIPApi::instance('tokopedia')->exec('GetProducts',$options);
        cdbg::var_dump($response);
    }

}
