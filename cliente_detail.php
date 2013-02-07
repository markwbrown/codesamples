<?php
	@include "inc/pre_header_info.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>
	
	<title>Grupo Cliente X - Clientes - Visualização</title>
	
	<script src="js/GenList.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/ListClientDetail.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
		});
		
		list = new ListClientDetail(<? echo $_GET['id']; ?>); // Gera a lista de pedidos
		
	</script>	
</head>

<body>
	<div id="container">
		
		<? @include "inc/menu.inc.php"; ?>
		
		<div id="box" class="cliente detail">
			<span class="right breadcrumb"><br/><br/><a href="listClients.php">voltar para a busca de clientes >></a>&nbsp;&nbsp;</span>
			<h1>Detalhes do Cliente</h1>
	
			<div class="painel callout">
				<div id="logo" class="logo-wrapper left"></div>
					
				<div class="right client-info" style="position:relative;">
					<h3 id="razao">[Nome do Cliente]</h3>
					<div style="position:absolute; right:0; top:0;"><a href="editClient.php?id=<?= $_GET['id']; ?>">Modificar</a></div>
					
					<div class="grid2col">
						<div class="col">
							<dl>
								<dt>Endereço:</dt>
								<dd id="endereco"></dd>

								<dt>Bairro:</dt>
								<dd id="bairro"></dd>

								<dt>Cidade:</dt>
								<dd id="cidade"></dd>

								<dt>Estado:</dt>
								<dd id="estado"></dd>
							</dl>
						</div>
						<div class="col last">
							<dl>
								<dt>CEP:</dt>
								<dd id="cep"></dd>

								<dt>Website:</dt>
								<dd id="website"></dd>

								<dt>CNPJ:</dt>
								<dd id="cnpj"></dd>

								<dt>Insc. Estadual:</dt>
								<dd id="insc"></dd>
							</dl>
						</div>
					</div> <!-- .grid2col-->
				</div>
			</div> <!-- /.painel -->
			
			<div class="orders" style="position:relative;">
				<h3>Histórico de Pedidos</h3>
				<div style="position:absolute; right:20px; top:30px;"><a href="pedido.php?c=<?= $_GET['id']; ?>">Novo Pedido</a></div>

				<div class="callout list-wrapper">
					<table id="order-list" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th>Data de Emissão</th>
								<th>N de Peças</th>
								<th>Valor</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<td colspan='4' align="center">Nenhum pedido encontrado no momento.</td>
						</tbody>
					</table>
				</div>
			</div><!-- /.orders -->
			<div class="divisor-upside"></div>
			
			<div class="orders" style="position:relative; margin-top:20px;">
				<h3>Histórico de Pedidos (Gráficos) - <b>(Ainda em construção)</b></h3>

				<div class="callout list-wrapper">
					<div class="center" style="padding:20px 12px;"><img src="img/fpo_graphs.png" width="280" height="129" /></div>
				</div>
			</div><!-- /.orders -->
			<div class="divisor-upside"></div>
			
			<div class="orders" style="position:relative; margin-top:20px;">
				<h3>Ultilização de Materiais (Gráficos) - <b>(Ainda em construção)</b></h3>

				<div class="callout list-wrapper">
					<div class="center" style="padding:20px 12px;"><img src="img/fpo_graphs.png" width="280" height="129" /></div>
				</div>
			</div><!-- /.orders -->
			<div class="divisor-upside"></div>
			
		</div> <!-- /box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>