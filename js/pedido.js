var Pedido = Class.create({
	
	initialize: function (cl_num) {

		this.rowCount = 0;
		this.orderExists = false;
		this.sizes = {};
		this.clientJsonInfo = {};
		this.userLoggedin = user_id; // Set inside of header_order.inc.php
		this.setClientId(cl_num);
		
		this.order_id = 0; // Will be changed later
		
		Event.observe('comecar', 'click', this.loadOrderInitialInfo.bind(this));
		
		// this.orderId = $$('.pedido-num span')[0].innerHtml || null; // Existing order?
		//this.order_num = order_num || null;
		
		this.beginOrder(this.getClientId());
		
	},
	
	beginOrder: function (id) { // When order first loads
		
		this.loadCustomerInfo(id);
		this.updateValues();
	
	},
	
	loadOrderInitialInfo: function () {
	
		this.hideElemById('adi-pecas', 0.2);
		this.insertRow(this);
		this.genFooter(this);
		this.insertFooter(this);
		this.showElemById('save-button', 1);
		
		this.setInitialListeners(this);
		this.setAdditionalListeners(this, this.getRowCount());
		
	},
	
	hideElemById: function (elem_id, dur) {
	
		Effect.Fade(elem_id, {duration: dur});
	
	},
	
	showElemById: function (elem_id, dur) {
	
		Effect.Appear(elem_id, {duration: dur});
	
	},
	
	updateValues: function () { // Updates field values
		
		var fields = 	["razao", "nome", "cnpj", "inscricao", "endereco", "cidade", "estado", "tel", "fax", "comprador", 
						"email", "fin", "fintel", "finemail", "obs", "desconto", "entrega", "prazo", "pagamento", "ordem", "pe",
						"status_p", "status_f", "status_d"];
					
		for (var i=0; i < fields.length; i++) {
			
			var n = fields[i];
			
			if($(n) !== null) {
				
				if(n == "obs") {
				
					this[n] = $(n).value.replace(/\'/g, '\\"');
				
				} else if(n == "pe"){
					
					this[n] = $(n).checked; // true or false
					
				} else {
					
					this[n] = $(n).value || null;
				}
			}
		};			

	},
	
	loadCustomerInfo: function (id) { // Top of the page info, such as address, telephone, etc.. 
		
		var el = this;
		
		var data = new Ajax.Request('inc/pedido_info_cliente.inc.php', { 
			method:'post',
			parameters:{"id":id},
			onSuccess: el.onCustomerSucess.bind(this),
			onFailure: function() { alert('Ocorreu um probleminha...'); return false; }
		});
		
	},
	
	onCustomerSucess: function (transport) { // Handler, customer info was returned
	
		var response = transport.responseText.evalJSON() || "no response text";
		
		// console.log(response);
		
		response = response.cliente;
		
		$('razao').value = response['Razao Social'];
		$('nome').value = response['Nome'] || "Indisponível";
		$('endereco').value = response['Rua'];
		
		if(response['Quadra'] !== null) {
			$('endereco').value += ", Qd. " + response['Quadra'];
		}
		
		$('endereco').value += ", Cep: " + response['Cep'];
		$('numero').value = response['Numero'];
		$('bairro').value = response['Bairro'];
		$('cnpj').value = response['CNPJ'];
		$('inscricao').value = response['Inscricao Estadual'];
		$('cidade').value = response['Cidade'];
		$('estado').value = this.getStateName(response['Estado']);
		
		$('comprador').value = response['Comprador'];
		$('email').value = response['Comprador Email'] || "";
		$('tel').value = response['Comprador Telefone'] || "";
		
		// if(response['Comprador Telefone'] && typeof(response['Comprador Telefone']) != "undefined") {
			
		//	$('tel').value += ", " + response['Telefone'];
		// } else {
		
			$('tel').value = response['Telefone'];
		//}
		
		$('fin').value = response.fin || "";
		$('finemail').value = response.fin_email || "";
		$('fintel').value = response.fin_tel || "";
		
	},
	
	insertRow : function (el) {
		
		this.increaseRowCount(1); // increase it by one
		var count = this.getRowCount();
		
		var rowWrapper = new Element('table', { "id": "row"+count, "class":"parent-item", "isnew":"yes", "num":0 });
		
		Element.insert(rowWrapper, { "bottom": el.getControlsHeader() } );
		Element.insert(rowWrapper, { "bottom": el.getFirstHeader() } );
		Element.insert(rowWrapper, { "bottom": el.getLowerFirstHeaderSizes() } );
		Element.insert(rowWrapper, { "bottom": el.getFirstRow() } );
		Element.insert(rowWrapper, { "bottom": el.getSecondHeader() } );
		Element.insert(rowWrapper, { "bottom": el.getSecondRow() } );
		
		Element.insert("dyn-rows", { "bottom": rowWrapper } );
		
		el.formatRowValues(count);
		
		el.loadSavedClothingSelect("peca-choose-row"+count);
		el.loadSupplierSelect('fabricante-select-row'+count, null, count); // sets the event listener also to load textile select info
		
		// Now lets load the Textile select
		Event.observe('fabricante-select-row'+count, 'change', el.fabricanteSelHandler.bind(el, count));
		
		this.showElemById("dyn-rows", 1); // Fade-in, 1 second
		
	},
	
	fabricanteSelHandler: function (count) {
		
		var el = this;
		
		var supplier_select = "fabricante-select-row"+count;
		var supplier_id = $(supplier_select).options[$(supplier_select).selectedIndex].value;
		var element_name = "tecido-select-row"+count;

		// fillSelect: function (type, element_id, this_client_id, item_db_id) { 
		this.fillSelect('fornec_tecidos', element_name, supplier_id);
		
		this.removeSavedCoating("row"+count); // Set header back to green
		
		// Now lets load the Textile select
		Event.observe('tecido-select-row'+count, 'change', this.removeSavedCoating.bind(el, "row"+count));
		
		
	
	},
	
	insertFooter : function (el) {
		
		Element.insert($$(".content")[0], { "bottom": el.getFooter() });
		
		this.showElemById('order-footer', 1);
		
	},
	
	saveOrderState: function (el) { // User hit the "Save" button
		
		if(this._onValidate() != false) {
			
			el.saveOrderInfo(el.getClientId());
		}
		
	},
	
	saveOrderInfo: function (id) { // Para salvar informações do pedido
		
		var pecaId = "",
			count = 0,
			el = this;
			
		this.pecas = {}; // starts out empty

	 	$$(".parent-item").each(function(elem) {
			
			pecaId = elem.id;
			var num = elem.getAttribute("num") || 0;
			var isnew = elem.getAttribute("isnew") || "yes";
			var wasremoved = elem.getAttribute("wasremoved") || "no";

			if ($(pecaId+"-size-36") && $(pecaId+"-size-36").value != "") var size_36 = $(pecaId+"-size-36").value; else var size_36 = 0;
			if ($(pecaId+"-size-38") && $(pecaId+"-size-38").value != "") var size_38 = $(pecaId+"-size-38").value; else var size_38 = 0;
			if ($(pecaId+"-size-40") && $(pecaId+"-size-40").value != "") var size_40 = $(pecaId+"-size-40").value; else var size_40 = 0;
			if ($(pecaId+"-size-42") && $(pecaId+"-size-42").value != "") var size_42 = $(pecaId+"-size-42").value; else var size_42 = 0;
			if ($(pecaId+"-size-44") && $(pecaId+"-size-44").value != "") var size_44 = $(pecaId+"-size-44").value; else var size_44 = 0;
			if ($(pecaId+"-size-46") && $(pecaId+"-size-46").value != "") var size_46 = $(pecaId+"-size-46").value; else var size_46 = 0;
			if ($(pecaId+"-size-48") && $(pecaId+"-size-48").value != "") var size_48 = $(pecaId+"-size-48").value; else var size_48 = 0;
			if ($(pecaId+"-size-50") && $(pecaId+"-size-50").value != "") var size_50 = $(pecaId+"-size-50").value; else var size_50 = 0;
			if ($(pecaId+"-size-52") && $(pecaId+"-size-52").value != "") var size_52 = $(pecaId+"-size-52").value; else var size_52 = 0;
			if ($(pecaId+"-size-54") && $(pecaId+"-size-54").value != "") var size_54 = $(pecaId+"-size-54").value; else var size_54 = 0;
			if ($(pecaId+"-size-56") && $(pecaId+"-size-56").value != "") var size_56 = $(pecaId+"-size-56").value; else var size_56 = 0;
			
			if ($$("#"+pecaId+"-v1 input")[0] && $$("#"+pecaId+"-v1 input")[0].value != "") var v1 = $$("#"+pecaId+"-v1 input")[0].value; else var v1 = "-";
			if ($$("#"+pecaId+"-v2 input")[0] && $$("#"+pecaId+"-v2 input")[0].value != "") var v2 = $$("#"+pecaId+"-v2 input")[0].value; else var v2 = "-";
			if ($(pecaId+"-size-v1") && $(pecaId+"-size-v1").value != "") var size_v1 = $(pecaId+"-size-v1").value; else var size_v1 = 0;
			if ($(pecaId+"-size-v2") && $(pecaId+"-size-v2").value != "") var size_v2 = $(pecaId+"-size-v2").value; else var size_v2 = 0;
			
			var espec = $$("#"+pecaId+'-lower .espec textarea')[0].value;
			espec = espec.replace(/\"/g, '\\"');
			//espec = (espec + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
			
			el.pecas[pecaId] = {

				"fabricante": $("fabricante-select-"+pecaId).options[$("fabricante-select-"+pecaId).selectedIndex].value,
				"tecido": $("tecido-select-"+pecaId).options[$("tecido-select-"+pecaId).selectedIndex].value,
				"espec": espec,
				"valor": $(pecaId+'-unit-price').value,
				"isnew": isnew,
				"wasremoved": wasremoved,
				"num": num,
				"tam_36" : size_36,
				"tam_38" : size_38,
				"tam_40" : size_40,
				"tam_42" : size_42,
				"tam_44" : size_44,
				"tam_46" : size_46,
				"tam_48" : size_48,
				"tam_50" : size_50,
				"tam_52" : size_52,
				"tam_54" : size_54,
				"tam_56" : size_56,
				"tam_v1" : size_v1,
				"tam_v2" : size_v2,
				"tam_cap_v1" : v1,
				"tam_cap_v2" : v2	
			};

		});

		this.stringfied = Object.toJSON(this.pecas);
		this.updateValues(); // Updates variables to the values in the fields
		this.saveDataCall(id, el); // Make Ajax call to safe the info
			
	},
	
	saveDataCall: function (id, el) { // order id
		
		if(typeof(this.order_id)=="undefined" || this.order_id=="") this.order_id = 0;
		
		var data = new Ajax.Request('inc/pedido_info_update.inc.php', {
			
			method:'post',
			parameters: { 
				"id":id,
				"cnpj":this.cnpj,
				"order_id":this.order_id,
				"user_loggedin":this.userLoggedin,
				"local_de_entrega":this.entrega,
				"prazo_de_entrega":this.prazo,
				"pagamento":this.pagamento,//.replace(/\'/g, '\\"'), Put it in later on
				"comprador":this.comprador,
				"telefone":this.tel,
				"email":this.email,
				"fin":this.fin,
				"fin_tel":this.fintel,
				"fin_email":this.finemail,
				"status_f":this.status_f,
				"status_p":this.status_p,
				"status_d":this.status_d,
				"obs":this.obs,
				"desconto":this.desconto,
				"ordem":this.ordem,
				"pe":this.pe,
				"pecas": this.stringfied
			},
			onSuccess: this.onSaveSucess.bind(this),
			onFailure: function() { alert('Ocorreu um probleminha...'); return false; }

		});
		
	},
	
	onSaveSucess: function(transport) {
		//console.log(transport.responseText);
		var response = transport.responseText.evalJSON() || "no response text";
		
		if(this.orderExists===false) {

			this.order_id = response.pedido_id;
			$$('.pedido-num span')[0].innerHTML = "Nº " + this.order_id;
			
			Effect.Appear($$('.data-wrapper')[0], { duration:1.5 });
			$('data').innerHTML = response.pedido_data_criacao;
			Effect.Appear($$('.autor-wrapper')[0], { duration:1.5 });
			$('autor').innerHTML = response.pedido_autor;
			
			this.orderExists = true;
			
			$$("#salvar span")[0].update("Salvar Modificações");
			$$("h1")[0].update("Modificar Pedido");
			
			alert("Novo pedido criado com sucesso.");				
		
		} else {
			
			alert("Pedido atualizado com sucesso.");
		}
		
		var c = 1;
		
		$$(".parent-item").each(function(elem) {
			
			if(elem.getAttribute("num")==0 || elem.getAttribute("num")=="0") {
				elem.setAttribute("num", response["pecas"]['row'+c]["num"]);
			}		
			
			if(elem.getAttribute("isnew") == "yes") { 
				elem.setAttribute("isnew", "no"); 
			}	
			
			if(elem.getAttribute("wasremoved") == "yes") { 
				Element.remove(elem);
			}
			
			c++;
		});
		
		$$(".undo-rmv-dialog").each(function (el) {
			
			Element.remove(el);
		});
		
		// Update totals here
		this.updateAndFormatAll(this);
	},
	
	getControlsHeader: function () {
		
		var row = this.genControlsHeader();
		return row;
	},
	
	getFirstHeader: function () {	
		
		var row = this.genFirstHeader();
		return row;
	},
	
	getLowerFirstHeaderSizes: function () {	
		
		var row = this.genLowerFirstHeaderSizes();
		return row;
	},
	
	getFirstRow: function (tabIndexStart) {
		
		if(!tabIndexStart)
			tabIndexStart = 30; // O index do ultimo item na primeira linha
			
		var row = this.genFirstRow(tabIndexStart);
		return row;
	},
	
	getSecondHeader: function () {
		
		var row = this.genSecondHeader();
		return row;
	},
	
	getSecondRow: function (tabIndexStart) {
		
		if(!tabIndexStart)
			tabIndexStart = 30;
		
		var row = this.genSecondRow(tabIndexStart);
		
		return row;
	},
	
	getFooter: function () {
		
		var footer = this.genFooter(this);
		
		return footer;
	},
	
	
	getClientId: function () {
		
		return this.clientId;
		
	},
	
	setClientId: function (id) {
		
		this.clientId = id;
		
		return this.clientId;
		
	},
	
	setInitialListeners: function (el) {
		
		Event.observe('addRow', 'click', function (event){ el.insertRow(el); el.setAdditionalListeners(el, el.getRowCount()); });
		Event.observe('desconto', 'change', function (event){ el.calcMainPrice(el.getMainPrice(el, el.getRowCount()), el.getDiscount()); el.formatDiscount(); });
		Event.observe('salvar', 'click', function (event){ el.saveOrderState(el); });
		
	},
	
	setAdditionalListeners: function (el, count) {
		
		var sizes = $$('#row'+count+' .update-values');
		sizes.each(function (e){
			Event.observe(e.id, 'change', function (event){ el.updateTotals(count); el.formatRowValues(count); el.updateAndFormatAll(el); });
		});
		Event.observe('row'+count+'-unit-price', 'change', function (event){ el.updateTotals(count); el.formatRowValues(count); el.updateAndFormatAll(el); el.removeSavedCoating("row"+count); });
		Event.observe('espec-row'+count, 'change', function (event){ el.removeSavedCoating("row"+count); });
		
		Event.observe('row'+count+'-remover', 'click', function (event){ el.removeSingleItem('row'+count); });
		Event.observe('row'+count+'-salvar', 'click', function (event){ el.createNameInput('row'+count, el); });
		
		Event.observe("peca-choose-row"+el.getRowCount(), 'change', function (event){ el.populateSingleItem('row'+count, el); });
		Event.observe("salvar-peca-nome-"+el.getRowCount(), 'click', function (event){ el.saveSingleItem(this, el); });
		
	},
	
	updateAndFormatAll: function (el) {
		
		for(i=1; i<=el.getRowCount(); i++) {
			
			el.updateTotals(i);
			el.formatRowValues(i);
			el.calcMainPrice(el.getMainPrice(el, i), el.getDiscount());
		}
		
		el.formatDiscount();
		
		// Mark all as old items
		$$('.row-item').each(function (elem) {
			elem.setAttribute('isnew', "no");
		});
	},
	
	genControlsHeader: function () {
		
		var row = new Element("tr", { "id":"row"+this.getRowCount()+"-controls", 'class':'controls-header row-'+this.getRowCount()+'-item' });
		var col = new Element("th", { 'colspan':18 });
		
		var controls_left = new Element("div", { 'class':"controls left" });
		var name_wrapper = new Element("div", { 'class':"name-wrapper left" }).setStyle({ "display":"none" });
		var controls_right = new Element("div", { 'class':"controls right" });
		var controls_label = new Element("label", { 'for':"peca-choose-row"+this.getRowCount() }).update('Peça existente?');
		var controls_use_btn = new Element("input", { "id":"usar-peca-nome-"+this.getRowCount(), "class":"peca-usar-nome right", "type":"button", "value":"Usar" }).setStyle({"display":"none"});
		var controls_first_option = new Element("option").update('-- Escolha uma peça --');
		var controls_name = new Element("input", { 'id':"row"+this.getRowCount()+'-peca-nome', 'class':"peca-nome left" });
		var controls_name_label = new Element("p", { "class":"shake" }).update("<-- Digite um nome e aperte em 'Salvar'.");
		var controls_name_btn = new Element("input", { "id":"salvar-peca-nome-"+this.getRowCount(), "class":"peca-salvar-nome left", "type":"button", "value":"Salvar" });
		var controls_select = new Element("select", { 'id':"peca-choose-row"+this.getRowCount() }).insert(controls_first_option);
		var controls_ul = new Element("ul");
		var controls_li1 = new Element("li").insert(new Element("a", {'href':'#adicionar-foto', 'id':"row"+this.getRowCount()+"-fotos"}).update("Fotos"));
		var controls_li2 = new Element("li").insert(new Element("a", {'href':'#remover-peca', 'id':"row"+this.getRowCount()+"-remover"}).update("Remover desse Pedido"));
		var controls_li3 = new Element("li", { "id":"salvar-peca-row"+this.getRowCount() }).insert(new Element("a", {'href':'#salvar-peca', 'id':"row"+this.getRowCount()+"-salvar"}).update("Salvar para Uso Futuro"));
		var controls_li3_saved = new Element("li", { "id":"salva-label-row"+this.getRowCount() }).insert(new Element("span", {'name':'Peça Salva'}).update("SALVA"));
		controls_li3_saved.setStyle({ "display":"none" });
		
		controls_left.insert(controls_label);
		controls_left.insert(controls_use_btn);
		controls_left.insert(controls_select);
		
		name_wrapper.insert(controls_name);
		name_wrapper.insert(controls_name_label);
		name_wrapper.insert(controls_name_btn);
		
		controls_ul.insert(controls_li1);
		controls_ul.insert(controls_li2);
		controls_ul.insert(controls_li3);
		controls_ul.insert(controls_li3_saved);
		
		controls_right.insert(controls_ul);
		
		col.insert(controls_left);
		col.insert(name_wrapper);
		col.insert(controls_right);
		
		row.insert(col);
		
		return row;
		
	},
	
	genFirstHeader: function () { //should be 29
		
		var cols = new Array();
		var row = new Element("tr", { 'class':'second-header row-'+this.getRowCount()+'-item' });
		
		cols[0] = new Element("th", { 'class':'first item', 'rowspan':'2' }).setStyle({ 'width':'30px' }).update("№");
		cols[1] = new Element("th", { 'class':'qtd', 'rowspan':'2' }).setStyle({ 'width':'40px' }).update("QTD");
		cols[2] = new Element("th", { 'class':'fabricante', 'rowspan':'2' }).setStyle({ 'width':'200px' }).update("FABRICANTE");
		cols[3] = new Element("th", { 'class':'first' }).update("36");
		
		var size_start = 36;
		var increment = 2;
		for(var i=4; i<=13; i++) {
			cols[i] = new Element("th", { 'class':'first' }).update(size_start+increment);
			increment+=2;
		}
		
		cols[14] = new Element("th", { 'id':'row'+this.getRowCount()+"-v1" });
		cols[14].insert(new Element("input", { "type":"text", "tabindex":30 }).setStyle({ "width":"30px" }));
		cols[15] = new Element("th", { 'id':'row'+this.getRowCount()+"-v2" });
		cols[15].insert(new Element("input", { "type":"text", "tabindex":30 }).setStyle({ "width":"30px" }));
		cols[16] = new Element("th", { 'colspan':'2', "class":"last" }).update("PREÇOS");

		for(var i=0; i<cols.length; i++) {
			row.insert(cols[i]);
		}
		
		return row;
	},
	
	genLowerFirstHeaderSizes: function () {
		
		var row = new Element("tr", { 'class':'bottom row-'+this.getRowCount()+'-item' });
		var cols = new Array();
		
		cols[0] = new Element("td", {'class':'first'}).update("P");
		cols[1] = new Element("td").update("M");
		cols[2] = new Element("td").update("G");
		
		var increment = 3;
		for(var i=0; i<=7; i++) {
			cols[i+increment] = new Element("td").update(i+1);
		}
		
		cols[12] = new Element("td").update("-");
		cols[13] = new Element("td").update("-");
		cols[14] = new Element("td").update("UNIT.");
		cols[15] = new Element("td", { "class":"last" }).update("TOTAL");
		
		for(var i=0; i<cols.length; i++) {
			row.insert(cols[i]);
		}
		
		return row;
		
	},
	
	genFirstRow: function (tabIndexStart) {
		
		var numOfSizes = 15;
		var loopInc = 3;
		var cols = new Array();
		var row = new Element("tr", {"id": "top-row"+this.rowCount, "class":"row-item row-"+this.getRowCount()+"-item", "isnew":"yes", "num":0});
		
		cols[0] = new Element("td", {'class':'first item', 'rowspan': '3' });
		cols[1] = new Element("td", {'class':'qtd', 'rowspan': '3' });
		cols[2] = new Element("td", {'class':'fabricante' });

		for(var i=0; i<numOfSizes; i++) {
			var tabindexCurrent = tabIndexStart+i+loopInc;
			cols[i+loopInc] = new Element("td");
		}

		cols[16] = new Element("td", {'class':'unit', 'rowspan': '3' });
		cols[17] = new Element("td", {'class':'total last', 'rowspan': '3' });

		for(var i=0; i<cols.length; i++) {
			if (i==0)
				var inp = new Element("input", {'type':'text', 'class':'read-only', 'value':this.rowCount, 'readonly':'readonly' });
			else if (i==1)
				var inp = new Element("input", {'type':'text', 'id':'row'+this.rowCount+'-qty', 'class':'read-only', 'readonly':'readonly', 'value':0 });
			else if(i==2)
				var inp = new Element("select", { "id":"fabricante-select-row"+this.getRowCount(), "class":"fabricante-select validate-selection" }).insert(new Element("option").update("-- Fabric. --"));
			else if (i>2 && i<=15) {
				var size;
				switch(i) {
					case 3: { size=36; break; }
					case 4: { size=38; break; }
					case 5: { size=40; break; }
					case 6: { size=42; break; }
					case 7: { size=44; break; }
					case 8: { size=46; break; }
					case 9: { size=48; break; }
					case 10: { size=50; break; }
					case 11: { size=52; break; }
					case 12: { size=54; break; }
					case 13: { size=56; break; }
					case 14: { size="v1"; break; }
					case 15: { size="v2"; break; }
					default: { console.log("Switch statement problem."); break; }
				}
				var inp = new Element("input", {'type':'text', 'id':'row'+this.rowCount+'-size-'+size, 'class':'update-values', 'tabindex': tabIndexStart });
			}
			else if (i==16)
				var inp = new Element("input", {'type':'text', 'id':'row'+this.rowCount+'-unit-price', 'tabindex': tabIndexStart, 'value':0 });
				
			else
				var inp = new Element("input", {'type':'text', 'id':'row'+this.rowCount+'-total', 'class':'read-only', 'tabindex': tabIndexStart, "value":0, 'readonly':'readonly' });
			
			cols[i].insert(inp);
			row.insert(cols[i]);	
		}
		
		return row;
	},
	
	genSecondHeader: function () { //should be 29
		
		var cols = new Array();
		var row = new Element("tr", { 'class':'second-header row-'+this.getRowCount()+'-item' });
		
		cols[0] = new Element("th", {'class':'ncor', 'class':'first', 'colspan':'4' }).update("TECIDO");
		cols[1] = new Element("th", {'class':'spec', 'colspan':'10' }).update("ESPECIFICAÇÃO");
		
		row.insert(cols[0]);
		row.insert(cols[1]);
		
		return row;
	},
	
	genSecondRow: function (tabIndexStart) {
		
		var cols = new Array();
		var row = new Element("tr", {"id": "row"+this.rowCount+"-lower", "class":"row-item row-"+this.getRowCount()+"-item", "isnew":"yes", "num":0});
			
		cols[0] = new Element("td", {'class':'tecido', 'colspan':'4' });
		cols[1] = new Element("td", {'class':'espec', 'colspan':'10' });
		
		var inp = new Element("select", { "id":"tecido-select-row"+this.getRowCount(), "class":"tecido-select validate-selection" }).insert(new Element("option").update("-- Selecione primeiro um fabricante --"));
		var inp2 = new Element("textarea", {'id':'espec-row'+this.getRowCount(), 'name':'espec', 'tabindex': tabIndexStart });
			
		cols[0].insert(inp);
		cols[1].insert(inp2);
		
		row.insert(cols[0]);
		row.insert(cols[1]);
		
		return row;
	},
	
	genFooter: function () {
	
		var table = new Element("table", { 'id':'order-footer' }).setStyle({ "display":"none" });
		var row = [];
		
		row[0] = new Element("tr");
		var cols = new Array();

		cols[0] = new Element("td", {'class':'first add-button' }).insert(new Element('a', { "href": "#add", "id": "addRow" }).insert(new Element("img", { "src":"img/add.png", "width":"22", "height":"22", "border":"0" })));
		cols[1] = new Element("td", { "rowspan":"3", "class":"obs" }).insert(new Element("textarea", { "tabindex":"10000", "id":"obs", "name":"obs", 'onfocus':"if(this.value=='Escreva suas observações aqui.') this.value=''", "onblur":"if(this.value=='') this.value='Escreva suas observações aqui.'" }).update("Escreva suas observações aqui."));
		cols[2] = new Element("td", { "rowspan":"3", "class":"grand-total last" });

		var dl = new Element("dl");
		
		var dt = {
			
			"class": ["null", "desc", "null"], 
			"content": ["V. Merch", "Desc.","TOTAL"]
		};
		
		var dd = {
			
			"class": ["merch", "desc", "main-total"], 
			"content": ["R$ <span id='merch' class='read-only'>0.00</span>", "R$ <input id='desconto' type='text' value=0.00></input>", "R$ <span id='main-total' class='read-only'>0.00</span>"]
		};
		
		for(var i=0; i < dt.content.length; i++) {
			
			if(dt.class[i] !== null) {
			
				var temp_dt = new Element("dt", { "class": dt.class[i] }).update(dt.content[i]);
			
			} else {
				
				var temp_dt = new Element("dt").update(dt.content[i]);
			}
				
			var temp_dd = new Element("dd", { "class": dd.class[i] }).update(dd.content[i]);
			
			dl.insert(temp_dt); // insert the dt into the column
			dl.insert(temp_dd); // insert the dt into the column
		
		};
		
		cols[2].insert(dl);
		
		for(var i = 0; i < cols.length; i++) {
			row[0].insert(cols[i]);
		};
		
		row[1] = new Element("tr").insert(new Element('th', { "class":"first has-border-top" }).insert(new Element("p").update("Quant. Total<br/>de Pecas")));		
		row[2] = new Element("tr").insert(new Element('td', { "class":"total-pecas first" }).insert(new Element("div", { "id":"qty-total", "class":"qty-total read-only" }).update("0")));
		
		for (var i=0; i < row.length; i++) {
			table.insert(row[i]);
		};
		
		return table;
		
	},
	
	deleteRow: function () {
		// Ajax call to delete the item from the database
		// Remove it from the DOM
	},
	
	setRowQty: function (count, value) {
		
		$('row'+count+'-qty').value = value;
	},
	
	getRowQty: function (count) {
		
		return $('row'+count+'-qty').value;
	},
	
	formatRowValues: function (count) {
		
		// Remove leading zeros and format size inputs as integer type
		var sizes = $$('#row'+count+' .update-values');
		
		for (var i=0; i < sizes.length; i++) {
			
			if(sizes[i].value!=="") {
				
				sizes[i].value = parseInt(sizes[i].value);
			}
		};
		
		if($('row'+count+'-unit-price').value!="") {
			var value = parseFloat($('row'+count+'-unit-price').value);
			var fixed = value.toFixed(2);
			$('row'+count+'-unit-price').value = fixed;
		}
		if($('row'+count+'-total').value!="") {
			var value = parseFloat($('row'+count+'-total').value);
			var fixed = value.toFixed(2);
			$('row'+count+'-total').value = fixed;
		}
	},
	
	setUnitPrice: function (count, value) {
		
		$('row'+count+'-unit-price').value = value;
	},
	
	getUnitPrice: function (count) {
		
		return $('row'+count+'-unit-price').value;
	},
	
	updateTotals: function (row) {
		
		var currentRowQty = this.updateCurrentRowQty(row);
		var totalQty = this.updateMainQty();
		var currentRowTotal = this.setRowTotal(row, this.getRowTotal(row));
		this.formatRowValues(row);
		
		this.calcMainPrice(this.getMainPrice(this, row), this.getDiscount());
		//Need to update Main total
	},
	
	getRowTotal: function (row) {
		
		if(!this.sizes[row])
			this.sizes[row] = $$('#row'+row+' .update-values');
		var sizes = this.sizes[row];
		
		var sum = 0;
		sizes.each(function(size) {
			if(size.value=="") newSize=0;
			else 
				newSize = parseInt(size.value);
				
			sum += newSize;
		});
		var unitPrice = $('row'+row+'-unit-price').value;
		return sum * unitPrice;
	},
	
	setRowTotal: function (count, value) {
		
		$('row'+count+'-total').value = value;
	},
	
	updateCurrentRowQty: function (row) {
		
		if(!this.sizes[row])
			this.sizes[row] = $$('#row'+row+' .update-values');
		var sizes = this.sizes[row];
		var sum = 0;
		sizes.each(function(size) {
			if(size.value=="") { 
				
				newSize=0;
			
			} else {
				newSize = parseInt(size.value);
			}	
			sum += newSize;
		});
		
		$('row'+row+'-qty').value = sum;
		return sum;
	},
	
	updateMainQty: function () {
		
		var sizes = $$('.update-values');
		var sum = 0;
		sizes.each(function(size) {
			if(size.value=="") newSize=0;
			else 
				newSize = parseInt(size.value);
				
			sum += newSize;
		});
		$('qty-total').innerHTML = sum;
		
		return sum;
	},
	
	getMainPrice: function (el, count) { 
		
		var price = 0;
		for(var i=1; i<=count; i++) {
			var newPrice = parseFloat($('row'+i+'-total').value);
			price += newPrice;
		}
		
		return price;
	},
	
	setMerchPrice: function (value) {
		
		$('merch').innerHTML = value.toFixed(2);
	},
	
	setMainPrice: function (value) {
		
		var newValue = parseFloat(value);
		$('main-total').innerHTML = newValue.toFixed(2);
	},
	
	calcMainPrice: function (value, discount) {
		
		var price = value.toFixed(2);
		$('merch').innerHTML = price;
		
		if(discount!=false) {
			var newDisc = parseFloat(discount);
			var newPrice = price - newDisc;
			this.setMainPrice(newPrice);
			return newPrice;
		} else {
			this.setMainPrice(price);
			return price;
		}
	},
	
	getDiscount: function () {
		
		if($('desconto').value!="") {
			var desconto = parseFloat($('desconto').value);
			return desconto.toFixed(2);
		} else {
			return false;
		}
		
	},
	
	setDiscount: function (value) {
		
		$('desconto').value = value;
	},
	
	formatDiscount: function () {
		
		var toFormat = parseFloat($('desconto').value);
		var formatted = toFormat.toFixed(2);
		this.setDiscount(formatted);
	},
	
	increaseRowCount: function (increaseBy) {
		
		this.rowCount += increaseBy;
		return this.rowCount;
	},

	decreaseRowCount: function (increaseBy) {
		
		this.rowCount -= increaseBy;
		return this.rowCount;
	},
	
	getRowCount: function () {
		return this.rowCount;
	},
	
	fillSelect: function (type, element_id, this_client_id, item_db_id) { // Valid types: dates, pedido_entrega, cliente_razao, autor_nome
			
		var data = new Ajax.Request('inc/list_gen_select.inc.php', { 
			method:'get',
			parameters: { 'type': type, 'client_id':this_client_id },
			onSuccess: function(transport) {

				var response = transport.responseText.evalJSON() || "no response text";

				$(element_id).length = 0;

				if($(element_id).hasClassName('fabricante-select')) {

					$(element_id).insert(new Element('option', {value: 0}).update("-- Pronto: Fabricantes --"));

				}

				if($(element_id).hasClassName('tecido-select')) {

					$(element_id).insert(new Element('option', {value: 0}).update("-- Pronto: Tecidos --"));

				}

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

					if(pair.key == item_db_id) {

						$(element_id).insert(new Element('option', {value: pair.key, "selected":"selected"}).update(newLabel));

					} else {

						$(element_id).insert(new Element('option', {value: pair.key}).update(newLabel));

					}

				});

			},
			onFailure: function() { alert('Ocorreu um probleminha...'); return false; }
		});
	},
	
	removeSingleItem: function (id) { // Remove a single item from the order
		
		if(this.getRowCount() > 1) {

			$(id).setAttribute("wasremoved", "yes");
		
			var count = $$("#"+id+" #top-"+id+" .first.item input")[0].value; // current row # we are at
			var undo_msg = "undo-rmv-row"+count;
			
			if($(undo_msg) === null) { // Create the element only if it does not exist
				
				var removedMsgWrapper = this.getUndoRmvMsg(count);
				$(id).insert({ before: removedMsgWrapper });
				Event.observe(undo_msg, 'click', this.onUndoRemove.bind(this, id));
			}
			
			Effect.Fade(id, { "duration":0.2 });
			Effect.Appear(undo_msg, { "duration":1.3 });

			// Decrease count
			this.decreaseRowCount(1);
			// Do update totals here next
			
			return $(id); // removed item

		} else {

			alert("Não se pode remover o único item do pedido. Modifique-o.");
		}
		
	},
	
	getUndoRmvMsg: function (count) {
	
		var removedMsgWrapper = new Element('table', { "id": "undo-rmv-row"+count, "class":"undo-rmv-dialog" }).setStyle({ "display":"none" });
		var td = new Element('td').update("<b>Peça número "+ count + "</b> será removida assim que o pedido for salvo. Desfazer essa ação? <a href='#desfazer' id='unremove-row-"+ count +"'>Sim</a>.");
		td.setStyle({ "padding":"8px", "font-size":"12px", "background-color":"#FAD2C6" });
		var row = new Element('tr').insert(td);
		
		removedMsgWrapper.insert(row);
		
		return removedMsgWrapper;		
	},
	
	onUndoRemove: function (id) {
		
		Effect.Fade("undo-rmv-"+id, { "duration":1.3 });
		Effect.Appear(id, { "duration":0.2 });
		
		$(id).setAttribute("wasremoved", "no");

		// Increase count
		this.increaseRowCount(1);
	},
	
	loadSavedClothingSelect: function (elem_id) {
		
		// elem_id has to be row[number]-controls
		if($(elem_id).hasAttribute("num")) {
		
			var item_db_id = $(elem_id).readAttribute("num");
			
		} else {
		
			var item_db_id = null;	
		}
			
		var this_client_id = this.getClientId();
		
		// Get saved items on the database for that particular client
		this.fillSelect('select_pecas_guardadas', elem_id, this_client_id, item_db_id);
		
	},
	
	// Pre requisites: Must have a row number, Item ID is optional
	// If Item ID is supplied, selects the manufacturer according to the info on the item
	loadSupplierSelect: function (element_name, item_db_id, count) {
		
		this.fillSelect('fornec_nome_tecidos', element_name, null, item_db_id);
	
	},
	
	populateSingleItem: function (rowId) {
	
		var r_id = "peca-choose-"+rowId;
		var item_id = $(r_id).options[$(r_id).selectedIndex].value;
		
		new Ajax.Request('inc/pedido_retrieve_item.inc.php', { 
			
			method:'get',
			parameters: { 'item_id': item_id, 'rowId':rowId },
			onSuccess: this.onPopulateSuccess.bind(this),
			onFailure: function() { alert('Ocorreu um probleminha...'); return false; }
		
		});
	},
	
	onPopulateSuccess: function (transport) {
	
		//console.log(transport.responseText);
		var response = transport.responseText.evalJSON() || "no response text";
		//console.log(response);
		var rowId = transport.request.parameters.rowId;
		var fabricanteSel = $("fabricante-select-" + rowId);
		
		var supplier_select = "fabricante-select-"+rowId;
		var supplier_id = response["peca_armazenada_fabricante"];
		var element_name = "tecido-select-"+rowId;
		var item_db_id = response["peca_armazenada_tecido"]; // f_tecido_id
		
		var count = $$("#"+rowId+" #top-"+rowId+" .first.item input")[0].value; // Get the row count
		
		$(rowId+'-unit-price').value = response["peca_armazenada_preco"];
		$('espec-'+rowId).value = response["peca_armazenada_espec"];
		
		for (var i=0; i < fabricanteSel.length; i++) {
			
			if(fabricanteSel.options[i].value == response["peca_armazenada_fabricante"]) {
				
				fabricanteSel.options[i].selected = true;
			
			}
		};

		// fillSelect: function (type, element_id, this_client_id, item_db_id)
		this.fillSelect('fornec_tecidos', element_name, supplier_id, item_db_id);
		
		this.formatRowValues(count);
		
		// Set the background back to the original green, now that a new saved item has been loaded
		this.removeSavedCoating(rowId);
	
	},
	
	removeSavedCoating: function(rowId) {
	
		// Set the background back to the original green, now that a new saved item has been loaded
		$$('#'+rowId+'-controls th')[0].setStyle({ "background-color":"#E3F9DC"});
		
		Effect.Appear("salvar-peca-"+rowId, { "duration":1.2 }); // Show "Salvar para uso futuro"
		Effect.Fade("salva-label-"+rowId, { "duration":0.2 }); // Hide "SALVA"
		
	},
	
	toggleUseButton: function (linkEl, rowId) {
	
		// If the currently selected id is 0, hide button
		// Else, show the button
		var r_id = "peca-choose-row"+rowId;
		var item_id = $(r_id).options[$(r_id).selectedIndex].value;
		var button = "usar-peca-nome-"+r_id;
		
		if($(r_id).selectedIndex === "zero") {
		
			Effect.Fade($(button), { "duration":1 });
			
		} else {
		
			Effect.Appear($(button), { "duration":1 });
		}
		
		
	},
	
	addItemImage: function () {
	
		// Upon button click, bring lightwindow with a slideshow of images? or a grid of 4 images?
		// Lets see what is better now.
		
	},

	getStateName: function (value) {
		switch(parseInt(value)) {
			case 1: { var state="Acre"; break; }
			case 2: { var state="Alagoas"; break; }
			case 3: { var state="Amapá"; break; }
			case 4: { var state="Amazonas"; break; }
			case 5: { var state="Bahia"; break; }
			case 6: { var state="Ceará"; break; }
			case 7: { var state="Distrito Federal"; break; }
			case 8: { var state="Espírito Santo"; break; }
			case 9: { var state="Goiás"; break; }
			case 10: { var state="Maranhão"; break; }
			case 11: { var state="Mato Grosso"; break; }
			case 12: { var state="Mato Grosso do Sul"; break; }
			case 13: { var state="Minas Gerais"; break; }
			case 14: { var state="Pará"; break; }
			case 15: { var state="Paraíba"; break; }
			case 16: { var state="Paraná"; break; }
			case 17: { var state="Pernambuco"; break; }
			case 18: { var state="Piauí"; break; }
			case 19: { var state="Roraima"; break; }
			case 20: { var state="Rondonia"; break; }
			case 21: { var state="Rio de Janeiro"; break; }
			case 22: { var state="Rio Grande do Norte"; break; }
			case 23: { var state="Rio Grande do Sul"; break; }
			case 24: { var state="Santa Catarina"; break; }
			case 25: { var state="São Paulo"; break; }
			case 26: { var state="Sergipe"; break; }
			case 27: { var state="Tocantins"; break; }

			default: { var state="erro!"; }
		}

		return state;
	},
	
	createNameInput: function (rowId, el) {
	
		// Set the item's name
		Effect.Fade($$("#"+rowId+" .controls.left")[0], { "duration":0.2 });
		Effect.Appear($$("#"+rowId+" .name-wrapper")[0], { "duration":1.6 });
		
	},
	
	saveSingleItem: function (thisLink, el) { // "el" is the instance of the order
		
		var rowId = thisLink.up("table").id;
		var client_id = el.getClientId();
		
		new Ajax.Request('inc/pedido_store_item.inc.php', {
			
			method:'get',
			parameters: { 
				"peca_armazenada_preco":$(rowId + "-unit-price").value,
				"peca_armazenada_nome":$(rowId + "-peca-nome").value,
				"f_pedido_id":null,
				"peca_armazenada_fabricante":$("fabricante-select-" + rowId).options[$("fabricante-select-" + rowId).selectedIndex].value,
				"peca_armazenada_tecido":$("tecido-select-" + rowId).options[$("tecido-select-" + rowId).selectedIndex].value,
				"peca_armazenada_espec":$("espec-"+rowId).value,
				"f_cliente_id":client_id,
				"rowId":rowId
			},
			onSuccess: el.onSaveSingleSucess.bind(this),
			onFailure: function() { alert('Ocorreu um probleminha...'); return false; }

		});
		
	},
	
	onSaveSingleSucess: function(transport) {
		
		var response = transport.responseText.evalJSON() || "no response text";
		var rowId = transport.request.parameters.rowId;
		var selElName = "peca-choose-"+rowId;
		
		$$("#"+rowId+" .peca-nome")[0].value = ""; // Reset the field's value
		Effect.Fade($$("#"+rowId+" .name-wrapper")[0], { "duration":0.2 }); // Hide it
		
		$("peca-choose-"+rowId).selectedIndex = 0; // Reset selection for saved clothing pieces
		Effect.Appear($$("#"+rowId+" .controls")[0], { "duration":1.2 }); // Show the select box
		
		// Color the row differently to say that it's been saved
		$$('#'+rowId+'-controls th')[0].setStyle({ "background-color":"#A7F7F7"});
		
		Effect.Appear("salva-label-"+rowId, { "duration":1.2 }); // Show "SALVA"
		Effect.Fade("salvar-peca-"+rowId, { "duration":0.2 }); // Hide "Salvar para uso futuro"
		
		$("salva-label-"+rowId).setAttribute("title", response["peca_armazenada_nome"]);
		
		// var msg = new Element('span', { "id":"item-saved-msg-"+rowId, "class":"callout green rmv msg", "maxlength":"40" }).update('"'+response['peca_armazenada_nome']+'" salva com sucesso. <a id="row'+this.getRowCount()+'-show-item-list" href="#close">Fechar</a>');
	},

	adjustStatusFields: function () {

		if(this.userLoggedin == 13) { // fin

			$('status_f').removeAttribute("readonly");
			
			$('status_d').setStyle({ "border":"1px dotted #f00" });
			$('status_p').setStyle({ "border":"1px dotted #f00" });

		} else if(this.userLoggedin == 19) { // producao

			$('status_p').removeAttribute("readonly");
			
			$('status_d').setStyle({ "border":"1px dotted #f00" });
			$('status_f').setStyle({ "border":"1px dotted #f00" });

		} else if (this.userLoggedin == 2) { // diretoria

			$('status_d').removeAttribute("readonly");
			
			$('status_f').setStyle({ "border":"1px dotted #f00" });
			$('status_p').setStyle({ "border":"1px dotted #f00" });

		} else if(this.userLoggedin == 3) { // eu
			
			$('status_d').removeAttribute("readonly");
			$('status_f').removeAttribute("readonly");
			$('status_p').removeAttribute("readonly");

		} else {  }
	},

	_onValidate: function () {
		
		return valid.validate();
	}, 
	
	sendEmail: function (subject, name_from, email_from, email_to) {
	
		// Do header security
		// Build email
		// Send over POST AJAX to a php file that will finally assemble and send the message
	}	

});
