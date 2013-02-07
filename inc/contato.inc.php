<?
	// Load the contact array with database information
	// Rafael Lucchese - July 2009

	// Contacts. Will be used for the comparison if(rowContato[contato_id] equal to comprador[f_contato_id])
	// and then they will be selected. This happens when we are editing a client record.
	$queryContato = "SELECT * FROM `contato`;";
	$resultContato1 = mysql_query($queryContato) or die("Erro: Contatos nao encontrados." . mysql_error());
	$resultContato2 = mysql_query($queryContato) or die("Erro: Contatos nao encontrados." . mysql_error());
	if(mysql_num_rows($resultContato2)>0)
	{
		$hasContato2 = true;	
	}
	$resultContato3 = mysql_query($queryContato) or die("Erro: Contatos nao encontrados." . mysql_error());
	//used below...
	
	
	//Comprador. Three queries, one for each buyer.
	$queryComprador = "SELECT * FROM `comprador` WHERE `f_cliente_id`='" . $_GET['id'] . "'";	
	$resultComprador1 = mysql_query($queryComprador) or die("Error:" . $queryComprador .  mysql_error());
	$comprador1 = mysql_fetch_array($resultComprador1);
	$resultComprador2 = mysql_query($queryComprador) or die("Error:" . $queryComprador .  mysql_error());
	$comprador2 = mysql_fetch_array($resultComprador2);
	$resultComprador3 = mysql_query($queryComprador) or die("Error:" . $queryComprador .  mysql_error());
	$comprador3 = mysql_fetch_array($resultComprador3);

	// Each SELECT element has to have a different name so it can be manipulated separately. Everything was done
	// separately, so that when we are editing a user each box gets populated and set to it's respective value separately.
	$contato1 = '
		<select name="contato_id"  tabindex="15" class="inputControl">
			<option value="start" checked>-- Selecione Um --</option>
			';
			
	
			while($rowContato1 = mysql_fetch_array($resultContato1, MYSQL_ASSOC))
			{
				if($rowContato1['contato_id']==$comprador1['f_contato_id'])
				{
					$contato1 .= '<option value="' . $rowContato1['contato_id'] . '" SELECTED>' . $rowContato1['contato_tipo'] . '</option>';
				}
				else
				{	
					$contato1 .= '<option value="' . $rowContato1['contato_id'] . '">' . $rowContato1['contato_tipo'] . '</option>';
				}	
			}

		$contato1 .= '
		</select>
		';
	
	$contato2 = '
		<select name="contato_id_2"  tabindex="15" class="inputControl">
			<option value="start" checked>-- Selecione Um --</option>
			';
		
		
			while($rowContato2 = mysql_fetch_array($resultContato2, MYSQL_ASSOC))
			{
				if(($rowContato2['contato_id']==$comprador2['f_contato_id']) && $hasContato2)
				{
					$contato2 .= '<option value="' . $rowContato2['contato_id'] . '" SELECTED>' . $rowContato2['contato_tipo'] . '</option>';
				}
				else
				{	
					$contato2 .= '<option value="' . $rowContato2['contato_id'] . '">' . $rowContato2['contato_tipo'] . '</option>';
				}	
			}

		$contato2 .= '
		</select>
		';
	
	
	$contato3 = '
		<select name="contato_id_3"  tabindex="15" class="inputControl">
			<option value="start" checked>-- Selecione Um --</option>
			';
			
	
			while($rowContato3 = mysql_fetch_array($resultContato3, MYSQL_ASSOC))
			{
				if($rowContato3['contato_id']==$comprador3['f_contato_id'])
				{
					$contato3 .= '<option value="' . $rowContato3['contato_id'] . '" SELECTED>' . $rowContato3['contato_tipo'] . '</option>';
				}
				else
				{	
					$contato3 .= '<option value="' . $rowContato3['contato_id'] . '">' . $rowContato3['contato_tipo'] . '</option>';
				}	
			}

		$contato3 .= '
		</select>
		';
	
?>