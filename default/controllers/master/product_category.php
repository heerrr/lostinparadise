<?php

class Product_Category_Controller extends LIPAdminUserController {

    private $product_type_id = 1; // Produk

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        LIPAdmin::check_permission('product_category_list');
        $org_id = LIPAdmin::org_id();
        $org = LIPAdmin::org();
        $org_code = $org->code;

        $app = CApp::instance();
        $db = CDatabase::instance();
        $user = $app->user();

        // USER INTERFACE

        $app->title(clang::__('Rincian Kategori Produk'));

        $tree = CTreeDB::factory('product_category')
            ->set_org_id($org_id)
            ->add_filter('product_type_id', $this->product_type_id);

        $widget = $app
            ->add_widget()
            ->set_title(clang::__('Kategori Produk'));

        $nestable = $widget->add_nestable();
        $nestable
            ->set_data_from_treedb($tree)
            ->set_id_key('product_category_id')
            ->set_value_key('name');
        $nestable
            ->set_applyjs(false)
            ->set_action_style('btn-dropdown');

        if (cnav::have_permission('edit_product_category')) {
            $actedit = $nestable
                ->add_row_action('edit')
                ->set_label(clang::__('Ubah') . ' ' . clang::__('Kategori Produk'))
                ->set_icon('pencil')
                ->set_link(curl::base() . 'master/product_category/edit/{product_category_id}');
        }

        if (cnav::have_permission('delete_product_category')) {
            $actdelete = $nestable
            ->add_row_action('delete')
            ->set_label(clang::__('Hapus') . ' ' . clang::__('Kategori Produk'))
            ->set_icon('trash')
            ->set_link(curl::base() . 'master/product_category/delete/{product_category_id}');
        }

        echo $app->render();
    }

    public function add() {
        LIPAdmin::check_permission('add_product_category');
        $this->edit();
    }

    public function edit($id = "") {
        if (strlen($id) > 0) {
            LIPAdmin::check_permission('edit_product_category');
        }
        $org_id = LIPAdmin::org_id();
        $org = LIPAdmin::org();
        $org_code = $org->code;

        $app = CApp::instance();
        $db = CDatabase::instance();
        $user = $app->user();

        $post = $_POST;
        $file = $_FILES;
        $err_code = 0;
        $err_message = '';

        $imgsrc = curl::base() . 'cresenity/noimage/120/120';
        $parent_id = '';
        $product_category_name = '';

        if (count($post) > 0 || count($file) > 0) {
            $parent_id = carr::get($post, 'parent_id');
            $product_category_name = carr::get($post, 'product_category_name');
            $image = carr::get($file, 'image_name');

            $filename = '';
            $tmp_name = '';

            if (count($image) > 0) {
                if (isset($image['name'])) {
                    $filename = $image['name'];
                }
                if (isset($image['tmp_name'])) {
                    $tmp_name = $image['tmp_name'];
                }
            }

            if ($err_code == 0) {
                if (strlen($product_category_name) == 0) {
                    $err_code++;
                    $err_message = clang::__('Nama Kategori Produk') . ' ' . clang::__('masih belum terisi');
                }
            }

            if ($err_code == 0) {
                $db->begin();
                try {
                    $url_key_ori = cstr::sanitize($product_category_name);
                    $is_duplicate = true;
                    $i = 0;
                    $url_key = $url_key_ori;
                    while($is_duplicate) {
                        $url_key = $url_key_ori;
                        if ($i > 0) {
                            $url_key = $url_key_ori . '-' . ($i + 1);
                        }
                        $q = "
                            SELECT
                                *
                            FROM
                                product_category
                            WHERE
                                url_key = " . $db->escape($url_key) . "
                        ";
                        if (strlen($id) > 0) {
                            $q .= "
                                AND product_category_id <> " . $db->escape($id) . "
                            ";
                        }
                        $check = cdbutils::get_row($q);
                        if ($check != NULL) {
                            $i++;
                        } else {
                            $is_duplicate = false;
                        }
                    }

                    $data = [];
                    $data['product_type_id'] = $this->product_type_id;
                    $data['org_id'] = $org_id;
                    $data['code'] = $url_key;
                    $data['name'] = $product_category_name;
                    $data['url_key'] = $url_key;

                    if (strlen($filename) > 0 && strlen($tmp_name) > 0) {
                        $resource = CResources::factory('image', 'productcategory', $org_code);
                        $path = file_get_contents($tmp_name);
                        $file_name_generated = $resource->save($filename, $path);

                        $imgsrc = $resource->get_url($file_name_generated);
                        $data['image_name'] = $file_name_generated;
                        $data['image_url'] = $imgsrc;
                    }
                    if (strlen($parent_id) > 0) {
                        $data['parent_id'] = $parent_id;
                        $max_depth = ccfg::get('maximum_depth');
                        if ($max_depth != null) {
                            $last_depth = cdbutils::get_value("
                                SELECT
                                    depth
                                FROM
                                    product_category
                                WHERE
                                    status > 0
                                    AND product_category_id = " . $db->escape($parent_id) . "
                            ");
                            if ($last_depth >= $max_depth) {
                                throw new LIPAdminException('Maximum depth cannot more than ' . $max_depth, 1);
                            }
                        }
                    }

                    $tree = CTreeDB::factory('product_category')->set_org_id($org_id);

                    if (strlen($id) == 0) {
                        $data_default = [
                            'created' => date("Y-m-d H:i:s"),
                            'createdby' => $user->username,
                            'updated' => date("Y-m-d H:i:s"),
                            'updatedby' => $user->username,
                        ];
                        $data = array_merge($data, $data_default);

                        $param = [
                            'user_id' => $user->user_id,
                            'before' => '',
                            'after' => $data,
                        ];

                        $tree->insert($data, $parent_id);
                        
                    } else {
                        $data_default = [
                            'updated' => date("Y-m-d H:i:s"),
                            'updatedby' => $user->username,
                        ];
                        $data = array_merge($data, $data_default);
                        $before = cdbutils::get_row("
                            SELECT
                                *
                            FROM
                                product_category
                            WHERE
                                product_category_id = " . $db->escape($id) . "
                        ");

                        $param = [
                            'user_id' => $user->user_id,
                            'before' => (array) $before,
                            'after' => $data,
                        ];

                        $tree->update($id, $data, $parent_id);
                    }
                } catch (Exception $e) {
                    $err_code++;
                    $err_message = 'Error, call administrator...' . $e->getMessage();
                }
            }
            if ($err_code == 0) {
                $db->commit();
                if (strlen($id) > 0) {
                    clog::activity($param, 'Edit_Product_Category', clang::__("Kategori Produk") . " [" . $product_category_name . "] " . clang::__("Berhasil Diubah") . " !");
                    cmsg::add('success', clang::__("Kategori Produk") . " [" . $product_category_name . "] " . clang::__("Berhasil diubah") . " !");
                    curl::redirect("master/product_category");
                } else {
                    clog::activity($param, 'Add_Product_Category', clang::__("Kategori Produk") . " [" . $product_category_name . "] " . clang::__("Berhasil Ditambah") . " !");
                    cmsg::add('success', clang::__("Kategori Produk") . " [" . $product_category_name . "] " . clang::__("Berhasil Ditambah") . " !");
                    curl::redirect("master/product_category/add");
                }
            } else {
                $db->rollback();
                cmsg::add("error", $err_message);
            }
        }

        if (strlen($id) > 0) {
            $q = "
                SELECT
                    pc.*
                    ,pt.name as product_type_name
                FROM
                    product_category as pc
                    LEFT JOIN product_type as pt ON pt.product_type_id = pc.product_type_id
                WHERE
                    pc.product_category_id = " . $db->escape($id) . "
            ";
            $row = cdbutils::get_row($q);

            if ($row != NULL) {
                $product_category_name = $row->name;
                $imgsrc = $row->image_url;
                $parent_id = $row->parent_id;
            }
        }

        // USER INTERFACE

        if (strlen($id) > 0) {
            $title = clang::__('Edit') . ' ' . clang::__('Kategori Produk');
        } else {
            $title = clang::__('Tambah') . ' ' . clang::__('Kategori Produk');
        }
        $app->title($title);

        $widget = $app
            ->add_widget()
            ->set_title($title);

        $form = $widget
            ->add_form()
            ->set_enctype('multipart/form-data');

        $div = $form
            ->add_div()
            ->add_class('row-fluid');

        $left = $div
            ->add_div()
            ->add_class('span6');

        $right = $div
            ->add_div()
            ->add_class('span6');

        $info = $right
            ->add_div()
            ->add_class('alert alert-info');
        $info->add('<h4>' . clang::__('Information') . '</h4>');
        $info->add('<ul>');
        $info->add('<li>Ukuran Gambar <br>width : <b>386px &nbsp</b>height: <b>469px</b></li>');
        $info->add('</ul>');

        if (strlen($id) > 0) {
            $parent_name = cdbutils::get_value("
                SELECT
                    name
                FROM
                    product_category
                WHERE
                    status > 0
                    AND product_category_id = " . $db->escape($parent_id) . "
            ");

            $left
                ->add_control('parent_id', 'hidden')
                ->set_value($parent_id);

            $left
                ->add_field()
                ->set_label(clang::__('Parent'))
                ->add_control('parent_name', 'text')
                ->set_value($parent_name)
                ->set_disabled(true);
        } else {
            $tree = CTreeDB::factory('product_category')
                ->set_org_id($org_id)
                ->add_filter('product_type_id', $this->product_type_id);

            $product_category = ['' => 'NONE'];
            $product_category = $product_category + $tree->get_list('&nbsp;&nbsp;&nbsp;&nbsp;');

            $product_category_control = $left
                ->add_div('div_product_category_control')
                ->add_field()
                ->set_label(clang::__('Parent'))
                ->add_control('parent_id', 'select')
                ->set_list($product_category)
                ->set_value($parent_id)
                ->set_applyjs('select2');
        }

        $left
            ->add_field()
            ->set_label("<span style='color:red;'>*</span> " . clang::__("Nama Kategori Produk"))
            ->add_control("product_category_name", "text")
            ->add_validation('required')
            ->set_value($product_category_name);

        $left
            ->add_field()
            ->set_label(clang::__("Image"))
            ->add_control('image_name', 'image')
            ->set_imgsrc($imgsrc)
            ->set_maxwidth(386)
            ->set_maxheight(469);

        $form
            ->add_action_list()
            ->add_action()
            ->set_label(clang::__("Simpan"))
            ->set_confirm(true)
            ->set_submit(true);

        echo $app->render();
    }

    public function delete($id = "") {
        LIPAdmin::check_permission('delete_product_category');
        $org_id = LIPAdmin::org_id();
        $org = LIPAdmin::org();
        $org_code = $org->code;

        $app = CApp::instance();
        $db = CDatabase::instance();
        $user = $app->user();

        $err_code = 0;
        $err_message = '';

        $before = cdbutils::get_row("
            SELECT
                *
            FROM
                product_category
            WHERE
                product_category_id = " . $db->escape($id) . "
        ");
        $param = [
            'user_id' => $user->user_id,
            'before' => (array) $before,
            'after' => '',
        ];

        if ($err_code == 0) {
            $q = "
                SELECT
                    *
                FROM
                    product_category
                WHERE
                    status > 0
                    AND parent_id = " . $db->escape($id) . "
            ";
            $r = $db->query($q);
            if ($r->count() > 0) {
                $err_code++;
                $err_message = clang::__('Kategori Produk') . ' ' . clang::__('sudah sebagai') . ' ' . clang::__('Parent');
            }
        }
        if ($err_code == 0) {
            $q = "
                SELECT
                    *
                FROM
                    product
                WHERE
                    status > 0
                    AND product_category_id = " . $db->escape($id) . "
            ";
            $r = $db->query($q);
            if ($r->count() > 0) {
                $err_code++;
                $err_message = clang::__('Kategori Produk sedang digunakan pada Produk, Hapus atau Ubah Produk dari Kategori yang akan dihapus');
            }
        }
        if ($err_code == 0) {
            try {
                $db->update('product_category', array('status' => 0, 'updated' => date('Y-m-d H:i:s'), 'updatedby' => $user->username), array('product_category_id' => $id));
            } catch (Exception $e) {
                $err_code++;
                $err_message = clang::__('system_delete_fail') . $e->getMessage();
            }
        }

        if ($err_code == 0) {
            clog::activity($param, 'Delete_Product_Category', clang::__("Kategori Produk") . " [" . $before->name . "] " . clang::__("Berhasil Dihapus") . " !");
                cmsg::add('success', clang::__("Kategori Produk") . " " . clang::__("Berhasil Dihapus") . " !");
        } else {
            cmsg::add("error", $error_message);
        }
        curl::redirect("master/product_category");
    }

    public function change_order() {
        LIPAdmin::check_permission('change_order_product_category');
        $org_id = LIPAdmin::org_id();
        $org = LIPAdmin::org();
        $org_code = $org->code;

        $app = CApp::instance();
        $db = CDatabase::instance();
        $user = $app->user();

        $post = $_POST;
        $file = $_FILES;
        $err_code = 0;
        $err_message = '';

        $tree = CTreeDB::factory('product_category')
            ->set_org_id($org_id)
            ->add_filter('product_type_id', $this->product_type_id);

        if (count($post) > 0) {
            $data = $post['data_order'];
            $data = cjson::gecode($data);
            $db->begin();
            try {
                if (!is_array($data)) {
                    throw new LIPAdminException('Invalid Data');
                }
                $this->update_recursive($data, null, 1);

                $q = "
                    SELECT
                        *
                    FROM
                        product_category
                    WHERE
                        parent_id IS NULL
                        AND status > 0
                        AND org_id = " . $db->escape($org_id) . "
                    ORDER BY
                        lft
                ";
                $r = $db->query($q);
                $left = 1;
                foreach ($r as $key => $value) {
                    $tree->rebuild_tree($value->product_category_id, $left);
                    $left = cdbutils::get_value("
                        SELECT
                            rgt
                        FROM
                            product_category
                        WHERE
                            product_category_id = " . $db->escape($value->product_category_id) . "
                    ") + 1;
                }
            } catch (Exception $e) {
                $err_code++;
                $err_message = clang::__("system_update_fail") . " " . $e->getMessage();
            }
            if ($err_code == 0) {
                $max_depth = ccfg::get('maximum_depth');
                if ($max_depth != NULL) {
                    $last_depth = cdbutils::get_row("
                        SELECT
                            depth
                        FROM
                            product_category
                        WHERE
                            status > 0
                            AND org_id = " . $db->escape($org_id) . "
                            AND depth >= " . $max_depth . "
                    ");
                    if ($last_depth != NULL) {
                        $err_code++;
                        $err_message = 'Maximum depth cannot more than ' . $max_depth;
                    }
                }
            }
            if ($err_code == 0) {
                $db->commit();
                cmsg::add('success', clang::__("Urutan Kategori Produk") . clang::__(" ") . clang::__("Berhasil Diubah") . " !");
                clog::activity($user->user_id, 'edit', clang::__("Urutan Kategori Produk") . clang::__(" ") . clang::__("Berhasil Diubah") . " !");
                curl::redirect('master/product_category/change_order');
            } else {
                $db->rollback();
                cmsg::add('error', $err_message);
            }
        }

        // USER INTERFACE

        $app->title(clang::__('Ubah Urutan Kategori Produk'));

        $widget = $app
            ->add_widget()
            ->set_title(clang::__('Ubah Urutan'));

        $form = $widget->add_form();

        $info = $form
            ->add_div()
            ->add_class('alert alert-info');
        $info->add('<h4>' . clang::__('Information') . '</h4>');
        $info->add('<ul>');
        $info->add("<li>Untuk merubah susunan. <b>Klik</b> dan <b>tahan (Drag n Drop)</b> item yang ingin dipindahkan lalu geser ke atas atau ke bawah sesuai urutan yang diinginkan.</li>");
        $info->add('</ul>');

        $nestable = $form->add_nestable();
        $nestable
            ->set_data_from_treedb($tree)
            ->set_id_key('product_category_id')
            ->set_value_key('name')
            ->set_input('data_order');

        $form
            ->add_control('data_order', 'hidden')
            ->set_value('');

        $form
            ->add_action_list()
            ->add_action()
            ->set_label(clang::__("Simpan"))
            ->set_submit(true);

        echo $app->render();
    }

    private function update_recursive($data, $parent_id = null, $left) {
        $org_id = LIPAdmin::org_id();
        $org = LIPAdmin::org();
        $org_code = $org->code;

        $app = CApp::instance();
        $db = CDatabase::instance();
        $user = $app->user();

        foreach ($data as $key => $value) {
            $id = $v['id'];

            $data_updated = [
                'parent_id' => $parent_id,
            ];
            $right = $left + 1;
            $children = [];
            if (isset($value['children'])) {
                $right = count($value['children'], COUNT_RECURSIVE);
                $data_updated = array_merge($data_updated, [
                    'lft' => $left,
                    'rgt' => $right,
                ]);
                $db->update('product_category', $data_updated, array('product_category_id' => $id));

                $left++;
                $this->update_recursive($value['children'], $id, $left);
            } else {
                $data_updated = array_merge($data_updated, [
                    'lft' => $left,
                    'rgt' => $right,
                ]);
                $db->update('product_category', $data_updated, array('product_category_id' => $id));
                $left += 2;
            }
        }
    }

}
