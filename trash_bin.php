<?php
	/* Cliente X Systema de Administracao - Rafael Lucchese */ 
	@include "inc/header.inc.php";
	@include "inc/trash_bin_backend.inc.php";
	
?>

<div id="box" class="trash-can">
	<h1>Cesto de Lixo</h1>
	<div class="sortWrapper">
		<div class="left sortTitle">organizar usando:&nbsp;</div>
		<form class="left" method="post" name="sortForm">
	    	<select name="sortBy" onchange="this.form.submit()">
	        	<option value="1" <? if($_POST['sortBy'] == 1) { echo "SELECTED"; } ?>>Razão Social</option>
	            <option value="2" <? if($_POST['sortBy'] == 2) { echo "SELECTED"; } ?>>Data de Criação</option>
	        </select>
	    </form>
	</div>
<?php 
	
	// queries located inside of trash_bin_backend.inc.php
	
	if(mysql_num_rows($results1) > 0)
	{
		// print the table and the contents
	?>

		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td class="center" colspan="5"><h2 class="large">Clientes</h2></td>
			</tr>
			<tr style="background-color:#babaca; border:1px solid #aa0; border-top:none;">
				<td><h2>Razão Social</h2></td>
				<td><h2>CNPJ</h2></td>
				<td><h2>Apagado</h2></td>
				<?
				if(isset($_SESSION["p_total"])) // somente administradores
				{ ?>
					<td><h2>Restaurar</h2></td>	
				<?
				}
			
				if(isset($_SESSION["p_total"])) // administradores somente
				{ ?>
					<td><h2>Apagar</h2></td>
				<?
				} ?>
				</tr>

	<?php 

		while($cliente = mysql_fetch_array($results1))
		{
			$data = date('d/m/y', $cliente['cliente_apagado_data']); //por a data em um formato legivel
		
			if($i & 1)
				$back = "#f5f5f5";
			else
				$back = "#fff";
	?>		
			<tr style="background-color: <? echo $back; ?> ;border:1px solid #666;">
			<td style='padding:8px 10px; width:400px;'>
            	<a href="#" class='tableLinks' onclick='window.open("report.php?id=<? echo $cliente['cliente_id'] ?>", "cliente", "location=0, status=0, scrollbars=1,width=720,height=540");return false;'>
					<? echo $cliente['cliente_razao_social']; ?>
                </a>
            </td>
			<td><? echo $cliente['cliente_cnpj1'] . "-" . $cliente['cliente_cnpj2'] . "-" . $cliente['cliente_cnpj3'] . "/" . $cliente['cliente_cnpj4'] . "-" . $cliente['cliente_cnpj5']; ?></td>
			<td><? echo $data ?></td>
			<?
			if(isset($_SESSION["p_total"])) // administradores somente
			{ ?>
				<td  class="upper" style='width:80px;'><a href='?id=<? echo $cliente["cliente_id"] ?>&r=1&cliente=1' class='tableLinks'>restaurar</a></td>
			<?
			}
			
			if(isset($_SESSION["p_total"])) // administradores somente
			{ ?>
				<td  class="upper" style='width:80px;'><a onclick='return confirmDelClient()' href='?delete=1&id=<? echo $cliente["cliente_id"] ?>&cliente=1' class='tableLinks'>apagar</a></td>
			<?
			} ?>
			</tr>
	<?		 
			$i++;
		} // end of while
	?>
	</table>

	<?
	} else {
	?>
	<div style="text-align:center; padding:20px 0px; border:1p solid #aa0;">
		<span class="error">Nenhum cliente encontrado no cesto de lixo. Que bom! 
		<!--<a href="cliente.php" class="tableLinks">Clique aqui</a> para adicionar clientes.-->
		</span>
	</div>
	
	<? 
	}
	?>

<?php 
	
	// queries located inside of trash_bin_backend.inc.php
	
	if(mysql_num_rows($results2) > 0)
	{
		// print the table and the contents
	?>

		<table width="100%" cellspacing="0" cellpadding="0">
			<tr style="border:none;">
				<td class="center" colspan="6"><h2 class="large">Pedidos</h2></td>
			</tr>
			<tr style="background-color:#babaca; border:1px solid #aa0;">
				<td><h2>Raz&atilde;o Social</h2></td>
				<td><h2>CNPJ</h2></td>
				<td><h2>Apagado</h2></td>
				<?
				if(isset($_SESSION["p_total"])) // somente administradores
				{ ?>
					<td><h2>Restaurar</h2></td>	
				<?
				}
			
				if(isset($_SESSION["p_total"])) // administradores somente
				{ ?>
					<td><h2>Apagar</h2></td>
				<?
				} ?>
				</tr>

	<?php 

		while($pedido = mysql_fetch_array($results2))
		{
			$data = date('d/m/y', $pedido['pedido_apagado_data']); //por a data em um formato legivel
		
			if($j & 1)
				$back = "#f5f5f5";
			else
				$back = "#fff";
	?>		
			<tr style="background-color: <? echo $back; ?> ;border:1px solid #666;">
			<td style='padding:8px 10px; width:400px;'>
            	<a href="#" class='tableLinks' onclick='window.open("report_pedido.php?id=<? echo $pedido['pedido_id'] ?>", "pedido", "location=0, status=0, scrollbars=1,width=720,height=540");return false;'>
					<? echo $pedido['cliente_razao_social']; ?>
                </a>
            </td>
			<td><? echo $pedido['cliente_cnpj1'] . "-" . $pedido['cliente_cnpj2'] . "-" . $pedido['cliente_cnpj3'] . "/" . $pedido['cliente_cnpj4'] . "-" . $pedido['cliente_cnpj5']; ?></td>
			<td><? echo $data ?></td>
			<?
			if(isset($_SESSION["p_total"])) // administradores somente
			{ ?>
				<td  class="upper" style='width:80px;'><a href='?id=<? echo $pedido["pedido_id"] ?>&r=1&pedido=1' class='tableLinks'>restaurar</a></td>
			<?
			}
			
			if(isset($_SESSION["p_total"])) // administradores somente
			{ ?>
				<td  class="upper" style='width:80px;'><a onclick='return confirmDelClient()' href='?delete=1&id=<? echo $pedido["pedido_id"] ?>&pedido=1' class='tableLinks'>apagar</a></td>
			<?
			} ?>
			</tr>
	<?		 
			$i++;
		} // end of while
	?>
	</table>

	<?
	} else {
	?>
	<div style="text-align:center; padding:20px 0px; border:1p solid #aa0;">
		<span class="error">Nenhum pedido apagado. Que bom!
			<!--<a href="cliente.php" class="tableLinks">Clique aqui</a> para adicionar clientes.-->
		</span>
	</div>
	
	<? 
	}
?>

<?php 
	
	// queries located inside of trash_bin_backend.inc.php - Orphan orders
	
	if(mysql_num_rows($results3) > 0)
	{
		// print the table and the contents
	?>

		<table width="100%" cellspacing="0" cellpadding="0">
			<tr style="border:none;">
				<td class="center" colspan="6"><h2 class="large">Pedidos Orf&atilde;os</h2></td>
			</tr>
			<tr style="background-color:#babaca; border:1px solid #aa0;">
				<td><h2>Raz&atilde;o Social</h2></td>
				<td><h2>CNPJ</h2></td>
				<td><h2>Orf&atilde;o</h2></td>
				<?
				if(isset($_SESSION["p_total"])) // administradores somente
				{ ?>
					<td><h2>Apagar</h2></td>
				<?
				} ?>
				</tr>

	<?php 

		while($pedido = mysql_fetch_array($results3))
		{
			$data = date('d/m/y', $pedido['cliente_apagado_data']); //por a data em um formato legivel
		
			if($j & 1)
				$back = "#f5f5f5";
			else
				$back = "#fff";
	?>		
			<tr style="background-color: <? echo $back; ?> ;border:1px solid #666;">
                <td class="upper" style='padding:8px 10px; width:400px;'>
                    <a href="#" class='tableLinks' onclick='window.open("report_pedido.php?id=<? echo $pedido['pedido_id'] ?>", "pedido", "location=0, status=0, scrollbars=1,width=720,height=540");return false;'>
                        <? if($pedido['cliente_razao_social']) echo $pedido['cliente_razao_social']; else echo "ORF&Atilde;O"; ?>
                    </a>
                </td>
                <td><? 
					if($pedido['cliente_cnpj1']) 
						echo $pedido['cliente_cnpj1'] . "-" . $pedido['cliente_cnpj2'] . "-" . $pedido['cliente_cnpj3'] . "/" . $pedido['cliente_cnpj4'] . "-" . $pedido['cliente_cnpj5']; 
					else
						echo $pedido['f_cliente_cnpj'];
				?></td>
                <td><? echo $data ?></td>
                <?
                if(isset($_SESSION["p_total"])) // administradores somente
                { ?>
                    <td  class="upper" style='width:80px;'><a onclick='return confirmDelClient()' href='?delete=1&id=<? echo $pedido["pedido_id"] ?>&pedido=1' class='tableLinks'>apagar</a></td>
                <?
                } ?>
			</tr>
	<?		 
			$i++;
		} // end of while
	?>
	</table>

	<?
	} else {
	?>
	<div style="text-align:center; padding:20px 0px; border:1p solid #aa0;">
		<span class="error">Nenhum pedido orf&atilde;o encontrado. Que bom!
			<!--<a href="cliente.php" class="tableLinks">Clique aqui</a> para adicionar clientes.-->
		</span>
	</div>
	
	<? 
	}
?>

</div> <!-- end of Box -->

<?php 
	@include "inc/footer.inc.php"; 
?>