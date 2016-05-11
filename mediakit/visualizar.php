<?php 

    // Includes e requires
    require_once "../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
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
    $return = verifyReturn("mediakit",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

    // Processa dados de template
    $data = [];

    if(isset($_GET['id']) AND $_GET['id'] != "" AND is_numeric($_GET['id'])){
	    $data["id"] = $_GET['id'];
	    $db->consulta_bd("SELECT * 
                          FROM mediakit 
                          WHERE media_id = ".$data["id"]);
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
    echo $twig->render('visualiza_mediakit.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Visualização de Media Kit",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ]);

?>
