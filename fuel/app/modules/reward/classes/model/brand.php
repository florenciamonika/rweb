<?php

namespace Reward;

class Model_Brand extends \Orm\Model {
    private $status_name = array('InActive', 'Active');
	protected static $_table_name = 'brand';

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'products' => array('before_insert'),
            'mysql_timestamp' => true
        ),
        'Orm\Observer_UpdatedAt' => array(
            'products' => array('before_update'),
            'mysql_timestamp' => true
        ),
        'Orm\Observer_Validation' => array(
            'products' => array('before_save')
        )
    );

    protected static $_properties = array(
        'id',
        'name' => array(
            'label' => 'Name',
            'validation' => array(
                'required',
                'max_length' => array(50),
            )
        ),
        'status' => array(
            'label' => 'Status',
            'validation' => array(
                'required',
            )
        ),
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );

    public function get_status_name() {
        $flag = $this->status;
        return isset($this->status_name[$flag]) ? $this->status_name[$flag] : '-';
    }

    public static function get_as_array ($filter=array()) {
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

    public function get_form_data_basic() {
        return array(
            'attributes' => array(
                'name' => 'frm_brand',
                'class' => 'form-horizontal',
                'role' => 'form',
                'action' => '',
                'method' => 'post',
            ),
            'hidden' => array(),
            'fieldset' => array(
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
        'promo' => array(
            'key_from'       => 'id',
            'model_to'       => 'Promo\\Model_Promo',
            'key_to'         => 'brand_id',
            'cascade_save'   => true,
            'cascade_delete' => false,
        ),
        'reward' => array(
            'key_from' => 'id',
            'model_to' => 'Reward\\Model_Reward',
            'key_to' => 'brand_id',
            'cascade_save' => true,
            'cascade_delete' => false,
        )
    );
}