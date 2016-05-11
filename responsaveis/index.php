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
    $return = verifyReturn("responsaveis",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

    // Processa dados de template
    $data = [];

    if($usertype == "administrador"){ 
        if(isset($_GET['id']) AND $_GET['id'] != ""){
            $data["credenciado_id"] = $_GET['id'];
        } else {
            $alert["type"] = "listar";
            $alert["value"] = "erro";
        }
    } else{
        $data["credenciado_id"] = $useraccred;
    }

    $db->consulta_bd("SELECT * FROM usuarios u 
                      LEFT JOIN responsaveis r 
                      ON u.usuario_referencia = r.responsavel_id
                      WHERE (u.usuario_privilegio = 2 OR u.usuario_privilegio = 3)
                      AND r.credenciado_id = ".$data["credenciado_id"]);                           
    $data['total'] = $db->consulta_registros();

    while($registro = mysqli_fetch_array($db->dados)){
        $data['responsaveis'][] = $registro;
    }

    // Renderiza template
    echo $twig->render('list_responsaveis.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Listagem de Responsáveis",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ]);

?>