<?php 

    // Includes e requires
    require_once "../../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/classes/class.upload.php"; 
    include RESOURCE_PATH."/php/functions.php"; 
    
    // Inicializa DB
    $db = new classMySQL(); 

    // Inicializa flag de validação
    $valida = true;

    // Arrays de campos
    $mediakit = [
        "media_nome" => "",
        "media_tipo" => "",
        "media_arquivo" => ""
    ];

    // Valida dados do mediakit
    // Processa dados
    if(isset($_POST['nome']) AND $_POST['nome'] != ""){
        $mediakit["media_nome"] = $_POST['nome'];
    } else{
        $valida = false;
    }
    if(isset($_POST['tipo']) AND $_POST['tipo'] != ""){
        $mediakit["media_tipo"] = $_POST['tipo'];
    } else{
        $valida = false;
    }
    if($_FILES['arquivo']['error'] == 0) {
        $arquivo = $_FILES['arquivo'];
        $mediakit["media_arquivo"] = sha1_file($arquivo["tmp_name"]);
        $handle = new upload($arquivo);
        if ($handle->uploaded){
            $handle->file_new_name_body = $mediakit["media_arquivo"];
            if($mediakit["media_tipo"] == 1){
                $folder = "images";
                $handle->allowed = array('image/*');
                $handle->image_resize = true;
                $handle->image_x = 800;
                $handle->image_ratio_y = true;
            } else if($mediakit["media_tipo"] == 2){
                $folder = "pdf";
                $handle->allowed = array('application/pdf');
            } else{
                $folder = "compacted";
                $handle->allowed = array('application/zip','application/x-compressed-zip','application/x-rar-compressed');
            }

        } else{
            $valida = false;
        }
    }

    // Executa upload de arquivo
    $handle->process(ROOT."/files/".$folder."/");
    $mediakit["media_arquivo"] = $handle->file_dst_name;
    if (!$handle->processed){
        $valida = false;
    }

    // Executa escrita em banco de dados
    if($valida){
        //Insere dados mediakit
        $db->inserir_dados('mediakit', $mediakit);
        header("Location: ".ROOT_WEB."/mediakit/index.php?cadastrar=sucesso");
    } else{
        header("Location: ".ROOT_WEB."/mediakit/index.php?cadastrar=erro");
    }

?>