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
    $return = verifyReturn("clientes",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

    // Processa dados de template
    $data = [];

	$db->consulta_bd("SELECT * FROM view_pessoa                       
                      ORDER BY PessoaFantasia DESC");

	$data['total'] = $db->consulta_registros();

    while($registro = mysqli_fetch_array($db->dados)){
        $db2->consulta_bd("SELECT EmailEndereco as endereco
                           FROM emails 
                           WHERE EmailPrincipal = 1
                           AND Contatos_idContato = ".$registro["idContato"]); 
        $email = mysqli_fetch_array($db2->dados);
        $registro['EnderecoEmail'] = $email["endereco"];
        $data['clientes'][] = $registro;
    }

    // Renderiza template
    echo $twig->render('list_clientes.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Listagem de Clientes",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ]);

?>	