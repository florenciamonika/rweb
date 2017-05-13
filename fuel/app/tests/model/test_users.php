<?php
/**
 * @group users
 */

class Test_Model_Users extends TestCase{
	
	private $login;

	public function true_data(){
        return [
        	[array('email' => array('sdf1@hotmail.com','sdf1@gmail.com'), 'password' => '1asdf123' , 'fb_id' => '1asf122'),array('email' => 'sdf1@gmail.com', 'address' => 'asdfaosdifo' , 'fb_id'=>'1asf122')]
        ];
    }

    public function empty_email(){
        return [
        	[array('password' => '1asdf123', 'fb_id' => '1asf122'),array('email' => 'sdf1@gmail.com', 'fb_id' => '1asf122')]
        ];
    }

    public function null_value(){
    	return [
        	[array(),array()]
        ];
    }

	protected function setUp(){
    	$this->login = new Model_Users();
    }

	/**
	 * @dataProvider true_data
	*/
	public function test_validlogin($initial,$request){
		$this->assertTrue($this->login->validate_login($initial,$request));
	}

	/**
	 * @dataProvider empty_email
	*/
	public function test_validloginwithnullemail($initial,$request){
		$this->assertEquals('No Email found',$this->login->validate_login($initial,$request));
	}

	/**
	 * @dataProvider null_value
	*/
	public function test_validloginwithemptyvalue($initial,$request){
		$this->assertEquals('Empty Value',$this->login->validate_login($initial,$request));	
	}

	/**
	 * @dataProvider true_data
	*/
	public function test_login($initial,$request){
		$this->assertTrue($this->login->login($initial,$request));
	}

	/**
	 * @dataProvider empty_email
	*/
	public function test_loginwithnullemail($initial,$request){
		$this->assertEquals('No Email found',$this->login->login($initial,$request));
	}

	/**
	 * @dataProvider null_value
	*/
	public function test_loginwithemptyvalue($initial,$request){
		$this->assertEquals('Empty Value',$this->login->login($initial,$request));	
	}

	/**
	 * @dataProvider true_data
	*/
	public function test_initial($initial){
		$this->assertTrue($this->login->add_data($initial));
	}

	/**
	 * @dataProvider empty_email
	*/
	public function test_initialwithnullemail($initial){
		$this->assertTrue($this->login->add_data($initial));
	}

	/**
	 * @dataProvider null_value
	*/
	public function test_initialwithemptyvalue($initial){
		$this->assertTrue($this->login->add_data($initial));
	}

	/**
	 * @dataProvider true_data
	*/
	public function test_update($initial,$request){
		$this->assertTrue($this->login->update_data($request));
	}

	/**
	 * @dataProvider empty_email
	*/
	public function test_updatewithnullemail($initial,$request){
		$this->assertTrue($this->login->update_data($request));
	}

	/**
	 * @dataProvider null_value
	*/
	public function test_updatewithemptyvalue($initial,$request){
		$this->assertEquals('No Value',$this->login->update_data($request));
	}

	public function test_updatewithemptyid(){
		$request = [array('password' => '1asdf123', 'fb_id' => '1asf122'),array('email' => 'sdf1@gmail.com', 'fb_id' => '1asf122')];
		$this->assertEquals('No fb_id',$this->login->update_data($request));
	}
}