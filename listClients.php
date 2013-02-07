<?php
	@include "inc/pre_header_info.inc.php";
	@include "inc/listClients_backend.inc.php";	
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>
	
	<title>Grupo Cliente X - Clientes - Lista</title>
	
	<script src="js/GenList.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/SearchList.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			
			list = new SearchList();
		});
	</script>	
</head>

<body>
	<div id="container">
		
		<? @include "inc/menu.inc.php"; ?>
			
		<div id="box" class="lista lista-de-clientes">
			<span class="right breadcrumb"><br/><br/><a href="cliente.php">adicionar clientes >></a>&nbsp;&nbsp;</span>
			<span class="right breadcrumb"><br/><br/><a href="#" onclick="history.go(-1); return false;">&nbsp;&nbsp;<< Página anterior</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
			
			<h1>Clientes</h1>

			<div class="list-container">
				<h3>Busca</h3>
				<dl>
					<dt>
						<input type="text" value="" id="cliente_busca"  class="search_param" />
						<input type="radio" name="cliente_radio" id="razao" value="Razão Social" class="search_param" checked /> 	
						<label for="razao">Razão Social</label>
						<input type="radio" name="cliente_radio" id="cnpj" value="CNPJ" class="search_param" />
						<label for="cnpj">CNPJ</label>
					</dt>
					<dd>
						<a href="#ver_pedidos" id="gen_list" class="button"><span>Buscar</span></a>
						<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:none" />
					</dd>
				</dl>	

				<div class="search-results">
					<div class="callout list-wrapper">
						<table id="client-list" cellspacing="0" cellpadding="0">
							<thead>
								<tr>
									<th>Razão Social</th>
									<th>CNPJ</th>
									<th>Modificar</th>
									<th>Vizualizar</th>
								</tr>
							</thead>
							<tbody><tr>
								<td colspan="4" class="single">Use a busca acima para exibir clientes.</td>
							</tr></tbody>
						</table>
					</div>
				</div>
			</div> <!-- /list-wrapper -->
		</div> <!-- /box -->

		<? @include "inc/footer_new.inc.php"; ?>
		
	</div> <!-- /container --> 
</body>
</html>		