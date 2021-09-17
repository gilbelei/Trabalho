<?php
	// =================================================================================================
	// CONEXÃO AO BANCO DE DADOS
	// =================================================================================================
	// ---------------------------------------------------------------------
	// Dados de acesso ao banco de dados
	// banco - nome da instância do banco de dados
	// host - servidor onde esta hospedado o banco de dados
	// usuario - usuário para acesso ao banco de dados
	// senha - senha para acesso banco de dados
	// ---------------------------------------------------------------------
	$banco = '';
	$host = '';
	$usuario = '';
	$senha = '';

	// ----------------------------------------------------------------------
	// Abre uma nova conexão com o servidor MySQL
	// ----------------------------------------------------------------------
	try {
		$pdo = new PDO("mysql:dbname=$banco;host=$host", "$usuario", "$senha");	
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
		$pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
		$pdo->exec("set names utf8");
        //echo 'Conectado com sucesso! ';
	} catch (Exception $e) {
		echo 'Erro ao conectar com o banco! ' . $e->getMessage();
	}
?>

