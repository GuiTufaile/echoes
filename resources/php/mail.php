<?php
/**
 * Classe envioEmail.php
 * Criada em: 05/10/2010, Por: Cauê Costa Neves
 * Direitos Reservados a Brunetti Comunicação
 * --------------------------------------------------------------------
 *
 * Modo de utilização:
 *	- Criar um array contendo os dados
 *		Ex: $dados = array("PARA" => "<email destino>", "DE" => "<email de envio>", ...);
 *		
 *   Valores obrigatórios: array("PARA"          =>"<OBRIGATÓRIO>", 
 *							   "DE"            =>"<OBRIGATÓRIO>", 
 *							   "COPIA"         =>"<NÃO OBRIGATÓRIO>",
 *							   "COPIAOCULTA"   =>"<NÃO OBRIGATÓRIO>",
 *							   "ASSUNTO"       =>"<OBRIGATÓRIO>",
 *							   "MENSAGEM"      =>"<OBRIGATÓRIO>",
 *							   "ANEXO_CAMINHO" =>"<NÃO OBRIGATÓRIO>");
 *							   
 *   Exemplo de envio: $retorno = Email::Enviar($dados, "SMTP");	
 *   		$retorno - TRUE ou FALSE						   
 *        Email::$error - Recupera o erro, caso tenha ocorrido
 *
 *   OBS: - PARA ENVIO COM ANEXO É OBRIGATÓRIO A UTILIZAÇÃO DO SMTP, POIS NÃO FOI IMPLEMENTADO NA FUNÇÃO mail()
 * --------------------------------------------------------------------- 		
 */



/**
 * Tempo de execução do script, caso seja 0, então ficará em execução, sem tempo determinado
 *
 * @param int 
 */
set_time_limit(0);


class Email {
			
	/**
	 * Configuração de Envio SMTP
	 *
	 * @value string HOST      => Endereço do servidor de saida para o envio de e-mails
	 * @value string EMAIL_AUT => Conta de e-mail utilizada para o envio dos e-mails
	 * @value string SENHA_AUT => Senha da conta de e-mail utilizada para o envio
	 * @value string NOME_MSG  => Nome que irá aparecer como remetente nos e-mails
	 */
	private static $configSMTP = array("HOST"      => "mail.hostdry.com.br",
									   "EMAIL_AUT" => "smtp@hostdry.com.br",
									   "SENHA_AUT" => "umquetenha7007",
									   "NOME_MSG"  => "TGM - HostDry Br");		
	
	/**
	 * Caminho do diretório onde esta a classe
	 * Pode ser utilizado o caminho absoluto "../../bibliotecas/email/", ou o caminho relativo "./"
	 *
	 * @param string $pathLibs
	 */
	private static $pathLibs = "./";
		
		
	/**
	 * Propriedade utilizada para armazenar os erros
	 *
	 * @param string $error
	 */
	public static $error = NULL;
	
	
	/**
	 * Método público utilizado para realizar o envio
	 *
	 * @param array $dados
	 * @param string $tipo => Por padrão esta setado para utilizar a função mail do servidor PHP
	 */
	 public static function Enviar($dados, $tipo = 'MAIL'){
		return ($tipo == 'SMTP') ? self::Autenticado($dados) : self::FuncaoMail($dados);	
	 }
		
		
	/**
	 * Método protegido, pode ser utilizado como herança.
	 * Envia o e-mail utilizando a função mail, nativa do PHP.
	 *
	 * @param array $dados
	 */
	 protected static function FuncaoMail($dados){
	  
		  try {
		  
				// monta o cabeçalho que será enviado na mensagem
				$cabecalhoEmail = "From: ".$dados['DE']."\n";
				$cabecalhoEmail .= "Reply-to: ".$dados['DE']."\n";
				
				 // verifica se é para enviar uma cópia ou cópia oculta
				 if (isset($dados['COPIA']) && !empty($dados['COPIA']))             
					$cabecalhoEmail .= "Cc: ".$dados['COPIA']."\n";
					
				 if (isset($dados['COPIAOCULTA']) && !empty($dados['COPIAOCULTA'])) 
					$cabecalhoEmail .= "Bcc: ".$dados['COPIAOCULTA']."\n";
				
				$cabecalhoEmail .= "Content-Type: text/html; charset=iso-8859-1\n";
				$cabecalhoEmail .= "X-Mailer: PHP5 Script Language\n";
				$cabecalhoEmail .= "X-Accept-Language: en\n";
				$cabecalhoEmail .= "MIME-Version: 1.0\n";
				$cabecalhoEmail .= "Content-Transfer-Encoding: 7bit\n";
						
				return (@mail($dados['PARA'], $dados['ASSUNTO'], nl2br($dados['MENSAGEM']), $cabecalhoEmail)) ? TRUE : FALSE;
		   
		  } catch(Exception $ex) {
			self::$error = $ex->getMessage();
			return FALSE;  
		  }
	 } 	
	 
	 
	 
	/**
	 * Método protegido, pode ser utilizado como herança.
	 * Envia o e-mail utilizando autenticação no servidor SMTP, é obrigatório a configuração da propriedade $pathLibs
	 *
	 * @param array $dados
	 */
	 protected static function Autenticado($dados){	 
	  require_once(self::$pathLibs.'class.phpmailer.php');
	  
			  try {
			  
						$phpmail = new PHPMailer();
						
						// não mexer nas configurações abaixo
						$phpmail->IsSMTP(); // envia por SMTP
						$phpmail->Host     = self::$configSMTP['HOST'];
						$phpmail->SMTPAuth = true; // Caso o servidor SMTP precise de autenticação
						$phpmail->Username = self::$configSMTP['EMAIL_AUT'];
						$phpmail->Password = self::$configSMTP['SENHA_AUT'];	    
						$phpmail->CharSet = "iso-8859-1";
						$phpmail->IsHTML(true);
						$phpmail->From     = $dados['DE'];
						$phpmail->FromName = self::$configSMTP['NOME_MSG'];
						$phpmail->AddAddress($dados['PARA']);
						$phpmail->Subject  = $dados['ASSUNTO'];	
						$phpmail->MsgHTML($dados['MENSAGEM']);
						
						 // verifica se é para enviar uma cópia
						 if (isset($dados['COPIA']) && !empty($dados['COPIA'])) 
							$phpmail->AddCC($dados['COPIA']);
							
						 // verifica se é para enviar uma cópia oculta	
						 if (isset($dados['COPIAOCULTA']) && !empty($dados['COPIAOCULTA'])) 
							$phpmail->AddBCC($dados['COPIAOCULTA']);
							
						// verifica se é para enviar um anexo	
						if ( isset($dados['ANEXO_CAMINHO']) && !empty($dados['ANEXO_CAMINHO']) ) 
							$phpmail->AddAttachment($dados['ANEXO_CAMINHO']);
												
						
						// Faz o envio, caso de erro dispara a excessão com a descrição do erro
						if($phpmail->Send())
						   return TRUE;
						else
						   throw new Exception($phpmail->ErrorInfo);
						
			  } catch(Exception $ex) {
				self::$error = $ex->getMessage();
				return FALSE;  
			  }
	 } 	
	 
	  
}
?>