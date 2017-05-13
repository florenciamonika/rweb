<?php
namespace Promo;

class Controller_Promo extends \Controller_Frontend
{
    public function before(){
        parent::before();
    }
    
	public function action_index(){
		$this->_data_template['list_promo'] = Model_Promo::query()->where('status',1)->get();
		$this->_data_template['validate_user_promo'] = \Session::get_flash('validate_user_promo');
		return \Response::forge(\View::forge('promo::frontend/promo.twig',$this->_data_template,FALSE));
	}

    public function action_task(){
        $today = date("Y-m-d");
        $user_id = \Session::get('user_id');
        if(empty($user_id)){
            \Session::set_flash('ask_login','Please login before continue');
            return \Response::redirect(\Uri::base().'login');
        }
        $this->_data_template['list_task'] = \Users\Model_userTask::query()->related('task')->where('user_id',$user_id)->where('date',$today)->where('action','1')->get();
        $this->_data_template['completed_task'] = \Users\Model_userTask::query()->related('task')->where('user_id',$user_id)->where('date',$today)->where('action','2')->get();
        return \Response::forge(\View::forge('promo::frontend/task.twig',$this->_data_template,FALSE));
    }

    public function action_join(){
        $_post_data = \Input::post();
    	$user_id = \Session::get('user_id');

    	if(empty($user_id)){
    		\Session::set_flash('ask_login','Please login before continue');
    		return \Response::redirect(\Uri::base().'login');
    	}

        if(Model_Promo::join_promo($_post_data,$user_id)){
            return \Response::redirect(\Uri::base().'task');
        }else{
            \Session::set_flash('validate_user_promo','Oops! Something went wrong,please try again.');
            return \Response::redirect(\Uri::base().'promo');
        }
	}
}