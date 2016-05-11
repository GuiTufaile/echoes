<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/functions.php"; 

    // Inicializa DB
    $db = new classMySQL(); 

    // Verifica ação de retorno
    $return = verifyReturn("responsaveis",(isset($_REQUEST["return"]))?$_REQUEST["return"]:"");

    // Inicializa flag de validação
    $valida = true;

    // Arrays de campos
    $responsavel = [
        "responsavel_nome"     => "",
        "responsavel_telefone" => "",
        "responsavel_email"    => "",
        "credenciado_id"       => ""
    ];   
    $usuario = [
        "usuario_nome"       => "",
        "usuario_email"      => "",
        "usuario_senha"      => "",
        "usuario_privilegio" => "",
    ];   

    // Valida dados do responsável
    if(isset($_POST['nome']) AND $_POST['nome'] != ""){
        $responsavel["responsavel_nome"] = $_POST['nome'];
        $usuario["usuario_nome"] = $_POST['nome'];    
    } else{
        $valida = false;
    }
    if(isset($_POST['email']) AND $_POST['email'] != ""){
        $responsavel["responsavel_email"] = $_POST['email'];
        $usuario["usuario_email"] = $_POST['email'];    
    } else{
        $valida = false;
    }
    if(isset($_POST['senha']) AND $_POST['senha'] != ""){
        $usuario["usuario_senha"] = password_hash($_POST["senha"], PASSWORD_DEFAULT, ["cost" => 12]);
    } else{
        $valida = false;
    }
    if(isset($_POST['privilegio']) AND $_POST['privilegio'] != ""){
        $usuario["usuario_privilegio"] = $_POST['privilegio'];
    } else{
        $valida = false;
    }
    if($usuario["usuario_privilegio"] == 2 || $usuario["usuario_privilegio"] == 3){
        if(isset($_POST['credenciado']) AND $_POST['credenciado'] != ""){
            $responsavel["credenciado_id"] = $_POST['credenciado'];
        } else{
            $valida = false;
        }
        if(isset($_POST['telefone']) AND $_POST['telefone'] != ""){
            $responsavel["responsavel_telefone"] = $_POST['telefone'];
        }
    }

    // Executa escrita banco de dados
    if($valida){
        //Insere dados responsável
        if($usuario["usuario_privilegio"] == 2 || $usuario["usuario_privilegio"] == 3){
            $ultResponsavel = $db->inserir_dados('responsaveis', $responsavel);
            $usuario["usuario_referencia"] = $ultResponsavel;
        }
        // Insere dados usuário
        $ultUsuario = $db->inserir_dados('usuarios', $usuario);
        header("Location: ".ROOT_WEB."/usuarios/index.php?cadastrar=sucesso");
    } else{
        header("Location: ".ROOT_WEB."/usuarios/index.php?cadastrar=erro");
    }
?>