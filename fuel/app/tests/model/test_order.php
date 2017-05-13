<?php

/**
 * @group order
 */

Class Test_Model_Order extends TestCase{
	
	private $order;	

	public function true_data(){
        return [
        	'Return TRUE' => [array('id' => array('18701321','187013212'), 'point'=>10),array('id' => '187013212', 'address' => 'asdfaosdifo', 'point' => 100)]
        ];
    }

    public function empty_point(){
        return [
            [array('id' => '18701321', 'point'=>10),array('id' => '18701321', 'address' => 'asdfaosdifo', 'point' => 0)]
        ];
    }

    public function no_address(){
        return [
            [array('id' => array('18701321','187013212'), 'slot'=>10),array('id' => '187013212', 'point' => 100)]
        ];
    }

    public function null_value(){
        return [
            [array(),array()]
        ];
    }

    protected function setUp(){
    	$this->order = new Model_Order();
    }

     /**
	  * @dataProvider true_data
	 */

	public function test_valid_order($initial,$request){
		$this->assertTrue($this->order->check_valid($initial,$request));
	}
	
     /**
      * @dataProvider empty_point
     */

    public function test_valid_orderwithemptypoint($initial,$request){
        $this->assertEquals('Not enough point',$this->order->check_valid($initial,$request));
    }

     /**
      * @dataProvider no_address
     */

    public function test_valid_orderwithnoaddress($initial,$request){
        $this->assertEquals('Check your address',$this->order->check_valid($initial,$request));
    }

     /**
      * @dataProvider null_value
     */

    public function test_valid_orderwithnullvalue($initial,$request){
        $this->assertEquals('Empty Value',$this->order->check_valid($initial,$request));
    }
}