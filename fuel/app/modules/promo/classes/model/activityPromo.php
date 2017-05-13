<?php

namespace Promo;

class Model_ActivityPromo extends \Orm\Model{
	protected static $_table_name = "activity_promo";

	protected static $_properties = array(
		'id',
		'user_id',
		'promo_id'
	);

	protected static $_belongs_to = array(
		'promo' => array(
			'key_from' 		 => 'promo_id',
			'model_to' 		 => 'Promo\\Model_Promo',
			'key_to'		 => 'id',
			'cascade_save'	 => true,
			'cascade_delete' => false,
		),
		'user' => array(
			'key_from' 		 => 'user_id',
			'model_to' 		 => 'Users\\Model_Members',
			'key_to'		 => 'id',
			'cascade_save'	 => true,
			'cascade_delete' => false,
		)
	);
}