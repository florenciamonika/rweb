<?php

class Controller_Frontend extends Controller
{
	protected $_is_login = FALSE;

    public function before(){
        session_start();
        $this->_check_login_session();
        $this->set_task();
        $this->_data_template['is_login'] = $this->_is_login;
        $this->_data_template['member'] = $this->_member;
        $this->_data_template['list_news'] = \Pages\Model_News::query()->where('status','1')->get();
    }

	private function _check_login_session() {
        $this->_member='';
        $user_id = \Session::get('user_id');
        if (!empty($user_id)) {
            $member = Users\Model_Members::query()->where('id', $user_id)->get_one();
            if (!empty($member)) {
                $this->_is_login = TRUE;
                $this->_member = $member;
            }
        }else{
            $this->_member='';
        }
    }
    
    private function set_task(){
        $today = date("Y-m-d");
        $user_id = \Session::get('user_id');
        if(!empty($user_id)){
            $check_task = \Users\Model_userTask::query()->select('task_id')->where('user_id',$user_id)->where('date',$today);
            $count_check_task = count($check_task->get());
            if($count_check_task<5){
                $limit = 5-$count_check_task;
                $subQuery = \Promo\Model_ActivityPromo::query()->select('promo_id')->where('user_id',$user_id);
                $task = \Promo\Model_Task::query()->where('promo_id','IN', $subQuery->get_query(true))->where('id','NOT IN',$check_task->get_query(true))->order_by(DB::expr('RAND()'))->limit($limit)->get();
                foreach ($task as $task) {
                    \Users\Model_UserTask::forge(
                        array(
                            'user_id'=>$user_id,
                            'task_id'=>$task->id,
                            'action'=>'1',
                            'date'=>date("Y-m-d H:i:s"),
                        )
                    )->save();
                }
            }
            
        } 
    }

}