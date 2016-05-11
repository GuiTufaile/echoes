<?php

    // Includes e requires
    require_once "../config.php";
    require_once RESOURCE_PATH."/configs/privilege_administrador.php";
    require_once RESOURCE_PATH."/configs/privilege_credenciado.php";
    require_once RESOURCE_PATH."/configs/privilege_responsavel.php";
    require RESOURCE_PATH."/php/verify_session.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
    include RESOURCE_PATH."/php/libs/Dompdf/dompdf_config.inc.php"; 

	// Inicializa DB
    $db = new classMySQL(); 

    // Inicia templates
    require_once RESOURCE_PATH.'/php/libs/Twig/Autoloader.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem(RESOURCE_PATH.'/templates');
   	$twig = new Twig_Environment($loader, $twig_config);

    // Processa dados de template
    $data = [
		"dataServico"  => "",
		"Crazaosocial" => "",
		"Ccnpj"        => "",
		"Cestado"      => "",
		"Ccidade"      => "",
		"Cendereco"    => "",
		"Cbairro"      => "",
		"Cnumero"      => "",
		"nomefantasia" => "",
		"razaosocial"  => "",
		"cnpj"         => "",
		"telefone"     => "",
		"email"        => "",
		"estado"       => "",
		"cidade"       => "",
		"endereco"     => "",
		"bairro"       => "",
		"numero"       => ""
    ];

	if(isset($_GET['id']) AND $_GET['id'] != "" AND is_numeric($_GET['id'])){
		$data["id"] = $_GET['id'];
		$db->consulta_bd("SELECT * FROM vw_orcamentos WHERE id = ".$data["id"]);
	    $registro = mysqli_fetch_array($db->dados);
		if($db->consulta_registros() > 0){
			$data["dataServico"]  = $registro["dataServico"];
			
			$data["Crazaosocial"] = $registro["cliRazao"];
			$data["Ccnpj"]        = $registro["cliCnpj"];
			$data["Cestado"]      = $registro["cliEstado"];
			$data["Ccidade"]      = $registro["cliCidade"];
			$data["Cendereco"]    = $registro["cliEndereco"];
			$data["Cbairro"]      = $registro["cliBairro"];
			$data["Cnumero"]      = $registro["cliNumero"];
			
			$data["nomefantasia"] = $registro["nomefantasia"];
			$data["razaosocial"]  = $registro["razaosocial"];
			$data["cnpj"]         = $registro["cnpj"];
			$data["telefone"]     = $registro["telefone"];
			$data["email"]        = $registro["email"];
			$data["estado"]       = $registro["estado"];
			$data["cidade"]       = $registro["cidade"];
			$data["endereco"]     = $registro["endereco"];
			$data["bairro"]       = $registro["bairro"];
			$data["numero"]       = $registro["numero"];
		}
	}

	// Renderiza html
	$html = $twig->render('pdf_certificado.html', [
		"resources" => RESOURCE_PATH_WEB,
		"data" => $data
	]);

	// Renderiza pdf
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->set_paper('A4','landscape');
	$dompdf->render();
	$pdf = $dompdf->output();

	// Salva copia de certificado gerado
	$nomeOrcamento = utf8_decode("Certificado");
	$file_location = ROOT."/files/certificados/#".$id.".pdf";
	file_put_contents($file_location,$pdf); 

	// Output
	$dompdf->stream("#".$id.".pdf", array("Attachment" => false));
	$dompdf->debugcss(true);

?>