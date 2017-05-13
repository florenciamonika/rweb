<?php

namespace Pages;

class Model_Homebanner extends \Orm\Model {
    private $status_name = array('InActive', 'Active');
    protected static $_table_name = 'home_banners';
    private $image_path = 'media/homebanner/';

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events'=>array('before_insert'),
            'mysql_timestamp' => true
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events'=>array('before_update'),
            'mysql_timestamp' => true
        ),
        'Orm\Observer_Validation' => array(
            'events'=>array('before_save')
        )
    );

    protected static $_properties = array(
        'id',
        'name' => array(
            'label' => 'Name',
            'validation' => array(
                'required',
                'max_length' => array(200),
            )
        ),
        'filename' => array(
            'label' => 'Filename',
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
        'url_link' => array(
            'label' => 'URL Link',
            'validation' => array(
                'max_length' => array(500),
            )
        ),
        'seq',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );

    public function get_status_name() {
        $flag = $this->status;
        return isset($this->status_name[$flag]) ? $this->status_name[$flag] : '-';
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

    public function get_form_data_basic() {
        return array(
            'attributes' => array(
                'name' => 'frm_homebanner',
                'class' => 'form-horizontal',
                'role' => 'form',
                'action' => '',
                'method' => 'post',
            ),
            'hidden' => array(),
            'fieldset' => array(
                array(
                    'label' => array(
                        'label' => 'Banner Name',
                        'id' => 'banner_name',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'banner_name',
                        'value' => $this->name,
                        'attributes' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Banner Name',
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
                array(
                    'label' => array(
                        'label' => 'Image',
                        'id' => 'banner_image',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'select_image_picker' => array(
                        'name' => 'banner_image',
                        'value' => $this->filename,
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
                        'label' => 'Seq',
                        'id' => 'seq',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'seq',
                        'value' => $this->seq,
                        'attributes' => array(
                            'class' => 'form-control',
                            'placeholder' => '0',
                            'required' => '',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'URL Link',
                        'id' => 'banner_link',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'banner_link',
                        'value' => $this->url_link,
                        'attributes' => array(
                            'class' => 'form-control',
                            'placeholder' => 'External link is start with http:// or https://, Internal link is just fill url path',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
            )
        );
    }

    public static function fill_form_data($the_model, $all_post_input) {
        $the_model->name = $all_post_input['banner_name'];
        $the_model->filename = isset($all_post_input['banner_image']) ? $all_post_input['banner_image'] : null;
        $the_model->status = $all_post_input['status'];
        $the_model->seq = $all_post_input['seq'];
        $the_model->url_link = $all_post_input['banner_link'];
    }

}