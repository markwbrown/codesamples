<?php
	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	// -------------------------------------------------------------
	// Called By GenCallList.js, listCalls.php
	// Generates a list after a search
	// -------------------------------------------------------------
	
	$today = mktime(0,0,0,date('n'), date('d'), date('Y'));
	$a_month_ago = mktime(0,0,0,date('n')-1, date('d'), date('Y'));
	
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
	
	function isCallOut($call_type) {
		if($call_type == "feita") {
			$calls_out = 1; 
		} else if($call_type == "recebida") {
			$calls_out = 0;
		} else {
			$calls_out = false; // padrao, ligacoes recebidas (por agora)
		}
		return $calls_out; 
	}
	
	function getList($table, $from, $to, $res_type, $is_out) {
		
		$a = array(); 
		
		$query = "SELECT * FROM $table WHERE `calls_timestamp` BETWEEN " . $from . " AND " . $to . " AND calls_is_out = " . $is_out . " ORDER BY `calls_timestamp` DESC";

		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		//echo $result;
		
		while($row =  mysql_fetch_array($result, $res_type)) {
			$a[] = $row;
		}
		
		// mysql_free_result($result);
		
		return $a;
	
	}
	
	$query_params = json_decode(stripslashes($_POST['query_params']), true);
	
	//print_r($query_params);
	
	// IDs: select_por_dia, select_por_entrega, select_por_cliente, select_por_autor
	// Example:
	// Array (
	// 		[select_por_resultado] => Array (
	//								[param_value] => numero_do_resultado_id
	//							)
	//	)

	if(sizeof($query_params) > 0) {		
		
		$query_string = "SELECT * from `calls` as v";
		
		foreach($query_params as $key=>$value) {
			foreach($value as $index=>$param_value) {
	 			if($key=="dates" && $index=="param_value") {
					
					if($param_value == "") {	
						$new_stamp[$index] = $a_month_ago;
					} else {
					 	$new_stamp[$index] = makeStamp($param_value);
					}
																	
					if(!$more_than_one) $query_string .= " WHERE"; else $query_string .= " AND";
					$query_string .= " `v`.`calls_timestamp` BETWEEN \"{$new_stamp[$index]}\"";
					if(!$more_than_one) $more_than_one = true;
				
				} else if ($key=="dates" && $index=="param_value2") {
					
					if($param_value == "") {	
						$new_stamp[$index] = $today;
					} else {
					 	$new_stamp[$index] = makeStamp($param_value);
					}

					$query_string .= " AND \"{$new_stamp[$index]}\"";
					
				} else if ($key=="dates" && $index=="para" && ($param_value!=0 || $param_value!="0")) { // If name was not selected, show calls for everyone
					
					if(!$more_than_one) $query_string .= " WHERE"; else $query_string .= " AND";
					$query_string .= " v.calls_para_f_receiver_uid={$param_value}";
					if(!$more_than_one) $more_than_one = true;
					
				} else if ($key=="dates" && $index=="call_type") { // call_type
					
					if(!$more_than_one) $query_string .= " WHERE"; else $query_string .= " AND";
					$is_call_out = isCallOut($param_value);
					$query_string .= " `v`.`calls_is_out` = " . $is_call_out;
					if(!$more_than_one) $more_than_one = true;
					
				} else {}
						
			}
			
			//echo $query_string;
		}
		
		$query_string .= " ORDER BY `calls_timestamp` DESC";
		
		$query = mysql_query($query_string);
		$result = array();
		
		while($row = mysql_fetch_array($query)) {
			$result[] = $row; 
		}
		echo json_encode($result);
		//echo $query_string;
	} else {
		
		$rows = getList('calls', $a_month_ago, $today, MYSQL_ASSOC, isCallOut($query_params['calls_is_out']));
		
		echo json_encode($rows);
	}
?>