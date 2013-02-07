<?php
	// Material Data - Included in Material.js (materiais.php)

	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	$params = json_decode(stripslashes($_POST['query_params']), true);
	
	function getList($table, $res_type) {
		
		$a = array(); 
		
		$query = "SELECT * FROM $table";

		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		//echo $result;
		
		while($row =  mysql_fetch_array($result, $res_type)) {
			
			$a[] = $row;
		}
		
		return $a;
	
	}
	
	if($_POST['record']) { // Record an item on the database
		
		$query = " INSERT into material (material_nome) values ('{$params['mat_nome']}')";
		
		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		$rows = array();
		$rows = getList('material', MYSQL_ASSOC);
		
	} else if ($_POST['update']) { // Modify an item
		
		$field_name = "material_nome";
		
		echo $query;
		
		$query = "	UPDATE material SET {$field_name}='{$params['info']}' WHERE material_id='{$params['item']}';";
		
		$r = mysql_query($query) or die("Error: " . mysql_error());
		
		$rows = $params;
	
	} else if ($_POST['remove']) { // Remove an item
		
		$query_select = mysql_query("SELECT * FROM material WHERE material_id={$_POST['id']}");
		$rows = mysql_fetch_array($query_select);
		
		$query = "DELETE from material WHERE material_id='{$_POST['id']}';";
		$result = mysql_query($query) or die($query . "   <br><br>" . mysql_error());
		
	} else { // Just show the list
		
		$rows = getList('material', MYSQL_ASSOC);

	}
	
	echo json_encode($rows);
	
	mysql_close($link1);
?>