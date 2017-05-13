<?php
namespace Users;

class Controller_Frontend_Register extends \Controller_Frontend
{
    public function before(){
        parent::before();
    }
    
    public function action_index(){
        $_post_data = \Input::post();
        if(!empty(\Session::get('user_id'))){
            \Response::redirect(\Uri::base());
        }
        if (count($_post_data) > 0) {
            $_err = Model_Register::form_validate();
            if(count($_err) > 0){
                $this->_data_template['error_message'] = $_err;
                return \Response::forge(\View::forge('users::frontend/register.twig',$this->_data_template,FALSE));
            }
            if(Model_Members::check_regis($_post_data['email'])){
                $this->_data_template['error_message'] = 'Email has been used to register';
                return \Response::forge(\View::forge('users::frontend/register.twig',$this->_data_template,FALSE));
            }
            try{
                Model_Register::fill($_post_data);
                \Session::set_flash('success_regis','Registrasi berhasil, silahkan login.');
                \Response::redirect(\Uri::base().'login');  
            }
            catch (\Exception $e){
                // $this->_data_template['error_message'] = $e -> getMessage();
                $this->_data_template['error_message'] = "Fail to register, please try again.";
            }
        }
    	return \Response::forge(\View::forge('users::frontend/register.twig',$this->_data_template,FALSE));
    }

 }