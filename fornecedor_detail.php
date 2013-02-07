<?php
	@include "inc/pre_header_info.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>
	
	<title>Grupo Cliente X - Fornecedores - Visualização</title>
	
	<script src="js/GenList.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/ListFornecedorDetail.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
		});
		
		f = new ListFornecedorDetail(<? echo $_GET['id']; ?>);
	</script>
</head>

<body>
	<div id="container">
		
		<? @include "inc/menu.inc.php"; ?>
		
		<div id="box" class="cliente detail">
			<span class="right breadcrumb"><br/><br/><a href="listFornecedores.php">busca de fornecedores >></a>&nbsp;&nbsp;</span>
			<span class="right breadcrumb"><br/><br/><a href="editFornecedor.php?id=<?= $_GET['id']; ?>">&nbsp;&nbsp;<< Modificar esse Fornecedor</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
			
			<h1>Detalhes do Fornecedor</h1>
	
			<div class="painel callout">
					
				<div class="client-info" style="position:relative;">
					<h3 id="razao">[Nome do Fornecedor]</h3>
					<div style="position:absolute; right:0; top:0;"><a href="editFornecedor.php?id=<?= $_GET['id']; ?>">Modificar</a></div>
					
					<div class="grid2col">
						<div class="col">
							<dl>
								<dt>Endereço:</dt>
								<dd id="endereco"></dd>

								<dt>Telefone:</dt>
								<dd id="tel"></dd>
								
								<dt>Vendedor:</dt>
								<dd id="comprador"></dd>
								
								<dt>Vendedor Email:</dt>
								<dd id="email"></dd>
								
								<dt>Nos Fornece:</dt>
								<dd id="mat"></dd>
							</dl>
						</div>
						<div class="col last">
							<dl>
								<dt>Site:</dt>
								<dd id="website"></dd>
								
								<dt>Telefone 2:</dt>
								<dd id="tel2"></dd>
								
								<dt>Fax:</dt>
								<dd id="fax"></dd>
								
								<dt>Vendedor Tel:</dt>
								<dd id="comprador_tel"></dd>
								
								<dt>Vendedor Cel:</dt>
								<dd id="comprador_cel"></dd>
								
							</dl>
						</div>
					</div> <!-- .grid2col-->
				</div>
			</div> <!-- /.painel -->
			
			<div class="orders" style="position:relative;">
				<h3>Histórico de Compras</h3>

				<div class="callout list-wrapper">
					<table id="order-list" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th>Data</th>
								<th>N de Ítens</th>
								<th>Valor</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<td colspan='4' align="center">Nehuma compra encontrada no momento. <b>(Ainda em construção)</b></td>
						</tbody>
					</table>
				</div>
			</div><!-- /.orders -->
			<div class="divisor-upside"></div>
			
			<div class="orders" style="position:relative; margin-top:20px;">
				<h3>Histórico de Compras (Gráficos) - <b>(Ainda em construção)</b></h3>

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