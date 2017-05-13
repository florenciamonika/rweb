<?php
namespace Reward;

class Controller_Reward extends \Controller_Frontend
{
	public function before(){
        parent::before();
    }
    
	public function action_index(){
		$user_id = \Session::get('user_id');
		$this->_data_template['brand_reward'] = Model_Brand::query()->where('status','1')->get();
		$this->_data_template['reward_list'] = Model_Reward::query()->where('status','1')->get();
		if(!empty($user_id)){
			$this->_data_template['point_user'] = \Users\Model_userPoint::query()->where('user_id',$user_id)->get();
		}
		$this->_data_template['err_msg'] = \Session::get_flash('no_point');
		return \Response::forge(\View::forge('reward::frontend/reward.twig',$this->_data_template,FALSE));
	}

	public function action_thankyou(){
		$user_id = \Session::get('user_id');
		if(empty($user_id)){
			\Session::set_flash('ask_login','Please login before exchanging your point');
			\Response::redirect(\Uri::base().'login');
		}

		$data = \Input::post();
		if(Model_Order::check_valid($data,$user_id))
		{
	        return \Response::forge(\View::forge('reward::frontend/thankyou.twig',$this->_data_template,FALSE));			
		}else{
			\Session::set_flash('no_point','Not enought point');
			\Response::redirect(\Uri::base().'reward');
		}	

    }
	
}