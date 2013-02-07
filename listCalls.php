<?php
	@include "inc/pre_header_info.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>

	<title>Grupo Cliente X - Ligações - Busca</title>

	<link href="datepicker.css" rel="stylesheet" type="text/css" />
	
	<script src="js/GenList.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/GenCallList.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/datepicker.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			
			list = new GenCallList();
			
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

		<div id="box" class="lista-de-ligacoes">
			<span class="right breadcrumb"><br/><br/><a href="callsOut.php">Adicionar Ligações Discadas >></a>&nbsp;&nbsp;</span>
			<span class="right breadcrumb"><br/><br/><a href="callsIn.php">&nbsp;&nbsp;<< Adicionar Ligações Recebidas</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
			
			<h1>Buscar Ligações</h1>
			
			<div class="painel callout">
				<div class="callout orange rmv msg"><b>Para uma lista completa de ligações</b>, selecione "discada" ou "recebida" clique diretamente em "Ver Ligações." <b>Aviso: </b>Demorará alguns segundos.</div>
		
				<dl>
			
					<dt>Por data:</dt>
					<dd>
						<fieldset>
					      	<input type="text" class="format-d-m-y highlight-days-67 show-weeks search_param" id="select_por_emissao" name="dp-normal-1" value="" maxlength="10" disabled />
					    </fieldset>
					
						<fieldset class="last">
							<input type="checkbox" id="por_emissao_2" class="right" onclick="toggleSelect('select_por_emissao', 'select_por_emissao2')" />
							
					      	<input type="text" class="format-d-m-y highlight-days-67 show-weeks search_param" id="select_por_emissao2" name="dp-normal-2" value="" maxlength="10" disabled />
					    </fieldset>
					</dd>
					
					<dt>Feita ou Recebida?</dt>
					<dd>
						<label for="radio_calls">Discada</label></dt>
						<input type="radio" name="radio_calls" id="radio_feita" class="search_param" value="feita" />
						
						<label for="radio_calls">Recebida</label>
						<input type="radio" name="radio_calls" id="radio_recebida" class="search_param" value="recebida" checked="checked" />
					</dd>
					
					<dt>Ligação Para:</dt>
					<dd>
						<select id="para" class="search_param required">
							<option value="0"> -- Nome -- </option>
						</select>
					</dd>
				</dl>
		
				<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:none" />
				
				<div class="gen-button clear">
					<a href="#ver_visitas" id="gen_list" class="button"><span>Ver Ligações</span></a>
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
									<th>Para</th>
									<th>Mins</th>
									<th>Detalhes</th>
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