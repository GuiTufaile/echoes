<?php 

    // Includes e requires
    require_once "../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    //require_once RESOURCE_PATH."/configs/privilege_parceiro.php";
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
    $return = verifyReturn("dashboard",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

    // Processa dados de template
    $data = [];

    // Renderiza template
    echo $twig->render('dashboard.html', array(
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Dashboard",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ));

?>