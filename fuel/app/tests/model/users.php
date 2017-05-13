<?php

class Model_Users extends \Model{
	
	public function validate_login($initial,$request){
		if(count($initial)==0 && count($request)==0){
            return 'Empty Value';
        }

		if(array_key_exists('password', $request)==FALSE){
			$request['password']=NULL;
		}
		if(array_key_exists('email', $request)==FALSE){
			$request['email']=NULL;
		}
		if(array_key_exists('email', $initial)==FALSE){
			$initial['email']=NULL;
		}

		if($request['email']==NULL && $initial['email']==NULL){
			return 'No Email found';
		}

		if(count($initial['email'])>1){
            foreach ($initial['email'] as $email) {
                if($email==$request['email']){
	                return TRUE;
                }
            }    
                return 'No Email found';
        }else{
            if($initial['email']==$request['email']){
                return TRUE;
            }
            return 'No Email found';
        }
	}

	public function login($initial,$request){

		if(count($initial)==0 && count($request)==0){
            return 'Empty Value';
        }

		if(array_key_exists('email', $request)==FALSE){
			$request['email']=NULL;
		}
		if(array_key_exists('email', $initial)==FALSE){
			$initial['email']=NULL;
		}

		if($request['email']==NULL && $initial['email']==NULL){
			return 'No Email found';
		}
		
		if(array_key_exists('fb_id', $request)==FALSE){
			return 'No fb_id';
		}
		if(array_key_exists('fb_id', $initial)==FALSE){
			return 'No fb_id';
		}

		if($initial['fb_id']==$request['fb_id']){
			if(count($initial['email'])>1){
	            foreach ($initial['email'] as $email) {
	                if($email==$request['email']){
		                $count = count($initial);
			            $newdata = array('fb_id'=>$request['fb_id']);
			            array_push($initial,$newdata);
			            if($count = $count+1){
				            return TRUE;	
			            }
	                }
	            }
	            
	        }else{
	            if($initial['email']==$request['email']){
	               $count = count($initial);
		            $newdata = array('fb_id'=>$request['fb_id']);
		            array_push($initial,$newdata);
		            if($count = $count+1){
			            return TRUE;	
		            }
	            }
	            return 'No Email found';
	        }
		}
	}

	public function add_data($initial) {
		$count = count($initial);
        $newdata = array('fb_id'=>'testinsertnewdata');
        array_push($initial,$newdata);
        if($count = $count+1){
            return TRUE;	
        }
	}

	public function update_data($request){
		if(count($request)>0){
			if(array_key_exists('fb_id', $request)){
				$request['fb_id'] = 'update something';
				return TRUE;
			}
			return 'No fb_id';
		}
		return 'No Value';
    }
}