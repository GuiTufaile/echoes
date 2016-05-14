<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require_once RESOURCE_PATH."/configs/privilege_responsavel.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 

    // Inicializa DB
    $db = new classMySQL(); 

    // Inicializa flag de validação
    $valida = true;

    // Processa dados de template
    $cliente = [
    /*    "credenciado_id"       => "",
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

    // Valida dados do cliente
    if(isset($_POST['nomefantasia']) AND $_POST['nomefantasia'] != ""){
        $cliente["cliente_nomefantasia"] = $_POST['nomefantasia'];
    } else{
        $valida = false;
    }
    if(isset($_POST['razaosocial']) AND $_POST['razaosocial'] != ""){
        $cliente["cliente_razaosocial"] = $_POST['razaosocial'];
    } else{
        $valida = false;
    }
    if(isset($_POST['cnpj']) AND $_POST['cnpj'] != ""){
        $cliente["cliente_cnpj"] = $_POST['cnpj'];
    } else{
        $valida = false;
    }
    if(isset($_POST['telefone']) AND $_POST['telefone'] != ""){
        $cliente["cliente_telefone"] = $_POST['telefone'];
    } else{
        $valida = false;
    }
    if(isset($_POST['email']) AND $_POST['email'] != ""){
        $cliente["cliente_email"] = $_POST['email'];
    } else{
        $valida = false;
    }
    if(isset($_POST['cep']) AND $_POST['cep'] != ""){
        $cliente["cliente_cep"] = $_POST['cep'];
    } else{
        $valida = false;
    }
    if(isset($_POST['pais']) AND $_POST['pais'] != ""){
        $cliente["pais_id"] = $_POST['pais'];
    } else{
        $valida = false;
    }
    if(isset($_POST['estado']) AND $_POST['estado'] != ""){
        $cliente["estado_id"] = $_POST['estado'];
    } else{
        $valida = false;
    }
    if(isset($_POST['cidade']) AND $_POST['cidade'] != ""){
        $cliente["cidade_id"] = $_POST['cidade'];
    } else{
        $valida = false;
    }
    if(isset($_POST['endereco']) AND $_POST['endereco'] != ""){
        $cliente["cliente_endereco"] = $_POST['endereco'];
    } else{
        $valida = false;
    }
    if(isset($_POST['bairro']) AND $_POST['bairro'] != ""){
        $cliente["cliente_bairro"] = $_POST['bairro'];
    } else{
        $valida = false;
    }
    if(isset($_POST['numero']) AND $_POST['numero'] != ""){
        $cliente["cliente_numero"] = $_POST['numero'];
    } else{
        $valida = false;
    }
    if(isset($_POST['complemento']) AND $_POST['complemento'] != ""){
        $cliente["cliente_complemento"] = $_POST['complemento'];
    }

    // Executa em escrita banco de dados
    if($valida){
        //Verifica CNPJ existente
        $db->consulta_bd("SELECT * FROM clientes 
                          WHERE cliente_cnpj = '".$cliente["cliente_cnpj"]."'");
        if($db->consulta_registros() == 0){
            //Insere dados cliente
            $cliente["credenciado_id"] = $useraccred;
            $ultcliente = $db->inserir_dados('clientes', $cliente);
            header("Location: ".ROOT_WEB."/clientes/index.php?cadastrar=sucesso");
        } else{
            header("Location: ".ROOT_WEB."/clientes/index.php?cadastrar=erro");
        }
    } else{
        header("Location: ".ROOT_WEB."/clientes/index.php?cadastrar=erro");
    }
?>