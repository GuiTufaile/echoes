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
        $html = "
          Prezado cliente, <br />
          Segue anexo o certificado do serviço solicitado. <br />
          Agradecemos pela preferência. <br />
          Att., <br />
          EMPRESA XXXXXX
        ";

        $SMTP = 'SMTP';
        $dados = array("PARA"          => $email,
                       "DE"            => 'smtp@hostdry.com.br',
                       "ASSUNTO"       => utf8_decode('Certificado - TGM Hostdry'),
                       "MENSAGEM"      => utf8_decode($html),
                       "ANEXO_CAMINHO" => "../../certificados/salvos/#".$id.".pdf"
                      );
                 
        if (Email::Enviar($dados, $SMTP)){      
            $tabela = 'orcamentos';
            $string = "id = $id";
            $dados = array("enviadoCert" => "1");
            $db->alterar_dados($tabela, $dados, $string);

            $data['status'] = "sucesso";
            echo json_encode($data);
        } else{
            $data['status'] = "erro";
            echo json_encode($data);
        }
    }

?>