var GenTecidoList = Class.create(List, {
	listURL		: 	'inc/list_tecidos_gen_table.inc.php',
	tecidoURL 	:   'inc/tecidos_data.inc.php',
	
	initialize: function () {
		Ajax.Responders.register({
			onCreate: function(){ Element.show('spinner') },
			onComplete: function(){ Element.hide('spinner') }
		});
		
		Event.observe('tecido_busca', 'keypress', this._onSearchKeyHandler.bind(this));
		Event.observe('gen_list', 'click', this._onClickHandler.bind(this));
		
		$('tecido_busca').focus();
	},
	
	getSearchParams: function ($super) {
		var query_params = {};
		
		if($$('input.search_param').size() > 0) {
			$$('input.search_param').each(function (el) {
				if(el.type == "text") {
					thisPhrase = el.value;	
				}
				if(el.type == "radio" && el.checked) {
					thisRadio = el.id;
				}
			});
			query_params = {
				"phrase": thisPhrase, "radio": thisRadio
			};
		}
		
		return Object.toJSON(query_params);
	},
	
	genlist: function ($super, params) {
		$$("#dyn-rows tbody tr").each(function (elem) {
			$$("#dyn-rows tbody")[0].removeChild(elem);
		});
		
		var options = {
			method:'post',
			parameters: { "query_params": params },
			onSuccess: this._onMonitorSucess.bind(this)
		};
		
		new Ajax.Request(this.listURL, options);
	},
	
	_onMonitorSucess: function(transport) {
		var response = transport.responseText.evalJSON() || "no response text";
		// console.log(transport.responseText);
		this._onCreateTable(response);
		
	},
	
	_onCreateTable: function(response) {
		
		var cols = new Array();
		
		var fr = this.formatResult;
		var rmv = this._onRmv;
		var ky = this._onKeyHandler;
		obj = this;
		
		if(response.length < 1) {
			var row = new Element("tr");

			cols[0] = new Element("td", { 'colspan':6 }).update("Nenhum tecido encontrado, tente novamente.");
			cols[0].setStyle({ 'text-align':'center', 'color':'red' });
			row.insert(cols[0]);

			$$("#dyn-rows tbody")[0].insert(row);

		} else {
			
			response.each(function (el) {
			
				var row = new Element("tr", { "nbr": el["tecido_id"] });
			
				cols[0] = new Element("td", { 'class':'fornec-nome tecido-fab' }).update(new Element("input", { "value": el["fornecedor_nome"] + " (" + el["fornecedor_razao_social"] + ")", "title":el["fornecedor_nome"] + " (" + el["fornecedor_razao_social"] + ")", "class":"read-only", "readonly":"readonly" }));
				cols[1] = new Element("td", { 'class':'tecido-codigo update-item' }).update(new Element("input", { "value": el["tecido_nome_codigo"], "title":el["tecido_nome_codigo"], "maxlength":25 }));
				cols[2] = new Element("td", { 'class':'tecido-nome update-item' }).update(new Element("input", { "value":el["tecido_nome"], "title":el["tecido_nome"], "maxlength":65 }));
				cols[3] = new Element("td", { 'class':'cor-codigo update-item' }).update(new Element("input", { "value": el["tecido_cor_codigo"], "title":el["tecido_cor_codigo"], "maxlength":10 }));
				cols[4] = new Element("td", { 'class':'cor-nome update-item' }).update(new Element("input", { "value": el["tecido_cor"], "title":el["tecido_cor"], "maxlength":25 }));
				cols[5] = new Element("td").update('<a href="#rm" id="rm-'+el["tecido_id"]+'" class="rmv-item" onclick="rmv(' + el["tecido_id"] + ')"><img src="img/rmv_med.png" width="16" height="16" /></a>');
			
				cols.each(function (elem) { row.insert(elem); });
			
				$$("#dyn-rows tbody")[0].insert(row);	
			
			});
		
			$$('.update-item').each( function (elem) {
				Event.observe(elem, 'keypress', ky);
			});
		
		} // /else
			
	},
	
	_onSearchKeyHandler: function (event) {
		
		if(event.keyCode == Event.KEY_RETURN) {
		
			var str = $('tecido_busca').value;
			var size = str.length;
		
			if(size < 5) {
			
				if(typeof($$('.callout.orange.search.msg')[0]=="undefined") && $$('.callout.orange.search.msg').length=== 0) {
					
					var rmv_msg = new Element('span', { "class":'callout orange search msg' }).update("Sua busca deve conter pelo menos 5 letras ou numeros, tente novamente.");
					$('box').insert(rmv_msg);
					
					$$('.callout.orange.search.msg')[0].style.display="none";
				
				}
					
				new Effect.Appear($$('.callout.orange.search.msg')[0], {duration:0.5, from:0, to:1.0});	
				
			} else {
				
				if(typeof($$('.callout.orange.search.msg')[0])=="object") {

					$$('.callout.orange.search.msg')[0].style.display="none";

				}
				
				this.genlist(this.getSearchParams());
				Event.stop(event);
			
			}
		
		}
		
	},
	
	_onClickHandler: function (event) {	
		
		var str = $('tecido_busca').value;
		var size = str.length;
		
		if(size < 5) {
			
			if(typeof($$('.callout.orange.search.msg')[0]=="undefined") && $$('.callout.orange.search.msg').length === 0) {
				
				var rmv_msg = new Element('span', { "class":'callout orange search msg' }).update("Sua busca deve conter pelo menos 5 letras ou numeros, tente novamente.");
				$('box').insert(rmv_msg);

				$$('.callout.orange.search.msg')[0].style.display="none";
			
			}	
			
			new Effect.Appear($$('.callout.orange.search.msg')[0], {duration:0.5, from:0, to:1.0});
			
		} else {
			
			if(typeof($$('.callout.orange.search.msg')[0])=="object") {

				$$('.callout.orange.search.msg')[0].style.display="none";

			}
			
			this.genlist(this.getSearchParams());
			$('tecido_busca').focus();
		
		}
		
	},
	
	_onRmv: function (a) { // Remove a row item

		var options = {
			method:'post',
			parameters: { "id": a, "remove": 1 },
			onSuccess: this._onRmvSuccess.bind(this),
			onFailure: this._onRmvFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.tecidoURL, options);
	},
	
	_onRmvSuccess: function (transport) {
		var response = transport.responseText.evalJSON();
		// console.log(transport.responseText.evalJSON());
		
		var rmv_msg = new Element('span', { "class":'callout orange rmv msg' }).update("Você acaba de deletar a visita para o cliente " + response['tecido_nome'] + " do fabricante " + response['fornecedor_nome'] + ".");
		$('box').insert(rmv_msg);
	},
	
	_onRmvFailure: function () {
		alert("Não foi possível remover as informações.")
	},
	
	_onKeyHandler: function (event) { // Handles the updates on the text boxes.
		
		if(event.keyCode == Event.KEY_RETURN) {

			if(this.hasClassName('tecido-codigo')) {
				var params = {
					tipo	: "tecido_codigo",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			if(this.hasClassName('tecido-nome')) {
				var params = {
					tipo	: "tecido_nome",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			if(this.hasClassName('cor-codigo')) {
				var params = {
					tipo	: "cor_codigo",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			if(this.hasClassName('cor-nome')) {
				var params = {
					tipo	: "cor_nome",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			Event.stop(event);
		}
		
		obj.updateItem(params);
	},

	_onUpdateParams: function () {
		this.params.tecido_codigo = $('tecido-codigo').value;
		this.params.tecido_nome = $('tecido-nome').value;
		this.params.cor_codigo = $('cor-codigo').value;
		this.params.cor_nome = $('cor-nome').value;
		this.params.tecido_fornec = $('fornec-nome').options[$('fornec-nome').options.selectedIndex].value;

		return this.params;
	},
	
	updateItem: function (params) {
		var stringfied  = Object.toJSON(params);

		var options = {
			method:'post',
			parameters: { "query_params": stringfied, "update": 1 },
			onSuccess: this._onUpSuccess.bind(this),
			onFailure: this._onUpFailure.bind(this)
		};

		var data = new Ajax.Request(this.tecidoURL, options);
	},

	_onUpSuccess: function (transport) {

		var response = transport.responseText.evalJSON();

		$$('tr').each(function (el) {
			if(el.getAttribute('nbr') == response['item']) {
				el.setStyle({ backgroundColor:"#babaca" });
				Effect.Pulsate(el, { pulses: 1, duration: 2 });
			}
		});

		var rmv_msg = new Element('span', { "class":'callout orange rmv msg', "id":"aviso" }).update("Você acaba de modificar o campo \"" + response['tipo'] + ".\"");
		if(!$('aviso')) {
			$('box').insert(rmv_msg);
		} else {
			$('aviso').update("Você acaba de modificar o campo \"" + response['tipo'] + ".\"");
		}

	},

	_onUpFailure: function () {
		alert("Não foi possível atualizar as informações.")
	}
	
});