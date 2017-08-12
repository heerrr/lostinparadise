<?php

class SMAdminOrg {

    const SUPERMALL_BACKEND_APP_ID = '852';
    const SUPERMALL_BACKEND_APP_CODE = 'supermalladmin';
    const SUPERMALL_FRONTEND_APP_ID = '851';
    const SUPERMALL_FRONTEND_APP_CODE = 'supermall';
    const HALLFAMILY_BACKEND_APP_ID = '890';
    const HALLFAMILY_BACKEND_APP_CODE = 'admin62hallfamily';
    const HALLFAMILY_FRONTEND_APP_ID = '800';
    const HALLFAMILY_FRONTEND_APP_CODE = '62hallfamily';

    protected $org_db_data = null;

    private function __construct($org_id) {
        $db = CDatabase::instance();
        $this->org_db_data = cdbutils::get_row("select * from org where org_id=" . $db->escape($org_id));
        if ($this->org_db_data == null) {
            throw new SMAdminException('Org ID [' . $org_id . '] not found in database, please pass the correct parameter to SMAdminOrg object');
        }
    }

    public function __get($name) {
        switch ($name) {
            case 'domain':
            case 'org_id':
            case 'code':
            case 'name':
            case 'email':
            case 'theme':
            case 'bank_account':
            case 'phone':
            case 'address':
                return $this->org_db_data->$name;
                break;
            case 'domain_admin':
                return 'admin.' . $this->domain;
                break;
            default:
                throw new Exception('property ' . $name . ' is not registered on object SMAdminOrg');
                break;
        }
    }

    public static function factory($org_id) {
        return new SMAdminOrg($org_id);
    }

    public function update_data_frontend_domain() {
        $domain = $this->domain;
        $app_id = self::SUPERMALL_FRONTEND_APP_ID;
        $app_code = self::SUPERMALL_FRONTEND_APP_CODE;
        $data_domain = array(
            "app_id" => $app_id,
            "app_code" => $app_code,
            "org_id" => $this->org_id,
            "org_code" => $this->code,
            "store_id" => null,
            "store_code" => null,
            "domain" => $this->domain,
            "theme" => $this->theme,
        );
        $file_domain = DOCROOT . 'data' . DS . 'domain' . DS . $this->domain . '.php';
        cphp::save_value($data_domain, $file_domain);
    }

    public function update_data_backend_domain() {
        $app_id = self::SUPERMALL_BACKEND_APP_ID;
        $app_code = self::SUPERMALL_BACKEND_APP_CODE;
        $data_domain = array(
            "app_id" => $app_id,
            "app_code" => $app_code,
            "org_id" => $this->org_id,
            "org_code" => $this->code,
            "store_id" => null,
            "store_code" => null,
            "domain" => $this->domain_admin,
        );
        $file_domain = DOCROOT . 'data' . DS . 'domain' . DS . $this->domain_admin . '.php';
        cphp::save_value($data_domain, $file_domain);
    }

    public function update_config_frontend_app() {
        $app_code = self::SUPERMALL_FRONTEND_APP_CODE;

        $data_app = array(
            "smtp_from" => "noreply@" . $this->domain,
            "theme" => $this->theme,
            "org_id" => $this->org_id,
            "have_clock" => false,
            "have_user_login" => false,
            "have_user_access" => false,
            "have_user_permission" => false,
            "title" => $this->name,
            "domain_admin" => $this->domain_admin,
            "domain" => $this->domain,
            "default_timezone" => "Asia/Jakarta",
            "set_timezone" => true,
            "multilang" => false,
            "decimal_separator" => " ",
            "thousand_separator" => ".",
            "decimal_digit" => "0",
            "date_formatted" => "d-m-Y",
            "long_date_formatted" => "d-m-Y H:i:s",
            "require_js" => false,
            "api_url" => "http://62pay.co.id/api/",
            "api_auth" => "b27d10bffb120d6796d1fc4fe0447c40",
            "api_domain_server" => "http://62pay.co.id/",
        );

        $path = DOCROOT . 'application' . DS . $app_code . DS . $this->code;
        if (!is_dir($path)) {
            @mkdir($path);
        }
        $path .= DS . 'config';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $file_app = $path . DS . 'app.php';
        
        cphp::save_value($data_app, $file_app);
    }

    public function update_config_backend_app() {
        $app_code = self::SUPERMALL_BACKEND_APP_CODE;

        $data_app = array(
            "smtp_from" => "noreply@" . $this->domain,
            "org_id" => $this->org_id,
            "have_clock" => false,
            "have_user_login" => true,
            "have_user_access" => true,
            "have_user_permission" => true,
            "title" => $this->name,
            "domain_admin" => $this->domain_admin,
            "domain" => $this->domain,
            "default_timezone" => "Asia/Jakarta",
            "set_timezone" => true,
            "multilang" => false,
            "decimal_separator" => " ",
            "thousand_separator" => ".",
            "decimal_digit" => "0",
            "date_formatted" => "d-m-Y",
            "long_date_formatted" => "d-m-Y H:i:s",
            "require_js" => false,
            "api_url" => "http://62pay.co.id/api/",
            "api_auth" => "b27d10bffb120d6796d1fc4fe0447c40",
            "api_domain_server" => "http://62pay.co.id/",
        );

        $path = DOCROOT . 'application' . DS . $app_code . DS . $this->code;
        if (!is_dir($path)) {
            @mkdir($path);
        }
        $path .= DS . 'config';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $file_app = $path . DS . 'app.php';
        
        cphp::save_value($data_app, $file_app);
    }

    public function update_all() {
        $this->update_data_backend_domain();
        $this->update_data_frontend_domain();
        $this->update_config_backend_app();
        $this->update_config_frontend_app();
    }

}
