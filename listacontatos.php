<?php
include_once('config.php');
include_once('conexao.php');

try{
    $query = $pdo->query("SELECT `nome`, `email`, `celular` FROM `mailing` ");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    for ($i=0; $i < count($res); $i++) { 
    foreach ($res[$i] as $key => $value) {
    }
        $dados[] = array(
            'nome' => $res[$i]['nome'],
            'email' => $res[$i]['email'],
            'celular' => $res[$i]['celular']
        );
    }
    $registros = count($res);

    if($registros > 0){
        $result = json_encode(array('sucesso'=>true, 'registros'=>$registros, 'result'=>$dados));
    }else{
        $result = json_encode(array('sucesso'=>true, 'registros'=>$registros));
    }
    echo $result;
	
} catch (Exception $e) {
	$result = json_encode(array('sucesso'=>false,'codigo'=>$e->getCode(), 'mensagem'=>$e->getMessage()));
	echo $result;
} 
?>