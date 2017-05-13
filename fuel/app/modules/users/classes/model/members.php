<?php

namespace Users;

class Model_Members extends \Orm\Model {

    protected static $_table_name = 'user';

    protected static $_properties = array(
        'id',
        'fb_id',
        'fName' => array(
            'label' => 'Full Name',
            'validation' => array(
                'required',
                'max_length' => array(100)
            )
        ),
        'email' => array(
            'label' => 'Email',
            'validation' => array(
                'required',
                'max_length' => array(100)
            )
        ),
        'password' => array(
            'label' => 'Password',
            'validation' => array(
                'required',
                'max_length' => array(250)
            )
        ),
        'gender' => array(
            'label' => 'Gender',
            'validation' => array(
                'max_length' => array(20)
            )
        ),
        'address' => array(
            'label' => 'Address',
            'validation' => array(
                'max_length' => array(200)
            )
        ),
        'phone' => array(
            'label' => 'Phone',
            'validation' => array(
                'max_length' => array(20)
            )
        )
    );

    public static function check_regis($email){
        return (self::query()->where('email',$email)->count()>0) ? TRUE : FALSE;
    }

    public static function generate_password() {
        $alpha = "abcdefghijklmnopqrstuvwxyz";
        $alpha_upper = strtoupper($alpha);
        $numeric = "0123456789";
        $special = ".-+=_,!@$#*%<>[]{}";
        $chars = $alpha.$alpha_upper.$numeric.$special;
        $len = strlen($chars);
        $length = 10;
        $pw = "";
        do {
            $pw .= substr($chars, rand(0, $len-1), 1);
        } while(strlen($pw)<$length);
        return str_shuffle($pw);
    }

     public static function update_profile($post_data,$user_id){

        if(count($post_data)>0){
            $_err = self::validate_profile($post_data);
            if(count($_err) > 0){
                \Session::set_flash('form_error',$_err);
                return FALSE;
            }
            try{
                $update = self::query()->where('id',$user_id)->get_one();
                $update->fName = $post_data['fName'];
                $update->phone = $post_data['phone'];
                $update->gender = $post_data['gender'];
                $update->address = $post_data['address'];
                $update->save();

                \Session::set_flash('success_update','Profile has been updated');
                return TRUE;  
            }
            catch (\Exception $e){
                \Session::set_flash('form_error','Fail to Update, please try again.');
                return FALSE;
            }
        }else{
            return FALSE;   
        }   
    }

    public static function validate_profile($_post_data){

        $val = \Validation::forge('validate_profile');
        $val->add_field('fName','Full Name','required');
        if($_post_data['new-password']){
            $val->add_field('new-password','New Password','min_length[6]');    
        }
        $val->add_field('gender','Gender','required');
        $val->add_field('address','Address','required');
        $val->add_field('phone','Phone','required|min_length[10]');
        $val->set_message('required','Field :label is required!');
        $val->set_message('min_length','Field :label require minimum 6 character!');
        if($val->run()){
            return[];
        } else {
            return $val->error();
        }
    }   

    protected static $_has_many = array(
        'activity_promo' => array(
            'key_from'       => 'id',
            'model_to'       => 'Promo\\Model_ActivityPromo',
            'key_to'         => 'user_id',
            'cascade_save'   => true,
            'cascade_delete' => false,
        ),
        'order' => array(
            'key_from'      => 'id',
            'model_to'      => 'Reward\\Model_Order',
            'key_to'        => 'user_id',
            'cascade_save'  => true,
            'cascade_delete'=> false,
        ),
        'point' => array(
            'key_from'  => 'id',
            'model_to'  => 'Users\\Model_userPoint',
            'key_to'    => 'user_id',
            'cascade_save'  => true,
            'cascade_delete'=> false,
        ),
        'user_task' => array(
            'key_from'  => 'id',
            'model_to'  => 'Users\\Model_userTask',
            'key_to'    => 'user_id',
            'cascade_save'  => true,
            'cascade_delete'=> false,
        ),
    );
   
}
