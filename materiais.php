<?php
	@include "inc/pre_header_info.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>

	<title>Grupo Cliente X - Material - Criar</title>
	
	<script src="js/Material.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			valid = new Validation('material-form', { onSubmit:false, stopOnFirst:true });
			m = new Material();
		});
		
		function rmv(el_nbr) {
			m._onRmv(el_nbr);
			
			var theRow = $('rm-'+el_nbr).up().up();
			new Effect.Fade(theRow, { duration:1});
		}

	</script>
</head>

<body class="material">
	<div id="container">

		<? @include "inc/menu.inc.php"; ?>

		<div id="box">
			<form method="post" id="material-form" action="">
				<span class="right breadcrumb"><br/><br/><a href="listFornecedores.php">Buscar fornecedores >></a>&nbsp;&nbsp;</span>
				<h1>Gerenciar Materiais de Fornecedores</h1>
		
				<div class="content">
					<div class="topinfo">		
						<div class="clear left mat-nome">
							<p>Nome do Material:</p>
							<input class="required" type="text" id="mat-nome" tabindex="1" maxlength="75" />
							<b>(Máximo 75 caractéres)</b>
						</div>
					</div>
					
					<div class="save-button clear">
						<a href="#salvar" id="salvar" class="salvar button"><span>Salvar Material</span></a>
					</div>
					
					<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:block" />
					
					<div class="search-results">
						<div class="table-head">
							<div class="mat-id left">#</div>
							<div class="mat-nome left">Nome do Material</div>
							<div class="rmv left"></div>
						</div>
						
						<div class="callout list-wrapper">
							<table id="dyn-rows" cellspacing="0" cellpadding="0">
								<thead></thead>
								<tbody></tbody>
							</table>
						</div>
						
					</div>					
					
				</div> <!-- /.content-->
			</form>
		</div> <!-- /box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>