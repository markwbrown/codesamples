<?php

	//SQL and PHP statements for the fornecedor.php file
	//Rafael Lucchese - 2012

	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	$f = array();
	
	$query = "	SELECT *
				FROM `fornecedor`
				INNER JOIN `endereco` ON `fornecedor`.`fornecedor_id` = `endereco`.`f_fornecedor_id`
				INNER JOIN `telefone` ON `fornecedor`.`fornecedor_id` = `telefone`.`f_fornecedor_id`
				INNER JOIN `fax` ON `fornecedor`.`fornecedor_id` = `fax`.`f_fornecedor_id`
				INNER JOIN `comprador` ON `fornecedor`.`fornecedor_id` = `comprador`.`f_fornecedor_id`
				LEFT OUTER JOIN `material` ON `fornecedor`.`f_material_id` = `material`.`material_id`
				WHERE `fornecedor`.`fornecedor_id`=" . $_GET['id'] . ";";
				
	$q = mysql_query($query) or die("Error: " . mysql_error());
	
	while($r = mysql_fetch_array($q, MYSQL_ASSOC)) {
		$row[] = $r;
	}
	
	echo json_encode($row);
	
	
?>