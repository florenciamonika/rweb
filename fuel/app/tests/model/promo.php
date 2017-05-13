<?php

class Model_Promo extends \Model{

    public function join_promo($initial,$requested){
        if(count($initial)==0 && count($requested)==0){
            return 'Empty Value';
        }
        if((array_key_exists('id', $initial))==FALSE){
            $initial['id']=NULL;
        }
        if((array_key_exists('id', $requested))==FALSE){
            $requested['id']=NULL;
        }

        if($initial['id']==NULL && $requested['id']==NULL){
            return 'No ID found';
        }
        if(array_key_exists('has_join', $requested)==False){
            $requested['has_join']=NULL;
        }
        if(array_key_exists('slot', $initial)==False){
            $initial['slot']=NULL;
        }
        if(count($initial['id'])>1){
            foreach ($initial['id'] as $id) {
                if($id==$requested['id']){
                    if($initial['slot']>0){
                            if($requested['has_join']!=True){
                                $requested['has_join']=TRUE;
                                return True;
                            }
                               
                    }
                    return 'No Slot left';
                }
            }    
                return 'No ID found';
        }else{
            if($initial['id']==$requested['id']){
                if($initial['slot']>0){
                        if($requested['has_join']!=True){
                            $requested['has_join']=TRUE;
                            return True;
                        }
                }
                return 'No Slot Left';
            }
            return 'No ID found';
        }    
    }
}