<?php  

    if(isset($_POST['cep']) AND $_POST['cep'] != ""){
        $cep = str_replace("-", "", $_POST['cep']);
        $resultado = file_get_contents('http://cep.s1mp.net/'.$cep);    
        $resultado = json_decode($resultado, true); 
        if($resultado['result'] == 'true'){
        	$resultado['dados']['estado'] 			= strtoupper($resultado['data']['uf']);
        	$resultado['dados']['cidade'] 			= $resultado['data']['cidade'];
            $resultado['dados']['rua']              = $resultado['data']['tp_logradouro']." ".$resultado['data']['logradouro'];
        	$resultado['dados']['bairro'] 			= $resultado['data']['bairro'];
        	$resultado['dados']['erro'] 			= 0;
        	$resultado = $resultado['dados'];
        	echo json_encode($resultado);
        }
    }
    
?>