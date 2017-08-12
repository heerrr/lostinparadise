<?php

return array(
    array(
        "name" => "transaction_order",
        "label" => "Transaksi",
        "controller" => "transaction/order",
        "method" => "index",
    ),
    array(
        "name" => "transaction_shipping",
        "label" => 'Shipping',
        "subnav" => array(
            array(
                "name" => "shipping_start",
                "label" => 'Start Pengiriman',
                "controller" => "transaction/shipping_deliver",
                "method" => "index",
                "action" => array(
                    array(
                        "name" => "shipping_start_printout",
                        "label" => 'Print Out',
                    ),
                ),
            ),
            array(
                "name" => "shipping_finish",
                "label" => 'Pengiriman Terkirim',
                "controller" => "transaction/shipping_delivered",
                "method" => "index",
                "action" => array(
                ),
            ),
        ),
    ),
);
