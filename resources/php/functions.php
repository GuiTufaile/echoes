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

	//Retorna mensagem no console
	function console_log( $data ) {
	if ( is_array( $data ) )
	$output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
	else
	$output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

	echo $output;
	}
?>