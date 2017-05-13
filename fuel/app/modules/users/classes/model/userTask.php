<?php

namespace Users;

class Model_userTask extends \Orm\Model{
	
	protected static $_table_name = 'user_task';

	protected static $_properties = array(
		'id',
		'user_id',
		'task_id',
		'action',
		'date',
	);

	

	public static function add_point($post_data,$user_id){
		if($post_data['id']==NULL){
			return FALSE;
		}
		switch($post_data['type']){
            case 'Share': $news = \Pages\Model_News::query()->related('promo')->where('id',$post_data['id'])->get_one();
                        $task = \Promo\Model_Task::query()->where('promo_id',$news->promo_id)->where('type','Share')->get_one();
                        if(self::calc_point($user_id,$task->id,$news->promo->brand_id)){
                        	return TRUE;
                        }else{
                        	return FALSE;
                        }
            break;
            case 'Like' : $task = \Promo\Model_Task::query()->related('promo')->where('description',$post_data['id'])->where('type','Like')->get_one();
		            	if(self::calc_point($user_id,$task->id,$task->promo->brand_id)){
                        	return TRUE;
                        }else{
                        	return FALSE;
                        }
            break;
            case 'Watch':$brand = \Promo\Model_Task::query()->related('promo')->where('id',$post_data['id'])->where('type','Watch')->get_one();
            			if(self::calc_point($user_id,$post_data['id'],$brand->promo->brand_id)){
                        	return TRUE;
                        }else{
                        	return FALSE;
                        }
            break;
        }
	}

	private static function calc_point($user_id,$task_id,$brand_id){
		$today = date("Y-m-d");
		$data = self::query()->where('user_id',$user_id)->where('task_id',$task_id)->where('date',$today)->where('action','1')->get_one();
		if(empty($data)){
			return FALSE;
		}
		try{
			$get_point = \Promo\Model_Task::query()->where('id',$task_id)->get_one();
			$update_point = Model_userPoint::query()->where('user_id',$user_id)->where('brand_id',$brand_id)->get_one();
			$update_point->point += $get_point['point'];
			$update_point->save();
			$data->action = '2';
			$data->save();
			return TRUE;
		}
		catch (\Exception $e){
            return FALSE;    
     	}
		
	}

	protected static $_belongs_to = array(
		'user' => array(
			'key_from' 		 => 'user_id',
			'model_to' 		 => 'Users\\Model_Members',
			'key_to'		 => 'id',
			'cascade_save'	 => true,
			'cascade_delete' => false,
		),
		'task' => array(
			'key_from' 		 => 'task_id',
			'model_to' 		 => 'Promo\\Model_Task',
			'key_to'		 => 'id',
			'cascade_save'	 => true,
			'cascade_delete' => false,
		),
	);
}