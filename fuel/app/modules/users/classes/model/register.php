<?php

namespace Users;

class Model_Register extends \Model {

	private static $_label = [
        'fName' => 'Full Name',
		'email' => 'Email',
		'password' => 'Password'
    ];

	public static function fill($post_data) {
		$member = Model_Members::forge(array(
			'fName' => $post_data['fName'],
			'email' => $post_data['email'],
			'gender' => $post_data['gender'],
		));
		$member->password = \Crypt::encode($post_data['password']);
		$member->save();
	}

	public static function form_validate(){
		$val = \Validation::forge('form_registration');
		$val->add_field('fName','Full Name','required');
		$val->add_field('email','Email','required');
		$val->add_field('password','Password','required|min_length[6]');
		$val->add_field('gender','Gender','required');
		$val->set_message('required','Field :label is required!');
		$val->set_message('min_length','Field :label require minimum 6 character!');
		if($val->run()){
			return[];
		} else {
			return $val->error();
		}
	}	

}