<?php
	// tecido Data - Included in tecido.js (tecido.php)

	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	$params = json_decode(stripslashes($_POST['query_params']), true);
	
	function formatNewVal($val) {
		
		$val = str_replace(",", "-", $val); // dashes where dots should be
		$val = str_replace(".", "|", $val); // pipe where commas should be
		
		$val = str_replace("|", "", $val); // put in nothing where the comma should go
		$val = str_replace("-", ".", $val); // put in the dots
		
		$val = floatval($val);
		// should be in the format 0000.00
	
		return $val;
	}
	
	function getList($table, $res_type) {
		
		$a = array(); 
		
		$query = "SELECT * FROM caixa LEFT JOIN material on caixa.f_material_id = material.material_id ORDER BY caixa_timestamp DESC";
		
		if($_POST['limit']) {
			
		//	$query .= " LIMIT {$_POST['limit']}";
		}
		
		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		$valor_in = 0;
		$valor_out = 0;
		$i = 0;
		
		while($r = mysql_fetch_array($result, $res_type)) {
			
			$a[$i] = $r;
			$a[$i]['caixa_valor_in'] = number_format($a[$i]['caixa_valor_in'], 2, ",", ".");
			$a[$i]['caixa_valor_out'] = number_format($a[$i]['caixa_valor_out'], 2, ",", ".");
			
			$valor_in += $r['caixa_valor_in'];
			$valor_out += $r['caixa_valor_out'];
			
			$i++;
		}
		
		$grand_total = $valor_in - $valor_out;
		
		$a[0]['caixa_grand_total'] = number_format($grand_total, 2, ",", ".");
		
		return $a;
	
	}
	
	if($_POST['record']) { // Record an item on the database
		
		$query = 	" INSERT into `caixa` (
						`caixa_data`, 
						`caixa_receptor`, 
						`caixa_finalidade`, 
						`caixa_nota`,
						`caixa_valor_in`,
						`caixa_valor_out`,
						`caixa_timestamp`,
						`f_fornecedor_id`,
						`f_material_id`
						) values (
						'" . $params['caixa_data'] . "',
						'" . $params['caixa_receptor'] . "',
						'" . $params['caixa_finalidade'] . "',
						'" . $params['caixa_nota'] . "',
						'" . formatNewVal($params['caixa_valor_in']) . "',
						'" . formatNewVal($params['caixa_valor_out']) . "',
						" . mktime() . ",
						'" . "0" . "',
						" . $params['caixa_material'] . ")";
		
		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		$rows = array();
		$rows = getList('caixa', MYSQL_ASSOC);
		
	} else if ($_POST['update']) { // Modify an item
		/*	Format: 
			var params = {
				tipo	: "telefone",
				info	: this.down().value,
				item	: this.up().getAttribute('nbr')
			}; */
	
		switch($params['tipo']) {
			case 'cx-data': $field_name = "caixa_data"; break;
			case 'cx-finalidade': $field_name = "caixa_finalidade"; break;
			case 'cx-receptor': $field_name = "caixa_receptor"; break;
			case 'cx-material': $field_name = "f_material_id"; break;
			case 'cx-nota': $field_name = "caixa_nota"; break;
			case 'cx-valor-in': $field_name = "caixa_valor_in"; break;
			
			default: $field_name = "caixa_valor_out"; // cx-valor
		}
		
		$query = "	UPDATE `caixa` SET `$field_name`='" . $params['info'] . "' WHERE `caixa_id`='" . $params['item'] . "';";
		
		$r = mysql_query($query) or die("Error: " . mysql_error());
		
		$rows = $params;
	
	} else if ($_POST['sw']) { // Negates the value of an item
		
		$query_select = mysql_query("SELECT * FROM caixa WHERE caixa_id={$_POST['id']}");
		$rows = mysql_fetch_array($query_select);
		
		$neg = $rows["caixa_valor"] * -1;
		
		$query = "UPDATE caixa SET caixa_valor={$neg} WHERE caixa_id={$_POST['id']};";
		$result = mysql_query($query) or die($query . "   <br><br>" . mysql_error());
	
	} else if ($_POST['remove']) { // Remove an item
		
		$query_select = mysql_query("SELECT * FROM `caixa` WHERE `caixa_id`='" . $_POST['id'] . "';");
		$deleted = mysql_fetch_array($query_select);
		
		$query = "DELETE from `caixa` WHERE `caixa_id`='" . $_POST['id'] . "';";
		$result = mysql_query($query) or die($query . "   <br><br>" . mysql_error());
		
		$rows = getList('caixa', MYSQL_ASSOC);
		
		$rows[0]['caixa_valor_in_deleted'] = number_format($deleted['caixa_valor_in'], 2, ",", "."); // So we can produce the message
		$rows[0]['caixa_valor_out_deleted'] = number_format($deleted['caixa_valor_out'], 2, ",", ".");
		$rows[0]['caixa_data_deleted'] = $deleted['caixa_data'];
		
	} else { // Just show the list
		$rows = getList('caixa', MYSQL_ASSOC);

	}
	
	echo json_encode($rows);
	
	mysql_close($link1);
?>