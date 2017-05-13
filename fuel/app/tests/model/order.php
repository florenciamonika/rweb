<?php

class Model_Order extends \Model{

	public function check_valid($initial,$request){
		if(count($initial)==0 && count($request)==0){
            return 'Empty Value';
        }
		if((array_key_exists('id', $initial))==FALSE){
            $initial['id']=NULL;
        }
		if(array_key_exists('id', $request)==FALSE){
			$request['id']=NULL;
		}
		
		if($initial['id']==NULL && $request['id']==NULL){
            return 'No ID found';
        }

		if(array_key_exists('address', $request)==FALSE){
			$request['address']=NULL;
		}
		if(array_key_exists('point', $request)==FALSE){
			$request['point']=NULL;
		}
		if(array_key_exists('point', $initial)==FALSE){
			$initial['point']=0;
		}
		if(count($initial['id'])>1){
            foreach ($initial['id'] as $id) {
                if($id==$request['id']){
	                if($request['address']=NULL || strlen($request['address'])<3){
						return 'Check your address';
					}
					
					if($request['point']<=$initial['point']){
						return 'Not enough point';
					}else{
						return TRUE;
					}                    
                }
            }    
                return 'No ID found';
        }else{
            if($initial['id']==$request['id']){
                if($request['address']=NULL || strlen($request['address'])<3){
					return 'Check your address';
				}

				if($request['point']<=$initial['point']){
					return 'Not enough point';
				}else{
					return TRUE;
				}
            }
            return 'No ID found';
        }
	}
}