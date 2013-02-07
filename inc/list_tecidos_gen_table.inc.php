<?php
	@include_once "dbconnect.inc.php";
	
	$params = json_decode(stripslashes($_POST['query_params']), true);

	if(strlen($params['phrase']) < 1) {
		
		$query = "	SELECT * from tecido
					INNER JOIN fornecedor on f_fornecedor_id=fornecedor_id
					ORDER BY f_fornecedor_id ASC;";

		$results = mysql_query($query);
		while($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$result_row[] = $row;
		}
		
	} else if ($params['radio'] == "fabricante") {
		
		$terms = explode(" ", $params['phrase']);
		
		foreach($terms as $key=>$value) {
			$query = "	SELECT * from tecido
						INNER JOIN fornecedor on f_fornecedor_id=fornecedor_id
						WHERE fornecedor_nome LIKE '%$value%'
						OR fornecedor_razao_social LIKE '%$value%'
						ORDER BY fornecedor_nome ASC;";

			$results = mysql_query($query);
			while($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
				$result_row[] = $row;
			}
		}
		
	} else if ($params['radio'] == "nome") {
		
		$terms = explode(" ", $params['phrase']);

		foreach($terms as $key=>$value) {
			
			$query = "	SELECT * from tecido
						INNER JOIN fornecedor on f_fornecedor_id=fornecedor_id
						WHERE tecido_nome LIKE '%$value%'
						ORDER BY tecido_nome ASC;";

			$results = mysql_query($query);
			while($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
				$result_row[] = $row;
			}
		}
		
	} else { // Cor
		
		$terms = explode(" ", $params['phrase']);

		foreach($terms as $key=>$value) {
			
			$query = "	SELECT * from tecido
						INNER JOIN fornecedor on f_fornecedor_id=fornecedor_id
						WHERE tecido_cor LIKE '%$value%'
						ORDER BY tecido_cor ASC;";

			$results = mysql_query($query);
			while($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
				$result_row[] = $row;
			}
		}
	}
	
	echo json_encode($result_row);
	
	//print_r($result_row);
?>