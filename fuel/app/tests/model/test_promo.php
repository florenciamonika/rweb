<?php
/**
 * @group promo
 */
Class Test_Model_Promo extends TestCase{
	private $promo;	

    public function true_data(){
        return [
            [array('id' => array('18701321','187013212'), 'slot'=>10),array('id' => '187013212', 'has_join' => false)]
        ];
    }

    public function empty_ID(){
        return [
            [array('password' => '1asdf123', 'fb_id' => '1asf122'),array('email' => 'sdf1@gmail.com', 'fb_id' => '1asf122')]
        ];
    }

    public function no_slot(){
        return [
            [array('id' => array('18701321','187013212'), 'slot'=>0),array('id' => '187013212', 'has_join' => false)]
        ];
    }

    public function null_value(){
        return [
            [array(),array()]
        ];
    }

    protected function setUp(){
    	$this->promo = new Model_Promo();
    }

    /**
     * @dataProvider true_data
    */
	public function test_join_promo($initial,$request){
		$this->assertTrue($this->promo->join_promo($initial,$request));
	}

    /**
     * @dataProvider empty_ID
    */
    public function test_join_promowithemptyID($initial,$request){
        $this->assertEquals('No ID found',$this->promo->join_promo($initial,$request));
    }

    /**
     * @dataProvider no_slot
    */
    public function test_join_promowithnoslot($initial,$request){
        $this->assertEquals('No Slot left',$this->promo->join_promo($initial,$request));
    }

    /**
     * @dataProvider null_value
    */
    public function test_join_promowithnull($initial,$request){
        $this->assertEquals('Empty Value',$this->promo->join_promo($initial,$request));
    }
}