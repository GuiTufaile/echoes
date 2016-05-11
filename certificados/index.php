<?php 

    // Includes e requires
    require_once "../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require_once RESOURCE_PATH."/configs/privilege_responsavel.php";
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
    $return = verifyReturn("orcamentos",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

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

    $db->consulta_bd("SELECT * FROM orcamentos o, clientes cl, responsaveis r, certificados ce
                      WHERE o.responsavel_id = r.responsavel_id
                      AND o.cliente_id = cl.cliente_id
                      AND ce.orcamento_id = o.orcamento_id
                      AND r.credenciado_id = ".$data["credenciado_id"]."
                      ORDER BY o.orcamento_timestamp DESC ");                                 
    $data['total'] = $db->consulta_registros();

    while($registro = mysqli_fetch_array($db->dados)){
          $db2->consulta_bd("SELECT *
                             FROM eventos
                             WHERE evento_tipo = 1
                             AND evento_referencia = ".$registro["orcamento_id"]."
                             AND evento_timestamp = (
                                 SELECT MAX(evento_timestamp)
                                 FROM eventos
                                 WHERE evento_tipo = 1
                                 AND evento_referencia = ".$registro["orcamento_id"].")"); 
        $status = mysqli_fetch_array($db2->dados);
        $registro["evento_status"] = $status["evento_status"];
        $data['orcamentos'][] = $registro;
    }

    // Renderiza template
    echo $twig->render('list_certificados.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Listagem de Certificados",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ]);

?>