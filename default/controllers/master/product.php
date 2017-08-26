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
        
        $org_id = LIPAdmin::org_id();
        $request = array_merge($_GET,$_POST);
        $marketplace_code = carr::get($request,'marketplace_code');
        $url = carr::get($request,'url');
        $options = array(
            'url'=>$url,
        );
        
        $api_data = LIPApi::instance($marketplace_code)->exec('GetProducts',$options);
        $data= carr::get($api_data,'data');
        $items= carr::get($data,'items');
        
        $div = $app->add_div()->add_class('row-fluid');
        $div_left = $div->add_div()->add_class('span3');
        $div_left->add('&nbsp;');
        $div_right = $div->add_div()->add_class('span9');
        $div_products = $div_right->add_div()->add_class('row-fluid');
        
        foreach($items as $item) {
            $image_urls = carr::get($item,'image_url');
            $name = carr::get($item,'name');
            $sell_price = carr::get($item,'sell_price');
            if(!is_array($image_urls)) {
                $image_urls=array($image_urls);
            }
            $image_url = carr::get($image_urls,0);
            $div_product = $div_products->add_div()->add_class('span4');
            $html = '';
            $html .= '<div class="product-wrapper">';
            $html .= '  <div class="product-image-wrapper">';
            $html .= '      <img class="img-product" src="'.$image_url.'" />';
            $html .= '  </div>';
            $html .= '  <div class="product-name" >'.ucwords(strtolower($name)).'</div>';
            $html .= '  <div class="product-price" >Rp '.ctransform::format_currency($sell_price).'</div>';
            $html .= '</div>';
            
            $div_product->add($html);
            
        }
        $tree = CTreeDB::factory('product_category')
                ->set_org_id($org_id)
                ->add_filter('product_type_id', 1);

        $product_category = array();
        $product_category = $product_category + $tree->get_list('&nbsp;&nbsp;&nbsp;&nbsp;');

        $product_category_control = $div_left
            ->add_div('div_product_category_control')
            ->add_control('product_category_id', 'select')
            ->set_list($product_category);
            
        
        $div_left->add_action()->set_label('Save');
        
        $app->add_js("
                $('.product-wrapper').click(function() {
                    $(this).toggleClass('active');
                });
                
                ");
        echo $app->render();
    }
}

