<?php

namespace Pages;

class Model_News extends \Orm\Model{

    private $status_name = array('InActive', 'Active');
    private $image_path = 'media/news/';
    private static $_promo;
    
    protected static $_table_name = 'news';

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
        'promo_id'=> array(
            'label' => 'Promo',
            'validation' => array(
                'required',
            )
        ),

        'title' => array(
            'label' => 'News Title',
            'validation' => array(
                'required',
                'max_length' => array(200)
            )
        ),
        'date'=> array(
            'label' => 'Date',
            'validation' => array(
                'required',
                'valid_date' => array(
                    'format' => 'Y-m-d'
                )
            )
        ),
        'highlight'  => array(
            'label' => 'Highlight',
            'validation' => array(
                'required',
                'max_length' => array(500)
            )
        ),
        'description'  => array(
            'label' => 'Description',
            'validation' => array()
        ),
        'image'=> array(
            'label' => 'Image',
            'validation' => array(
                'required',
                'max_length' => array(500),
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

    public function get_promo_name(){
        if(empty(self::$_promo)){
            self::$_promo = \Promo\Model_Promo::get_as_array();
        }
        $flag = $this->promo_id;
        return isset(self::$_promo[$flag]) ? self::$_promo[$flag] : '-';
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
                $data[$item->id] = $item->title;
            }
        }
        return $data;
    }

    public function get_form_data_basic($promo) {
        return array(
            'attributes' => array(
                'name' => 'frm_news',
                'class' => 'form-horizontal',
                'role' => 'form',
                'action' => '',
                'method' => 'post',
            ),
            'hidden' => array(),
            'fieldset' => array(
                array(
                    'label' => array(
                        'label' => 'Promo',
                        'id' => 'promo_id',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'select' => array(
                        'name' => 'promo_id',
                        'value' => $this->promo_id,
                        'options' => $promo,
                        'attributes' => array(
                            'class' => 'form-control bootstrap-select',
                            'placeholder' => 'Promo',
                            'data-live-search' => 'true',
                            'data-size' => '3',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'Title',
                        'id' => 'title',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'title',
                        'value' => $this->title,
                        'attributes' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Title',
                            'required' => '',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'Date',
                        'id' => 'date',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'date',
                        'value' => $this->date,
                        'attributes' => array(
                            'class' => 'form-control mask-date',
                            'placeholder' => 'Date',
                            'required' => '',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'Highlight',
                        'id' => 'highlight',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'highlight',
                        'value' => $this->highlight,
                        'attributes' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Highlight',
                            'required' => '',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'Description',
                        'id' => 'description',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'textarea' => array(
                        'name' => 'description',
                        'value' => $this->description,
                        'attributes' => array(
                            'class' => 'form-control ckeditor',
                            'placeholder' => 'description',
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

    protected static $_belongs_to = array(
        'promo' => array(
            'key_from'       => 'promo_id',
            'model_to'       => 'Promo\\Model_Promo',
            'key_to'         => 'id',
            'cascade_save'   => true,
            'cascade_delete' => false,
        ),
    );
}