<?php

class Product_Controller extends LIPAdminController {
    
    
    public function index() {
         $app = CApp::instance();
        
        
        echo $app->render();
    }
    
    public function add() {
        $app = CApp::instance();
        $app->title("Tambah Produk");
        $widget = $app->add_widget()->set_title('Tambah Produk');
        $form = $widget->add_form()->set_action(curl::base().'master/product/load_marketplace_product')->set_method('post');
        $form->add_field()->set_label('Marketplace')->add_control('marketplace_code','select')->set_list(marketplace::get_list());
        $form->add_field()->set_label('Url')->add_control('url','text')->set_value('')->set_placeholder('Ex:https://www.tokopedia.com/p/fashion-wanita');
        
        
        
        $action_list = $form->add_action_list()->set_style('form-action');
        $action_list->add_action()->set_submit(true)->set_label('Perlihatkan Produk');
        
        $form->set_ajax_submit(true)->set_ajax_submit_target('marketplace-product-container');
        
        $app->add_div('marketplace-product-container');
        echo $app->render();
    }
    
    public function load_marketplace_product() {
        $app = CApp::instance();
        
        $request = array_merge($_GET,$_POST);
        $marketplace_code = carr::get($request,'marketplace_code');
        $url = carr::get($request,'url');
        $lip = LIP::factory($marketplace_code);
        $options = array(
            'url'=>$url,
        );
        $products = $lip->get_products($options);
        $app->add($products);
        echo $app->render();
    }
}

