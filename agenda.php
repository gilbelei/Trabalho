<?php
include_once('config.php');

try{
	//echo gethostname(); 
	//echo php_uname('n'); 
	//echo $_SERVER['HTTP_HOST'];
	if(empty($postjson['requisicao'])){
		throw new Exception('A informação do tipo de requisição é obrigatória.');
	} else if(empty($postjson['chave'])){
		throw new Exception('A chave é obrigatória.');
	} else if($postjson['chave'] != $_SERVER['HTTP_HOST']){
		//throw new Exception('Chave: '. $_SERVER['HTTP_HOST']);
		throw new Exception('A chave válida é obrigatória.');
	}
	if($postjson['requisicao'] == 'agenda') {

		try {

			if(empty($postjson['nome'])){
				throw new Exception('A informação nome é obrigatória.');
			} else if(empty($postjson['email'])){
				throw new Exception('A informação email é obrigatória.');
			}else {
				EnviaEmailAgenda($postjson['nome'], $postjson['email'], $postjson['telefone'], $error); 
				if(!empty($error)){
					throw new Exception($error[1]);
				}
			}
			$result = json_encode(array('sucesso'=>true));

		} catch (Exception $e) {
			$result = json_encode(array('sucesso'=>false,'codigo'=>$e->getCode(), 'mensagem'=>$e->getMessage()));
		} 

		echo $result;

	} else {
		throw new Exception('O recurso solicitado ou o endpoint não foi encontrado.',404);
	}
} catch (Exception $e) {
	$result = json_encode(array('sucesso'=>false,'codigo'=>$e->getCode(), 'mensagem'=>$e->getMessage()));
	echo $result;
} 
?>