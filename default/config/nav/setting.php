<?php

return array(
    array(
        "name" => "access",
        "label" => "Access",
        "subnav" => array(
            array(
                "name" => "roles_menu",
                "label" => "Roles",
                "subnav" => array(
                    array(
                        "name" => "role_add",
                        "label" => "Add Role",
                        "controller" => "setting_users/roles",
                        "method" => "add",
                    ),
                    array(
                        "name" => "role_list",
                        "label" => "Role List",
                        "controller" => "setting_users/roles",
                        "method" => "index",
                        'action' => array(
                            array(
                                'name' => 'edit_roles',
                                'label' => 'Edit',
                                'controller' => 'setting_users/roles',
                                'method' => 'edit',
                            ),
                            array(
                                'name' => 'delete_roles',
                                'label' => 'Delete',
                                'controller' => 'setting_users/roles',
                                'method' => 'delete',
                            ),
                        ), //end action roles
                    ),
                ),
            ),
            array(
                "name" => "users",
                "label" => "Users",
                "subnav" => array(
                    array(
                        "name" => "user_add",
                        "label" => "Add User",
                        "controller" => "setting_users/users",
                        "method" => "add",
                    ),
                    array(
                        "name" => "user_list",
                        "label" => "User List",
                        "controller" => "setting_users/users",
                        "method" => "index",
                        'action' => array(
                            array(
                                'name' => 'edit_users',
                                'label' => 'Edit',
                                'controller' => 'setting_users/users',
                                'method' => 'edit',
                            ),
                            array(
                                'name' => 'delete_users',
                                'label' => 'Delete',
                                'controller' => 'setting_users/users',
                                'method' => 'delete',
                            ),
                        ), //end action users
                    ),
                ),
            ),
            array(
                "name" => "user_permission",
                "label" => "Users Permission",
                "controller" => "setting_users/user_permission",
                "method" => "index",
            ),
            array(
                "name" => "user_dashboard",
                "label" => "Users Dashboard",
                "controller" => "setting_users/user_permission_dashboard",
                "method" => "index",
            ),
        ), //end subnav access
    ),
    array(
        "name" => "merchant_setting",
        "label" => "Pengaturan",
        "controller" => "setting/merchant_setting",
        "method" => "index",
    ),
    array(
        "name" => "org_bank",
        "label" => 'Bank',
        "subnav" => array(
            array(
                "name" => "add_org_bank",
                "label" => 'Tambah Bank',
                "controller" => "setting/org_bank",
                "method" => "add",
            ),
            array(
                "name" => "list_org_bank",
                "label" => 'Rincian Bank',
                "controller" => "setting/org_bank",
                "method" => "index",
                'action' => array(
                    array(
                        'name' => 'edit_org_bank',
                        'label' => 'Ubah',
                        'controller' => 'setting/org_bank',
                        'method' => 'edit',
                    ),
                    array(
                        'name' => 'delete_org_bank',
                        'label' => 'Hapus',
                        'controller' => 'setting/org_bank',
                        'method' => 'delete',
                    ),
                ), //end action 
            ),
        ),
    ),
    
);
