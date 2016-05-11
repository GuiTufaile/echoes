<?php 

	header('Content-Type: text/html; charset=UTF-8');

	//Configuração de banco de dados
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASSWORD", "");
	define("DB_NAME", "echoesbd");

	//Paths to sistema
	define("PROJECT_FOLDER", "/echoes"); 

	define("ROOT", $_SERVER["DOCUMENT_ROOT"].PROJECT_FOLDER); 
	define("RESOURCE_PATH", ROOT."/resources");

	define("ROOT_WEB", "http://".$_SERVER["HTTP_HOST"].PROJECT_FOLDER); 
	define("RESOURCE_PATH_WEB", ROOT_WEB."/resources");

	//Inicialza privilegios
	$privileges = [];

	//Configuração geral Twig
	$twig_config = [
		//'cache'        => RESOURCE_PATH.'/templates/cache',
		//'auto_reload ' => true
	];

?>