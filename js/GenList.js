var List = Class.create({
	initialize: function () {
		
		Ajax.Responders.register({
			onCreate: function(){ Element.show('spinner') },
			onComplete: function(){ Element.hide('spinner') }
		});
		
	},
	toggle: function (id, id2) {
		if($(id).hasAttribute("disabled")) {
			$(id).removeAttribute("disabled");
		} else {
			$(id).setAttribute("disabled", "disabled");
		}
		
		if(typeof(id2!=="undefined") && id2!=null) {
			if($(id2).hasAttribute("disabled")) {
				$(id2).removeAttribute("disabled");
			} else {
				$(id2).setAttribute("disabled", "disabled");
			}
		}
	},
	getSearchParams: function () {
		var query_params = {};
		
		if($$('select.search_param').size() > 0) {
			$$('select.search_param').each(function (el) {
				if(!el.hasAttribute('disabled')) {
					thisId = el.id;
					thisParam = el.options[el.options.selectedIndex].value;

					query_params[thisId] = {
						"param_value": thisParam
					};
				}
			});
		}
		
		if($$('input.search_param').size() > 0) {
			
			if(!$$('input.search_param')[0].hasAttribute('disabled') && !$$('input.search_param')[1].hasAttribute('disabled')) {
				query_params["dates"] = {
					"param_value": $$('input.search_param')[0].value, "param_value2": $$('input.search_param')[1].value
				};
			}
		}
		
		return Object.toJSON(query_params);
	},
	genlist: function (params) {
		$$("#home-list tbody tr").each(function (elem) {
			$$("#home-list tbody")[0].removeChild(elem);
		});
		
		var data = new Ajax.Request('inc/list_gen_table.inc.php', { 
			method:'post',
			parameters: { "query_params": params },
			onSuccess: function(transport) {
				var response = transport.responseText.evalJSON() || "no response text";
				//console.log(response);
				
				var cols = new Array();
				
				if(response.length < 1) {
					var row = new Element("tr");

					cols[0] = new Element("td", { 'colspan':4 }).update("Nenhum pedido encontrado, tente novamente.");
					cols[0].setStyle({ 'text-align':'center', 'color':'red' });
					row.insert(cols[0]);
					$$("#home-list tbody")[0].insert(row);
				} else {
					response.each(function (el) {
						var row = new Element("tr");

						cols[0] = new Element("td", { 'class':'razao' });
						cols[1] = new Element("td", { 'class':'autor' });
						cols[2] = new Element("td", { 'class':'cnpj' });
						cols[3] = new Element("td", { 'class':'emissao' });
						cols[4] = new Element("td", { 'class':'num' });
						cols[5] = new Element("td");

						$H(el).each(function (pair){
							switch (pair.key) {
								case "cnpj": cols[2].update(pair.value); break;
								case "data_emissao": cols[3].update(pair.value); break;
								case "autor": cols[1].update(pair.value); break;
								case "razao_social": cols[0].update(pair.value); break;
								case "id": {
										cols[4].update(pair.value);
										cols[5].insert(new Element('a', { "href":"pedidoEdit.php?n=" + pair.value, "target":"_blank" }).update("Visualizar")); 
								} break;
								default : console.log("Nenhum deles.") ; break;
							}
						});
						cols.each(function (elem) { row.insert(elem); });
						$$("#home-list tbody")[0].insert(row);
					});
				} // else
			},
			onFailure: function() { alert('Ocorreu um probleminha...'); return false; }
		});
	},
	fillSelect: function (type, id) { // Valid types: dates, pedido_entrega, cliente_razao, autor_nome
		var data = new Ajax.Request('inc/list_gen_select.inc.php', { 
			method:'get',
			parameters: { 'type': type },
			onSuccess: function(transport) {
				var response = transport.responseText.evalJSON() || "no response text";
				
				$H(response).each(function (pair) {
					var newLabel = "", 
					i = 0;
					
					$H(pair.value).each(function (thispair) {
						if(i>0) { 
							newLabel += " ";
						}
						
						newLabel += thispair.value;
						i++;
					});
					$(id).insert(new Element('option', {value: pair.key}).update(newLabel));
				});
			},
			onFailure: function() { alert('Ocorreu um probleminha...'); return false; }
		});
	}
});