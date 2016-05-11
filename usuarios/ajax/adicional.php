<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 

    // Inicializa DB
    $db = new classMySQL(); 

    // Inicia templates
    require_once RESOURCE_PATH.'/php/libs/Twig/Autoloader.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem(RESOURCE_PATH.'/templates');
    $twig = new Twig_Environment($loader, $twig_config);

    // Processa dados de template
    $data = [];

    $db->consulta_bd("SELECT credenciado_id, credenciado_nomefantasia 
                      FROM credenciados");     

    while($registro = mysqli_fetch_array($db->dados)){
        $data['credenciados'][] = $registro;
    }

    // Renderiza template
    echo $twig->render('form_usuarios_adicional.html', [
        "root" => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "data" => $data,    
    ]);
       
?>