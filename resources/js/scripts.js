// FUNÇÔES

// Posionar rodapé no final da página
function sticky_footer() {
	var mFoo = $("footer");
	if ((($(document.body).height() + mFoo.outerHeight()) < $(window).height() && mFoo.css("position") == "fixed") || ($(document.body).height() < $(window).height() && mFoo.css("position") != "fixed")) {
		mFoo.css({ position: "fixed", bottom: "0px" });
	} else {
		mFoo.css({ position: "static" });
	}
}  

// Calcula total orçamento
function calc_total(valor,area,desconto,tipodesconto){
	if(desconto == "")
		desconto = 0;
	if(tipodesconto == "1"){
		total = (parseFloat(valor) * parseInt(area)) * (1-(desconto/100));
  		total = total.toFixed(2);
  	} else if(tipodesconto == "2"){
  	  	total = (parseFloat(valor) * parseInt(area)) - parseInt(desconto);
  		total = total.toFixed(2);
  	} else{
  		total = (parseFloat(valor) * parseInt(area));
  		total = total.toFixed(2);
  	}

  	if(!isNaN(total))
  		return total;
  	else
  		return parseFloat(0).toFixed(2);
}	

//EVENTOS PARA ONLOAD
$(document).ready(function() {	

	// Ativar tooltip formulário
	$('[data-toggle="tooltip"]').tooltip();

	// Fechar box de alerta
	$(".alert").delay(4000).slideUp(200, function() {
	    $(this).alert('close');
	});

	// Listener para seleção de cidade
	$("#estado").change(function() {
		$.ajax({
  			method: "GET",
  			url: RESOURCES_PATH+"/ajax/cidades.php",
  			data: { estado: $(this).val() }
		}).done(function(data) {
    		$("#cidade").html(data);
  		});
	});

	// Listener para seleção de privilegios de usuário
	$("#privilegio").on("change",function() {
		console.log("teste");
		if($(this).val() == 2 || $(this).val() == 3){
			$.ajax({
	  			url: ROOT_PATH+"/usuarios/ajax/adicional.php"
			}).done(function(data) {
	    		$("#adicional").html(data);
	    		$('[data-toggle="tooltip"]').tooltip();
	    		sticky_footer();
	    		$("#telefone").mask("(99) 9999-9999n", 
				{'translation':{
				        'n': {pattern: /[0-9]/, optional: true}
				    }
			    });
	  		});
		} else{
			$("#adicional").html("");
			sticky_footer();
		}
	});

	// Listener para seleção de clientes
	$("#cliente").on("change", function(){
		if($(this).val() != ""){
			$.ajax({
	  			method: "GET",
	  			url: ROOT_PATH+"/orcamentos/ajax/cliente.php",
	  			data: { id: $(this).val() }
			}).done(function(data) {
	    		$("#clientedados").html(data);
	  		});
		} else{
			console.log("asd");
			$("#clientedados").html("");
		}
	});

	// Listener edição valores orçamento
	$("#valor, #desconto, #area").on("keyup", function(){
		total = calc_total($("#area").val(),$("#valor").val(),$("#desconto").val(),$("#tipodesconto").val());
		$("#valortotal").val(total);		
  		$("#total .valor").html(total);
  	});
  	$("#tipodesconto").on("change", function(){  	
  		total = calc_total($("#area").val(),$("#valor").val(),$("#desconto").val(),$("#tipodesconto").val());
		$("#valortotal").val(total);		
  		$("#total .valor").html(total);
  	});

	// Posiciona rodapé
	sticky_footer();
	$(window).scroll(sticky_footer);
	$(window).resize(sticky_footer);
	$(window).load(sticky_footer);

	// Inicializa datatables
	$('table').dataTable({
  		"language": {
		    "sEmptyTable": "Nenhum registro encontrado",
		    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
		    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
		    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
		    "sInfoPostFix": "",
		    "sInfoThousands": ".",
		    "sLengthMenu": "_MENU_ resultados por página",
		    "sLoadingRecords": "Carregando...",
		    "sProcessing": "Processando...",
		    "sZeroRecords": "Nenhum registro encontrado",
		    "sSearch": "Pesquisar ",
		    "oPaginate": {
		        "sNext": "Próximo",
		        "sPrevious": "Anterior",
		        "sFirst": "Primeiro",
		        "sLast": "Último"
		    },
		    "oAria": {
		        "sSortAscending": ": Ordenar colunas de forma ascendente",
		        "sSortDescending": ": Ordenar colunas de forma descendente"
		    }
		}
  	});

	/*
		VALIDAÇÔES
	 */

  	// Validação Media Kit
	$("#formmediakit").validate({
	    rules: {
	    	nome: "required",
	      	tipo: "required",
	      	arquivo: "required"
	    },
	    messages: {
	      	nome: "Informe um nome de identificação",
	      	tipo: "Informe o tipo de arquivo",
	      	arquivo: "Selecione o arquivo a ser cadastrado"
	    }
  	});

  	// Validação Perfil
	$("#formperfil").validate({
	    rules: {
	    	nome: "required",
	      	senha: {
				minlength: 8
			},
	      	email: {
	        	required: true,
	        	email: true
	      	}
	    },
	    messages: {
	      	nome: "Informe o nome usuário",
	      	senha: {
				minlength: "A senha deve conter mais que 8 caracteres"
			},
		    email: {
		        required: "Informe o e-mail",
		        email: "E-mail inválido"
		    }
	    }
  	});

  	// Validação Usuario
	$("#formusuario").validate({
	    rules: {
	    	nome: "required",
	      	senha: {
	      		required: true,
				minlength: 8
			},
	      	email: {
	        	required: true,
	        	email: true
	      	},
	      	privilegio: "required",
	      	telefone: {
				minlength: 14
			},
	      	credenciado: "required"
	    },
	    messages: {
	      	nome: "Informe o nome usuário",
	      	senha: {
	      		required: "Informe a senha de acesso",
				minlength: "A senha deve conter mais que 8 caracteres"
			},
		    email: {
		        required: "Informe o e-mail",
		        email: "E-mail inválido"
		    },
	      	privilegio: "Selecione o privilégio de acesso",
	      	telefone: {
				minlength: "Numero telefônico inválido"
			},
	      	credenciado: "Selecione o credênciado para vincular o usuário"
	    }
  	});

  	// Validação Usuario
	$("#formresponsavel").validate({
	    rules: {
	    	nome: "required",
	      	senha: {
	      		required: true,
				minlength: 8
			},
	      	email: {
	        	required: true,
	        	email: true
	      	}
	    },
	    messages: {
	      	nome: "Informe o nome usuário",
	      	senha: {
	      		required: "Informe a senha de acesso",
				minlength: "A senha deve conter mais que 8 caracteres"
			},
		    email: {
		        required: "Informe o e-mail",
		        email: "E-mail inválido"
		    }
	    }
  	});

	// Validação Credenciado e Cliente
  	$("#formcredenciado , #formcliente").validate({
	    rules: {
	    	nomefantasia: "required",
	    	cnpj: {
	      		required: true, 
	      		cnpj:true
	      	},
	      	razaosocial: "required",
	      	telefone: "required",
	      	email: {
	        	required: true,
	        	email: true
	      	},
	      	pais: "required",
	      	estado: "required",
	      	cidade: "required",
	      	endereco: "required",	      	
	      	bairro: "required",
	      	numero: "required",
	      	cep: "required"
	    },
	    messages: {
	    	nomefantasia: "Informe o Nome Fantasia de empresa",
	    	cnpj: {
	      		required: "Informe o CNPJ da empresa", 
	      		cnpj: "CNPJ Inválido"
	      	},
	      	razaosocial: "Informe a Razão Social da empresa",
	      	telefone: "Informe um telefone de contato da empresa",
	      	email: {
	        	required: "Informe um e-mail de contato da empresa",
		        email: "E-mail inválido"
	      	},
	      	endereco: "Informe o endereço",
	      	bairro: "Informe o bairro",
	      	numero: "Informe o número",
	      	pais: "Informe o País",
	      	estado: "Informe o estado",
	      	cidade: "Informe a cidade",
	      	cep: "Informe o CEP"
	    }
  	});

  	// Validação Credenciado e Cliente
  	$("#formorcamento").validate({
	    rules: {
	    	cliente: "required",
	      	responsavel: "required",
	      	estado: "required",
	      	area: "required",
	      	valor: "required",	      	
	      	pagamento: "required",
	      	validade: "required"
	    },
	    messages: {
	    	cliente: "Selecione o cliente",
	      	responsavel: "Selecione o responsável",
	      	estado: "Infomre o estado do carpete",
	      	area: "Informe a área ",
	      	valor: "Informe o valor do metro quadrado",	      	
	      	pagamento: "required",
	      	validade: "Informe a validade do orçamento"
	    }
  	});
		
	// Adiciona método de validaçâo de CNPJ
	jQuery.validator.addMethod("cnpj", function(cnpj, element) {
	   cnpj = jQuery.trim(cnpj);
	   cnpj = cnpj.replace('/','');
	   cnpj = cnpj.replace('.','');
	   cnpj = cnpj.replace('.','');
	   cnpj = cnpj.replace('-','');
	   var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
	   digitos_iguais = 1;
	   if (cnpj.length < 14 && cnpj.length < 15){
	      return false;
	   }
	   for (i = 0; i < cnpj.length - 1; i++){
	      if (cnpj.charAt(i) != cnpj.charAt(i + 1)){
	         digitos_iguais = 0;
	         break;
	      }
	   }
	   if (!digitos_iguais){
	      tamanho = cnpj.length - 2
	      numeros = cnpj.substring(0,tamanho);
	      digitos = cnpj.substring(tamanho);
	      soma = 0;
	      pos = tamanho - 7;
	      for (i = tamanho; i >= 1; i--){
	         soma += numeros.charAt(tamanho - i) * pos--;
	         if (pos < 2){
	            pos = 9;
	         }
	      }
	      resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	      if (resultado != digitos.charAt(0)){
	         return false;
	      }
	      tamanho = tamanho + 1;
	      numeros = cnpj.substring(0,tamanho);
	      soma = 0;
	      pos = tamanho - 7;
	      for (i = tamanho; i >= 1; i--){
	         soma += numeros.charAt(tamanho - i) * pos--;
	         if (pos < 2){
	            pos = 9;
	         }
	      }
	      resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	      if (resultado != digitos.charAt(1)){
	         return false;
	      }
	      return true;
	   } else{
	      return false;
	   }
	}, "Informe um CNPJ válido");

	// Prevenir letras em campos numéricos
	$("input.numbers").keypress(function(event) {
        return /\d/.test(String.fromCharCode(event.keyCode));
    });

    // Mascaras de formulário
    $(".dataServico").mask("99/99/9999");
	$("#cep").mask("99999-999");
	$("#cnpj").mask("99.999.999/9999-99");
	$("#telefone").mask("(99) 9999-9999n", {'translation':{'n': {pattern: /[0-9]/, optional: true}}});
	$("#numero").mask("9999");
  	$("#valor").maskMoney({thousands:'', decimal:'.'});

});
