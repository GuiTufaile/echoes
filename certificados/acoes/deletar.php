<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require_once RESOURCE_PATH."/configs/privilege_responsavel.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/classes/functions.php"; 
   
    // Inicio o objeto da classe de manipulação do MySQL.
    $db = new classMySQL();

    if(isset($_GET['id']) AND $_GET['id'] != "" AND is_numeric($_GET['id'])){
        $id = $_GET['id'];
        $db->consulta_bd("SELECT * FROM orcamentos WHERE id = $id");
        $registro = mysqli_fetch_array($db->dados);
        if($db->consulta_registros() > 0){
            $tabela = "orcamento";
            $dados = "id = $id";
            $db->deletar_dados($tabela, $dados);
            header("Location: ../index.php?deletar=sucesso");
        } else{
           header("Location: ../index.php?deletar=erro"); 
       }
    } else{
        header("Location: ../index.php?deletar=erro");
    }

?>