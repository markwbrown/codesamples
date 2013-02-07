<?php
	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	$query_params = json_decode(stripslashes($_POST['query_params']), true);
	
	// Example:
	// Array (
	// 		[search_param_name] => Array (
	//								[param_value] => value, [param_type] => type
	//							)
	//	)

	if(sizeof($query_params) > 0) {
		$query_string = "SELECT * from `pedido` ";
		
		foreach($query_params as $key=>$value) {
			foreach($value as $index=>$param_value) {
				switch($key) {		
					case "razao": { 
										if($query_params['param_value']) {
											$separated = explode(' ', $query_params['param_value']);
											
											$query_string .= "WHERE `cliente_razao_social` LIKE '%" . $separated[0] . "'%";
											
											for ($i=1; $i < count($separated); $i++) {
												$query_string .= " OR `cliente_razao_social` LIKE '%" . $separated[$i] . "'%";
											}
											$query_string .= ";";
										}
								} break;
					
					case "cnpj": { 
										if($query_params['param_value']) {
											$take_slash = implode(explode("/", $query_params['param_value']));
											echo $take_slash . "------------";
											$take_dashes = implode(explode("-", $query_params['param_value']));
											echo $take_dashes . "------------";
											if(stristr($take_dashes, "."))
												$clean_string = implode(explode(".", $take_dashes);
												
											$query_string .= "WHERE `cliente_cnpj` =" . $clean_string . ";";
											
											echo $clean_string . "------------";
										}
								} break;
					
					default: break;
				}
			}
		}
		
		$query_string .= " AND `cliente_apagado`= 0;";
		
		$query = mysql_query($query_string);

		$result = array();
		$i = 0;
		while($row = mysql_fetch_array($query)) {
			$result[$i]['razao_social'] = $row['cliente_razao_social'];
			$result[$i]['cnpj'] = $row['cliente_cnpj'];
			$result[$i]['id'] = $row['cliente_id'];
			
			$i++;
		}
		echo json_encode($result);
		//echo $query_string;
	} else {
		
		echo "Especifique um parâmetros para sua busca!";
	}
?>