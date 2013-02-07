<?
	// Load the clientList string with database information
	// Rafael Lucchese - May 2009

	//Clientes - Load a Dropdown box with clients
	$queryClient = "SELECT * FROM `cliente` ORDER BY `cliente_razao_social` ASC";
	$resultClient = mysql_query($queryClient) or die("Error:" . $queryClient .  mysql_error());

	$clientList = '
		<select name="client_id" id="client_id" tabindex="0" class="inputControlClient">
			<option value="0" checked>-- Selecione Um Cliente --</option>
			';

			while($rowClient = mysql_fetch_array($resultClient, MYSQL_ASSOC))
			{
				
				if(!$cliente['cliente_apagado']) {
					$clientList .= '<option value="' . $rowClient['cliente_id'] . '">' . ucwords($rowClient['cliente_razao_social']) . '</option>';	
				} // se o cliente esta marcado como apagado entao ele nao deve aparecer aqui
			}

		$clientList .= '
		</select>
		';
?>