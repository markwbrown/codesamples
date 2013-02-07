var SearchList = Class.create(List, {
	listURL		: 	'inc/search.inc.php',
	
	initialize: function () {
		Ajax.Responders.register({
			onCreate: function(){ Element.show('spinner') },
			onComplete: function(){ Element.hide('spinner') }
		});
		
		Event.observe('cliente_busca', 'keypress', this._onKeyHandler.bind(this));
		Event.observe('gen_list', 'click', this._onClickHandler.bind(this));
		
		$('cliente_busca').focus();
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
		$$("#client-list tbody tr").each(function (elem) {
			$$("#client-list tbody")[0].removeChild(elem);
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

		if(response.length < 1) {
			var row = new Element("tr");

			cols[0] = new Element("td", { 'colspan':4 }).update("Nenhum cliente encontrado, tente novamente.");
			cols[0].setStyle({ 'text-align':'center', 'color':'red' });
			row.insert(cols[0]);
			
			$$("#client-list tbody")[0].insert(row);
			
		} else {
			response.each(function (el) {
				var row = new Element("tr");
				cols[0] = new Element("td", { 'class':'razao' });
				cols[1] = new Element("td", { 'class':'cnpj' });
				cols[2] = new Element("td").insert(new Element('a', { "href":"editClient.php?id=" + el['cliente_id'] }).update("Editar"));
				cols[3] = new Element("td").insert(new Element('a', { "href":"cliente_detail.php?id=" + el['cliente_id'] }).update("Vizualizar"));
				$H(el).each(function (pair){
					switch (pair.key) {
						case "cliente_cnpj": cols[1].update(pair.value); break;
						case "cliente_razao_social": cols[0].update(pair.value); break;
						default : ; break;
					}
				});
				cols.each(function (elem) { row.insert(elem); });
				$$("#client-list tbody")[0].insert(row);
			});			
		}
	},
	
	_onKeyHandler: function (event) {
		if(event.keyCode == Event.KEY_RETURN) {
			this.genlist(this.getSearchParams());
			Event.stop(event);
		}
	},
	
	_onClickHandler: function (event) {
		this.genlist(this.getSearchParams());
		$('cliente_busca').focus();
	}
	
});