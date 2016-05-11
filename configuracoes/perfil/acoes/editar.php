<?php 

    // Includes e requires
    require_once "../../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require_once RESOURCE_PATH."/configs/privilege_responsavel.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/functions.php"; 

    // Inicializa DB
    $db = new classMySQL(); 

    // Inicializa flag de validação
    $valida = true;

    // Arrays de campos
    $responsavel = [
        "responsavel_telefone" => "",
        "responsavel_email" => "",
    ];   
    $usuario = [
        "usuario_email" => "",
        "usuario_senha" => ""
    ];   

    // Valida dados do responsável
    $db->consulta_bd("SELECT * 
                      FROM usuarios 
                      WHERE usuario_id = ".$userid);
    $registro = mysqli_fetch_array($db->dados);
    if($db->consulta_registros() == 1){
        if(isset($_POST['telefone']) AND $_POST['telefone'] != ""){
            $responsavel["responsavel_telefone"] = $_POST['telefone'];
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
            unset($usuario["usuario_senha"]);
        }

        if($valida){
            // Execução alteração em banco de dados
            $db->alterar_dados('usuarios', $usuario, "usuario_id = ".$userid);
            if($usertype == "credenciado" || $usertype == "responsavel"){
                $db->alterar_dados('responsaveis', $responsavel, "responsavel_id = ".$registro["usuario_referencia"]);
            }
            header("Location: ".ROOT_WEB."/configuracoes/perfil/index.php?editar=sucesso");
        } else{
            header("Location: ".ROOT_WEB."/configuracoes/perfil/index.php?editar=erro");
        }

    } else{
        header("Location: ".ROOT_WEB."/configuracoes/perfil/index.php?editar=erro");
    }
  
?>