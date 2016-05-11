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
    $return = verifyReturn("clientes",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

    // Processa dados de template
    $data = [
        "cliente_nomefantasia" => "",
        "cliente_razaosocial"  => "",
        "cliente_cnpj"         => "",
        "cliente_telefone"     => "",
        "cliente_email"        => "",
        "cliente_cep"          => "",
        "pais_id"              => "",
        "estado_id"            => "",
        "cidade_id"            => "",
        "cliente_endereco"     => "",
        "cliente_bairro"       => "",
        "cliente_numero"       => "",
        "cliente_complemento"  => ""
    ];

    if(isset($_GET['id']) AND $_GET['id'] != "" AND is_numeric($_GET['id'])){
	    $data["cliente_id"] = $_GET['id'];
	    $db->consulta_bd("SELECT * 
                          FROM clientes 
                          WHERE cliente_id = ".$data["cliente_id"]);
	    $registro = mysqli_fetch_array($db->dados);
	    if($db->consulta_registros() == 1){
            $data = $registro;
	    } else{
	        $alert["type"] = "editar";
            $alert["value"] = "erro";
	    }
	} else{
	    $alert["type"] = "editar";
        $alert["value"] = "erro";
	}

    // Renderiza template
    echo $twig->render('edit_clientes.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Editar Cliente",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ]);

?>