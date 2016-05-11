<?php 

    // Includes e requires
    include_once "config.php";
    include RESOURCE_PATH."/php/verify_error.php";
    include RESOURCE_PATH."/php/functions.php"; 
    
    // Inicia sessão
    session_start();

    // Valida sessão
    if(!isset($_SESSION['usuarioId']) AND !isset($_SESSION['usuarioEmail']) AND !isset($_SESSION['usuarioNome'])){
        session_destroy();
    } else{
       header("Location: ".ROOT_WEB."/dashboard/index.php");
    }

    // Inicia templates
    require_once RESOURCE_PATH.'/php/libs/Twig/Autoloader.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem(RESOURCE_PATH.'/templates');
    $twig = new Twig_Environment($loader, $twig_config);

    // Verifica ação de retorno
    $return = verifyReturn("",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

    // Processa dados de template
    $data = [];

    // Renderiza template
    echo $twig->render('login.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Sistema Echoes",
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return  
    ]);

?>