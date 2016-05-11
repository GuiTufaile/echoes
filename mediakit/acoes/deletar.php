<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/functions.php"; 

    // Inicializa DB
    $db = new classMySQL(); 

    // Valida dados do mediakit
    if(isset($_GET['id']) AND $_GET['id'] != "" AND is_numeric($_GET['id'])){
        $id = $_GET['id'];
        $db->consulta_bd("SELECT * 
                          FROM mediakit 
                          WHERE media_id = ".$id);
        $registro = mysqli_fetch_array($db->dados);
        if($db->consulta_registros() > 0){
            // Executa remoção em banco de dados
            $db->deletar_dados("mediakit", "media_id = ".$id);
            // Executa remoção de arquivo
            if($registro["media_tipo"] == 1){
                unlink(ROOT."/files/images/".$registro["media_arquivo"]);
            } else if($registro["media_tipo"] == 2){
                unlink(ROOT."/files/pdf/".$registro["media_arquivo"]);
            } else{
                unlink(ROOT."/files/compacted/".$registro["media_arquivo"]);
            }
            header("Location: ".ROOT_WEB."/mediakit/index.php?deletar=sucesso");
        } else{
           header("Location: ".ROOT_WEB."/mediakit/index.php?deletar=erro");
       }
    } else{
        header("Location: ".ROOT_WEB."/mediakit/index.php?deletar=erro");
    }
    
?>