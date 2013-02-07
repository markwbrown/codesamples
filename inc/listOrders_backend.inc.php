<? /* Include on top of listOrders.php */

if(isset($_SESSION["p_total"]) || isset($_SESSION["p_pedidoList"])) { // if the user has permissions to modify clients
	
	if($_GET['delete']) {
		
		if(isset($_SESSION["p_total"]) || isset($_SESSION["p_pedidoDel"])) { // if the user has permissions to modify clients
			//apargar informacoes do cliente
			$queryPedido = "UPDATE `pedido` SET `pedido_apagado`='1', `pedido_apagado_data`='" . mktime() . "' 
								WHERE `pedido_id`='" . $_GET['id'] . "';";
			$resultPedido = mysql_query($queryPedido) or die($queryPedido . "   <br><br>" . mysql_error());
		} else {
			//Nao possui permissao para ver essa pagina
			echo "	<script>	
						alert('Sua conta nao tem pivilegios suficientes para apagar esse cliente.'); 
						location.href='index.php';
					</script>
				";
		}
	}
	
} else {
	echo "	<script>	
				alert('Sua conta nao tem pivilegios suficientes para ver a lista de clientes.'); 
				location.href='index.php';
			</script>
		";
}
?>