<?php
	@include "inc/pre_header_info.inc.php";
	@include "inc/listtecidoes_backend.inc.php";	
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>
	
	<title>Grupo Cliente X - Tecidos - Lista</title>
	
	<script src="js/GenList.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/GenTecidoList.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
	
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			
			list = new GenTecidoList();
		});
		
		function rmv(el_nbr) {
			list._onRmv(el_nbr);
			
			var theRow = $('rm-'+el_nbr).up().up();
			new Effect.Fade(theRow, { duration:1});
		}
		
	</script>	
</head>

<body>
	<div id="container">
		
		<? @include "inc/menu.inc.php"; ?>
			
		<div id="box" class="lista lista-de-tecidos">
			<span class="right breadcrumb"><br/><br/><a href="tecidos.php">adicionar tecidos >></a>&nbsp;&nbsp;</span>
			<span class="right breadcrumb"><br/><br/><a href="#" onclick="history.go(-1); return false;">&nbsp;&nbsp;<< Página anterior</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
			
			<h1>Tecidos</h1>

			<div class="list-container">
				<h3>Busca</h3>
				
				<p>Use pelo menos 5 caractéres em sua busca. <b>Exemplo:</b> Quando se digita <b>santa</b> e se mantém "Fabricante" selecionado ao lado, o sistema busca todos os tecidos para a santanense.</p>
				<br/>
				<p><b>Quanto mais específica, mais rápida é a busca.</b></p>
				
				<dl>
					<dt>
						<input type="text" value="" id="tecido_busca"  class="search_param" />
						<input type="radio" name="tecido_radio" id="fabricante" value="Fabricante" class="search_param" checked /> 	
						<label for="fabricante">Fabricante</label>
						<input type="radio" name="tecido_radio" id="nome" value="Nome" class="search_param" />
						<label for="nome">Nome do Tecido</label>
						<input type="radio" name="tecido_radio" id="cor" value="Cor" class="search_param" />
						<label for="cor">Cor</label>
					</dt>
					<dd>
						<a href="#ver_tecidos" id="gen_list" class="button"><span>Buscar</span></a>
						<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:none" />
					</dd>
				</dl>	

				<div class="search-results">
					<div class="callout list-wrapper">
						<table id="dyn-rows" cellspacing="0" cellpadding="0">
							<thead>
								<tr>
									<th>Fabricante</th>
									<th>Código do Tecido</th>
									<th>Nome do Tecido</th>
									<th>Código da Cor</th>
									<th>Nome da Cor</th>
									<th></th>
								</tr>
							</thead>
							<tbody><tr>
								<td colspan="6" class="single">Use a busca acima para exibir tecidos.</td>
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