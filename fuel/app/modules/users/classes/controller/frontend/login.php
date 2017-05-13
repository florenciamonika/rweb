<?php
namespace Users;

class Controller_Frontend_Login extends \Controller_Frontend
{
    public function before(){
        parent::before();
    }
    
    public function action_index(){
        $_post_data = \Input::post();
        if(!empty(\Session::get('user_id'))){
            \Response::redirect(\Uri::base());
        }
        $this->_data_template['fb_login'] = Model_Login::get_fb_url();
        $this->_data_template['success_regis'] = \Session::get_flash('success_regis');
        $this->_data_template['fail_fb'] = \Session::get_flash('fail_fb');
        $this->_data_template['please_login'] =\Session::get_flash('ask_login');
        if(count($_post_data)>0){
            if(Model_Login::validate_login($_post_data)){
                \Response::redirect(\Uri::base());
            }else{
                $this->_data_template['login_message'] = Model_login::get_err_message('email_password');
            }
        }
    	return \Response::forge(\View::forge('users::frontend/login.twig',$this->_data_template,FALSE));
    }

    public static function action_fb(){
        if(Model_login::login_fb()){
            \Response::redirect(\Uri::base());
        }
        else{
            \Session::set_flash('fail_fb','Fail to connect to FB account');
            \Response::redirect(\Uri::base().'login');
        }
    }
 }