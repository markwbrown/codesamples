<?php
	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	// -------------------------------------------------------------
	// Called By ListFornecedorDetail.js, fornecedor_detail.php
	// Generates a list of materials
	// -------------------------------------------------------------
	
	function getList($table, $res_type) {
		
		$a = array(); 
		
		$query = "SELECT * FROM $table ORDER BY `fornecedor_nome` DESC";

		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		//echo $result;
		
		while($row =  mysql_fetch_array($result, $res_type)) {
			$a[] = $row;
		}
		
		// mysql_free_result($result);
		
		return $a;
	
	}
	
	function getSingleMaterial($table, $table2, $res_type, $fornecedor) {
		
		$a = array(); 
		
		$query = "	SELECT `material_nome` FROM $table as m
					INNER JOIN $table2 as f  
					WHERE m.`material_id`= f.`f_material_id`
					AND f.`fornecedor_id`=$fornecedor";

		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		//echo $result;
		
		while($row =  mysql_fetch_array($result, $res_type)) {
			$a[] = $row;
		}
		
		return $a;
	
	}
	
	function getMaterial($table, $res_type, $mat) {
		
		$a = array(); 
		
		$query = "	SELECT `material_nome` FROM $table  
					WHERE material_id= $mat LIMIT 1";

		$result = mysql_query($query) or die($query . "  Result: " . mysql_error());
		
		//echo $result;
		
		while($row =  mysql_fetch_array($result, $res_type)) {
			$a[] = $row;
		}
		
		return $a;
	
	}
	
	if($_GET['fornec_get_material'] && $_GET['fornec_num']!=null) {	
		
		$rows = getSingleMaterial('material', 'fornecedor' , MYSQL_ASSOC, $_GET['fornec_num']);
	
	} else if($_GET['material_id']) {	

		$rows = getMaterial('material', MYSQL_ASSOC, $_GET['material_id']);
	
	} else {
		
		$rows = getList('fornecedor', MYSQL_ASSOC);
	
	}
	
	echo json_encode($rows);
?>