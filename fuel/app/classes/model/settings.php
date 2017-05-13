<?php
class Model_Settings extends \Orm\Model {
	protected static $_table_name = 'settings';

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events'=>array('before_insert'),
			'mysql_timestamp' => true
		),
		'Orm\Observer_UpdatedAt' => array(
			'events'=>array('before_update'),
			'mysql_timestamp' => true
		),
		'Orm\Observer_Validation' => array(
			'events'=>array('before_save')
		)
	);
        
	protected static $_properties = array(
		'id',
		'setting_name' => array(
			'label' => 'Setting Name',
			'validation' => array(
				'required',
				'max_length' => array(100),
			)
		),
		'setting_value' => array(
			'label' => 'Setting Value',
			'validation' => array(
				'max_length' => array(500),
			)
		),
		'setting_type' => array(
			'label' => 'Setting Type',
			'validation' => array(
				'required',
				'max_length' => array(50),
			)
		),
		'created_by',
		'created_at',
		'updated_by',
		'updated_at'
	);
	
	private static function _filter_type($type) {
		return self::query()->where('setting_type', $type);
	}
	
	public static function filter_basic() {
		return self::_filter_type('basic');
	}
	
//	public static function filter_facebook() {
//		return self::_filter_type('facebook');
//	}
}
