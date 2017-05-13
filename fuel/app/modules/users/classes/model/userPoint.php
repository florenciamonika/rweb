<?php

namespace Users;

class Model_UserPoint extends \Orm\Model{
	
	protected static $_table_name = 'user_point';

	protected static $_properties = array(
		'id',
		'user_id',
		'brand_id',
		'point'
	);

	protected static $_belongs_to = array(
		'user' => array(
			'key_from' 		 => 'user_id',
			'model_to' 		 => 'Users\\Model_Members',
			'key_to'		 => 'id',
			'cascade_save'	 => true,
			'cascade_delete' => false,
		),
		'brand' => array(
			'key_from' 		 => 'brand_id',
			'model_to' 		 => 'Reward\\Model_Brand',
			'key_to'		 => 'id',
			'cascade_save'	 => true,
			'cascade_delete' => false,
		)
	);
}