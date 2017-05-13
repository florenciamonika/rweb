<?php

namespace Promo;

class Model_Promo extends \Orm\Model{
    private $status_name = array('InActive', 'Active');
    private $image_path = 'media/promo/';
    private static $_brand;
	protected static $_table_name = "promo";

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
        'name' => array(
            'label' => 'Promo Name',
            'validation' => array(
                'required',
                'max_length' => array(200)
            )
        ),
        'brand_id'=> array(
            'label' => 'Brand',
            'validation' => array(
                'required',
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
        'slot' => array(
            'label' => 'Slot',
            'validation' => array(
                'required',
            )
        ),
        'end_date'=> array(
            'label' => 'End Date',
            'validation' => array(
                'required',
                'valid_date' => array(
                    'format' => 'Y-m-d'
                )
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

    public static function join_promo($post_data,$user_id){
        //Check Promo Slot
        $selected_promo = self::query()->where('id',$post_data['id'])->get_one();
        if($selected_promo['slot']>0){
            //Check if user already join selected promo
            $validate_user = Model_ActivityPromo::query()->where('user_id',$user_id)->where('promo_id',$post_data['id'])->get_one();
            if(empty($validate_user)){

                try{
                    $join = Model_ActivityPromo::forge(array(
                    'user_id' => $user_id,
                    'promo_id' => $post_data['id'],
                    ));
                    $join->save();

                    $point = \Users\Model_userPoint::forge(array(
                        'user_id'   => $user_id,
                        'brand_id'  => $selected_promo->brand_id,
                        'point'     => '0'
                    ));

                    $point->save();

                    $selected_promo['slot'] -= 1;
                    $selected_promo->slot = $selected_promo['slot'];
                    $selected_promo->save();
                    return TRUE;
                }
                catch (\Exception $e){
                    return FALSE;
                }
                
            }else{
                \Session::set_flash('validate_user_promo','You have been registered in this '.$selected_promo->name.' promo');
                return \Response::redirect(\Uri::base().'promo');
            }
        }else{
            \Session::set_flash('validate_user_promo','This promo have reached maximum slot');
            return \Response::redirect(\Uri::base().'promo');
        }
    }
	

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
                        'label' => 'Slot',
                        'id' => 'slot',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'slot',
                        'value' => $this->slot,
                        'attributes' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Slot',
                            'required' => '',
                        ),
                        'container_class' => 'col-sm-10'
                    )
                ),
                array(
                    'label' => array(
                        'label' => 'End Date',
                        'id' => 'end_date',
                        'attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        )
                    ),
                    'input' => array(
                        'name' => 'end_date',
                        'value' => $this->end_date,
                        'attributes' => array(
                            'class' => 'form-control mask-date',
                            'placeholder' => 'End Date',
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

    protected static $_has_one = array(
        'news' => array(
            'key_from' => 'id',
            'model_to' => 'Pages\\Model_News',
            'key_to' => 'promo_id',
            'cascade_save' => true,
            'cascade_delete' => false,
        )
    );

    protected static $_has_many = array(
        'activity_promo' => array(
            'key_from'       => 'id',
            'model_to'       => 'Promo\\Model_ActivityPromo',
            'key_to'         => 'promo_id',
            'cascade_save'   => true,
            'cascade_delete' => false,
        ),
        'task' => array(
            'key_from'       => 'id',
            'model_to'       => 'Promo\\Model_Task',
            'key_to'         => 'promo_id',
            'cascade_save'   => true,
            'cascade_delete' => false,
        ),
        
    );

    protected static $_belongs_to = array(
        'brand' => array(
            'key_from'       => 'brand_id',
            'model_to'       => 'Reward\\Model_Brand',
            'key_to'         => 'id',
            'cascade_save'   => true,
            'cascade_delete' => false,
        )
    );

}