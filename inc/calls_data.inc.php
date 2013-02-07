<?php
	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	if($_POST['query_params'] != "") {
		$params = json_decode(stripslashes($_POST['query_params']), true);
	}
	
	// Info: client_num
	// Array (
	// 		[params] => (
	//			[calls_user_id] => user_id,
	//			[calls_data] => $(data),
	//			[calls_nome] => $(nome),
	//			[calls_telefone] => $(telefone),
	//			[calls_para] => $(para),
	//			[calls_obs] => $(obs)
	//			
	//		)
	//	)
	
	$today = mktime(0,0,0,date('n'), date('d'), date('Y'));
	$two_weeks_ago = mktime(0,0,0,date('n'), date('d')-15, date('Y'));
	$a_month_ago = mktime(0,0,0,date('n')-1, date('d'), date('Y'));
	$three_months_ago = mktime(0,0,0,date('n')-1, date('d'), date('Y'));
	
	
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
	
	function getList($table, $from, $to, $res_type, $is_out, $uid = false) {
		
		$a = array(); 
		
		if(!$uid) {
			$query = "SELECT * FROM $table WHERE `calls_timestamp` BETWEEN " . $from . " AND " . $to . " AND `calls_is_out` = " . $is_out . " ORDER BY `calls_timestamp` DESC";
		} else {
			$query = "SELECT * FROM $table WHERE `calls_timestamp` BETWEEN " . $from . " AND " . $to . " AND `calls_is_out` = " . $is_out . " AND `calls_para_f_receiver_uid` = " . $uid . " ORDER BY `calls_timestamp` DESC";
		}
		
		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		//echo $result;
		
		while($row =  mysql_fetch_array($result, $res_type)) {
			$a[] = $row;
		}
		
		// mysql_free_result($result);
		
		return $a;
	
	}
	
	if($_POST['record']) { // Record an item on the database
		$name_query = "SELECT `users_firstname` FROM `users` WHERE `users_id`=" . $params['calls_uid'];
		$name_result = mysql_query($name_query);
		$name_row = mysql_fetch_array($name_result);
		
		if($params['calls_is_out']) {
			$query = 	" INSERT into `calls` (
							`calls_data`,
							`calls_cliente`, 
							`calls_telefone`,
							`calls_mins`,
							`calls_para`,
							`calls_para_f_receiver_uid`,
							`calls_timestamp`,
							`calls_detalhes`,
							`calls_is_out`,
							`f_users_id`,
							`f_users_firstname`
							) values (
							'" . $params['calls_data'] . "',
							'" . $params['calls_nome'] . "',
							'" . $params['calls_telefone'] . "',
							'" . $params['calls_mins'] . "',
							'" . $params['calls_para'] . "',
							'" . $params['calls_para_uid'] . "',
							'" . makeStamp($params['calls_data']) . "',
							'" . $params['calls_obs'] . "',
							'" . $params['calls_is_out'] . "',
							'" . $params['calls_uid'] . "',
							'" . $name_row['users_firstname'] . "'
						
			)";
		
		} else {
			$query = 	" INSERT into `calls` (
							`calls_data`,
							`calls_cliente`, 
							`calls_telefone`,
							`calls_para`,
							`calls_para_f_receiver_uid`,
							`calls_timestamp`,
							`calls_detalhes`,
							`calls_is_out`,
							`f_users_id`,
							`f_users_firstname`
							) values (
							'" . $params['calls_data'] . "',
							'" . $params['calls_nome'] . "',
							'" . $params['calls_telefone'] . "',
							'" . $params['calls_para'] . "',
							'" . $params['calls_para_uid'] . "',
							'" . makeStamp($params['calls_data']) . "',
							'" . $params['calls_obs'] . "',
							'" . $params['calls_is_out'] . "',
							'" . $params['calls_uid'] . "',
							'" . $name_row['users_firstname'] . "'
						
			)";
		}
		
		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		$rows = array();
		$rows = getList('calls', $a_month_ago, $today, MYSQL_ASSOC, $params['calls_is_out']);
		
		//mysql_free_result($result);
		
	} else if ($_POST['update']) { // Modify an item
		/*	Format: 
			var params = {
				tipo	: "telefone",
				info	: this.down().value,
				item	: this.up().getAttribute('nbr')
			}; */
		
		switch($params['tipo']) {
			case 'data': $field_name = "calls_data"; break;
			case 'nome': $field_name = "calls_cliente"; break;
			case 'telefone': $field_name = "calls_telefone"; break;
			case 'para': $field_name = "calls_para"; break;
			case 'mins': $field_name = "calls_mins"; break;
			
			default: $field_name = "calls_detalhes";
		}
		
		$query = "	UPDATE `calls` SET `$field_name`='" . $params['info'] . "' WHERE `calls_id`='" . $params['item'] . "';";
		
		$r = mysql_query($query) or die("Error: " . mysql_error());
		
		$rows = $params;
	
	} else if ($_POST['remove']) { // Remove an item
		$query_select = mysql_query("SELECT * FROM `calls` WHERE `calls_id`='" . $_POST['id'] . "';");
		$rows = mysql_fetch_array($query_select);
		
		$query = "DELETE from `calls` WHERE `calls_id`='" . $_POST['id'] . "';";
		$result = mysql_query($query) or die($query . "   <br><br>" . mysql_error());
		
		//mysql_free_result($result);
		
	} else { // Just show the list
		if($_POST['user_id']) { // list of calls for a particular user
			$rows = getList('calls', $a_month_ago, $today, MYSQL_ASSOC, $_POST['calls_is_out'], $_POST['user_id']);
		} else {
			$rows = getList('calls', $a_month_ago, $today, MYSQL_ASSOC, $_POST['calls_is_out']);
		}
			

	}
	
	//mysql_close($link1);
	
	echo json_encode($rows);
	
?>