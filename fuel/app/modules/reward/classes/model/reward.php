<?php

namespace Reward;

class Model_Reward extends \Orm\Model {
    private $status_name =  array('InActive', 'Active');
    private $image_path = 'media/reward/';
    private static $_brand;
	protected static $_table_name = 'reward';

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'faqs'=>array('before_insert'),
            'mysql_timestamp' => true
        ),
        'Orm\Observer_UpdatedAt' => array(
            'faqs'=>array('before_update'),
            'mysql_timestamp' => true
        ),
        'Orm\Observer_Validation' => array(
            'faqs'=>array('before_save')
        )
    );

    protected static $_properties = array(
        'id',
        'brand_id'=> array(
            'label' => 'Brand',
            'validation' => array(
                'required',
            )
        ),
        'name' => array(
            'label' => 'Brand',
            'validation' => array(
                'required',
            )
        ),
        'image'=> array(
            'label' => 'Image',
            'validation' => array(
                'required',
                'max_length' => array(500),
            )
        ),
        'point' =>  array(
            'validation' => array(
                'required',
            )
        ),
        'stock' => array(
            'validation' => array(
                'required',
            )
        ),
        'status' => array(
            'validation' => array(
                'required',
            )
        ),
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );

    public function get_brand_name(){
        if(empty(self::$_brand)){
            self::$_brand = \Reward\Model_Brand::get_as_array();
        }
        $flag = $this->brand_id;
        return isset(self::$_brand[$flag]) ? self::$_brand[$flag] : '-';
    }

    public function get_image_path() {
        return $this->image_path;
    }

    public function get_all_images() {
        if (file_exists(DOCROOT.$this->image_path)) {
            $contents = \File::read_dir(DOCROOT.$this->image_path);
        } else {
            $contents = array();
        }
        return $contents;
    }

    public function get_status_name() {
        $flag = $this->status;
        return isset($this->status_name[$flag]) ? $this->status_name[$flag] : '-';
    }

    public static function get_as_array($filter = array()) {
        $items = self::find('all', $filter);
        if (empty($items)) {
            $data = array();
        } else {
            foreach ($items as $item) {
                $data[$item->id] = $item->name;
            }
        }
        return $data;
    }

    public function get_form_data_basic($brand) {
        return array(
            'attributes' => array(
                'name' => 'frm_promo',
                'class' => 'form-horizontal',
                'role' => 'form',
                'action' => '',
                'method' => 'post',
            ),
            'hidden' => array(),
            'fieldset' => array(
                array(
                    'label' => array(
                        'label' => 'Brand',
                        'id' => 'brand_id',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'select' => array(
                        'name' => 'brand_id',
                        'value' => $this->brand_id,
                        'options' => $brand,
                        'attributes' => array(
                            'class' => 'form-control bootstrap-select',
                            'placeholder' => 'Brand',
                            'data-live-search' => 'true',
                            'data-size' => '3',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'Name',
                        'id' => 'name',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'name',
                        'value' => $this->name,
                        'attributes' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Name',
                            'required' => '',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'Image',
                        'id' => 'image_filename',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'select_image_picker' => array(
                        'name' => 'image_filename',
                        'value' => $this->image,
                        'options' => $this->get_all_images(),
                        'attributes' => array(
                            'class' => 'form-control image-picker',
                        ),
                        'container_class' => 'col-sm-10',
                        'image_url' => \Uri::base().$this->image_path,
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'Point',
                        'id' => 'point',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'point',
                        'value' => $this->point,
                        'attributes' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Point',
                            'required' => '',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'Stock',
                        'id' => 'stock',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'stock',
                        'value' => $this->stock,
                        'attributes' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Stock',
                            'required' => '',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'Status',
                        'id' => 'status',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'select' => array(
                        'name' => 'status',
                        'value' => $this->status,
                        'options' => $this->status_name,
                        'attributes' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Status',
                            'required' => ''
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
            )
        );
    }

    protected static $_has_many = array(
        'order' => array(
            'key_from'       => 'id',
            'model_to'       => 'Reward\\Model_Order',
            'key_to'         => 'reward_id',
            'cascade_save'   => true,
            'cascade_delete' => false,
        ),
    );

    protected static $_belongs_to = array(
        'brand' => array(
            'key_from' => 'brand_id',
            'model_to' => 'Reward\\Model_Brand',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false,
        )
    );
}