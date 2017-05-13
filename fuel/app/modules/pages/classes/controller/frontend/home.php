<?php
namespace Pages;

class Controller_Frontend_Home extends \Controller_Frontend
{
    public function before(){
        parent::before();
    }
    
    public function action_index(){
        $this->_data_template['banner_data'] = \Pages\Model_Homebanner::query()->where('status', 1)->order_by('seq')->get();
        $this->_data_template['list_reward'] = \Reward\Model_Reward::query()->where('status',1)->order_by('id')->limit(5)->get();
    	return \Response::forge(\View::forge('pages::frontend/home.twig',$this->_data_template,FALSE));
    }

    public function action_news(){
        return \Response::forge(\View::forge('pages::frontend/news.twig',$this->_data_template,FALSE));
    }

    public function action_news_detail(){
        $id = $this->param('id');
        $this->_data_template['news_detail'] = \Pages\Model_News::query()->where('id',$id)->get();
    	return \Response::forge(\View::forge('pages::frontend/news_detail.twig',$this->_data_template,FALSE));
    }

    public function action_watch(){
        $id = $this->param('id');
        $this->_data_template['watch_detail'] = \Promo\Model_Task::query()->where('id',$id)->get_one();
        return \Response::forge(\View::forge('pages::frontend/watch.twig',$this->_data_template,FALSE));
    }

    public function action_profile(){
        $user_id = \Session::get('user_id');
        if(empty($user_id)){
            \Session::set_flash('ask_login','Please login before continue');
            \Response::redirect(\Uri::base().'login');
        }
        $this->_data_template['profile'] = \Users\Model_Members::query()->where('id',$user_id)->get_one();

        $_post_data = \Input::post();
        if(\Users\Model_Members::update_profile($_post_data,$user_id)){
            $this->_data_template['success_update'] = \Session::get_flash('success_update');
        }else{
            $this->_data_template['error_message'] = \Session::get_flash('form_error');
        }
        return \Response::forge(\View::forge('pages::frontend/profile.twig',$this->_data_template,FALSE));
    }

    public function action_logout(){
        \Session::destroy();
        \Response::redirect(\Uri::base());
    }

    public function action_add_point(){
        $_post_data = \Input::post();
        $user_id = \Session::get('user_id');
        if(empty($user_id)){
            \Session::set_flash('ask_login','Please login before continue');
            return \Response::redirect(\Uri::base().'login');
        }else if(empty($_post_data)){
            return \Response::redirect(\Uri::base().'login');
        }

        \Users\Model_userTask::add_point($_post_data,$user_id);
    }

}