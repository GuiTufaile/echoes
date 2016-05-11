<?php 

	function rearrange( $arr ){
	    foreach( $arr as $key => $all ){
	        foreach( $all as $i => $val ){
	            $new[$i][$key] = $val;    
	        }    
	    }
	    return $new;
	}
	
	function verifyReturn($default, $return){
		if(isset($return) AND $return != "")
			return $return;
		else
			return $default;
	}
?>