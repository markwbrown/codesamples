<?php
	@include "inc/pre_header_info.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>

	<title>Grupo Cliente X - Tecido - Criar</title>
	
	<script src="js/Tecidos.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			valid = new Validation('tecido-form', { onSubmit:false, stopOnFirst:true });
			t = new tecido();
		});
		
		function rmv(el_nbr) {
			t._onRmv(el_nbr);
			
			var theRow = $('rm-'+el_nbr).up().up();
			new Effect.Fade(theRow, { duration:1});
		}

	</script>
</head>

<body class="tecido">
	<div id="container">

		<? @include "inc/menu.inc.php"; ?>

		<div id="box">
			<form method="post" id="tecido-form" action="">
				<span class="right breadcrumb"><br/><br/><a href="listTecidos.php">Buscar tecidos >></a>&nbsp;&nbsp;</span>
				<h1>Criar Tecidos</h1>
		
				<div class="content">
					<div class="topinfo">
						<div class="left fornec-nome">
							<p>Fabricante:</p>
							<select class="validate-selection" tabindex="1" id="fornec-nome">
								<option value="0">-- Selecione Um --</option>
							</select>
						</div>		
						<div class="clear left tecido-codigo">
							<p>Código do Tecido:</p>
							<input class="required" type="text" id="tecido-codigo" tabindex="1" maxlength="25" />
						</div>
						<div class="left tecido-nome">
							<p>Nome do Tecido:</p>
							<input class="required" type="text" id="tecido-nome" tabindex="2" maxlength="65" />
						</div>
						<div class="clear left cor-codigo">
							<p>Código da Cor:</p>
							<input class="required" tabindex="3" type="text" id="cor-codigo" maxlength="10" />
						</div>
						
						<div class="left cor-nome">
							<p>Nome da Cor:</p>
							<input class="required" tabindex="4" type="text" id="cor-nome" maxlength="25" />
						</div>
					</div>
					
					<div class="save-button">
						<a href="#salvar" id="salvar" class="salvar button"><span>Salvar Tecido</span></a>
					</div>
					
					<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:none" />
					
					<div class="search-results">
						<div class="table-head">
							<div class="tecido-fab left">Fabricante</div>
							<div class="tecido-codigo left">Código do Tecido</div>
							<div class="tecido-nome left">Nome do Tecido</div>
							<div class="cor-codigo left">Código da Cor</div>
							<div class="cor-nome left">Nome da Cor</div>
							<div class="rmv left"></div>
						</div>
						
						<div class="callout list-wrapper">
							<table id="dyn-rows" cellspacing="0" cellpadding="0">
								<thead></thead>
								<tbody></tbody>
							</table>
						</div>
						
						<div class="arrows">
							<a href="#down" id="down"><img src="img/arrow_down.png" width="30" height="29" /></a>
							<a href="#up" id="up"><img src="img/arrow_up.png" width="30" height="29" /></a>
						</div>	
						
					</div>					
					
				</div> <!-- /.content-->
			</form>
		</div> <!-- /box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>