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
                        "controller" => "setting/roles",
                        "method" => "add",
                    ),
                    array(
                        "name" => "role_list",
                        "label" => "Role List",
                        "controller" => "setting/roles",
                        "method" => "index",
                        'action' => array(
                            array(
                                'name' => 'edit_roles',
                                'label' => 'Edit',
                                'controller' => 'setting/roles',
                                'method' => 'edit',
                            ),
                            array(
                                'name' => 'delete_roles',
                                'label' => 'Delete',
                                'controller' => 'setting/roles',
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
                        "controller" => "setting/users",
                        "method" => "add",
                    ),
                    array(
                        "name" => "user_list",
                        "label" => "User List",
                        "controller" => "setting/users",
                        "method" => "index",
                        'action' => array(
                            array(
                                'name' => 'edit_users',
                                'label' => 'Edit',
                                'controller' => 'setting/users',
                                'method' => 'edit',
                            ),
                            array(
                                'name' => 'delete_users',
                                'label' => 'Delete',
                                'controller' => 'setting/users',
                                'method' => 'delete',
                            ),
                        ), //end action users
                    ),
                ),
            ),
            array(
                "name" => "user_permission",
                "label" => "Users Permission",
                "controller" => "setting/user_permission",
                "method" => "index",
            ),
            
        ), //end subnav access
    ),
    array(
        "name" => "app_setting",
        "label" => "Pengaturan",
        "controller" => "setting/app_setting",
        "method" => "index",
    ),
    
    
);
