<?php

    // Includes e requires
    include_once "config.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
	
	// Inicializa DB
	$db = new classMySQL();

	if(isset($_POST['login']) AND $_POST['login'] != "" AND isset($_POST['password']) AND $_POST['password'] != ""){
		$email = trim($_POST['login']);	
		$senha = $_POST['password'];
		$db->consulta_bd("SELECT * from emails e 
						inner JOIN contatos c ON c.idContato = e.Contatos_idContato 
						inner JOIN pessoas p on c.Pessoas_idPessoa = p.idPessoa 
						WHERE e.EmailPrincipal = 1 
						AND e.EmailEndereco = '".$email."'");
		if($db->consulta_registros() == 1){
	    	$registro = mysqli_fetch_array($db->dados);
	    	if(password_verify($senha, $registro["EmailSenha"])){
				if(!isset($_SESSION)){
					session_start();
					$_SESSION['usuarioId']          = $registro["idContato"];
					$_SESSION['usuarioNome']        = $registro["ContatoNome"];
					$_SESSION['usuarioEmpresa']     = $registro["PessoaNome"];
					$_SESSION['usuarioEmail']       = $registro["EmailEndereco"];					
					switch($registro["PessoaTipo"]){
						case 1: $_SESSION['usuarioTipo'] = "administrador"; break;
						case 2: $_SESSION['usuarioTipo'] = "parceiro"; break;
						case 3: $_SESSION['usuarioTipo'] = "cliente"; break;
					}
					header("Location: ".ROOT_WEB."/dashboard/index.php");
				}
			} else {
				header("Location: ".ROOT_WEB."/index.php?erro=login_senha_invalido");
			}
		} else{
			header("Location: ".ROOT_WEB."/index.php?erro=login_senha_invalido");
		}
	} else{
		header("Location: ".ROOT_WEB."/index.php?erro=campo_vazio");
	}
?>