<?php
	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	$ar = array();
	$ar = $_POST;
	$ar['pecas'] = json_decode(stripslashes($_POST['pecas']), true);
	
	$user_loggedin = $ar["user_loggedin"];
	$cliente_id = $ar['id'];
	$cnpj = $ar['cnpj'];
	$pedido_id = $ar['order_id'];
	$local = $ar['local_de_entrega'];
	$prazo = $ar['prazo_de_entrega'];
	$pagamento = $ar['pagamento'];
	$obs = $ar['obs'];
	$desc = $ar['desconto'];
	$ordem = $ar['ordem'];
	
	$status_d = $ar['status_d'];
	$status_f = $ar['status_f'];
	$status_p = $ar['status_p'];
	
	$comprador = $ar['comprador'];
	$tel = $ar['telefone'];
	$email = $ar['email'];
	$fin = $ar['fin'];
	$fin_tel = $ar['fin_tel'];
	$fin_email = $ar['fin_email'];
	
	$pe = 0;
	
	if($ar['pe'] === true || $ar['pe'] === "true") {
		
		$pe = 1;
	}
	
	$cliente_existe = FALSE;
	if($cliente_id) $cliente_existe = TRUE;
	
	if($cliente_existe) { // Client has to exist as per the front end, but still let's use a fail-check mechanism
		
		$pedido_existe = FALSE;
		if($pedido_id && $pedido_id != 0) { $pedido_existe = TRUE; }

		$newItems = array();
		
		if($pedido_existe && $pedido_id != 0) {
			
			$queryPedidoMod = "UPDATE `pedido` SET 	`pedido_local`='" . $local . "',
													`pedido_prazo`='" . $prazo . "',
													`pedido_pgto`='" . $pagamento . "',
													`pedido_obs`='" . $obs . "',
													`pedido_desc`='" . $desc . "',
													`pedido_ordem`='" . $ordem . "',
													`pedido_pronta_entrega`=" . $pe . ",
													`pedido_comprador`='" . $comprador . "',
													`pedido_tel`='" . $tel . "',
													`pedido_email`='" . $email . "',
													`pedido_fin`='" . $fin . "',
													`pedido_fin_tel`='" . $fin_tel . "',
													`pedido_fin_email`='" . $fin_email . "',
													`pedido_status_p`='" . $status_p . "',
													`pedido_status_f`='" . $status_f . "',
													`pedido_status_d`='" . $status_d . "'
													WHERE `pedido_id`='" . $pedido_id . "'
													;";
			$resultPedidoMod = mysql_query($queryPedidoMod) or die($queryPedidoMod . "  Result: " . mysql_error());
			
			foreach($ar['pecas'] as $key=>$value) {
				
				if($value['isnew']==="yes" && $value['wasremoved']==="no") { // Add a brand new item to an existing order
					
					$queryPecas = " INSERT into `pecas` (
									`pecas_preco`, 
									`f_pedido_id`, 
									`f_fornecedor_id`, 
									`f_tecido_id`, 
									`pecas_espec`,
									`pecas_timestamp`,
									`tam_36`,
									`tam_38`,
									`tam_40`,
									`tam_42`,
									`tam_44`,
									`tam_46`,
									`tam_48`,
									`tam_50`,
									`tam_52`,
									`tam_54`,
									`tam_56`,
									`tam_v1`,
									`tam_v2`,
									`tam_cap_v1`,
									`tam_cap_v2`,
									`f_cliente_id`
									) values (
									'" . $value['valor'] . "', 
									'" . $pedido_id . "', 
									'" . $value['fabricante'] . "',
									'" . $value['tecido'] . "',
									'" . $value['espec'] . "',
									'" . mktime() . "',
									'" . $value['tam_36'] . "',
									'" . $value['tam_38'] . "',
									'" . $value['tam_40'] . "',
									'" . $value['tam_42'] . "',
									'" . $value['tam_44'] . "',
									'" . $value['tam_46'] . "',
									'" . $value['tam_48'] . "',
									'" . $value['tam_50'] . "',
									'" . $value['tam_52'] . "',
									'" . $value['tam_54'] . "', 
									'" . $value['tam_56'] . "',
									'" . $value['tam_v1'] . "',
									'" . $value['tam_v2'] . "',
									'" . $value['tam_cap_v1'] . "',
									'" . $value['tam_cap_v2'] . "',
									'" . $cliente_id . "'
					)";
					$resultPecas = mysql_query($queryPecas) or die($queryPecas . "  Result: " . mysql_error());
					$ar['pecas'][$key]["num"] = mysql_insert_id();
				
				} else if($value['wasremoved']==="yes") { // Remove an existing item from an order
					
					$query = "DELETE from pecas WHERE f_pedido_id={$pedido_id} AND pecas_id={$value['num']};";
					$result = mysql_query($query) or die($query . "   <br><br>" . mysql_error());
					
				} else { // Update an existing item from an order
					
					$espec = addslashes($value['espec']);
					
					$query = "UPDATE pecas SET 
									pecas_preco=\"{$value['valor']}\", 
									f_tecido_id={$value['tecido']}, 
									f_fornecedor_id={$value['fabricante']},
									pecas_espec=\"{$espec}\",
									tam_36={$value['tam_36']},
									tam_38={$value['tam_38']},
									tam_40={$value['tam_40']},
									tam_42={$value['tam_42']},
									tam_44={$value['tam_44']},
									tam_46={$value['tam_46']},
									tam_48={$value['tam_48']},
									tam_50={$value['tam_50']},
									tam_52={$value['tam_52']},
									tam_54={$value['tam_54']},
									tam_56={$value['tam_56']},
									tam_v1={$value['tam_v1']},
									tam_v2={$value['tam_v2']},
									tam_cap_v1=\"{$value['tam_cap_v1']}\",
									tam_cap_v2=\"{$value['tam_cap_v2']}\"
									WHERE 
									f_pedido_id={$pedido_id}
									AND 
									pecas_id={$value["num"]}
					;";
					
					$r = mysql_query($query) or die("Error: " . mysql_error());
		
				}
			}
			
		} else { // Completely new order and items
			
			$queryPedido = " INSERT into `pedido` (
							`pedido_local`, 
							`pedido_prazo`, 
							`pedido_pgto`,
							`pedido_obs`,
							`pedido_pronta_entrega`,
							`pedido_ordem`,
							`pedido_desc`,
							`pedido_comprador`,
							`pedido_tel`,
							`pedido_email`,
							`pedido_fin`,
							`pedido_fin_tel`,
							`pedido_fin_email`,
							`f_cliente_id`, 
							`f_users_id`, 
							`pedido_timestamp`, 
							`f_cliente_cnpj`, 
							`pedido_status_p`, 
							`pedido_status_d`, 
							`pedido_status_f`
							) values (
							'" . $local . "', 
							'" . $prazo . "', 
							'" . $pagamento . "',
							'" . $obs . "',
							" . $pe . ",
							'" . $ordem . "',
							'" . $desc . "',
							'" . $comprador . "',
							'" . $tel . "',
							'" . $email . "',
							'" . $fin . "',
							'" . $fin_tel . "',
							'" . $fin_email . "',
							'" . $cliente_id . "', 
							'" . $user_loggedin . "',
							'" . mktime() . "', 
							'" . $cnpj . "',
							'" . $status_p . "',
							'" . $status_d . "',
							'" . $status_f . "'
							
			)";
		  
		  	$resultPedido = mysql_query($queryPedido) or die($queryPedido . "  Result: " . mysql_error());
			$pedido_id = mysql_insert_id();
			$queryPecas = "";
			
			foreach($ar['pecas'] as $key=>$value) {
				
				if($value['isnew']==="yes" || $value['wasRemoved']==="no") { // New item into a brand new order
					
					$queryPecas = " INSERT into `pecas` (
									`pecas_preco`, 
									`f_pedido_id`,
									`f_tecido_id`,
									`f_fornecedor_id`, 
									`pecas_espec`,
									`pecas_timestamp`,
									`tam_36`,
									`tam_38`,
									`tam_40`,
									`tam_42`,
									`tam_44`,
									`tam_46`,
									`tam_48`,
									`tam_50`,
									`tam_52`,
									`tam_54`,
									`tam_56`,
									`tam_v1`,
									`tam_v2`,
									`tam_cap_v1`,
									`tam_cap_v2`,
									`f_cliente_id`
									) values (
									'" . $value['valor'] . "', 
									'" . $pedido_id . "', 
									'" . $value['tecido'] . "',
									'" . $value['fabricante'] . "',
									'" . $value['espec'] . "',
									'" . mktime() . "',
									'" . $value['tam_36'] . "',
									'" . $value['tam_38'] . "',
									'" . $value['tam_40'] . "',
									'" . $value['tam_42'] . "',
									'" . $value['tam_44'] . "',
									'" . $value['tam_46'] . "',
									'" . $value['tam_48'] . "',
									'" . $value['tam_50'] . "',
									'" . $value['tam_52'] . "',
									'" . $value['tam_54'] . "', 
									'" . $value['tam_56'] . "',
									'" . $value['tam_v1'] . "',
									'" . $value['tam_v2'] . "',
									'" . $value['tam_cap_v1'] . "',
									'" . $value['tam_cap_v2'] . "',
									'" . $cliente_id . "'
					)";
					$resultPecas = mysql_query($queryPecas) or die($queryPecas . "  Result: " . mysql_error());
					$ar['pecas'][$key]["num"] = mysql_insert_id();
				}
				
			} // New order - items - end
			
		}
		
		$query_data_autor = mysql_query("SELECT p.pedido_timestamp, u.users_firstname, u.users_lastname FROM pedido as p INNER JOIN users as u on p.f_users_id=u.users_id WHERE p.pedido_id={$pedido_id}");
		$row_data_autor = mysql_fetch_row($query_data_autor);
		
		$ar['pedido_id'] = $pedido_id;
		$ar['pedido_data_criacao'] = date('d/m/Y', $row_data_autor[0]);
		$ar['pedido_autor'] = $row_data_autor[1] . " " . $row_data_autor[2];
		
		echo json_encode($ar);
		
	} else {
		
		echo "Cliente ainda não selecionado.";
	}

?>