<?php

/**
 * Description of home
 *
 * @author Ecko Santoso
 * @since 10 Jun 16
 */
class Home_Controller extends LIPAdminController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $app = CApp::instance();
        $app->title('Dashboard');
        
        echo $app->render();
    }
}
