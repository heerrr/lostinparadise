<?php

    return array(
        array(
            "name" => "dashboard",
            "label" => "Dashboard",
            "controller" => "home",
            "method" => "index",
            "icon" => "home",
        ),
        array(
            "name" => "master_list",
            "label" => "Data",
            "icon" => "gear",
            "subnav" => include dirname(__FILE__) . "/nav/master" . EXT,
        ),
        array(
            "name" => "transaction",
            "label" => "Transaction",
            "icon" => "money",
            "subnav" => include dirname(__FILE__) . "/nav/transaction" . EXT,
        ),
        array(
            "name" => "setting_list",
            "label" => "Setting",
            "icon" => "gear",
            "subnav" => include dirname(__FILE__) . "/nav/setting" . EXT,
        ),
        
       
    );
    