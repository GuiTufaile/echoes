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

    // Verificamos se o nome do tamanho está setado.
    if(isset($_POST['id']) AND $_POST['id'] != "" AND is_numeric($_POST['id'])){
        $id = $_POST['id'];
        $db->consulta_bd("SELECT * FROM orcamentos WHERE id = $id");
        $registro = mysqli_fetch_array($db->dados);
        if($db->consulta_registros() == 1){
            
            if(isset($_POST['cliente_id']) AND $_POST['cliente_id'] != ""){
                $cliente_id = ($_POST['cliente_id']);
            } else{
                $cliente_id = "";
            }

            if(isset($_SESSION['usuarioId']) AND $_SESSION['usuarioId'] != ""){
                $credenciado_id = $_SESSION['usuarioId'];
            } else{
                $credenciado_id = "";
            }

            if(isset($_POST['area']) AND $_POST['area'] != ""){
                $area = ($_POST['area']);
            } else{
                $area = "";
            }

            if(isset($_POST['valor']) AND $_POST['valor'] != ""){
                $valor = ($_POST['valor']);
            } else{
                $valor = "";
            }

            if(isset($_POST['desconto']) AND $_POST['desconto'] != ""){
                $desconto = ($_POST['desconto']);
            } else{
                $desconto = "";
            }

            if(isset($_POST['tipoDesconto']) AND $_POST['tipoDesconto'] != ""){
                $tipoDesconto = ($_POST['tipoDesconto']);
            } else{
                $tipoDesconto = "";
            }

            if(isset($_POST['formaPagamento']) AND $_POST['formaPagamento'] != ""){
                $formaPagamento = ($_POST['formaPagamento']);
            } else{
                $formaPagamento = "";
            }

            if(isset($_POST['observacoes']) AND $_POST['observacoes'] != ""){
                $observacoes = ($_POST['observacoes']);
            } else{
                $observacoes = "";
            }

            // Setamos em qual tabela iremos fazer a inserção dos dados.
            $tabela = 'orcamentos';
            $string = "id = $id";

            // Iniciamos o array com os campos do banco => dados a serem inseridos.
            $dados = array(
                "cliente_id"       => "$cliente_id",
                "credenciado_id"   => "$credenciado_id",
                "area"            => "$area",
                "valor"           => "$valor",
                "desconto"        => "$desconto",
                "tipoDesconto"    => "$tipoDesconto",
                "formaPagamento"  => "$formaPagamento",
                "observacoes"     => "$observacoes",
                "status"          => 1
            );

            // Inserimos as informações no banco de dados.
            $db->alterar_dados($tabela, $dados, $string);        
            header("Location: ../index.php?editar=sucesso");
        } else{
           header("Location: ../index.php?editar=erro"); 
       }
    } else{
        header("Location: ../index.php?editar=erro");
    }
    
?>