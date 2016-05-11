<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require_once RESOURCE_PATH."/configs/privilege_responsavel.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/classes/class.upload.php"; 
    include RESOURCE_PATH."/php/classes/functions.php"; 
    
    // Inicializa DB
    $db = new classMySQL(); 

    // Inicializa flag de validação
    $valida = true;

    // Arrays de campos
    $cliente = [
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
    ];

    // Valida dados do cliente
    if(isset($_POST['id']) AND $_POST['id'] != "" AND is_numeric($_POST['id'])){
        $id = $_POST['id'];
        $db->consulta_bd("SELECT * 
                          FROM clientes 
                          WHERE cliente_id = ".$id);
        $registro = mysqli_fetch_array($db->dados);
        if($db->consulta_registros() == 1){
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

            if($valida){
                // Execução alteração em banco de dados
                $db->alterar_dados('clientes', $cliente, "cliente_id = ".$id);
                header("Location: ".ROOT_WEB."/clientes/index.php?cadastrar=sucesso");
            } else{
                header("Location: ".ROOT_WEB."/clientes/index.php?cadastrar=erro");
            }

        } else{
            header("Location: ".ROOT_WEB."/clientes/index.php?editar=erro");
        }
    } else{
        header("Location: ".ROOT_WEB."/clientes/index.php?editar=erro");
    }   
    
?>