<?php

class LIPAdminFormInput_OrgSelect extends CFormInputSelectSearch {

    protected $applyjs;
    protected $all;
    protected $none;

    public function __construct($id) {
        parent::__construct($id);

        $this->all = false;
        $this->none = true;
        $this->query = "SELECT * FROM org WHERE status > 0";
        $this->type = "select";
        $this->applyjs = 'select2';
        $this->placeholder = clang::__('Pilih Salah Satu Merchant');
        $this->format_result = '{domain}-{name}';
        $this->format_selection = '{domain}-{name}';
        $this->search_field = array('domain', 'name');
        $this->key_field = 'org_id';
    }

    public static function factory($id = '') {
        return new LIPAdminFormInput_OrgSelect($id);
    }

    public function set_all($bool) {
        $this->all = $bool;
        return $this;
    }

    public function set_none($boolean) {
        $this->none = $boolean;
        return $this;
    }

    

    public function ajax($data) {

        $app = CApp::instance();
        $db = CDatabase::instance();
        $user = $app->user();
        $lft = null;
        $rgt = null;
        $org_id = IBAdmin::org_id();
        $none = cobj::get($data, 'none');
        $all = cobj::get($data, 'all');
        
        $obj = new stdclass();
        $q = "
                SELECT 
                    org_id, code, name,domain
                FROM
                    org 
                WHERE 
                    status > 0 and 
		";



        
       
        if (strlen($all) > 0) {
            $q = "
                    select
                        'ALL' as org_id
                        ,'' as code
                        ,'ALL' as name
                        ,'' as domain
                    union
                    " . $q . "
                ";
        }
        
        if (strlen($org_id) == 0) {
            if ($none) {
                $q = "SELECT
                            'NONE' as org_id
                            ,'' as code
                            ,'NONE' as name
                            ,'' as domain
                        UNION ALL " . $q;
            }
        }
//        cdbg::var_dump($data->parent_id);
//        cdbg::var_dump($q);
//        die;
        $data->query = $q;
        $obj->data = $data;
        $request = array_merge($_GET, $_POST);
        echo cajax::searchselect($obj, $request);
    }

    public function create_ajax_url() {
        return CAjaxMethod::factory()
                        ->set_type('callback')
                        ->set_data('callable', array('SMAdminFormInput_OrgSelect', 'ajax'))
                        ->set_data('key_field', $this->key_field)
                        ->set_data('search_field', $this->search_field)
                        ->set_data('applyjs', $this->applyjs)
                        ->set_data('none', $this->none)
                        ->set_data('all', $this->all)
                        ->makeurl();
    }

}
