<?php

defined('SYSPATH') OR die('No direct access allowed.');

class Roles_Controller extends LIPAdminController {

    public function index() {
        LIPAdmin::check_permission('role_list');
        $org_id = LIPAdmin::org_id();

        $app = CApp::instance();
        $app->title(clang::__("Roles"));

        $tree = CTreeDB::factory('roles');
        $db = CDatabase::instance();

        if (strlen($org_id) > 0) {
            $tree->set_org_id($org_id);
        }

        $role = $app->role();


        $widget = $app->add_widget()->set_title(clang::__("Roles"));
        $nestable = $widget->add_nestable();
        $widget->clear_both();
        $nestable->set_data_from_treedb($tree, $role->role_id)->set_id_key('role_id')->set_value_key('name')->set_input('data_order');


        $nestable->set_applyjs(false);
        $nestable->set_action_style('btn-dropdown');
        if (cnav::have_permission('edit_roles')) {
            $actedit = $nestable->add_row_action('edit');
            $actedit->set_label("")->set_icon("pencil")->set_link(curl::base() . "setting/roles/edit/{param1}")->set_label(" " . clang::__("Edit") . " " . clang::__("Roles"));
        }


        if (cnav::have_permission('delete_roles')) {
            $actedit = $nestable->add_row_action('delete');
            $actedit->set_label("")->set_icon("trash")->set_link(curl::base() . "setting/roles/delete/{param1}")->set_confirm(true)->set_label(" " . clang::__("Delete") . " " . clang::__("Roles"));
        }

        echo $app->render();
    }

    public function add() {
        LIPAdmin::check_permission('role_add');
        $this->edit();
    }

    public function edit($id = "") {
        if (strlen($id) > 0) {
            LIPAdmin::check_permission('edit_roles');
        }
        $app = CApp::instance();
        $title = clang::__("Edit") . " " . clang::__("Role");
        $icon = "pencil";
        if ($id == "") {
            $title = clang::__("Add") . " " . clang::__("Role");
            $icon = "plus";
        }
        $org_id = LIPAdmin::org_id();
        $tree = CTreeDB::factory('roles');
        $tree->set_org_id($org_id);
        $app->title($title);
        $post = $_POST;
        $db = CDatabase::instance();
        $is_add = 0;
        if (strlen($id) == 0) {
            $is_add = 1;
        }
        $role = $app->role();
        $user = $app->user();


        $action = $is_add == 0 ? "edit" : "add";
        $name = "";
        $description = "";
        $parent_id = "";
        if (strlen($id) > 0) {
            $q = "
				select 
					r.name
					,r.description
					,r.parent_id
				from 
					roles as r
				where 
                                    r.role_type='lip' and 
					r.role_id=" . $db->escape($id) . "
			";
            $result = $db->query($q);
            if ($result->count() > 0) {
                $row = $result[0];
                $name = $row->name;
                $description = $row->description;
                $parent_id = $row->parent_id;
            }
        }
        
        if ($post != null) {

            $error = 0;
            $error_message = "";
            try {
                $parent_id = $post["parent_id"];
                $name = $post["name"];
                $description = $post["description"];
                //checking
                if ($error == 0) {
                    if (strlen($name) == 0) {
                        $error_message = "Role name is required !";
                        $error++;
                    }
                }


                if ($error == 0) {
                    $qcheck = "select * from roles where name=" . $db->escape($name) . " and status>0 ";
                    if (strlen($org_id) > 0) {
                        $qcheck.="and org_id=" . $db->escape($org_id);
                    }

                    if ($is_add == 0)
                        $qcheck .= " and name<>" . $db->escape($name) . "";
                    $rcheck = $db->query($qcheck);
                    if ($rcheck->count() > 0) {
                        $error_message = "Role name is already exist, please try another name !";
                        $error++;
                    }
                }

                $parent = crole::get($parent_id);
                $role_level = 0;
                if ($parent != null) {
                    $role_level = $parent->role_level + 1;
                }

                if (strlen($org_id) == 0) {
                    $org_id = null;
                }
                if ($error == 0) {
                    $data = array(
                        "org_id" => $org_id,
                        "parent_id" => $parent_id,
                        "role_level" => $role_level,
                        "name" => $name,
                        "description" => $description,
                    );
                    if (strlen($id) == 0) {
                        $data = array_merge($data, array(
                            "role_type" => 'lip',
                            "created" => date("Y-m-d H:i:s"),
                            "createdby" => $user->username,
                            "updated" => date("Y-m-d H:i:s"),
                            "updatedby" => $user->username,
                        ));
                        //$db->insert("roles", $data);
                        $tree->insert($data, $parent_id);
                    } else {
                        $data = array_merge($data, array(
                            "updated" => date("Y-m-d H:i:s"),
                            "updatedby" => $user->username
                        ));
                        //$db->update("roles", $data,array("role_id" => $id));
                        $tree->update($id, $data, $parent_id);
                    }
                }
            } catch (Exception $e) {
                $error++;
                $error_message = "Error, call administrator..." . $e->getMessage();
                ;
            }
            if ($error == 0) {
                if ($id > 0) {
                    cmsg::add("success", clang::__("Role") . " [" . $name . "] " . clang::__("Successfully Modified") . " !");
                    clog::activity($user->user_id, 'edit', clang::__("Role") . " [" . $name . "] " . clang::__("Successfully Modified") . " !");
                    curl::redirect("setting/roles");
                } else {
                    cmsg::add("success", clang::__("Role") . " [" . $name . "] " . clang::__("Successfully Added") . " !");
                    clog::activity($user->user_id, 'add', clang::__("Role") . " [" . $name . "] " . clang::__("Successfully Added") . " !");
                    curl::redirect("setting/roles/add");
                }
            } else {
                cmsg::add("error", $error_message);
            }
        }

        $html = '';

        $widget = $app->add_widget()->set_icon($icon)->set_title($title);


        $form = $widget->add_form();

        $parent_list[$role->role_id] = $role->name;
        $child_list = $app->get_role_child_list();
        foreach ($child_list as $k => $v) {
            $child_list[$k] = "&nbsp;&nbsp;&nbsp;&nbsp;" . $v;
        }
        $parent_list = $parent_list + $child_list;
        //if edit
        if (strlen($id) > 0) {
            //remove all list where parent_id is child of current roles or current id
            $child_list = $app->get_role_child_list($id);
            foreach ($parent_list as $k => $v) {
                if (array_key_exists($k, $child_list) || $k == $id) {
                    unset($parent_list[$k]);
                }
            }
        }


        $form->add_field()->set_label('Parent')->add_control('parent_id', 'select')->add_validation('required')->set_value($parent_id)->set_list($parent_list);
        $form->add_field()->set_label('<span style="color: red;">*</span> ' . 'Name')->add_control('name', 'text')->add_validation('required')->set_value($name);
        $form->add_field()->set_label('Description')->add_control('description', 'textarea')->add_validation(null)->set_value($description);
        $form->add_control('id', 'hidden')->add_validation(null)->set_value($id);
        $actions = $form->add_action_list();
        $actions->set_style('form-action');


        $act_next = $actions->add_action()->set_label('Submit')->set_submit(true)->set_confirm(true);






        echo $app->render();
    }

    public function delete($id = "") {
        if (!cnav::have_permission('delete_roles')) {
            cmsg::add('error', clang::__('You do not have access to this module') . ', ' . clang::__("call administrator"));
            curl::redirect('home');
        }
        if (strlen($id) == 0) {
            curl::redirect('roles');
        }
        $app = CApp::instance();
        $session = Session::instance();
        $db = CDatabase::instance();
        $error = 0;
        $role = crole::get($id);
        $user = $app->user();
        $roleapp = $app->role();
        $tree = CTreeDB::factory('roles');
        $i = crole::get($id);
        if ($error == 0) {
            if ($roleapp->role_id == $role->role_id) {
                $error++;
                $error_message = clang::__("Fail on delete, you can't delete your own role...");
            }
        }
        if ($error == 0) {
            if ($role->is_base == 1) {
                $error++;
                $error_message = clang::__("Fail on delete, data is required by system...");
            }
        }
        if ($error == 0) {
            $user_count = cdbutils::get_value("select count(*) as cnt from users where status>0 and role_id=" . $db->escape($id));
            if ($user_count > 0) {
                $error++;
                $error_message = clang::__("Fail on delete, role have user");
            }
        }
        if ($error == 0) {
            try {
                $tree->delete($id);
                //$db->update("roles",array("status"=>"0","updated"=>date("Y-m-d H:i:s"),"updatedby"=>$app->user()->username),array("role_id" => $id));
                //$db->delete("roles", array("role_id" => $id));
            } catch (Exception $e) {
                $error++;
                $error_message = clang::__("Fail on delete, please call the administrator...");
            }
        }

        if ($error == 0) {
            cmsg::add('success', "Role [" . $role->name . "] Successfully Deleted !");
            clog::activity($user->user_id, 'delete', clang::__("Role") . " [" . $i->name . "] " . clang::__("Successfully Deleted") . " !");
        } else {
            //proses gagal
            cmsg::add('error', $error_message);
        }
        curl::redirect('setting/roles');
    }

    private function update_recursive($data, $parent_id) {
        $db = CDatabase::instance();
        $app = CApp::instance();

        $user = $app->user();
        $org_id = null;

        foreach ($data as $d) {
            $id = $d['id'];

            $data_updated['parent_id'] = $parent_id;

            $db->update('roles', $data_updated, array('role_id' => $id));
            $children = array();
            if (isset($d['children'])) {
                $this->update_recursive($d['children'], $id);
            }
        }
    }

    public function ordering() {
        $app = CApp::instance();
        $app->title(clang::__("Roles"));
        $app->add_breadcrumb(clang::__("Roles"), curl::base() . "roles");
        $user = $app->user();
        $role = $app->role();
        $app_role_id = $role->role_id;

        $org_id = null;
        $db = CDatabase::instance();
        $tree = CTreeDB::factory('roles');
        $post = $_POST;
        if ($post != null) {
            $error = 0;
            $data = $post['data_order'];
            $data = cjson::decode($data);
            try {
                $db->begin();
                $this->update_recursive($data, $app_role_id);
                $q = "select * from roles where parent_id is null ";
                if (strlen($org_id) > 0) {
                    $q.=" and org_id=" . $db->escape($org_id) . " ";
                }

                $r = $db->query($q);
                $left = 1;
                foreach ($r as $row) {
                    $tree->rebuild_tree($row->role_id, $left);
                    $left = cdbutils::get_value('select rgt from roles where role_id=' . $db->escape($row->role_id)) + 1;
                }
            } catch (Exception $e) {
                $error++;
                $error_message = clang::__("system_update_fail") . $e->getMessage();
            }
            if ($error == 0) {
                $db->commit();
                cmsg::add('success', clang::__("Role Order") . clang::__(" ") . clang::__("Successfully Modified") . " !");
                clog::activity($user->user_id, 'edit', clang::__("Role Order") . clang::__(" ") . clang::__("Successfully Modified") . " !");
                curl::redirect('roles');
            } else {
                //proses gagal
                $db->rollback();
                cmsg::add('error', $error_message);
            }
        }

        $widget = $app->add_widget()->set_title(clang::__('Change Order'));
        $nestable = $widget->add_nestable();
        $widget->clear_both();
        $nestable->set_data_from_treedb($tree, $role->role_id)->set_id_key('role_id')->set_value_key('name')->set_input('data_order');

        $form = $widget->add_form();
        $form->add_control('data_order', 'hidden')->set_value('');
        $actions = $form->add_action_list()->set_style('form-action');
        $actions->add_action()->set_label('Submit')->set_submit(true);
        echo $app->render();
    }

}
