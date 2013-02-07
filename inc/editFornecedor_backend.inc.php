<?php

	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";

	$params = json_decode(stripslashes($_GET['query_params']), true);

	$cnpj = $params['forn_cnpj_1'] . $params['forn_cnpj_2'] . $params['forn_cnpj_3'] . $params['forn_cnpj_4'] . $params['forn_cnpj_5'];

	$query = "SELECT * FROM `fornecedor` WHERE `fornecedor_cnpj`='" . $cnpj . "'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);

	if($row['fornecedor_cnpj'] !== null && $row['fornecedor_id'] !== $params['client_id']) { //se o cnpj já existe, o fornecedor jah existe

		$r["error"] = "cnpj_repetido";
		$r["msg"] = "O CNPJ " . $params['forn_cnpj_1'] . "." . $params['forn_cnpj_2'] . "." . $params['forn_cnpj_3'] . "/" . $params['forn_cnpj_4'] . "-" . $params['forn_cnpj_5'] . " já registrado para " . $row['fornecedor_razao_social'] . ". Por favor escolha outro."; 
		echo json_encode($r);

	} else {
	
		$queryFornecedor = "UPDATE `fornecedor` SET 
		`fornecedor_razao_social`='" . $params['forn_razao_social'] . "', 
		`fornecedor_nome`='" . $params['forn_nome_fantasia'] . "',
		`fornecedor_cnpj`='" . $cnpj . "', 
		`fornecedor_cnpj1`='" . $params['forn_cnpj_1'] . "',
		`fornecedor_cnpj2`='" . $params['forn_cnpj_2'] . "',
		`fornecedor_cnpj3`='" . $params['forn_cnpj_3'] . "',
		`fornecedor_cnpj4`='" . $params['forn_cnpj_4'] . "',
		`fornecedor_cnpj5`='" . $params['forn_cnpj_5'] . "',
		`fornecedor_inscricao_estadual`='" . $params['forn_insc_estadual']. "',
		`fornecedor_timestamp`='" . mktime() . "',
		`f_material_id`='" . $params['material_id'] . "'
		WHERE 
		`fornecedor_id`='" . $params['client_id'] . "';";

		$resultFornecedor = mysql_query($queryFornecedor) or die($query . "   <br><br>" . mysql_error());
	
		$queryEndereco = "UPDATE `endereco` SET 
		`endereco_rua`='" . $params['endereco_rua'] . "', 
		`endereco_quadra`='" . $params['endereco_quadra'] . "',
		`endereco_numero`='" . $params['endereco_numero'] . "',
		`endereco_cidade`='" . $params['endereco_cidade'] . "',
		`endereco_estado`='" . $params['endereco_estado'] . "', 
		`endereco_bairro`='" . $params['endereco_bairro']. "',
		`endereco_cep`='" . $params['endereco_cep'] . "',
		`endereco_website`='" . $params['endereco_website'] . "',
		`endereco_timestamp`='" . mktime() . "'
		WHERE 
		`f_fornecedor_id`='" . $params['client_id'] . "';";

		$resultEndereco = mysql_query($queryEndereco) or die($query . "   <br><br>" . mysql_error());
	
		$queryTelefone = "UPDATE `telefone` SET 
		`telefone_numero`='" . $params['telefone_numero_1'] . "',
		`telefone_numero_2`='" . $params['telefone_numero_2'] . "',
		`telefone_numero_3`='" . $params['telefone_numero_3'] . "',
		`telefone_timestamp`='" . mktime() . "'
		WHERE 
		`f_fornecedor_id`='" . $params['client_id'] . "';";

		$resultTelefone = mysql_query($queryTelefone) or die($query . "   <br><br>" . mysql_error());
	
		$queryFax = "UPDATE `fax` SET 
		`fax_numero`='" . $params['fax'] . "', 
		`fax_timestamp`='" . mktime() . "'
		WHERE 
		`f_fornecedor_id`='" . $params['client_id'] . "';";

		$resultFax = mysql_query($queryFax) or die($query . "   <br><br>" . mysql_error());
	
		$i = 0;

		foreach($params['contato'] as $key=>$value) {

			if($value['c_id'] !== null) {

				$queryComprador = "UPDATE `comprador` SET 
											`comprador_nome`='" . $value['nome'] . "', 
											`comprador_telefone`='" . $value['telefone'] . "',
											`comprador_celular`='" . $value['celular'] . "',
											`comprador_email`='" . $value['email'] . "',
											`comprador_timestamp`='" . mktime() . "'
											WHERE 
											`f_fornecedor_id`='" . $params['client_id'] . "'
											AND
											`comprador_id`='" . $value['c_id'] . "'
											;";

	
				//echo "\nQuery comprador: " . $queryComprador;
				$resultComprador = mysql_query($queryComprador) or die($queryComprador . "  Result Comprador: " . mysql_error());
		
			} else {
			
				$queryComprador = "INSERT into `comprador` (
												`comprador_nome`, 
												`comprador_telefone`,
												`comprador_celular`,
												`comprador_email`, 
												`f_fornecedor_id`, 
												`comprador_timestamp`, 
												`comprador_cargo`
												) values (
													'" . $value['nome'] . "' , 
													'" . $value['telefone'] . "',
													'" . $value['celular'] . "',
													'" . $value['email'] . "', 
													'" . $params['client_id'] . "', 
													'" . mktime() . "', 
													'" . $value['cargo']. "')";

			
				//echo "\nQuery comprador: " . $queryComprador;
				$resultComprador = mysql_query($queryComprador) or die($queryComprador . "  Result Comprador: " . mysql_error());
		
			}
		
			$i++;
		
		} // /foreach
	
	
		// Now get all of the recorded info and send it back to the page
		$f = array();

		$queryForn = "	SELECT *
					FROM `fornecedor`
					INNER JOIN `endereco` ON `fornecedor`.`fornecedor_id` = `endereco`.`f_fornecedor_id`
					INNER JOIN `telefone` ON `fornecedor`.`fornecedor_id` = `telefone`.`f_fornecedor_id`
					INNER JOIN `fax` ON `fornecedor`.`fornecedor_id` = `fax`.`f_fornecedor_id`
					INNER JOIN `comprador` ON `fornecedor`.`fornecedor_id` = `comprador`.`f_fornecedor_id`
					INNER JOIN `material` ON `fornecedor`.`f_material_id` = `material`.`material_id`
					WHERE `fornecedor`.`fornecedor_id`=" . $params['client_id'] . ";";

		$q = mysql_query($queryForn) or die("Error: " . mysql_error());

		while($r = mysql_fetch_array($q, MYSQL_ASSOC)) {
			$res[] = $r;
		}

		echo json_encode($res);
	}
?>