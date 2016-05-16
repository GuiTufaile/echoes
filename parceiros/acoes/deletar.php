<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/classes/functions.php"; 
    
    // Inicializa DB
    $db = new classMySQL(); 

    // Valida dados do credenciado
    if(isset($_POST['id']) AND $_POST['id'] != "" AND is_numeric($_POST['id'])){
        $id = $_POST['id'];

        $db->consulta_bd("SELECT * 
                          FROM credenciados 
                          WHERE credenciado_id = ".$id);
        $registro = mysqli_fetch_array($db->dados);
        if($db->consulta_registros() > 0){
            $db->deletar_dados("credenciados", "credenciado_id = ".$id);
            header("Location: ".ROOT_WEB."/credenciados/index.php?deletar=sucesso");
        } else{
           header("Location: ".ROOT_WEB."/credenciados/index.php?deletar=erro"); 
       }
    } else{
        header("Location: ".ROOT_WEB."/credenciados/index.php?deletar=erro");
    }
    
?>