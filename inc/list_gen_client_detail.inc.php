<?php
	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	//echo "ID = " . $_POST['client_num'];
	
	// Info: client_num
	// Array (
	// 		[client_num] => value
	//	)
	
	if($_POST['client_info']) {
		
		$client_query = "	SELECT * FROM `cliente` as c
							INNER JOIN `endereco` as e on `e`.`f_cliente_id`=`c`.`cliente_id`
							WHERE `c`.`cliente_id`=" . $_POST['client_num'];

		$client_result = mysql_query($client_query);
		$client_row =  mysql_fetch_array($client_result, MYSQL_ASSOC);
		
		echo json_encode($client_row);
	
	} else {
		
		$query_string = "	SELECT * from `pedido`
							WHERE `f_cliente_id`=" . $_POST['client_num'] . "
							AND `pedido_apagado`= 0";

		$query = mysql_query($query_string);

		$result = array();

		$i = 0; $num_pecas_tmp = 0; $total_price_tmp = 0;

		$client_row['totals'] = array();

		while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {

			$result[$i] = $row;

			$query_pecas = "SELECT * from `pecas` as p WHERE `p`.`f_pedido_id`=" . $row['pedido_id'];
			$result_pecas = mysql_query($query_pecas);

			while($peca = mysql_fetch_array($result_pecas, MYSQL_ASSOC)) {

				$num_temp = $peca['tam_34'] + $peca['tam_36'] + $peca['tam_38'] + $peca['tam_40'] + $peca['tam_42'] + $peca['tam_44'] + $peca['tam_46'] + $peca['tam_48'] + $peca['tam_50'] + $peca['tam_52'] + $peca['tam_54'] + $peca['tam_56'];

				$price_temp = $num_temp * $peca['pecas_preco'];

				$num_pecas_tmp += $num_temp;
				$total_price_tmp += $price_temp;
			}
			
			$result[$i]['emissao'] = date('d-m-Y', $row['pedido_timestamp']);
			$result[$i]['num_pecas'] = $num_pecas_tmp;
			$result[$i]['total_price'] = number_format($total_price_tmp - $row['pedido_desc'], 2, ',', '.');

			$i++;

		}
		
		echo json_encode($result);
		
	}
	
?>