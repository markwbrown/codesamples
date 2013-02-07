<?php
	@include_once "dbconnect.inc.php";
	
	$params = json_decode(stripslashes($_POST['query_params']), true);

	if(strlen($params['phrase']) < 1) {
		$query = "	SELECT * from `cliente`
					WHERE `cliente_apagado` = 0
					ORDER BY `cliente_razao_social` ASC;";
		//echo $query;

		$results = mysql_query($query);
		while($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$result_row[] = $row;
		}
	} else if ($params['radio'] == "razao") {
		$terms = explode(" ", $params['phrase']);
		
		foreach($terms as $key=>$value) {
			$query = "	SELECT * from `cliente`
						WHERE `cliente_apagado` = 0 AND `cliente_razao_social` LIKE '%" . $value . "%' ORDER BY `cliente_razao_social` ASC;";
			//echo $query;

			$results = mysql_query($query);
			while($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
				$result_row[] = $row;
			}
		}
	} else {
		if(stristr($params['phrase'], "/"))
			$params['phrase'] = implode(explode("/", $params['phrase']));
	
		if(stristr($params['phrase'], "-"))
			$params['phrase'] = implode(explode("-", $params['phrase']));

		if(stristr($params['phrase'], "."))
			$params['phrase'] = implode(explode(".", $params['phrase']));
		
		$query = "	SELECT * from `cliente`
						WHERE `cliente_apagado` = 0 AND `cliente_cnpj`= '" . $params['phrase'] . "' ORDER BY `cliente_razao_social` ASC;";
		//echo $query;

		$results = mysql_query($query);
		while($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$result_row[] = $row;
		}
	}
	//print_r($result_row);
	
	echo json_encode($result_row);
?>