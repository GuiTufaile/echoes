<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/functions.php"; 

    // Inicializa DB
    $db = new classMySQL(); 

    // Inicializa flag de validação
    $valida = true;

    // Arrays de campos 
    $usuario = [
        "usuario_senha" => ""
    ];   

    // Valida dados do responsável
    if(isset($_POST['id']) AND $_POST['id'] != "" AND is_numeric($_POST['id'])){
        $id = $_POST['id'];
        $db->consulta_bd("SELECT * 
                          FROM usuarios 
                          WHERE usuario_id = ".$id);
        $registro = mysqli_fetch_array($db->dados);
        if($db->consulta_registros() == 1){
              if(isset($_POST['senha']) AND $_POST['senha'] != ""){
                    $usuario["usuario_senha"] = password_hash($_POST["senha"], PASSWORD_DEFAULT, ["cost" => 12]);
                } else{
                    $valida = false;
                }

            if($valida){
                // Execução alteração em banco de dados
                $db->alterar_dados('usuarios', $usuario, "usuario_id = ".$id);
                header("Location: ".ROOT_WEB."/usuarios/index.php?editar=sucesso");
            } else{
                header("Location: ".ROOT_WEB."/usuarios/index.php?editar=erro");
            }

        } else{
            header("Location: ".ROOT_WEB."/usuarios/index.php?editar=erro");
        }
    } else{
        header("Location: ".ROOT_WEB."/usuarios/index.php?editar=erro");
    }   
    
?>