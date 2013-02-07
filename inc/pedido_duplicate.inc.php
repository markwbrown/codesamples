<?php
	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	$order_id = $_POST['orderId'];
	$timestamp = mktime();
	
	if($order_id !== null) {
		
		$queryOrder = "SELECT * FROM pedido WHERE pedido_id={$order_id}";
		$resultOrder = mysql_query($queryOrder) or die($queryOrder . "  Result: " . mysql_error());
		
		$rowO = mysql_fetch_array($resultOrder, MYSQL_ASSOC);
		
		// Escape characters to prevent breakage
		$rowO['pedido_obs'] = addslashes($rowO['pedido_obs']);
		$rowO['pedido_local'] = addslashes($rowO['pedido_local']);
		$rowO['pedido_prazo'] = addslashes($rowO['pedido_prazo']);
		$rowO['pedido_pgto'] = addslashes($rowO['pedido_pgto']);
		$rowO['pedido_obs'] = addslashes($rowO['pedido_obs']);
		$rowO['pedido_entrega'] = addslashes($rowO['pedido_entrega']);
		
		$queryOrder2 = "INSERT INTO pedido (
								pedido_timestamp, 
								pedido_local, 
								pedido_prazo, 
								pedido_pgto, 
								pedido_obs, 
								f_users_id,
								f_cliente_id, 
								f_cliente_cnpj, 
								pedido_desc, 
								pedido_entrega, 
								pedido_entrega_timestamp, 
								pedido_emissao,
								pedido_ordem,
								pedido_pronta_entrega
								) VALUES (
								{$timestamp}, 
								\"{$rowO['pedido_local']}\", 
								\"{$rowO['pedido_prazo']}\", 
								\"{$rowO['pedido_pgto']}\", 
								\"{$rowO['pedido_obs']}\", 
								{$rowO['f_users_id']}, 
								{$rowO['f_cliente_id']},
								{$rowO['f_cliente_cnpj']}, 
								{$rowO['pedido_desc']}, 
								\"{$rowO['pedido_entrega']}\", 
								{$rowO['pedido_entrega_timestamp']}, 
								{$rowO['pedido_emissao']},
								\"{$rowO['pedido_ordem']}\",
								{$rowO['pedido_pronta_entrega']}
						)";
		
		$resultOrder2 = mysql_query($queryOrder2) or die($queryOrder2 . "  Result: " . mysql_error());
		
		$f_pedido_id = mysql_insert_id(); // Link the new items to the duplicate order's ID, and not the original order's ID	
		
		$queryPecas = "SELECT * FROM pecas WHERE f_pedido_id={$order_id} ORDER BY pecas_id ASC";
		$resultPecas = mysql_query($queryPecas) or die($queryPecas . "  Result: " . mysql_error());
		
		while($rowP = mysql_fetch_array($resultPecas, MYSQL_ASSOC)) {
		
			// Escape characters to prevent breakage
			$rowP['pecas_espec'] = addslashes($rowP['pecas_espec']);
			$rowP['tam_cap_v1'] = addslashes($rowP['tam_cap_v1']);
			$rowP['tam_cap_v2'] = addslashes($rowP['tam_cap_v2']);
			
			$queryPecas2 = "INSERT INTO pecas (
									pecas_preco, 
									f_pedido_id, 
									f_tecido_id, 
									f_fornecedor_id, 
									pecas_espec, 
									pecas_timestamp, 
									tam_34, 
									tam_36, 
									tam_38, 
									tam_40, 
									tam_42, 
									tam_44, 
									tam_46, 
									tam_48, 
									tam_50, 
									tam_52, 
									tam_54, 
									tam_56, 
									tam_cap_v1, 
									tam_cap_v2, 
									tam_v1, 
									tam_v2, 
									f_cliente_id
									) VALUES (
									{$rowP['pecas_preco']}, 
									{$f_pedido_id}, 
									{$rowP['f_tecido_id']}, 
									{$rowP['f_fornecedor_id']}, 
									\"{$rowP['pecas_espec']}\", 
									{$timestamp}, 
									{$rowP['tam_34']}, 
									{$rowP['tam_36']}, 
									{$rowP['tam_38']}, 
									{$rowP['tam_40']}, 
									{$rowP['tam_42']}, 
									{$rowP['tam_44']}, 
									{$rowP['tam_46']}, 
									{$rowP['tam_48']}, 
									{$rowP['tam_50']}, 
									{$rowP['tam_52']}, 
									{$rowP['tam_54']}, 
									{$rowP['tam_56']}, 
									\"{$rowP['tam_cap_v1']}\", 
									\"{$rowP['tam_cap_v2']}\", 
									{$rowP['tam_v1']}, 
									{$rowP['tam_v2']}, 
									{$rowP['f_cliente_id']}
						)";
			
			$resultPecas2 = mysql_query($queryPecas2) or die($queryPecas2 . "  Result: " . mysql_error());
		}
		
		echo json_encode($f_pedido_id);
						
	} else {
		
		echo "Nenhum número de pedido selecionado.";
	}

?>