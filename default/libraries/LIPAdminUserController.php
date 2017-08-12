<?php

/*
 * Description of destination
 * @author Joko Jainul A
 * @since Mar 4, 2016 3:20:04 PM
 */

class LIPAdminUserController extends LIPAdminController {

    public function __construct() {
        parent::__construct();
        $app = CApp::instance();
        $user = $app->user();
        if ($user == null) {
            cmsg::add('error', 'Your session has ended, please refresh your page & relogin');
            echo $app->render();
            die();
        }
    }

}
