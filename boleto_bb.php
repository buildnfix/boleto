<head>
	<title>
		Boleto | GG Gamers
	</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	
</head>
<?php
	session_start();
	include('conexoes/connect.php');
		if(!isset($_SESSION['usuario_logado']))
		{
			echo'<script>window.alert("Você não está logado!");window.location="login.php";</script>';
		}else{

		// DADOS DO BOLETO PARA O SEU CLIENTE
		$dias_de_prazo_para_pagamento = 5;
		$taxa_boleto = 1.90;
		$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
		$valor_cobrado = $_SESSION['total']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
		$valor_cobrado = str_replace(",", ".",$valor_cobrado);
		$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

		$dadosboleto["nosso_numero"] = "7632";
		$dadosboleto["numero_documento"] = "25.010985.17";	// Num do pedido ou do documento
		$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
		$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
		$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
		$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

		//select/array para pegar o cliente e seus  dados

		$sql_cliente='SELECT *FROM clientes WHERE id_cliente="'.$_SESSION['id_cliente'].'";';
		$sel_cliente=mysqli_query($conexao,$sql_cliente);
		$con=mysqli_fetch_array($sel_cliente);

		//seleciona o estado para sua exibição no perfil
		$sql_estado='SELECT * FROM tb_estados WHERE id="'.$con['estado'].'";';
		$sql_query2=mysqli_query($conexao,$sql_estado);
		$con_estado=mysqli_fetch_array($sql_query2);

		//seleciona a cidade para sua exibição no perfil
		$sql_cidade='SELECT * FROM tb_cidades WHERE estado="'.$con['estado'].'";';
		$sql_query3=mysqli_query($conexao,$sql_cidade);
		$con_cidade=mysqli_fetch_array($sql_query3);

		// DADOS DO SEU CLIENTE
		$dadosboleto["sacado"] = $con['nome_cliente'];
		$dadosboleto["endereco1"] = $con['bairro_cliente'].' '.$con['rua_cliente'];
		$dadosboleto["endereco2"] = $con_estado['nome'].' - '.$con_cidade['nome'].' - '.'CEP: 00000-000';
		// INFORMACOES PARA O CLIENTE
		$dadosboleto["demonstrativo1"] = "Pagamento de Compra no site GG Gamers";
		$dadosboleto["demonstrativo2"] = "Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
		$dadosboleto["demonstrativo3"] = "GG Gamers - http://www.gggamers.com.br";

		// INSTRUÇÕES PARA O CAIXA
		$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 10% após o vencimento";
		$dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
		$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: gggamersclub@gmail.com";
		$dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema GG izi - www.gggamers.com.br";

		// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
		$dadosboleto["quantidade"] = "";
		$dadosboleto["valor_unitario"] = "";
		$dadosboleto["aceite"] = "N";		
		$dadosboleto["especie"] = "R$";
		$dadosboleto["especie_doc"] = "DM";

		// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


		// DADOS DA SUA CONTA - BANCO DO BRASIL
		$dadosboleto["agencia"] = "2345"; // Num da agencia, sem digito
		$dadosboleto["conta"] = "1664"; 	// Num da conta, sem digito

		// DADOS PERSONALIZADOS - BANCO DO BRASIL
		$dadosboleto["convenio"] = "1234976";  // Num do convênio - REGRA: 6 ou 7 ou 8 dígitos
		$dadosboleto["contrato"] = "123456789"; // Num do seu contrato
		$dadosboleto["carteira"] = "17";
		$dadosboleto["variacao_carteira"] = "-013";  // Variação da Carteira, com traço (opcional)

		// TIPO DO BOLETO
		$dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Convênio c/ 8 dígitos, 7 p/ Convênio c/ 7 dígitos, ou 6 se Convênio c/ 6 dígitos
		$dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Convênio c/ 6 dígitos: informe 1 se for NossoNúmero de até 5 dígitos ou 2 para opção de até 17 dígitos

		/*
		#################################################
		DESENVOLVIDO PARA CARTEIRA 18

		- Carteira 18 com Convenio de 8 digitos
		  Nosso número: pode ser até 9 dígitos

		- Carteira 18 com Convenio de 7 digitos
		  Nosso número: pode ser até 10 dígitos

		- Carteira 18 com Convenio de 6 digitos
		  Nosso número:
		  de 1 a 99999 para opção de até 5 dígitos
		  de 1 a 99999999999999999 para opção de até 17 dígitos

		#################################################
		*/
		 // SEUS DADOS
		$dadosboleto["identificacao"] = 'GG Gamers - "Seus produtos gamers a um clique de distancia de você"';
		$dadosboleto["cpf_cnpj"] = "897.345.242-13";
		$dadosboleto["endereco"] = "www.gggamers.com.br";
		$dadosboleto["cidade_uf"] = "São Paulo / São Paulo";
		$dadosboleto["cedente"] = "Grupo GG izi LTDA";

		// NÃO ALTERAR!
		include("conexoes/funcoes_bb.php"); 
		include("conexoes/layout_bb.php");
	}
?>
	<?php?> 
	<input type=button value=Imprimir onclick=window.print();>
	<a href="index.php"><input type=button value="Voltar"></a>
