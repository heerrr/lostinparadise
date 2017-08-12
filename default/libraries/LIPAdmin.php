<?php


class LIPAdmin {
    
    private static $org = null;
    
    public function org_id() {
        $org_id = CF::org_id();
        $app = CApp::instance();
        $user = $app->user();
        if($org_id == null && $user != null) {
            $org_id = $user->org_id;
        }
        return $org_id;
    }
    
    
    public function org($org_id = null) {
        $db = CDatabase::instance();

        if($org_id != null) {
            return cdbutils::get_row('select * from org where org_id = ' . $db->escape($org_id));
        }
        $org_id = self::org_id();
        if(self::$org == null) {
            self::$org = cdbutils::get_row('select * from org where org_id = ' . $db->escape($org_id));
        }
        return self::$org;
    }
    
    public function not_accessible() {
        cmsg::add('error', clang::__('You do not have access to this module, please call administrator'));
        curl::redirect('home');
        return false;
    }
    
    public function check_permission($permission_name) {
        if (!cnav::have_permission($permission_name)) {
            self::not_accessible();
            return false;
        }
        return true;
    }
}

