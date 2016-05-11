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

    $db->consulta_bd("SELECT *  
                      FROM mediakit");                                    
    $data['total'] = $db->consulta_registros();

    while($registro = mysqli_fetch_array($db->dados)){
	    if($registro["media_tipo"] == 1){
	        $registro["media_tipo"] = "Imagem";
	        $registro["visualizar"] = true;
	    } elseif($registro["media_tipo"] == 2){
	        $registro["media_tipo"] =  "PDF";
	        $registro["visualizar"] = true;
	    } elseif($registro["media_tipo"] == 3){
	        $registro["media_tipo"] =  "Arquivo Compactado";
	        $registro["visualizar"] = false;
	    } else{
	        $registro["media_tipo"] =  "Arquivo";
	        $registro["visualizar"] = false;
	    }
	    $data['mediakits'][] = $registro;
	}
	
    // Renderiza template
    echo $twig->render('list_mediakit.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Listagem de Credenciados",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ]);

?>
