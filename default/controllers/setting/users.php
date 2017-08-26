<?php

defined('SYSPATH') OR die('No direct access allowed.');

class Users_Controller extends LIPAdminController {

    public function index() {
        LIPAdmin::check_permission('user_list');
        $org_id = LIPAdmin::org_id();

        $app = CApp::instance();
        $app->title((ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label')));
        $org = $app->org();
        $user = $app->user();
        $form = $app->add_form();

        $form->set_ajax_submit(true)->set_ajax_submit_target('table-container')->set_action(curl::base() . "setting/users/table");
        $form->add_listener('ready')->add_handler('submit');
        $app->add_div('table-container');
        echo $app->render();
    }

    public function table() {
        $app = CApp::instance();
        $role = $app->role();
        $db = CDatabase::instance();

        $user = $app->user();
        $org_id = LIPAdmin::org_id();

        $request = array_merge($_GET, $_POST);
        $q = "select u.user_id,o.name as store_name,r.role_id
            ,r.name as role_name,u.username,u.description,u.updated,u.updatedby
                from 
                users u 
                inner join roles as r on u.role_id=r.role_id 
                left join org as o on o.org_id=u.org_id 
                where r.role_type='lip' and u.status>0 and r.depth>" . $role->depth;

        $q.= ' ORDER BY u.updated desc ';


        $table = $app->add_table('users_table');
        $table->add_column('store_name')->set_label(clang::__('Nama Merchant'));
        $table->add_column('role_name')->set_label(clang::__('Nama Role'));
        $table->add_column('username')->set_label(clang::__('Username'));
        $table->add_column('description')->set_label(clang::__('Keterangan'));
        $table->add_column('updated')->set_label(clang::__('Updated'))->set_editable(false)->add_transform('format_long_date');
        $table->add_column('updatedby')->set_label(clang::__('Updated By'))->set_editable(false);


        $table->set_data_from_query($q)->set_key('user_id');
        //$table->add_row_action('edit')->set_label('Edit')->set_icon('pencil');
        //$table->add_row_action('delete')->set_icon('trash');
        $table->set_action_style("btn-dropdown");
        $table->set_title((ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label')));

        $table->set_ajax(true);

//            if (cnav::have_permission('detail_users')) {
//                $actedit = $table->add_row_action();
//                $actedit->set_label("")->set_icon("search")->set_label("Detail " . (ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label')));
//                $actedit->add_listener('click')->add_handler('dialog')->set_url(curl::base() . "users/detail/{param1}");
//            }
        if (cnav::have_permission('edit_users')) {
            $actedit = $table->add_row_action('edit');
            $actedit->set_label("")->set_icon("pencil")->set_link(curl::base() . "setting/users/edit/{param1}")->set_label(clang::__("Edit " . (ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label'))));
        }
        if (cnav::have_permission('delete_users')) {
            $actedit = $table->add_row_action('delete');
            $actedit->set_label("")->set_icon("trash")->set_link(curl::base() . "setting/users/delete/{param1}")->set_confirm(true)->set_label(clang::__("Delete " . (ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label'))));
        }
        echo $app->render();
    }

    public function add() {
        LIPAdmin::check_permission('user_add');
        $this->edit();
    }

    public function edit($id = "") {
        if (strlen($id) > 0) {
            LIPAdmin::check_permission('edit_users');
        }
        $org_id = LIPAdmin::org_id();
        $app = CApp::instance();
        $title = clang::__("Edit") . " " . clang::__((ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label')));
        $icon = "pencil";
        if (strlen($id) == 0) {
            $title = clang::__("Add") . " " . clang::__((ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label')));
            $icon = "plus";
        }
        $app->title($title);
        $app->add_breadcrumb(clang::__((ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label'))), curl::base() . "setting/users");
        $session = Session::instance();
        $post = $_POST;
        $db = CDatabase::instance();
        $is_add = 0;
        if (strlen($id) == 0) {
            $is_add = 1;
        }
        $user = $app->user();

        $role = $app->role();
        $action = $is_add == 0 ? "edit" : "add";
        $username = "";
        $description = "";
        $role_id = "";
        $is_disabled = 0;
        $user_employee_id = "";
        $have_notification = "0";
        $vendor_id = NULL;

        $store_id = array();

        if (strlen($id) > 0) {
            $q = "select " .
                    " v.org_id" .
                    " ,v.username" .
                    " ,v.role_id" .
                    " ,v.password" .
                    " ,v.description" .
                    " ,v.vendor_id" .
                    " " .
                    " from users v where v.user_id = " . $db->escape($id);
            $result = cdbutils::get_row($q);
            if ($result != null) {
                //$row = $result[0];
                $org_id = $result->org_id;
                $username = $result->username;
                $role_id = $result->role_id;
                $password = $result->password;
                $description = $result->description;
                $vendor_id = $result->vendor_id;
                //die($employee_id);
            }
        }

        if ($post != null) {
            $error = 0;
            $error_message = "";
            try {
                $username = carr::get($post, 'username');
                $password = carr::get($post, 'password');
                $role_id = carr::get($post, 'role_id');
                $vendor_id = carr::get($post, 'vendor_id');
                $description = carr::get($post, 'description');

                if (strlen($org_id) == 0) {
                    $org_id = carr::get($post, 'org_id');
                }



                //checking
                if ($error == 0) {
                    if (strlen($username) == 0) {
                        $error_message = "Username is required !";
                        $error++;
                    }
                }
                if ($error == 0) {
                    if ($is_add == 1) {
                        if (strlen($password) == 0) {
                            $error_message = "Password is required !";
                            $error++;
                        }
                    }
                }
                if ($error == 0) {

                    $qcheck = "select * from users where username=" . $db->escape($username) . " and status>0 ";
                    if ($is_add == 0)
                        $qcheck .= " and user_id<>" . $db->escape($id) . "";

                    if (strlen($org_id) > 0) {
                        $qcheck .= " and org_id=" . $db->escape($org_id) . "";
                    }
                    $rcheck = $db->query($qcheck);
                    if ($rcheck->count() > 0) {
                        $error_message = clang::__("Username is already exist, please try another name !");
                        $error++;
                    }
                }

                if ($error == 0) {
                    $data = array(
                        "username" => $username,
                        "role_id" => $role_id,
                        "description" => $description,
                    );
                    if (strlen($org_id) > 0) {
                        $data['org_id'] = $org_id;
                    }
                    if (strlen($vendor_id) > 0) {
                        $data['vendor_id'] = $vendor_id;
                    }
                    if (strlen($password) > 0) {
                        $data = array_merge($data, array(
                            "password" => md5($password)
                        ));
                    }
                    if (strlen($id) == 0) {
                        $data = array_merge($data, array(
                            "created" => date("Y-m-d H:i:s"),
                            "createdby" => $user->username,
                            "updated" => date("Y-m-d H:i:s"),
                            "updatedby" => $user->username,
                        ));
                        $r = $db->insert("users", $data);
                        $user_id = $r->insert_id();
                    } else {
                        $data = array_merge($data, array(
                            "updated" => date("Y-m-d H:i:s"),
                            "updatedby" => $user->username
                        ));
                        $db->update("users", $data, array("user_id" => $id));
                        $user_id = $id;
                    }
                }
                if ($error == 0) {
                    
                }
            } catch (Exception $e) {
                $error++;
                $error_message = "Error, call administrator..." . $e->getMessage();
            }
            if ($error == 0) {
                if ($id > 0) {
                    cmsg::add("success", clang::__((ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label'))) . ' [' . $username . '] ' . clang::__("Successfully Modified !"));
                    clog::activity($user->user_id, 'edit', clang::__((ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label'))) . " [" . $username . "] " . clang::__("Successfully Modified") . " !");
                    curl::redirect("setting/users");
                } else {
                    cmsg::add("success", clang::__((ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label'))) . ' [' . $username . '] ' . clang::__("Successfully Added !"));
                    clog::activity($user->user_id, 'add', clang::__((ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label'))) . " [" . $username . "] " . clang::__("Successfully Modified") . " !");
                    curl::redirect("setting/users/add");
                }
            } else {
                cmsg::add("error", $error_message);
            }
        }

        $html = '';


        $widget = $app->add_widget();


        $widget->set_title($title)->set_icon($icon);
        //$role_list = cdbutils::get_list("select role_id as k,concat(name) as v from roles where org_id=".$db->escape($org_id)."order by role_id asc,name asc;");
        $role_list = $app->get_role_child_list();
        $form = $widget->add_form();
        $form
                ->add_field()
                ->set_label('<span style="color: red;">*</span> ' . clang::__("Org"))
                ->add_control("org_id", "org-select")
                ->set_value($org_id);


        $form->add_field()->set_label(clang::__('Role'))->add_control('role_id', 'select')->add_validation(null)->set_value($role_id)->set_list($role_list);
        $form->add_field()->set_label(clang::__('Username'))->add_control('username', 'text')->add_validation('required')->set_value($username);
        $form->add_field()->set_label(clang::__('Password'))->add_control('password', 'password')->add_validation(null)->set_value('');

        $employee_list = array();

        $class_list = array();

        /*
          if(torsb2b::is_ho()) {
          $control->set_all(true);
          }
         */
        $form->add_field('description-field')->set_label('Description')->add_control('description', 'textarea')->add_validation(null)->set_value($description);



        $form->add_control('id', 'hidden')->add_validation(null)->set_value($id);
        $actions = $form->add_action_list();
        $actions->set_style('form-action');
        $act = $actions->add_action();
        $act->set_label('Submit')->set_submit(true)->set_confirm(true);
        echo $app->render();
    }

    public function detail($user_id, $method = "") {
        if ($user_id == "tab")
            return $this->tab($method);


        $app = CApp::instance();
        $app->title(clang::__("Detail " . (ccfg::get('user_label') == NULL ? 'User' : ccfg::get('user_label'))));

        $tabs = $app->add_tab_list();
        $tab = $tabs->add_tab('info')->set_label(clang::__('Info'))->set_ajax_url(curl::base() . 'users/tab_info/' . $user_id);
        echo $app->render();
    }

    public function tab_info($user_id) {
        $app = CApp::instance();
        $app->title(clang::__("User Detail"));
        $db = CDatabase::instance();
        $user = cdbutils::get_row("select u.username, r.name as rolename, u.last_login, u.email, u.created from users u inner join roles r on r.role_id=u.role_id where u.user_id=" . $db->escape($user_id));
        $form = $app->add_form();
        $div_row = $form->add_div()->add_class('row-fluid');
        $col1 = $div_row->add_div()->add_class('span6');
        $col2 = $div_row->add_div()->add_class('span6');

        $col1->add_field()->set_label('Username')->add_control('username', 'label')->set_value($user->username);
        $col1->add_field()->set_label('email')->add_control('email', 'label')->set_value($user->email);

        $col2->add_field()->set_label('Role')->add_control('role', 'label')->set_value($user->rolename);
        $col2->add_field()->set_label('Last_login')->add_control('last_login', 'label')->set_value(ctransform::format_datetime($user->last_login));

        echo $app->render();
    }

    public function activity($user_id) {
        $app = CApp::instance();
        $org = $app->org();
        $role = $app->role();
        csess::refresh_user_session();
        $db = CDatabase::instance();

        $form = $app->add_form();
        $widget = $form->add_widget();
        $widget = $app->add_widget()->set_nopadding(true)->set_title(clang::__('My Last Activity'));
        $table = $widget->add_table();
        $table->set_title('My Last Activity');
        $q = "select * from log_activity order by activity_date desc limit 10 where user_id=" . $user_id;
        $table->set_data_from_query($q);
        $table->add_column('activity_date')->set_label("Activity Date");
        $table->add_column('description')->set_label("Description");
        $table->set_apply_data_table(false);
    }

    public function delete($id = "") {
        if (!cnav::have_permission('delete_users')) {
            cmsg::add('error', clang::__('You do not have access to this module') . ', ' . clang::__("call administrator"));
            curl::redirect('home');
        }
        if (strlen($id) == 0) {
            curl::redirect('users');
        }
        $app = CApp::instance();
        $user = $app->user();
        $session = Session::instance();
        $db = CDatabase::instance();
        $q = '';
        $error = 0;

        $user = cuser::get($id);
        $userapp = $app->user();
        if ($error == 0) {
            if ($userapp->user_id == $user->user_id) {
                $error++;
                $error_message = "Fail on delete, you can't delete your own account...";
            }
        }
        if ($error == 0) {
            if ($user->is_base == 1) {
                $error++;
                $error_message = "Fail on delete, data is required by system...";
            }
        }
        if ($error == 0) {
            try {
                $db->begin();
                $db->update("users", array("status" => 0, "updated" => date("Y-m-d H:i:s"), "updatedby" => $userapp->username), array("user_id" => $id));
            } catch (Exception $e) {
                $db->rollback();
                $error++;
                $error_message = "Fail on delete, please call the administrator...";
            }
        }

        if ($error == 0) {
            $db->commit();
            cmsg::add('success', clang::__("User") . ' - ' . $user->username . ' - ' . clang::__("Successfully Deleted"));
        } else {
            //proses gagal
            cmsg::add('error', $error_message);
        }
        curl::redirect('setting/users');
    }

}
