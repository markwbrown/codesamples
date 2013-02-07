<?php
	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	// -------------------------------------------------------------
	// Called By GenList.js, listOrders.php
	// Generates a list after a search
	// -------------------------------------------------------------
	
	$tomorrow = mktime(0,0,0,date('n'), date('d')+1, date('Y'));
	$a_year_ago = mktime(0,0,0,date('n'), date('d'), date('Y')-1);
	
	function makeStamp ($date_val) {
		if(stristr($date_val, '/')) {
			$a = explode('/', $date_val);
			$stamp = mktime(0, 0, 0, $a[1], $a[0], $a[2]);
		}
		else if(stristr($date_val, '-')) {
			$a = explode('-', $date_val);
			$stamp = mktime(0, 0, 0, $a[1], $a[0], $a[2]);
		}
		else {
			return 0;
		}
		
		return $stamp;
	}
	
	function format_date($date_val) {
		if(stristr($date_val, "/")) {
			return $date_val . " ";
		}
		else if(stristr($date_val, "-")) {
			$new_date = implode('/', explode('-', $date_val));
			return $new_date;
		}
		else {
			return 0;
		}
	}
	
	function getList($table, $from, $to, $res_type) {
		
		$query = "	SELECT * FROM $table as p
					INNER JOIN `cliente` as c on `p`.`f_cliente_id`=`c`.`cliente_id`
					INNER JOIN `users` as u on `p`.`f_users_id`=`u`.`users_id` 
					WHERE `pedido_timestamp` BETWEEN " . $from . " 
					AND " . $to . " 
					AND pedido_apagado=0 
					ORDER BY `pedido_id` DESC";

		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		//echo $result;
		
		$res = array();
		$i = 0;
		while($row =  mysql_fetch_array($result, $res_type)) {	
			$res[$i]['razao_social'] = $row['cliente_razao_social'];
			$res[$i]['autor'] = $row['users_username'];
			$res[$i]['cnpj'] = $row['cliente_cnpj'];
			$res[$i]['data_emissao'] = date('d/m/Y', $row['pedido_timestamp']);
			//$result[]['data_entrega'] = $result['pedido_timestamp_entrega'];
			$res[$i]['id'] = $row['pedido_id'];
			
			$i++;
		}
		
		// mysql_free_result($result);
		
		return $res;
	
	}
	
	$query_params = json_decode(stripslashes($_POST['query_params']), true);
	
	// IDs: select_por_dia, select_por_entrega, select_por_cliente, select_por_autor
	// Example:
	// Array (
	// 		[select_por_dia] => Array (
	//								[param_value] => hoje
	//							)
	//	)

	if(sizeof($query_params) > 0) {
		$query_string = "SELECT * from `pedido` as p";
		
		$query_string .= " 	INNER JOIN `cliente` as c on `p`.`f_cliente_id`=`c`.`cliente_id`
							INNER JOIN `users` as u on `p`.`f_users_id`=`u`.`users_id`";
		
		foreach($query_params as $key=>$value) {
			foreach($value as $index=>$param_value) {
				switch($key) {
				 	/* case "select_por_entrega": $query_string .= " WHERE `p`.`pedido_timestamp_entrega` = {};"; break; */
									
					case "select_por_cliente": { 
												if(!$more_than_one) $query_string .= " WHERE"; else $query_string .= " AND";
												$query_string .= " `p`.`f_cliente_id` = {$param_value}";
												if(!$more_than_one) $more_than_one = true;
												} break;
					
					case "select_por_autor": { 
												if(!$more_than_one) $query_string .= " WHERE"; else $query_string .= " AND";
												$query_string .= " `p`.`f_users_id` = {$param_value}";
												if(!$more_than_one) $more_than_one = true; 
												} break;
					
					default: break;
				}
				
	 			if($key=="dates" && $index=="param_value") {
					$new_stamp[$index] = makeStamp($param_value);
					//$new_date[$index] = format_date($param_value);
																	
					if(!$more_than_one) $query_string .= " WHERE"; else $query_string .= " AND";
					$query_string .= " `p`.`pedido_timestamp` BETWEEN \"{$new_stamp[$index]}\"";
					if(!$more_than_one) $more_than_one = true;
				} else if ($key=="dates" && $index=="param_value2") {
					$new_stamp[$index] = makeStamp($param_value) + (60*60*24);
					//$new_date[$index] = format_date($param_value);
					$query_string .= " AND \"{$new_stamp[$index]}\"";
				}
				// echo "This is the new stamp: ({$index}) " . $new_stamp[$index];
							
			}
		}
		
		$query_string .= " AND `p`.`pedido_apagado`= 0 ORDER BY p.pedido_id DESC;"; 
		
		$query = mysql_query($query_string);
		
		$result = array();
		$i = 0;
		while($row = mysql_fetch_array($query)) {
			$result[$i]['razao_social'] = $row['cliente_razao_social'];
			$result[$i]['autor'] = $row['users_username'];
			$result[$i]['cnpj'] = $row['cliente_cnpj'];
			$result[$i]['data_emissao'] = date('d/m/Y', $row['pedido_timestamp']);
			//$result[]['data_entrega'] = $result['pedido_timestamp_entrega'];
			$result[$i]['id'] = $row['pedido_id'];
			
			$i++;
		}
		echo json_encode($result);
		//echo $query_string;
	} else {
		
		$rows = getList('pedido', $a_year_ago, $tomorrow, MYSQL_ASSOC);
		
		echo json_encode($rows);
	}
?>