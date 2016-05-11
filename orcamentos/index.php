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

    if(isset($_GET['credenciado'])){
        
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
        $db->consulta_bd("SELECT * FROM orcamentos o, clientes c, responsaveis r
                          WHERE o.responsavel_id = r.responsavel_id
                          AND o.cliente_id = c.cliente_id
                          AND r.credenciado_id = ".$data["credenciado_id"]."
                          ORDER BY o.orcamento_timestamp DESC ");                                 
        $data['total'] = $db->consulta_registros();

    } elseif(isset($_GET['cliente'])){

        if(isset($_GET['id']) AND $_GET['id'] != ""){
            $data["cliente_id"] = $_GET['id'];
        } else {
            $alert["type"] = "listar";
            $alert["value"] = "erro";
        }
        $db->consulta_bd("SELECT * FROM orcamentos o, clientes c, responsaveis r
                          WHERE o.responsavel_id = r.responsavel_id
                          AND o.cliente_id = c.cliente_id
                          AND c.cliente_id = ".$data["cliente_id"]."
                          ORDER BY o.orcamento_timestamp DESC ");                                 
        $data['total'] = $db->consulta_registros();

    }
    else{
        $alert["type"] = "listar";
        $alert["value"] = "erro";
    }

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
        if($status["evento_status"] == 1){
            $registro["evento_nome"] = "Orçamento Cadastrado";
        } elseif($status["evento_status"] == 2){
            $registro["evento_nome"] =  "Orçamento Enviado";
        } elseif($status["evento_status"] == 3){
            $registro["evento_nome"] =  "Orçamento Aprovado";
        } elseif($status["evento_status"] == 4){
            $registro["evento_nome"] =  "Trabalho em Execução";
        } elseif($status["evento_status"] == 5){
            $registro["evento_nome"] =  "Tranbalho Executado";
        } elseif($status["evento_status"] == 5){
            $registro["evento_nome"] =  "Certificado Enviado";
        } else{
            $registro["evento_nome"] =  "-";
        }
        $registro["evento_status"] = $status["evento_status"];
        $registro["evento_timestamp"] = $status["evento_timestamp"];
        $data['orcamentos'][] = $registro;
    }

    // Renderiza template
    echo $twig->render('list_orcamentos.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Listagem de Orçamentos",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ]);

?>


