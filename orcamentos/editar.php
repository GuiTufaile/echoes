<?php 

    // Includes e requires
    require_once "../config.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require_once RESOURCE_PATH."/configs/privilege_responsavel.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/verify_error.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/functions.php"; 

    // Inicializa DB
    $db = new classMySQL(); 

    // Inicia templates
    require_once RESOURCE_PATH.'/php/libs/Twig/Autoloader.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem(RESOURCE_PATH.'/templates');
    $twig = new Twig_Environment($loader, $twig_config);

    // Verifica ação de retorno
    $return = verifyReturn("orcamentos",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

    // Processa dados de template
    $data = [
        "cliente"        => "",
        "area"           => "",
        "estado"         => "",
        "desconto"       => "",
        "tipoDesconto"   => "",
        "valor"          => "",
        "pagamento"      => "",
        "formaPagamento" => "",
        "responsavel"    => "",
        "vendedor"       => "",
        "observacoes"    => ""
    ]

    if($usertype == "administrador"){ 
        if(isset($_GET['id']) AND $_GET['id'] != ""){
            $data["id"] = $_GET['id'];
        }
    } else{
        $data["id"] = $userid;
    }

    $db->consulta_bd("SELECT * FROM clientes WHERE credenciado_id = ".$data["id"]);
    while($registro = mysqli_fetch_array($db->dados)){
    	 $data['clientes'][] = $registro;
    }

    if(isset($_GET['id']) AND $_GET['id'] != "" AND is_numeric($_GET['id'])){
    	$data["id"] = $_GET['id'];
		$db->consulta_bd("SELECT * FROM orcamentos WHERE id = ".$data["id"]);
        $registro = mysqli_fetch_array($db->dados);
        if($db->consulta_registros() == 1){
            $data["cliente_id"]     = $registro["cliente_id"];
            $data["area"]           = $registro["area"];
            $data["estado"]         = $registro["estado"];
            $data["desconto"]       = $registro["desconto"];
            $data["tipoDesconto"]   = $registro["tipoDesconto"];
            $data["valor"]          = $registro["valor"];
            $data["pagamento"]      = $registro["pagamento"];
            $data["formaPagamento"] = $registro["formaPagamento"];
            $data["observacoes"]    = $registro["observacoes"];

			$db->consulta_bd("SELECT * FROM credenciados WHERE id = ".$data["id"]);
		    $registro = mysqli_fetch_array($db->dados);
		    if($db->consulta_registros() == 1){
		    	$data["vendedor"] = $registro["responsavel"];
		    } else{
	            $alert["type"] = "editar";
	            $alert["value"] = "erro";
	        }
        } else{
            $alert["type"] = "editar";
            $alert["value"] = "erro";
        }
	} else{
		$alert["type"] = "editar";
        $alert["value"] = "erro";
	}

    // Renderiza template
    echo $twig->render('edit_orcamentos.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Editar Orçamento",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ]);

?>