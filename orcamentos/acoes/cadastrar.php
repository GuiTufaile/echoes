<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require_once RESOURCE_PATH."/configs/privilege_responsavel.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/classes/class.upload.php"; 
    include RESOURCE_PATH."/php/functions.php"; 
    
    // Inicializa DB
    $db = new classMySQL(); 

    // Inicializa flag de validação
    $valida = true;

    // Arrays de campos
    $orcamento = [
        "cliente_id"               => "",
        "responsavel_id"           => "",
        "orcamento_area"           => "",
        "orcamento_estado"         => "",
        "orcamento_valor"          => "",
        "orcamento_valortotal"     => "",
        "orcamento_desconto"       => "",
        "orcamento_tipodesconto"   => "",
        "orcamento_pagamento"      => "",
        "orcamento_formapagamento" => "",
        "orcamento_validade"       => "",
        "orcamento_observacoes"    => ""
    ];
    $evento = [
        "evento_tipo"       => "",
        "evento_referencia" => ""
    ];

    // Valida dados do orcamento
    if(isset($_POST['cliente']) AND $_POST['cliente'] != ""){
        $orcamento["cliente_id"] = $_POST['cliente'];
    } else{
        $valida = false;
    }
    if(isset($_POST['responsavel']) AND $_POST['responsavel'] != ""){
        $orcamento["responsavel_id"] = $_POST['responsavel'];
    } else{
        $valida = false;
    }
    if(isset($_POST['area']) AND $_POST['area'] != ""){
        $orcamento["orcamento_area"] = $_POST['area'];
    } else{
        $valida = false;
    }
    if(isset($_POST['estado']) AND $_POST['estado'] != ""){
        $orcamento["orcamento_estado"] = $_POST['estado'];
    } else{
        $valida = false;
    }
    if(isset($_POST['valor']) AND $_POST['valor'] != ""){
        $orcamento["orcamento_valor"] = $_POST['valor'];
    } else{
        $valida = false;
    }
    if(isset($_POST['valortotal']) AND $_POST['valortotal'] != ""){
        $orcamento["orcamento_valortotal"] = $_POST['valortotal'];
    } else{
        $valida = false;
    }
    if(isset($_POST['desconto']) AND $_POST['desconto'] != ""){
        $orcamento["orcamento_desconto"] = $_POST['desconto'];
    }
    if(isset($_POST['tipodesconto']) AND $_POST['tipodesconto'] != ""){
        $orcamento["orcamento_tipodesconto"] = $_POST['tipodesconto'];
    }
    if(isset($_POST['pagamento']) AND $_POST['pagamento'] != ""){
        $orcamento["orcamento_pagamento"] = $_POST['pagamento'];
    } else{
        $valida = false;
    }
    if(isset($_POST['formapagamento']) AND $_POST['formapagamento'] != ""){
        $orcamento["orcamento_formapagamento"] = $_POST['formapagamento'];
    }
    if(isset($_POST['validade']) AND $_POST['validade'] != ""){
        $orcamento["orcamento_validade"] = $_POST['validade'];
    } else{
        $valida = false;
    }
    if(isset($_POST['observacoes']) AND $_POST['observacoes'] != ""){
        $orcamento["orcamento_observacoes"] = $_POST['observacoes'];
    }

    // Executa escrita banco de dados
    if($valida){
        // Insere dados orcamento
        $ultorcamento = $db->inserir_dados('orcamentos', $orcamento);
        $evento["evento_status"] = 1;
        $evento["evento_tipo"] = 1;
        $evento["evento_referencia"] = $ultorcamento;
        $db->inserir_dados('eventos', $evento);
        header("Location: ".ROOT_WEB."/orcamentos/index.php?cadastrar=sucesso&credenciado");
    } else{
        header("Location: ".ROOT_WEB."/orcamentos/index.php?cadastrar=erro&credenciado");
    }
?>