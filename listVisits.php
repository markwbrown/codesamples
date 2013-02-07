<?php
	@include "inc/pre_header_info.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>

	<title>Grupo Cliente X - Visitas - Lista</title>

	<link href="datepicker.css" rel="stylesheet" type="text/css" />
	
	<script src="js/GenList.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/GenVisitList.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/datepicker.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			
			list = new GenVisitList();
			
			list.fillSelect('resultado_nome', 'select_por_resultado');
			list.fillSelect('vendedor_nome', 'select_por_vendedor');
			
			toggleSelect = function (id, id2) {
				if(typeof(id2!=="undefined") && id2!=null) {
					list.toggle(id, id2);
				} else {
					list.toggle(id);
				}	
			}
		});

	</script>	
</head>

<body>
	<div id="container">

		<? @include "inc/menu.inc.php"; ?>

		<div id="box" class="lista-de-visitas">
			<span class="right breadcrumb"><br/><br/><a href="visitas.php">Adicionar Visitas >></a>&nbsp;&nbsp;</span>
			<span class="right breadcrumb"><br/><br/><a href="#" onclick="history.go(-1); return false;">&nbsp;&nbsp;<< Página anterior</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
			
			<h1>Buscar Visitas</h1>
			
			<div class="painel callout">
				<div class="callout orange rmv msg"><b>Para uma lista completa de visitas dos ultimos 30 dias</b> (de <? echo date('d/m/Y', mktime(0,0,0,date('n')-1, date('d'), date('Y'))); ?> à <? echo date('d/m/Y', mktime(0,0,0,date('n'), date('d'), date('Y'))); ?>), <b>clique diretamente em "Ver Visitas."</b></div>
		
				<dl>
			
					<dt>Por data de emissão:</dt>
					<dd>
						<fieldset>
					      	<input type="text" class="format-d-m-y highlight-days-67 show-weeks search_param" id="select_por_emissao" name="dp-normal-1" value="" maxlength="10" disabled />
					    </fieldset>
					
						<fieldset class="last">
							<input type="checkbox" id="por_emissao_2" class="right" onclick="toggleSelect('select_por_emissao', 'select_por_emissao2')" />
					      	<input type="text" class="format-d-m-y highlight-days-67 show-weeks search_param" id="select_por_emissao2" name="dp-normal-2" value="" maxlength="10" disabled />
					    </fieldset>
					</dd>
					<dt>Por resultado:</dt>
					<dd>
						<select id="select_por_resultado" class="search_param" disabled>
							<option value="0">-- Selecione um resultado --</option>
						</select>
						<input type="checkbox" id="por_resultado" onclick="toggleSelect('select_por_resultado')" />
					</dd>

					<dt>Por vendedor:</dt>
					<dd>
						<select id="select_por_vendedor" class="search_param" disabled>
							<option value="0">-- Selecione um vendedor --</option>
						</select>
						<input type="checkbox" id="por_vendedor" onclick="toggleSelect('select_por_vendedor')" />
					</dd>
				</dl>
		
				<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:none" />
				
				<div class="gen-button clear">
					<a href="#ver_visitas" id="gen_list" class="button"><span>Ver Visitas</span></a>
				</div>
				
				<a href="#print" class="print" id="print"><img src="img/icon_print.png" width="72" height="72" /></a>

				
				<div class="search-results">
					<div class="callout list-wrapper">
						<table id="dyn-rows" cellspacing="0" cellpadding="0">
							<thead>
								<tr>
									<th>Data</th>
									<th>Nome</th>
									<th>Telefone</th>
									<th>Bairro</th>
									<th>Comprador</th>
									<th>Resultado</th>
									<th>Observações</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div> <!-- /.painel -->
			
		</div> <!-- end of Box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>