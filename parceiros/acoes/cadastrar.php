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
    $credenciado = [
        "credenciado_nomefantasia" => "",  
        "credenciado_razaosocial" => "",
        "credenciado_cnpj" => "",
        "credenciado_logo" => "",
        "credenciado_slogan" => "",
        "credenciado_telefone" => "",
        "credenciado_email" => "",
        "credenciado_cep" => "",
        "pais_id" => "",
        "estado_id" => "",
        "cidade_id" => "",
        "credenciado_endereco" => "",
        "credenciado_bairro" => "",
        "credenciado_numero" => "",
        "credenciado_complemento" => ""
    ];

    // Valida dados do credenciado
    if(isset($_POST['nomefantasia']) AND $_POST['nomefantasia'] != ""){
        $credenciado["credenciado_nomefantasia"] = $_POST['nomefantasia'];
    } else{
        $valida = false;
    }
    if(isset($_POST['razaosocial']) AND $_POST['razaosocial'] != ""){
        $credenciado["credenciado_razaosocial"] = $_POST['razaosocial'];
    } else{
        $valida = false;
    }
    if(isset($_POST['cnpj']) AND $_POST['cnpj'] != ""){
        $credenciado["credenciado_cnpj"] = $_POST['cnpj'];
    } else{
        $valida = false;
    }
    if(isset($_POST['slogan']) AND $_POST['slogan'] != ""){
        $credenciado["credenciado_slogan"] = $_POST['slogan'];
    }
    if($_FILES['logo']['error'][0] == 0) {
        $logo = rearrange($_FILES['logo'])[0];
        $credenciado["credenciado_logo"] = sha1_file($logo["tmp_name"]);
        $handle = new upload($logo);
        if ($handle->uploaded){
            $handle->allowed = array('image/*');
            $handle->file_new_name_body = $credenciado["credenciado_logo"];
            $handle->image_resize = true;
            $handle->image_x = 400;
            $handle->image_ratio_y = true;
        } else{
            $valida = false;
        }
    }
    if(isset($_POST['telefone']) AND $_POST['telefone'] != ""){
        $credenciado["credenciado_telefone"] = $_POST['telefone'];
    } else{
        $valida = false;
    }
    if(isset($_POST['email']) AND $_POST['email'] != ""){
        $credenciado["credenciado_email"] = $_POST['email'];
    } else{
        $valida = false;
    }
    if(isset($_POST['cep']) AND $_POST['cep'] != ""){
        $credenciado["credenciado_cep"] = $_POST['cep'];
    } else{
        $valida = false;
    }
    if(isset($_POST['pais']) AND $_POST['pais'] != ""){
        $credenciado["pais_id"] = $_POST['pais'];
    } else{
        $valida = false;
    }
    if(isset($_POST['estado']) AND $_POST['estado'] != ""){
        $credenciado["estado_id"] = $_POST['estado'];
    } else{
        $valida = false;
    }
    if(isset($_POST['cidade']) AND $_POST['cidade'] != ""){
        $credenciado["cidade_id"] = $_POST['cidade'];
    } else{
        $valida = false;
    }
    if(isset($_POST['endereco']) AND $_POST['endereco'] != ""){
        $credenciado["credenciado_endereco"] = $_POST['endereco'];
    } else{
        $valida = false;
    }
    if(isset($_POST['bairro']) AND $_POST['bairro'] != ""){
        $credenciado["credenciado_bairro"] = $_POST['bairro'];
    } else{
        $valida = false;
    }
    if(isset($_POST['numero']) AND $_POST['numero'] != ""){
        $credenciado["credenciado_numero"] = $_POST['numero'];
    } else{
        $valida = false;
    }
    if(isset($_POST['complemento']) AND $_POST['complemento'] != ""){
        $credenciado["credenciado_complemento"] = $_POST['complemento'];
    }

    // Executa upload de arquivo
    $handle->process(ROOT."/files/images/logos/");
    $credenciado["credenciado_logo"] = $handle->file_dst_name;
    if (!$handle->processed){
        $valida = false;
    }

    // Executa em escrita banco de dados
    if($valida){
        //Verifica CNPJ existente
        $db->consulta_bd("SELECT * FROM credenciados 
                          WHERE credenciado_cnpj = '".$credenciado["credenciado_cnpj"]."'");
        if($db->consulta_registros() == 0){
            //Insere dados credenciado
            $ultCredenciado = $db->inserir_dados('credenciados', $credenciado);
            header("Location: ".ROOT_WEB."/responsaveis/cadastro.php?id=".$ultCredenciado."&return=credenciados");
        } else{
            header("Location: ".ROOT_WEB."/credenciados/index.php?cadastrar=erro");
        }
    } else{
        header("Location: ".ROOT_WEB."/credenciados/index.php?cadastrar=erro");
    }
?>