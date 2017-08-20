<?php

defined('SYSPATH') OR die('No direct access allowed.');

class User_permission_Controller extends LIPAdminController {

    public static function cell_callback($table, $col, $row, $text) {
        $db = CDatabase::instance();
        $is_leaf = cnav::is_leaf($row);
        $level = $row["level"];
        $app = CApp::instance();
        $role = $app->role();
        //print_r($row['a']);
        switch ($col) {
            case "label":
                $indent = str_repeat("&nbsp;", ($level) * 4);
                return $indent . clang::__($text);
                break;
            case "access":

                if (!isset($row["controller"]) || $row["controller"] == "")
                    return "";
                $checked = "";
                $role_id = $row["role_id"];

                if (cnav::have_access($row, $role_id, $row["app_id"])) {
                    $checked = ' checked="checked"';
                }

                $html = '';
                $html .= '<input type="checkbox" name="cb_' . $row['name'] . '" id="cb_' . $row['name'] . '" class="input-unstyled cb-access" value="' . $row['name'] . '"' . $checked . '>';

                return $html;

                break;

            case "permission":
                $html = '';
                if (isset($row["action"])) {
                    $html .= '<div class="btn-group" >';
                    foreach ($row["action"] as $act) {
                        if (!cnav::permission_available($act["name"], $row))
                            continue;
                        $active = "";
                        //check this user permission
                        if (!cnav::have_permission($act["name"], $row, $role->role_id, $row["app_id"])) {
                            continue;
                        }
                        if (cnav::have_permission($act["name"], $row, $row["role_id"], $row["app_id"])) {
                            $active = ' active';
                        }
                         if (cnav::have_permission($act["name"])) {
                            $html.='<button class="btn btn-success btn-permission btn-' . $row['name'] . '' . $active . '" name="' . $act['name'] . '" type="button">';
                            $html.='' . $act['label'] . '';
                            $html.='</button>';
                             
                         }
                    }
                    $html .='</div>';
                }
                return $html;
                break;
        }
        return $text;
    }

    public function save() {
        $app = CApp::instance();
        $org = $app->org();
        $user = $app->user();
        $org_id = LIPAdmin::org_id();
        $error = 0;
        $error_message = "";
        $result = array();
        $post = $_POST;
        $db = CDatabase::instance();

        if ($post != null) {

            try {
                $db->begin();
                $app_id = 1;
                if (isset($post["app_id"])) {
                    $app_id = $post["app_id"];
                }
                $role_id = $post["role_id"];
                if ($role_id == "PUBLIC")
                    $role_id = null;
                if (!isset($post['access'])) {
                    $error++;
                    $error_message = clang::__('Please select minimal one access for this role');
                }
                if ($error == 0) {
                    if ($role_id == null) {
                        $db->query("delete from role_permission where role_id is null and app_id=" . $db->escape($app_id));
                        $db->query("delete from role_nav where role_id is null and app_id=" . $db->escape($app_id));
                    } else {
                        $db->delete("role_permission", array("role_id" => $role_id, "app_id" => $app_id));
                        $db->delete("role_nav", array("role_id" => $role_id, "app_id" => $app_id));
                    }
                    $access = $post["access"];

                    foreach ($access as $a) {
                        $nav = $a["name"];
                        $data = array(
                            'role_id' => $role_id,
                            'app_id' => $app_id,
                            'org_id' => $org_id,
                            'nav' => $nav,
                            'created' => date('Y-m-d H:i:s'),
                            'createdby' => $user->username,
                        );
                        $r = $db->insert('role_nav', $data);
                        $data = array(
                            'role_id' => $role_id,
                            'app_id' => $app_id,
                            'org_id' => $org_id,
                            'nav' => $nav,
                            'name' => $nav,
                            'created' => date('Y-m-d H:i:s'),
                            'createdby' => $user->username,
                        );
                        $r = $db->insert('role_permission', $data);
                        if (isset($a['action'])) {
                            $action = $a["action"];
                            foreach ($action as $act) {
                                $data = array(
                                    'role_id' => $role_id,
                                    'app_id' => $app_id,
                                    'org_id' => $org_id,
                                    'nav' => $nav,
                                    'name' => $act,
                                    'created' => date('Y-m-d H:i:s'),
                                    'createdby' => $user->username,
                                );
                                $r = $db->insert('role_permission', $data);
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $error++;
                $error_message = clang::__("Error, call administrator") . $e->getMessage();
            }
            if ($error == 0) {
                $db->commit();
                $result["result"] = "1";
                $result["message"] = clang::__("User Permission") . " " . clang::__("Successfully Modified") . " !";
            } else {
                $db->rollback();
                $result["result"] = "0";
                $result["message"] = $error_message;
            }
            echo json_encode($result);
        }
    }

    public function index() {
        if (!cnav::have_permission('user_permission')) {
            cmsg::add('error', clang::__('You do not have access to this module') . ', ' . clang::__("call administrator"));
            curl::redirect('home');
        }
        $app = CApp::instance();
        $error = 0;

        $app->title(clang::__('User Permission'));
        $post = $_POST;
        $db = CDatabase::instance();
        $user = $app->user();
        $org = $app->org();
        $org_id = 0;

        if ($org != null) {
            $org_id = $org->org_id;
        }
        $app_id = "";
        $role_id = "";
        $user = $app->user();
        $domain = CF::domain();
        

        $html = '';
        //$app_list = array('204'=>'TMS');
        $app_list = array('856' => 'ADMIN INDOBILD');
        $app_id = "";
        if (isset($_GET["app_id"])) {
            $app_id = $_GET["app_id"];
        }
        if (strlen($app_id) == 0) {
            if (isset($_POST["app_id"])) {
                $app_id = $_POST["app_id"];
            }
        }
        if (strlen($app_id) == 0) {
            foreach ($app_list as $k => $v) {
                $app_id = $k;
                break;
            }
        }
        $role_list = array();
        if (ccfg::get("have_public")) {
            $role_list = array("PUBLIC" => "PUBLIC") + $role_list;
        }
        $role_list = $this->get_role_child_list();
        if (isset($_GET['debug'])) {
            cdbg::var_dump($role_list);exit;
        }
        //$role_list = cdbutils::get_list("select a.role_id,a.name as name from roles a where status>0 and org_id=".$db->escape($org_id)." order by a.role_id asc;");

        $role_id = "";
        if (isset($_GET["role_id"])) {
            $role_id = $_GET["role_id"];
        }
        if (strlen($role_id) == 0) {
            if (isset($_POST["role_id"])) {
                $role_id = $_POST["role_id"];
            }
        }
        if (strlen($role_id) == 0) {
            foreach ($role_list as $k => $v) {
                $role_id = $k;
                break;
            }
        }
        
        


        $form = $app->add_div()->add_class("row-fluid")->add_div()->add_class("span12")->add_widget()->set_title(clang::__('User Permission'))->set_icon('user')->add_form('user_filter_form')->set_method('get');
        
        $form->add_control('app_id', 'hidden')->set_value($app_id);
        $form->add_field('role-field')->set_label(clang::__("Role"))->add_control('role_id', 'select')->set_value($role_id)->set_applyjs('select2')->set_list($role_list)->add_validation(null);
        $form->add_field('check-all-field')->set_label(clang::__("Check All"))->add_control('check_all', 'checkbox')->set_value("1");

        $org_id="";
        if($app->org()!=null){
            $org_id= $app->org()->org_id;
        }


        $data = cnav::app_user_rights_array($app_id, $role_id, $app->role()->role_id, $domain);

        if(strlen($role_id)> 0) {
            


            $form = $app->add_form('user_rights_form');

            $table = $form->add_div()->add_class("row-fluid")->add_div()->add_class("span12")->add_widget()->set_nopadding(true)->set_title(clang::__('Permission'))->set_icon('table')->add_table('permission_table');

            $table->set_key("menu_id");
            $table->set_data_from_array($data);
            $table->add_column('label')->set_label(clang::__("Label"));
            $table->add_column('access')->set_label(clang::__("Access"))->set_align('center');
            $table->add_column('permission')->set_label(clang::__("Permission"))->set_align('left');

            $table->cell_callback_func(array("User_permission_Controller", "cell_callback"), __FILE__);
            $table->set_apply_data_table(false);

            $additional_js = "";


            $actions = $form->add_action_list();
            $actions->add_action('cmd-save')->set_label(' ' . clang::__('Submit'))->set_submit(false)->set_icon('ok')->add_class('btn-primary')->set_link('javascript:;');
            $actions->set_style('form-action');


            $app->add_js("
                jQuery(document).ready(function() {
                    $('#app_id, #role_id').change(function() {
                        $('#user_filter_form').submit();
                    });
                    $('#cmd-save').click(function() {
                        $('#user_rights_form').submit();
                    });
                    $('#check_all').click(function() {
                        var checked = $('#check_all').is(':checked');
                        $('#permission_table tbody tr').each(function() {
                            var tr = $(this);
                            var cb = tr.find('.cb-access');
                            if(cb.length>0) {
                                if(checked) {
                                    cb.attr('checked','checked');
                                } else {
                                    cb.removeAttr('checked');
                                }
                                //for permission
                                var btns = $(this).closest('tr').find('.btn-'+cb.val()+'');
                                if(btns.length>0) {
                                    if(checked) {
                                        btns.removeAttr('disabled','disabled');
                                        btns.addClass('active');

                                    } else {
                                        btns.attr('disabled','disabled');
                                        btns.removeClass('active');
                                    }
                                }
                            }
                        });
                    });
                    $('#permission_table tbody tr').each(function() {
                        var tr = $(this);
                        var cb = tr.find('.cb-access');

                        if(cb.length>0) {

                            cb.click(function() {
                                var cb = $(this);

                                var btns = $(this).closest('tr').find('.btn-'+cb.val()+'');

                                if(cb.is(':checked')) {
                                    btns.removeAttr('disabled','disabled');
                                    btns.removeClass('active');



                                } else {
                                    btns.attr('disabled','disabled');
                                    btns.removeClass('active');
                                }
                            });
                            var btns = tr.find('.btn-'+cb.val()+'');
                            if(cb.is(':checked')) {
                                btns.removeAttr('disabled','disabled');
                            } else {
                                btns.attr('disabled','disabled');
                            }
                        }
                    });
                    $('#permission_table tbody tr .btn-group .btn').click(function() {
                        if($(this).attr('disabled')!='disabled') {
                            $(this).toggleClass('active');
                        }
                    });


                    $('#user_rights_form').submit(function(e) {
                        e.preventDefault();
                        if($('#cmd-save').hasClass('loading')) return;
                        icon = $('#cmd-save').find('i');
                        icon.removeClass('icon-ok').addClass('icon-repeat').addClass('icon-spin');
                        $('#cmd-save').addClass('loading');
                        data = new Array();
                        var i=0;
                        $('#permission_table tbody tr').each(function() {
                            var tr = $(this);
                            cb = tr.find('.cb-access:checked');

                            if(cb.length>0) {
                                btns = tr.find('.btn-'+cb.val()+'.active');
                                var btn_array = new Array();
                                var j=0;
                                if(btns.length>0) {
                                    btns.each(function() {
                                        var btn = $(this);
                                        btn_array[j]=btn.attr('name');
                                        j++;
                                    });
                                };

                                var cb_obj = {
                                    'name':cb.val(),
                                    'action':btn_array
                                };
                                data[i] = cb_obj;
                                i++;
                            }

                        });

                        jQuery.ajax({
                            type: 'post',
                            url: '" . curl::base() . "setting/user_permission/save',
                            dataType: 'json',
                            data: {'access':data,'role_id':$('#role_id').val(),'app_id':$('#app_id').val()}
                        }).done(function( data ) {
                            var type='error';
                            if(data.result==1) {
                                type='success';
                            } 
                            $('#cmd-save').removeClass('pos_cmdloading');

                            $.cresenity.message(type,data.message);
                            $('#cmd-save').removeClass('loading');
                            icon = $('#cmd-save').find('i');
                            icon.addClass('icon-ok').removeClass('icon-repeat').removeClass('icon-spin');
                            window.location.href='" . curl::base() . "setting/user_permission?app_id='+$('#app_id').val()+'&role_id='+$('#role_id').val();
                        }).error(function(XMLHttpRequest, textStatus, errorThrown) {
                            $.cresenity.message('error',textStatus);
                            $('#cmd-save').removeClass('loading');
                            icon = $('#cmd-save').find('i');
                            icon.addClass('icon-ok').removeClass('icon-repeat').removeClass('icon-spin');


                        });
                    });
                });

            ");
        } else {
            $app->add('
                <div class="alert alert-dismissible  alert-error" role="alert">
                    <a class="close" data-dismiss="alert">×</a>
                    <strong>Error!</strong> <p>Anda masih tidak mempunyai jabatan yang akan diatur aksesnya, buatlah jabatan terlebih dahulu atau hubungi administrator jika anda tidak bisa membuat jabatan baru</p>
                </div>
            ');
        }

        echo $app->render();
    }

    public function get_child_array($id = "", $level = 0) {
        $app = CApp::instance();
        $db = CDatabase::instance();
        $is_compro = false;

        if (strpos(CF::domain(), 'compromall.id')) {
            $is_compro = true;
        } else {
            $is_compro = false;
        }

        $org_id = CF::org_id();
        $org_parent_id = null;

        if (strlen($org_id) > 0) {
            $org_parent_id = cdbutils::get_value('SELECT parent_id FROM org WHERE org_id = ' . $db->escape($org_id));
        }

        $q = "
            SELECT
                role_id
                ,name
            FROM
                roles
            WHERE
                status > 0
        ";

        if (strlen($id) > 0) {
            $q .= " AND parent_id = " . $db->escape($id);
        }

        if ($is_compro) {
            if (strlen($org_parent_id) > 0) {
                $q .= ' AND org_id = ' . $db->escape($org_id);
            }
        } else {
            $user = $app->user();

            if($user != null) {
                if(strlen($org_id) == 0) {
                    $org_id = $user->org_id;
                }
            }

            if($org_id == 0) $org_id = null;

            if(strlen($org_id) > 0) {
                $q .= " AND org_id = " . $db->escape($org_id);
            }
        }

        $result = array();
        $r = $db->query($q);
        foreach ($r as $row) {
            $role = array();
            $role["id"] = $row->role_id;
            $role["name"] = $row->name;
            $role["level"] = $level;
            $result[] = $role;
            $childs = $this->get_child_array($row->role_id, $level + 1);
            if (count($childs) > 0) $result = array_merge($result, $childs);
        }
        return $result;
    }

    public function get_role_child_list($role_id = null) {
        $app = CApp::instance();

        if (strlen($role_id) == 0) $role_id = $app->role()->role_id;
        $child_array = $this->get_child_array($role_id);
        $child_list = array();

        foreach ($child_array as $child) {
            $child_list[$child["id"]] = cutils::indent($child["level"], "&nbsp;&nbsp;&nbsp;&nbsp;") . $child["name"];
        }
        return $child_list;
    }

}

?>