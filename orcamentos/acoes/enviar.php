<?php

    // Includes e requires
    require_once "../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/envioEmail.php";

    $db = new classMySQL();
    if(isset($_POST['email']) AND $_POST['email'] != ""){
        $email = $_POST['email'];
        $id    = $_POST['id']; 

        // HTML //
        /*
        DEFINIR e REMOVER AS VARIÁVEIS DO COMENTÁRIO:
        $cliente = Nome Fantasia do Cliente (Destinatário)
        $credenciado = Nome fantasia do Credenciado (remetente)
        $credemail = E-mail do Credenciado
        $credtel Telefone do Credenciado
        $credresp Responsável pelo orçamento
        */
        $html = "
        Prezado cliente $cliente, <br />
        Nós da $credenciado, recebemos sua solicitação de orçamento que segue anexo em PDF. <br />
        <br />
        <b>Por favor, responda esse e-mail para $credemail ou ligue para $credtel </b> <br />
        <br />
        Agradecemos pela preferência, <br />        
        EMPRESA $credresp.";

        $SMTP = 'SMTP';
        $dados = array("PARA"          => $email,
                 "DE"            => 'smtp@hostdry.com.br',
                 "ASSUNTO"       => utf8_decode('$credenciado - Solicitação de Orçamento - TGM Hostdry'),
                 "MENSAGEM"      => utf8_decode($html),
                 "ANEXO_CAMINHO" => "../../orcamentos/salvos/#".$id.".pdf"
                );
           
        if (Email::Enviar($dados, $SMTP)){
            $tabela = 'orcamentos';
            $string = "id = $id";
            $dados = array("enviado" => "1");
            $db->alterar_dados($tabela, $dados, $string);

            $data['status'] = "sucesso";
            echo json_encode($data);
        } else{
            $data['status'] = "erro";
            echo json_encode($data);
        }
    }
    
?>