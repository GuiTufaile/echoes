<?php

    // Includes e requires
    require_once "../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 

   	// Remove timeout para coneções lentas
	set_time_limit(0);

	// Inicializa DB
    $db = new classMySQL(); 

    // Valida dados do mediakit
	if(isset($_GET['id']) AND $_GET['id'] != "" AND is_numeric($_GET['id'])){
	    $id = $_GET['id'];
	    $db->consulta_bd("SELECT * 
	    	              FROM mediakit 
	    	              WHERE media_id = $id");
	    $registro = mysqli_fetch_array($db->dados);
	    if($db->consulta_registros() == 1){
			$nome = $registro["media_nome"];
			// Executa leitura de arquivo
			if($registro["media_tipo"] == 1){
				$arquivo = ROOT.'/files/images/'.$registro["media_arquivo"];
			} else if($registro["media_tipo"] == 2){
				$arquivo = ROOT.'/files/pdf/'.$registro["media_arquivo"];
			} else{
				$arquivo = ROOT.'/files/compacted/'.$registro["media_arquivo"];
			}
			$extensao = explode(".",$registro["media_arquivo"]);
			$extensao = $extensao[1];
			if (!file_exists($arquivo)) {
				header("Location: ".PROJECT_FOLDER."/errors/404.php");
				exit;
			}	
			header("Content-Description: File Transfer"); 
			header("Content-Type: application/octet-stream"); 
			header("Content-Disposition: attachment; filename=\"$nome.$extensao\""); 
			readfile ($arquivo); 
		}
	}

?>