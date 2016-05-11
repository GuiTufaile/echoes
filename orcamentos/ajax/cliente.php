<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
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
    $data = [
		"cliente_nomefantasia" => "",
		"cliente_razaosocial"  => "",
		"cliente_cnpj"         => "",
		"cliente_telefone"     => "",
		"cliente_email"        => "",
		"cliente_endereco"     => "",
		"cliente_numero"       => "",
		"cliente_bairro"       => "",
		"cidade_nome"          => "",
		"estado_sigla"         => ""
    ];

    if(isset($_GET['id']) AND $_GET['id'] != "" AND is_numeric($_GET['id'])){
	    $data["cliente_id"] = $_GET['id'];
	    $db->consulta_bd("SELECT * FROM clientes c, estados est, cidades cid 
                      	  WHERE c.cidade_id = cid.cidade_id
                          AND c.estado_id = est.estado_id
                          AND cliente_id = ".$data["cliente_id"]);
	    $registro = mysqli_fetch_array($db->dados);
	    if($db->consulta_registros() == 1){
            $data = $registro;
	    }
	} 

    // Renderiza template
    echo $twig->render('form_orcamentos_adicional.html', [
        "root" => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "data" => $data,    
    ]);
       
?>
