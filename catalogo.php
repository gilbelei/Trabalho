<?php
include_once('config.php');
include_once('conexao.php');

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
	if($postjson['requisicao'] == 'catalogo') {

		try {

			if(empty($postjson['nome'])){
				throw new Exception('A informação nome é obrigatória.');
			} else if(empty($postjson['email'])){
				throw new Exception('A informação email é obrigatória.');
			}else {

				$pdo->beginTransaction();

				//-----------------------------------------------------------------------------------
				// Verifica se a pessoa já existe
				//-----------------------------------------------------------------------------------
				$query = $pdo->prepare("SELECT mailing.id, mailing.nome, mailing.email, mailing.celular FROM mailing WHERE mailing.email = :email LIMIT 0, 1 ");
				$query->bindValue(":email", $postjson['email']);
				$query->execute();
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				// Se existe atualiza
				if(count($res)  > 0 ){
					// Se celular vazio mantem o que existe
					if(empty($postjson['telefone'])){
						$query = $pdo->prepare("UPDATE mailing SET nome=:nome,  email=:email WHERE mailing.email = :email  ");
					}else{ // Se celular preenchido, então atualiza
						$query = $pdo->prepare("UPDATE mailing SET nome=:nome,  email=:email, celular=:celular WHERE mailing.email = :email  ");
						$query->bindValue(":celular", $postjson['telefone']);
					}
				}else{ //Se não existe insere
					$query = $pdo->prepare("INSERT INTO mailing SET nome=:nome, email=:email, celular=:celular ");
					if(empty($postjson['telefone'])){
						$query->bindValue(":celular", null, PDO::PARAM_NULL);
					}else{
						$query->bindValue(":celular", $postjson['telefone']);
					}
				}
				$query->bindValue(":nome", $postjson['nome']);
				$query->bindValue(":email", $postjson['email']);
				$query->execute();
				EnviaEmailCatalogo($postjson['nome'], $postjson['email'], $postjson['telefone'], $error); 
				if(!empty($error)){
					$pdo->rollBack();
					throw new Exception($error[1]);
				}
				$pdo->commit();
			}
			$result = json_encode(array('sucesso'=>true));

		} catch (Exception $e) {
			$pdo->rollBack();
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