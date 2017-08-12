<?php

return array(
    array(
        "name" => "product_category",
        "label" => 'Kategori Produk',
        "subnav" => array(
            array(
                "name" => "add_product_category",
                "label" => 'Tambah Kategori Produk',
                "controller" => "master/product_category",
                "method" => "add",
            ),
            array(
                "name" => "change_order_product_category",
                "label" => 'Ganti Urutan Kategori Produk',
                "controller" => "master/product_category",
                "method" => "change_order",
            ),
            array(
                "name" => "product_category_list",
                "label" => 'Rincian Kategori Produk',
                "controller" => "master/product_category",
                "method" => "index",
                'action' => array(
                    array(
                        'name' => 'edit_product_category',
                        'label' => 'Ubah',
                        'controller' => 'master/product_category',
                        'method' => 'edit',
                    ),
                    array(
                        'name' => 'delete_product_category',
                        'label' => 'Hapus',
                        'controller' => 'master/product_category',
                        'method' => 'delete',
                    ),
                ), //end action
            ),
        ),
    ),
    array(
        "name" => "product",
        "label" => 'Produk',
        "subnav" => array(
            array(
                "name" => "add_product",
                "label" => 'Tambah Produk',
                "controller" => "master/product",
                "method" => "add",
            ),
            array(
                "name" => "product_list",
                "label" => 'Rincian Produk',
                "controller" => "master/product",
                "method" => "index",
                'action' => array(
                    array(
                        'name' => 'edit_product',
                        'label' => 'Ubah',
                        'controller' => 'master/product',
                        'method' => 'edit',
                    ),
                    array(
                        'name' => 'delete_product',
                        'label' => 'Hapus',
                        'controller' => 'master/product',
                        'method' => 'delete',
                    ),
                    array(
                        "name" => "rincian_product",
                        "label" => 'Rincian Produk',
                        "controller" => "master/product",
                        "method" => "detail_group",
                    ),
                    array(
                        "name" => "activenon_product",
                        "label" => 'Aktif & Nonaktif Produk',
                        "controller" => "master/product",
                        "method" => "activenon",
                    ),
                ), //end action
            ),
        ),
    ),
    
);
