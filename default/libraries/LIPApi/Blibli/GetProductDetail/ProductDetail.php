<?php

class LIPApi_Blibli_GetProductDetail_ProductDetail extends LIPApi_Parser {

    public function request_parser($request) {
        $product_id = carr::get($request, 'product_id');
        $this->url = 'https://www.blibli.com/x-' . $product_id . '.htm';
    }

    public function response_parser($response) {
        $err_code = 0;
        $err_message = '';
        if (preg_match('#<title>Not Found</title>#ims', $response, $matches)) {
            throw new LIPApi_Exception('Category ' . $this->url . ' Blibli Return Not Found');
        }
        $data = array();
        if (preg_match('#<h1 class="product-name">(.*?)</h1>'
                        . '.+?<img id="mainProductImage" src="(.+?)"'
                        . '.+?columns product-usp">(.+?)</div>'
                        . '.+?<div class="product-desc product-rich-info" id="productinfo">(.+?)</div>'
                        . '.+?<div class="product-spec hide-display product-rich-info" id="productdetail">(.+?)<div class="product-review hide-display product-rich-info"'
                        . '#ims', $response, $matches)) {

            $sku = '';
            $code = '';
            $price = '';
            $offer_price = '';

            if (preg_match('#"sku":"(.+?)"#ims', $response, $match_sku)) {
                $sku = !empty($match_sku) ? $match_sku[1] : '';
            }
            if (preg_match('#"productCode":"(.+?)"#ims', $response, $match_code)) {
                $code = !empty($match_code) ? $match_code[1] : '';
            }
            if (preg_match('#"listPrice":(.+?)"#ims', $response, $match_price)) {
                $price = !empty($match_price) ? str_replace(',', '', $match_price[1]) : 0;
            }
            if (preg_match('#"offerPrice":(.+?)"#ims', $response, $match_offer)) {
                $offer_price = !empty($match_offer) ? str_replace(',', '', $match_offer[1]) : 0;
            }



            $name = trim($matches[1]);

            $description = trim($matches[3]);
            $detail = trim(strip_tags($matches[4]));
            $specification = trim($matches[5]);
            $image_file = trim($matches[2]);

            $data = array(
                'sku' => $sku,
                'code' => $code,
                'name' => $name,
                'sell_price' => $offer_price,
                'sell_price_before' => $price,
                'description' => $description,
                'detail' => $detail,
                'specification' => $specification,
                'image_file' => $image_file,
            );
        }


        return $data;
    }

}
