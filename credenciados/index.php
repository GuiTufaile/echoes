<?php 
    
    // Includes e requires
    require_once "../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/verify_error.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/functions.php"; 

    // Inicializa DB
    $db = new classMySQL(); 
    $db2 = new classMySQL(); 

    // Inicia templates
    require_once RESOURCE_PATH.'/php/libs/Twig/Autoloader.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem(RESOURCE_PATH.'/templates');
    $twig = new Twig_Environment($loader, $twig_config);

    // Verifica ação de retorno
    $return = verifyReturn("credenciados",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

    // Processa dados de template
    $data = [];
    
    $db->consulta_bd("SELECT * 
                      FROM credenciados c, estados est, cidades cid 
                      WHERE c.cidade_id = cid.cidade_id
                      AND c.estado_id = est.estado_id
                      ORDER BY credenciado_nomefantasia DESC");                                      
    $data['total'] = $db->consulta_registros();

    while($registro = mysqli_fetch_array($db->dados)){
        $db2->consulta_bd("SELECT count(*) as quantidade 
                           FROM clientes 
                           WHERE credenciado_id = ".$registro["credenciado_id"]); 
        $clientes = mysqli_fetch_array($db2->dados);
        $registro["credenciado_qtdcliente"] = $clientes["quantidade"];
        $data['credenciados'][] = $registro;
    }
    

    // Renderiza template
    echo $twig->render('list_credenciados.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Listagem de Credenciados",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error        
    ]);
?>
