<?php
	// =================================================================================================
	// FUNÇÕES DE CABEÇALHO
	// =================================================================================================
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Credential: true');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
	header('Content-Type: application/json; charset=UTF-8');
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 

	// =================================================================================================
	// FUNÇÕES DO PHP
	// =================================================================================================
	// ---------------------------------------------------------------------
	// Configura o fuso horário padrão utilizado 
	// por todas as funções de data e hora em um script
	// ---------------------------------------------------------------------
	date_default_timezone_set('America/Sao_Paulo');
    
	// Lê todo o conteúdo de um arquivo para uma string
	// Analisa a string codificada JSON e converte-a em uma variável do PHP.
	// ---------------------------------------------------------------------
	$postjson = json_decode(file_get_contents('php://input'), true);

	foreach ($postjson as $string) {
    //echo 'Decodificando: ' . $string;
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				//echo ' - Sem erros';
			break;
			case JSON_ERROR_DEPTH:
				echo ' - Profundidade máxima da pilha excedida (Maximum stack depth exceeded)';
			break;
			case JSON_ERROR_STATE_MISMATCH:
				echo ' - Influxo ou incompatibilidade de modos (Underflow or the modes mismatch)';
			break;
			case JSON_ERROR_CTRL_CHAR:
				echo ' - Caractere de controle inesperado encontrado (Unexpected control character found)';
			break;
			case JSON_ERROR_SYNTAX:
				echo ' - Erro de sintaxe, JSON malformado (Syntax error, malformed JSON)';
			break;
			case JSON_ERROR_UTF8:
				echo ' - Caracteres UTF-8 malformados, possivelmente codificados incorretamente (Malformed UTF-8 characters, possibly incorrectly encoded)';
			break;
			default:
				echo ' - Outro erro';
			break;
		}
	}
    
    // ----------------------------------------------------------------------
    // CONFIGURAÇÃO DE ENVIO DE NOTIFICAÇÕES POR E-MAIL
    // ----------------------------------------------------------------------
    $EMAIL_ATENDIMENTO = '';

    // ----------------------------------------------------------------------
    // CONFIGURAÇÃO DE TEMPLATES DE NOTIFICAÇÕES POR E-MAIL
    // ----------------------------------------------------------------------
    $TEMPLATE_CONFIRMA_ENVIO_AGENDA = "emails/confirmAgenda.html"; 
	$TEMPLATE_EMAIL_AGENDA = "emails/formAgenda.html"; 
	$TEMPLATE_ENVIA_CATALOGO = "emails/enviaCatalogo.html"; 

    // =========================================================================
    // FUNÇÕES REFERENTE AO ENVIO DE E-MAILS
    // =========================================================================
    function smtpmail($para,$para_nome, $de, $de_nome, $assunto, $corpo) { 

        $headers = 'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=UTF-8' . "\r\n" .
        'From: '.$de . "\r\n" .
    'Reply-To: '.$de . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

        return mail($para, $assunto, $corpo, $headers);
    }
    // ---------------------------------------------------------------------------
    // Envia e-mail de agendamento
    // ---------------------------------------------------------------------------
    function EnviaEmailAgenda($nomeUsuario, $emailUsuario, $contatoUsuario, &$error) 
    { 
        $template = file_get_contents($GLOBALS['TEMPLATE_EMAIL_AGENDA']);
        $template = str_replace( "{{nome-usuario}}", $nomeUsuario, $template);
        $template = str_replace( "{{email-usuario}}", $emailUsuario, $template);
        $template = str_replace( "{{contato-usuario}}", $contatoUsuario, $template);
		$template = str_replace( "{{data-hora-envio}}", apresentaDataHoraAtual(), $template);

        if (!smtpmail($GLOBALS['EMAIL_ATENDIMENTO'], 'Cheiro Bom', $emailUsuario, $nomeUsuario, 'NOVO CONTATO DE '.strtoupper($nomeUsuario). ' VINDO DO FORMULÁRIO DO SITE. ', $template))
        {
            $error[] = "O e-mail para a Cheiro Bom não pode ser enviado, tente mais tarde.";	

        } else {

			$template = file_get_contents($GLOBALS['TEMPLATE_CONFIRMA_ENVIO_AGENDA']);
			$template = str_replace( "{{nome-usuario}}", $nomeUsuario, $template);

			if (!smtpmail($emailUsuario, $nomeUsuario, $GLOBALS['EMAIL_ATENDIMENTO'], 'Cheiro Bom', 'OLÁ '.strtoupper($nomeUsuario). ', OBRIGADA POR ENTRAR EM CONTATO COM A CHEIRO BOM ', $template))
			{
				$error[] = "O e-mail para o usuário não pode ser enviado, tente mais tarde.";		
			}
		}
    }
	// ---------------------------------------------------------------------------
    // Envia e-mail do catalogo
    // ---------------------------------------------------------------------------
    function EnviaEmailCatalogo($nomeUsuario, $emailUsuario, $contatoUsuario, &$error) 
    { 
        $template = file_get_contents($GLOBALS['TEMPLATE_ENVIA_CATALOGO']);
        $template = str_replace( "{{nome-usuario}}", $nomeUsuario, $template);
        $template = str_replace( "{{email-usuario}}", $emailUsuario, $template);
        $template = str_replace( "{{contato-usuario}}", $contatoUsuario, $template);
		$template = str_replace( "{{data-hora-envio}}", apresentaDataHoraAtual(), $template);

        if (!smtpmail($GLOBALS['EMAIL_ATENDIMENTO'], 'Cheiro Bom', $emailUsuario, $nomeUsuario, 'NOVO CONTATO DE '.strtoupper($nomeUsuario). ' VINDO DO FORMULÁRIO DO SITE. ', $template))
        {
            $error[] = "O e-mail para a Cheiro Bom não pode ser enviado, tente mais tarde.";	

        } else {

			$template = file_get_contents($GLOBALS['TEMPLATE_ENVIA_CATALOGO']);
			$template = str_replace( "{{nome-usuario}}", $nomeUsuario, $template);

			if (!smtpmail($emailUsuario, $nomeUsuario, $GLOBALS['EMAIL_ATENDIMENTO'], 'Cheiro Bom', 'OLÁ '.strtoupper($nomeUsuario). ', AQUI ESTÁ O CATÁLOGO DA DEMILLUS, EM BREVE ENTRO EM CONTATO. ', $template))
			{
				$error[] = "O e-mail para o usuário não pode ser enviado, tente mais tarde.";		
			}
		}
    }
	
	// ----------------------------------------------------------------------
	// Apresenta a Data e Hora atual
	// ----------------------------------------------------------------------
	function apresentaDataHoraAtual()
	{
		date_default_timezone_set('America/Sao_Paulo');
		return date('d/m/Y \à\s H:i:s');
	}
?>