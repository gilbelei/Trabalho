"use strict";
var get = null;
var set = null;
var configuracoes = {
	//BASE_URL: 'http://localhost/faesa/',
	//BASE_API: 'http://localhost/faesa/',
	BASE_URL: 'https://faesa.gilhhb.ninja/',
	BASE_API: 'https://faesa.gilhhb.ninja/',
	APLICACAO: 'FAESA',
	//SALT_SISTEMA: "localhost",
	SALT_SISTEMA: "faesa.gilhhb.ninja",
	HABILITAR_LOG: true
}
var api = {
	FORMULARIOS: {
		AGENDA: {
			ENDPOINT: configuracoes.BASE_API + 'agenda.php'
		},
		CATALOGO: {
			ENDPOINT: configuracoes.BASE_API + 'catalogo.php'
		}
	}
}

/**
 * @description Gera logs no console do browser
 * @param {string} varLog Objeto a ser enviado para o console do browser quando o log habilitado
 */
function log(varLog) {
	if (configuracoes.HABILITAR_LOG) {
		console.log(varLog);
	}
}


(function ($) {
	$(function () {
		var input = {
			NOME: $('#name'),
			EMAIL: $('#email'),
			TELEFONE: $('#mobile'),
			NOME_CATALOG: $('#name_catalog'),
			EMAIL_CATALOG: $('#email_catalog'),
			TELEFONE_CATALOG: $('#mobile_catalog')
		}
		$('#btn-enviar-form-agenda').click(function (e) {
			log('O botão ENVIAR foi clicado');

			var atende = true;

			e.preventDefault();
			var botao = $(e.target);

			log(input.NOME.val());
			log(input.EMAIL.val());
			log(input.TELEFONE.val());

			if (!input.NOME.val()) {
				log('NOME VAZIO!');
				input.NOME.addClass('invalid');
				var msg = '<H2>Informe o seu nome.</H2>';
				M.toast({
					html: msg,
					classes: 'card-panel  red accent-4 white-text'
				});

				atende = false;
			} else {
				input.NOME.removeClass('invalid');
			}
			if (!input.EMAIL.val()) {
				log('E-MAIL VAZIO!');
				input.EMAIL.addClass('invalid');
				var msg = '<H2>Informe o e-mail.</H2>';
				M.toast({
					html: msg,
					classes: 'card-panel  red accent-4 white-text'
				});

				atende = false;
			} else {
				input.EMAIL.removeClass('invalid');
			}

			if (atende) {
				log('Atendeu!');
				input.NOME.removeClass('invalid');
				input.EMAIL.removeClass('invalid');

				set = {
					requisicao: 'agenda',
					nome: input.NOME.val(),
					email: input.EMAIL.val(),
					telefone: input.TELEFONE.val(),
					chave: configuracoes.SALT_SISTEMA
				};

				log(JSON.stringify(set));

				$.ajax({ //Função AJAX
					url: api.FORMULARIOS.AGENDA.ENDPOINT,
					type: "post", //Método de envio
					data: JSON.stringify(set), //Dados
					beforeSend: function () {//antes de requisitar
					},
					success: function (result) { //Sucesso no AJAX
						log(' # resujt');
						log(result);
						log(result.sucesso);
						if (result.sucesso){
							var msg = '<h2>Sua mensagem foi enviada!</h2>';
							log(msg);
							M.toast({
								html: msg,
								classes: 'card-panel  green darken-1 white-text'
							});

							input.NOME.val("");
							input.EMAIL.val("");
							input.TELEFONE.val("");

						}else{
							var msg = '<h2>' + result.mensagem + '</h2>';
							log(msg);
							log(result.mensagem);
							M.toast({
								html: msg,
								classes: 'card-panel  red accent-4 white-text'
							});
						}
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						log(' # XMLHttpRequest');
						log(XMLHttpRequest);
						log(' # textStatus');
						log(textStatus);
						log(' # errorThrown');
						log(errorThrown);
						var msg = '<h2>' + errorThrown + '</h2>';
						M.toast({
							html: msg,
							classes: 'card-panel  red accent-4 white-text'
						});

					},
					complete: function (jqXHR, textStatus) {
						log(' # jqXHR');
						log(jqXHR);
						log(' # textStatus');
						log(textStatus);

					}
				}).done(function (data) {
					log(' # done');
					log(data);

				}); //AJAX API
			}

		}); //#btn-enviar-form-agenda

		$('#btn-enviar-form-catalog').click(function (e) {
			log('O botão ENVIAR foi clicado');

			var atende = true;

			e.preventDefault();
			var botao = $(e.target);

			log(input.NOME_CATALOG.val());
			log(input.EMAIL_CATALOG.val());
			log(input.TELEFONE_CATALOG.val());

			if (!input.NOME_CATALOG.val()) {
				log('NOME VAZIO!');
				input.NOME.addClass('invalid');
				var msg = '<H2>Informe o seu nome.</H2>';
				M.toast({
					html: msg,
					classes: 'card-panel  red accent-4 white-text'
				});

				atende = false;
			} else {
				input.NOME_CATALOG.removeClass('invalid');
			}
			if (!input.EMAIL_CATALOG.val()) {
				log('E-MAIL VAZIO!');
				input.EMAIL_CATALOG.addClass('invalid');
				var msg = '<H2>Informe o e-mail.</H2>';
				M.toast({
					html: msg,
					classes: 'card-panel  red accent-4 white-text'
				});

				atende = false;
			} else {
				input.EMAIL_CATALOG.removeClass('invalid');
			}

			if (atende) {
				log('Atendeu!');
				input.NOME.removeClass('invalid');
				input.EMAIL.removeClass('invalid');

				set = {
					requisicao: 'catalogo',
					nome: input.NOME_CATALOG.val(),
					email: input.EMAIL_CATALOG.val(),
					telefone: input.TELEFONE_CATALOG.val(),
					chave: configuracoes.SALT_SISTEMA
				};

				log(JSON.stringify(set));

				$.ajax({ //Função AJAX
					url: api.FORMULARIOS.CATALOGO.ENDPOINT,
					type: "post", //Método de envio
					data: JSON.stringify(set), //Dados
					beforeSend: function () {//antes de requisitar
					},
					success: function (result) { //Sucesso no AJAX
						log(' # resujt');
						log(result);
						log(result.sucesso);
						if (result.sucesso){
							var msg = '<h2>Sua mensagem foi enviada!</h2>';
							log(msg);
							M.toast({
								html: msg,
								classes: 'card-panel  green darken-1 white-text'
							});

							input.NOME_CATALOG.val("");
							input.EMAIL_CATALOG.val("");
							input.TELEFONE_CATALOG.val("");

						}else{
							var msg = '<h2>' + result.mensagem + '</h2>';
							log(msg);
							log(result.mensagem);
							M.toast({
								html: msg,
								classes: 'card-panel  red accent-4 white-text'
							});
						}
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						log(' # XMLHttpRequest');
						log(XMLHttpRequest);
						log(' # textStatus');
						log(textStatus);
						log(' # errorThrown');
						log(errorThrown);
						var msg = '<h2>' + errorThrown + '</h2>';
						M.toast({
							html: msg,
							classes: 'card-panel  red accent-4 white-text'
						});

					},
					complete: function (jqXHR, textStatus) {
						log(' # jqXHR');
						log(jqXHR);
						log(' # textStatus');
						log(textStatus);

					}
				}).done(function (data) {
					log(' # done');
					log(data);

				}); //AJAX API
			}

		}); //#btn-enviar-form-catalog



	}); // end of document ready
})(jQuery); // end of jQuery name space