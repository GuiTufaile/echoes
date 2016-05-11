<?php

    // Includes e requires
    require_once "../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require_once RESOURCE_PATH."/configs/privilege_responsavel.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/libs/Dompdf/dompdf_config.inc.php"; 
    include RESOURCE_PATH."/php/functions.php"; 

	// Inicializa DB
    $db = new classMySQL(); 

    // Inicia templates
    require_once RESOURCE_PATH.'/php/libs/Twig/Autoloader.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem(RESOURCE_PATH.'/templates');
    $twig = new Twig_Environment($loader, $twig_config);

    // Processa dados de template
    $data = [
		"nomefantasiaC"  => "",
		"razaosocialC"   => "",
		"sloganC"        => "",
		"cnpjC"          => "",
		"telefoneC"      => "",
		"responsavelC"   => "",
		"emailC"         => "",
		"cepC"           => "",
		"paisC"          => "",
		"estadoC"        => "",
		"cidadeC"        => "",
		"enderecoC"      => "",
		"bairroC"        => "",
		"numeroC"        => "",
		"complementoC"   => "",
		"statusC"        => "",
		"logoC"          => "",
		"dataC"          => "",
		"cliente_id"     => "",
		"credenciado_id" => "",
		"area"           => "",
		"valor"          => "",
		"desconto"       => "",
		"tipoDesconto"   => "",
		"formaPagamento" => "",
		"observacoes"    => "",
		"dataCadastro"   => "",
		"estado"         => ""
    ];

	if(isset($_GET['id']) AND $_GET['id'] != "" AND is_numeric($_GET['id'])) {
		$data["id"] = $_GET['id'];
		$db->consulta_bd("SELECT * FROM vw_orcamentos WHERE id = ".$data["id"]);
	    $registro = mysqli_fetch_array($db->dados);
		if($db->consulta_registros() > 0) {
			$data["nomefantasiaC"]  = $registro["nomefantasia"];
			$data["razaosocialC"]   = $registro["razaosocial"];
			$data["sloganC"]        = $registro["slogan"];
			$data["cnpjC"]          = $registro["cnpj"];
			$data["telefoneC"]      = $registro["telefone"];
			$data["responsavelC"]   = $registro["responsavel"];
			$data["emailC"]         = $registro["email"];
			$data["cepC"]           = $registro["cep"];
			$data["paisC"]          = $registro["pais"];
			$data["estadoC"]        = $registro["uf"];
			$data["cidadeC"]        = $registro["cidade"];
			$data["enderecoC"]      = $registro["endereco"];
			$data["bairroC"]        = $registro["bairro"];
			$data["numeroC"]        = $registro["numero"];
			$data["complementoC"]   = $registro["complemento"];
			$data["statusC"]        = $registro["status"];
			$data["logoC"]          = $registro["logo"];

			$data["dataC"]          = $registro["dataC"];
			$data["cliente_id"]     = $registro["cliente_id"];
			$data["credenciado_id"] = $registro["credenciado_id"];
			$data["area"]           = $registro["area"];
			$data["valor"]          = $registro["valor"];
			$data["desconto"]       = $registro["desconto"];
			$data["tipoDesconto"]   = $registro["tipoDesconto"];
			$data["formaPagamento"] = $registro["formaPagamento"];
			$data["observacoes"]    = $registro["observacoes"];
			$data["dataCadastro"]   = $registro["dataCadastro"];
			$data["estado"]         = $registro["estado"];

			if($data["estado"] == 1){$data["estado"] = "Muito Sujo";}
			if($data["estado"] == 2){$data["estado"] = "Sujo";}
			if($data["estado"] == 3){$data["estado"] = "Mediano";}

			if(isset($registro["tipoDesconto"]) AND $registro["tipoDesconto"] != ""){ 
	            if($registro["tipoDesconto"] == "v"){
					$data["valorArea"] = ($registro["valor"] * $registro["area"]) - $registro["desconto"];               
	            } else {
	                $data["valorArea"] = ($registro["area"] * $registro["valor"]) - (($registro["area"] * $registro["valor"]) * ($registro["desconto"]/100));
	            } 
	        } else{ 
	            $data["valorArea"] = $registro["valor"] * $registro["area"];
	        }

			$data["subtotal"]       = $registro["valor"] * $registro["area"];
			$data["validade"]       = date("d/m/Y", strtotime("+15 days",strtotime($data["dataC"])));
			
			$data["nomefantasia"]   = $registro["cliFantasia"];
			$data["razaosocial"]    = $registro["cliRazao"];
			$data["cnpj"]           = $registro["cliCnpj"];
			$data["telefone"]       = $registro["cliTelefone"];
			$data["responsavel"]    = $registro["cliResponsavel"];
			$data["email"]          = $registro["cliEmail"];
			$data["cep"]            = $registro["cliCep"];
			$data["pais"]           = $registro["cliPais"];
			$data["estado"]         = $registro["cliEstado"];
			$data["cidade"]         = $registro["cliCidade"];
			$data["endereco"]       = $registro["cliEndereco"];
			$data["bairro"]         = $registro["cliBairro"];
			$data["numero"]         = $registro["cliNumero"];
			$data["complemento"]    = $registro["cliComplemento"];
			$data["status"]         = $registro["cliStatus"];	

		}
	}

	// Renderiza html
	$html = $twig->render('pdf_orcamento.html', [  
		"resources" => RESOURCE_PATH_WEB,
		"username" => $username,
        "data" => $data
    ]);

	// Renderiza pdf
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->set_paper('A4','portrait');
	$dompdf->render();
	$pdf = $dompdf->output();

	// Salva copia de orçamento gerado
	$nomeOrcamento = utf8_decode("Orçamento");
	$file_location = ROOT."/files/orcamentos/#".$id.".pdf";
	file_put_contents($file_location,$pdf); 

	// Output
	$dompdf->stream("#".$id.".pdf", array("Attachment" => false));
	$dompdf->debugcss(true);	

?>