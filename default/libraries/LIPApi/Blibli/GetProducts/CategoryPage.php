<?php

/*
  <a class="single-product" href="https://www.blibli.com/axioo-windroid-9g-tablet-hitam-32gb-2gb-AXI.18805.00036.html?ds=AXI-18805-00036-00001" onclick="rr.log_click('');
  return true;">
  <div class="product-detail">
  <div class="product-preview">
  <div class="product-block">
  <div class="product-image">
  <span class="img-lazy-container">
  <img class="lazy" alt="Axioo Windroid 9G+ Tablet - Hitam [32GB/ 2GB]" title="Axioo Windroid 9G+ Tablet - Hitam [32GB/ 2GB]" data-original="https://www.static-src.com/wcsstore/Indraprastha/images/catalog/medium/axioo_axioo-windroid-9g---hitam-tablet_full03.jpg" width="195" height="195" src="https://www.static-src.com/wcsstore/Indraprastha/images/catalog/medium/axioo_axioo-windroid-9g---hitam-tablet_full03.jpg" style="display: inline-block;">
  </span>
  </div>
  <div class="product-title" title="Axioo Windroid 9G+ Tablet - Hitam [32GB/ 2GB]">
  Axioo Windroid 9G+ Tablet - Hitam [32GB/ 2GB]</div>
  <div class="product-price">
  <div class="new-price">
  <span class="new-price-text">Rp 2,899,000</span>
  </div>
  </div>
  <div class="product-status">
  <span class="sold">STOK HABIS</span><span class="cicilan-text">CICILAN 0%</span>
  </div>
  </div>
  </div>
  </div>
  </a>

 */

class LIPApi_Blibli_GetProducts_CategoryPage extends LIPApi_Parser {

    public function request_parser($request) {
        $this->url = carr::get($request, 'url');
    }

    public function response_parser($response) {
        $err_code = 0;
        $err_message = '';
        if (preg_match('#<title>Not Found</title>#ims', $response, $matches)) {
            throw new LIPApi_Exception('Category ' . $this->url . ' Blibli Return Not Found');
        }
        $data = array();
        preg_match_all('#<div class="product-detail-wrapper".+?<a class="single-product".+?</a>#ims', $response, $matches, PREG_SET_ORDER);
        if (count($matches) > 0) {
            foreach ($matches as $matches_k => $matches_v) {
                //cdbg::var_dump($matches_v);
                //die();
                $product_string = carr::get($matches_v, 0);
                $product = array();
                $product_id = '';
                $name = '';
                if (preg_match('#<div class="product-detail-wrapper".+?onclick="trackSingleCatalogProduct\(.+?\'(.+?)\',.+?\'(.+?)\',.+?\'(.+?)\',.+?\'(.+?)\',.+?\'(.+?)\',.+?\'(.+?)\',.+?\'(.+?)\'.+?\)"#ims', $product_string, $m)) {
                    $product_id = carr::get($m, 2);
                    $name = carr::get($m, 4);
                    $price = carr::get($m, 5);
                }


                $image_url = '';
                if (preg_match('#<img class="lazy".+?data-original="(.+?)".+?src="(.+?)"#ims', $product_string, $m)) {
                    $image_url = carr::get($m, 1);
                }
                $product['product_id'] = $product_id;
                $product['name'] = $name;
                $product['sell_price'] = $price;
                $product['sku'] = $product_id;
                $product['image_url'] = $image_url;
                $data[] = $product;
            }
        }

        return $data;
    }

}
