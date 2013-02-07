<?php

	//SQL and PHP statements for the fornecedor.php file
	//Rafael Lucchese - 2012

	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";

	$params = json_decode(stripslashes($_GET['query_params']), true);

	$cnpj = $params['forn_cnpj_1'] . $params['forn_cnpj_2'] . $params['forn_cnpj_3'] . $params['forn_cnpj_4'] . $params['forn_cnpj_5'];

	$query = "SELECT * FROM `fornecedor` WHERE `fornecedor_cnpj`='" . $cnpj . "'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);

	if($row['fornecedor_cnpj'] !== null) { //se o cnpj já existe, o fornecedor jah existe
	
		$r["error"] = "cnpj_repetido";
		$r["msg"] = "O CNPJ " . $params['forn_cnpj_1'] . "." . $params['forn_cnpj_2'] . "." . $params['forn_cnpj_3'] . "/" . $params['forn_cnpj_4'] . "-" . $params['forn_cnpj_5'] . " já registrado para " . $row['fornecedor_razao_social'] . ". Por favor escolha outro."; 
		echo json_encode($r);

	} else {
		$queryFornecedor = "INSERT into `fornecedor` (
												`fornecedor_razao_social`, 
												`fornecedor_nome`, 
												`fornecedor_cnpj`,
												`fornecedor_cnpj1`, 
												`fornecedor_cnpj2`, 
												`fornecedor_cnpj3`, 
												`fornecedor_cnpj4`, 
												`fornecedor_cnpj5`,
												`fornecedor_inscricao_estadual`, 
												`fornecedor_timestamp`, 
												`f_material_id`
												) values (
													'" . $params['forn_razao_social'] . "',
													'" . $params['forn_nome_fantasia'] . "',
													'" . $cnpj . "',
													'" . $params['forn_cnpj_1'] . "', 
													'" . $params['forn_cnpj_2'] . "', 
													'" . $params['forn_cnpj_3'] . "', 
													'" . $params['forn_cnpj_4'] . "', 
													'" . $params['forn_cnpj_5'] . "',
													'" . $params['forn_insc_estadual'] . "', 
													'" . mktime() . "', 
													 " . $params['material_id'] . ")";
												
	
		//echo "Result fornecedor: " . $queryFornecedor;
		$resultFornecedor = mysql_query($queryFornecedor) or die($queryFornecedor . "  Result: " . mysql_error());

		$fornecedor = mysql_insert_id();
   
		$queryEndereco = "INSERT into `endereco` (
											`endereco_rua`, 
											`endereco_quadra`, 
											`endereco_numero`, 
											`endereco_bairro`, 
											`endereco_cidade`, 
											`endereco_estado`, 
											`endereco_cep`, 
											`endereco_website`, 
											`f_fornecedor_id`, 
											`endereco_timestamp`
											) values (
												'" . $params['endereco_rua'] . "' , 
												'" . $params['endereco_quadra'] . "' , 
												'" . $params['endereco_numero'] . "' , 
												'" . $params['endereco_bairro'] . "' , 
												'" . $params['endereco_cidade'] . "', 
												'" . $params['endereco_estado'] . "', 
												'" . $params['endereco_cep'] . "', 
												'" . $params['endereco_website'] . "', 
												'" . $fornecedor . "', 
												'" . mktime() . "')";
											
	
		//echo "\nResult endereco: " . $queryEndereco;
		$resultEndereco = mysql_query($queryEndereco) or die($queryEndereco . "  Result Endereco: " . mysql_error());
	
		$queryTelefone = "INSERT into `telefone` (
											`telefone_numero`, 
											`telefone_numero_2`, 
											`telefone_numero_3`, 
											`f_fornecedor_id`, 
											`telefone_timestamp`
											) values (
												'" . $params['telefone_numero_1'] . "', 
												'" . $params['telefone_numero_2'] . "', 
												'" . $params['telefone_numero_3'] . "', 
												'" . $fornecedor . "', 
												'" . mktime() . "')";
											
	
		//echo "\nResult telefone: " . $queryTelefone;
		$resultTelefone = mysql_query($queryTelefone) or die($queryTelefone . "  Result Telefone: " . mysql_error());

		$queryFax = "INSERT into `fax` (
									`fax_numero`, 
									`f_fornecedor_id`, 
									`fax_timestamp`
									) values (
										'" . $params['fax'] . "', 
										'" . $fornecedor . "', 
										'" . mktime() . "')";
	
	
		//echo "\nResult fax: " . $queryFax;
		$resultFax = mysql_query($queryFax) or die($queryFax . "  Result Fax: " . mysql_error());
	
		$i = 0;
	
		foreach($params['contato'] as $key=>$value) {
		
			$contato[$i] = array();
		
			foreach($value as $index=>$forn_info) {
			
				$contato[$i][$index] = $forn_info;
			
			}
		
			$queryComprador = "INSERT into `comprador` (
											`comprador_nome`, 
											`comprador_telefone`,
											`comprador_celular`, 
											`comprador_email`, 
											`f_fornecedor_id`, 
											`comprador_timestamp`, 
											`comprador_cargo`
											) values (
												'" . $contato[$i]['nome'] . "' , 
												'" . $contato[$i]['telefone'] . "' , 
												'" . $contato[$i]['celular'] . "' , 
												'" . $contato[$i]['email'] . "', 
												'" . $fornecedor . "', 
												'" . mktime() . "', 
												'" . $contato[$i]['cargo']. "')";

		
			//echo "\nQuery comprador: " . $queryComprador;
			$resultComprador = mysql_query($queryComprador) or die($queryComprador . "  Result Comprador: " . mysql_error());
		
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
					WHERE `fornecedor`.`fornecedor_id`=" . $fornecedor . ";";

		$q = mysql_query($queryForn) or die("Error: " . mysql_error());

		while($r = mysql_fetch_array($q, MYSQL_ASSOC)) {
			$res[] = $r;
		}

		echo json_encode($res);
		
	}



/* =========================================================================================

	Referencia: 

	Array
	(
	    [material_uid] => 3
	    [material_nome] => 0
	    [material_id] => -- material fornecido --
	    [forn_razao_social] => 
	    [forn_nome_fantasia] => 
	    [forn_cnpj_1] => 
	    [forn_cnpj_2] => 
	    [forn_cnpj_3] => 
	    [forn_cnpj_4] => 
	    [forn_cnpj_5] => 
	    [forn_insc_estadual] => 
	    [endereco_rua] => 
	    [endereco_quadra] => 
	    [endereco_numero] => 
	    [endereco_bairro] => 
	    [endereco_cidade] => São Luís
	    [endereco_estado] => 10
	    [endereco_cep] => 
	    [endereco_website] => 
	    [telefone_numero_1] => 
	    [telefone_numero_2] => 
	    [telefone_numero_3] => 
	    [fax] => 
	    [contato] => Array
	        (
	            [0] => Array
	                (
	                    [nome] => nome 1
	                    [email] => email 1
	                    [telefone] => telefone 1
	                    [cargo] => cargo 1
	                )

	            [1] => Array
	                (
	                    [nome] => nome 2
	                    [email] => email 2
	                    [telefone] => telefone 2
	                    [cargo] => cargo 2
	                )

	        )

	)

*/
	
?>