<?php
	@include "inc/pre_header_info.inc.php";
	@include "inc/listOrders_backend.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>

	<title>Grupo Cliente X - Pedidos - Lista</title>

	<link href="datepicker.css" rel="stylesheet" type="text/css" />
	
	<script src="js/GenList.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/datepicker.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			
			list = new List();
			
			list.fillSelect('cliente_nome', 'select_por_cliente');
			list.fillSelect('autor_nome', 'select_por_autor');
			
			toggleSelect = function (id, id2) {
				if(typeof(id2!=="undefined") && id2!=null) {
					list.toggle(id, id2);
				} else {
					list.toggle(id);
				}	
			}
			
			$('gen_list').observe('click', function(event) { list.genlist(list.getSearchParams()); });
		});

	</script>	
</head>

<body>
	<div id="container">

		<? @include "inc/menu.inc.php"; ?>

		<div id="box" class="lista-de-pedidos">
			
			<span class="right breadcrumb"><br/><br/><a href="pedidoSplash.php">Novo pedido >></a>&nbsp;&nbsp;</span>
			<span class="right breadcrumb"><br/><br/><a href="index.php">&nbsp;&nbsp;<< Página inicial</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
			
			<h1>Pedidos</h1>
			
			<div class="painel callout">
				
				<div class="callout orange rmv msg"><b>Para uma lista completa de pedidos para esse último ano</b> (de <? echo date('d/m/Y', mktime(0,0,0,date('n'), date('d'), date('Y')-1)); ?> à <? echo date('d/m/Y', mktime(0,0,0,date('n'), date('d'), date('Y'))); ?>), <b>clique diretamente em "Ver Pedidos."</b></div>
		
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
					<dt>Por cliente:</dt>
					<dd>
						<select id="select_por_cliente" class="search_param" disabled>
							<option value="0">-- Selecione um cliente --</option>
						</select>
						<input type="checkbox" id="por_cliente" onclick="toggleSelect('select_por_cliente')" />
					</dd>

					<dt>Por autor:</dt>
					<dd>
						<select id="select_por_autor" class="search_param" disabled>
							<option value="0">-- Selecione um autor --</option>
						</select>
						<input type="checkbox" id="por_autor" onclick="toggleSelect('select_por_autor')" />
					</dd>
				</dl>
		
				<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:none" />
		
				<div class="gen-button clear">
					<a href="#ver_pedidos" id="gen_list" class="button"><span>Ver Pedidos</span></a>
				</div>
		
				<div class="search-results">
					<div class="callout list-wrapper">
						<table id="home-list" cellspacing="0" cellpadding="0">
							<thead>
								<tr>
									<th>Razão Social</th>
									<th>Autor</th>
									<th>CNPJ</th>
									<th>Data de emissão</th>
									<th>Nro</th>
									<th></th>
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