<?php

	// Inicia sessão
    session_start();

    // Verifica sessão
	if(!isset($_SESSION['usuarioId']) AND !isset($_SESSION['usuarioEmail']) AND !isset($_SESSION['usuarioNome']) AND !is_numeric($_SESSION['usuarioId'])){
		session_destroy();
		header("Location: ".ROOT_WEB."/index.php");
	}

	// Variaveis globais para sessão
	if(isset($_SESSION['usuarioTipo']) AND !empty($_SESSION['usuarioTipo'])){
        $usertype = $_SESSION['usuarioTipo'];
    } else{
        $usertype = false;
    }

    // Verifica privilégio de usuario
    if($usertype){
        if(in_array($usertype, $privileges) == false){
            session_destroy();
            header("Location: ".ROOT_WEB."/index.php");
        } else{ 
            $userid = $_SESSION['usuarioId'];
            $username = $_SESSION['usuarioNome'];
        }
    } else{
        session_destroy();
        header("Location: ".ROOT_WEB."/index.php");
    }
    
?>