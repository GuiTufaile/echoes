<?php

	if(isset($_GET['erro']) AND !empty($_GET['erro'])){
        $error = $_GET['erro'];
    } else{
        $error = false;
    }

    if(isset($_GET['cadastrar']) AND !empty($_GET['cadastrar'])){
    	$alert["type"] = "cadastrar";
        $alert["value"] = $_GET['cadastrar'];
    } elseif(isset($_GET['editar']) AND !empty($_GET['editar'])){
    	$alert["type"] = "editar";
        $alert["value"] = $_GET['editar'];
    } elseif(isset($_GET['deletar']) AND !empty($_GET['deletar'])){
    	$alert["type"] = "deletar";
        $alert["value"] = $_GET['deletar'];
    } elseif(isset($_GET['duvida']) AND !empty($_GET['duvida'])){
        $alert["type"] = "duvida";
        $alert["value"] = $_GET['duvida'];
    } else{
        $alert = false;
    }
    
?>