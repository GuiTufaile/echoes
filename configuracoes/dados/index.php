<?php 

    // Includes e requires
    require_once "../../config.php";
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
    $return = verifyReturn("configuracoes",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

    // Processa dados de template
    $data = [
        "credenciado_nomefantasia" => "",  
        "credenciado_razaosocial"  => "",
        "credenciado_cnpj"         => "",
        "credenciado_logo"         => "",
        "credenciado_slogan"       => "",
        "credenciado_telefone"     => "",
        "credenciado_email"        => "",
        "credenciado_cep"          => "",
        "pais_id"                  => "",
        "estado_id"                => "",
        "cidade_id"                => "",
        "credenciado_endereco"     => "",
        "credenciado_bairro"       => "",
        "credenciado_numero"       => ""
    ];

    $db->consulta_bd("SELECT * 
                      FROM credenciados 
                      WHERE credenciado_id = ".$useraccred);
    $registro = mysqli_fetch_array($db->dados);
    if($db->consulta_registros() == 1){
        $data = $registro;
    } else{
        $alert["type"] = "editar";
        $alert["value"] = "erro";
    }

    // Renderiza template
    echo $twig->render('edit_dados.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Editar dados credênciado",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ]);

?>
