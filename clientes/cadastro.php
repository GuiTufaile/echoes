<?php 

    // Includes e requires
    require_once "../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/verify_error.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/functions.php"; 

    // Inicia templates
    require_once RESOURCE_PATH.'/php/libs/Twig/Autoloader.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem(RESOURCE_PATH.'/templates');
    $twig = new Twig_Environment($loader, $twig_config);

    // Verifica ação de retorno
    $return = verifyReturn("clientes",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");
/*

-idPessoa
PessoaTipo
PessoaRazao
PessoaFantasia
PessoaBairro
PessoaLogradouro
PessoaNumero
PessoaComplemento
Cidades_idCidade
PessoaCNPJ
PessoaCPF
PessoaLogo
PessoaDatacadastro
PessoaStatus
PessoaCasestatus
PessoaCaseNome
PessoaCase
-idContato
-Pessoas_idPessoa
ContatoNome
ContatoObs
ContatoPrincipal
-Cargos_idCargo
-Setores_idSetor
ContatoPermissaologin
-idCargo
CargoNome
-idSetor
SetorNome
-idCidade
CidadeNome
Estados_idEstado
-idEstado
EstadoNome
EstadoSigla
-Paises_idPais
-idPais
PaisNome
PaisLang

*/
    // Processa dados de template
    $data = [
    /*
        "cliente_nomefantasia" => "",
        "cliente_razaosocial"  => "",
        "cliente_cnpj"         => "",
        "cliente_telefone"     => "",
        "cliente_email"        => "",
        "cliente_cep"          => "",
        "pais_id"              => "",
        "estado_id"            => "",
        "cidade_id"            => "",
        "cliente_endereco"     => "",
        "cliente_bairro"       => "",
        "cliente_numero"       => "",
        "cliente_complemento"  => ""
       */
    ];

    // Renderiza template
    echo $twig->render('add_clientes.html', [
        "root"      => ROOT_WEB,
        "resources" => RESOURCE_PATH_WEB,
        "title"     => "Cadastro de Clientes",
        "usertype"  => $usertype,
        "userid"    => $userid,
        "username"  => $username,
        "data"      => $data,
        "alert"     => $alert,
        "error"     => $error,
        "return"    => $return          
    ]);

?>