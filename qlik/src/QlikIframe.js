/********************************************************
 * 														*	
 * 						FUNCTIONS						*
 * 														*
 *******************************************************/
// SHOW IN DETAILVIEW

function show_iframeDet(url, h, pos, name, url_debug) {
	url_debug = url_debug == 1 ? "<a href='" + url + "' target='_blank'>" + url + "</a>" : "";

	if (jQuery('#iframe_' + name).length) {
		if (pos == "Bottom") {
			jQuery('#qlikiframe_iframe_bottom').html("");
		}
		else {
			jQuery('#qlikiframe_iframe_top').html("");
		}
	}
	else {
		if (pos == "Bottom") {//show at the end of the page
			jQuery('<div id="qlikiframe_iframe_bottom"></div>').insertBefore(jQuery('#RelatedLists'));
			jQuery('#qlikiframe_iframe_bottom').html("<div id='iframe_" + name + "' style='background-color:#fff'></div>" + url_debug + "<iframe bgcolor='#000033' src='" + url + "' width='100%' height='" + h + "px' frameborder='0'></iframe></div>");
			goToRelated("qlikiframe_iframe_bottom");
		}
		else {//put iframe on the top of page
			jQuery('<div id="qlikiframe_iframe_top"></div>').insertBefore(jQuery('#DetailViewBlocks'));
			newdivpage = "<div id='iframe_" + name + "' style='background-color:#fff'></div>" + url_debug + "<iframe bgcolor='#000033' src='" + url + "' width='100%' height='" + h + "px' frameborder='0'></iframe></div>";
			jQuery('#qlikiframe_iframe_top').append(newdivpage);
		}
	}
}

//SHOW IN LISTVIEW
function show_iframeList(url, h, pos, name, url_debug) {


	if (jQuery('#iframe_' + name).length) {
		jQuery('#qlikiframe_iframe').html("");
	}
	else {
		if (pos != "Bottom") {
			jQuery('<div id="qlikiframe_iframe"></div>').insertBefore(jQuery('#ListViewContents'));
		}
		else {
			jQuery('<div id="qlikiframe_iframe"></div>').insertBefore(jQuery('#vte_footer'));
		}
		jQuery('#qlikiframe_iframe').append("<div id='iframe_" + name + "' style='background-color:#fff'></div>" + url_debug + "<iframe bgcolor='#000033' src='" + url + "' width='100%' height='" + h + " px' frameborder='0'></iframe></div>");
	}
}

//SHOW PREVIEW
function show_iframePrev(url, h, pos, name, url_debug) {
	// sostituito #iframe_ con #pr_iframe in quanto, se presenti iframe di tipo modulo, non viene vista la preview.
	if (jQuery('#pr_iframe_' + name).length) {
		jQuery('#qlikiframe_iframe_prev').html("");
	}
	else {
		newdiv = '<div id="preview"><table class="small" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="dvInnerHeader"><div style="float:left;font-weight:bold;width:100%;"><div style="float:left;"><b>PREVIEW</b></div><div style="float:right;"><div style="float:right;"></div></div></td></tr></tbody></table></div>';
		jQuery('#qlikiframe_iframe_prev').html(newdiv + " <div id='pr_iframe_" + name + "' style='background-color:#fff'></div>" + url_debug + "<iframe bgcolor='#000033' src='" + url + "' width='100%' height='" + h + "px' frameborder='0'></iframe></div>");
	}
}
/* ********************** FOR STATIC MENU ****************
//PARAMS IN GET
function show_iframeLink(url, h, name, recordid){
	newurl=url.replace(/&/g,'|$|');
	newname=name.replace(' ','');
	newtd='<tr><td id="iframe_'+newname+'"><a id="aiframe_'+newname+'" href="index.php?module=QlikIframe&amp;action=Show_iframe&amp;parenttab=Tools&amp;height='+h+'&amp;record='+recordid+'&amp;url_iframe=\''+newurl+'\'" class="drop_down">'+name+'</a></td></tr>';
	jQuery('#Custom_Analysis_sub tbody').append(newtd);
}

//PARAMS IN POST
function show_iframeLink(url, h, name, recordid){
	newurl=url.replace(/&/g,'|$|');
	newname=name.replace(' ','');
	urlpage="'index.php?module=QlikIframe&amp;action=Show_iframe&amp;parenttab=Tools'" ;
	newtd='<tr><td id="iframe_'+newname+'"><a id="aiframe_'+newname+'" href="#" onclick="invia_dati('+urlpage+',{\'url_iframe\':\''+newurl+'\',\'height\': '+h+',\'record\':'+recordid+'}, \'post\' )" class="drop_down">'+name+'</a></td></tr>';
	jQuery('#Analisi_Qlik_sub tbody').append(newtd);
}
**********************************************************/
//*************** FOR DYNAMIC MENU ***************************
function fnShowDropDownW() {
	var div = document.getElementById('Analisi_Qlik_tab');
	if (document.getElementById('Analisi_Qlik_sub').style.display == 'block')
		fnHideDrop('Analisi_Qlik_sub');
	else
		fnDropDown(div, 'Analisi_Qlik_sub');
}
//***************************************************************
/* ************ FOR PARAMS IN GET ************
function show_iframeLinkDyn(url, h, name, recordid){
	newurl=url.replace(/&/g,'|$|');
	newname=name.replace(' ','');
	newtd='<tr><td id="iframe_'+newname+'"><a id="aiframe_'+newname+'" href="index.php?module=QlikIframe&amp;action=Show_iframe&amp;parenttab=Tools&amp;height='+h+'&amp;record='+recordid+'&amp;url_iframe=\''+newurl+'\'" class="drop_down">'+name+'</a></td></tr>';
	jQuery('#Analisi_Qlik_sub tbody').append(newtd);
}
******************************************/
// ***** FOR PARAMS IN POST **********
function show_iframeLinkDyn(url, h, name, recordid, url_debug) {
	url_debug = url_debug == 1 ? "<a href='" + url + "' target='_blank'>" + url + "</a>" : "";
	newurl = url.replace(/&/g, '|$|');
	newname = name.replace(' ', '');
	urlpage = "'index.php?module=QlikIframe&amp;action=Show_iframe&amp;parenttab=Tools'";
	csrf = getCSRF();

	let getModuleMenuItem = jQuery("a[data-original-title='Analisi Custom']").parent();


	let getChildItem = getModuleMenuItem.find('ul.collapsibleMenu__submenu');

	if (window.current_theme === 'next22') {
		//getChildItem.append('<li class="collapsibleMenu__menuItemBox collapsibleMenu__menuItemBox--submenu" id="iframe_' + newname + '"><a class="collapsibleMenu__menuItem" href="#" data-action="collapsibleMenu-menuItem-toggle" title="Anagrafiche" data-placement="right" target="_self" onclick="invia_dati(\'' + urlpage + '\', {\'url_iframe\':\'' + newurl + '\',\'height\': ' + h + ',\'record\':' + recordid + ',\'__csrf_token\':\'' + csrf + '\',\'url_debug\':\'' + url_debug + '\'}, \'post\' )"><div class="collapsibleMenu__text"><span>' + name + '</span></div></a></li>');
		//		getChildItem.append('<li class="collapsibleMenu__menuItemBox collapsibleMenu__menuItemBox--submenu" id="iframe_' + newname + '"><a class="collapsibleMenu__menuItem" href="#" data-action="collapsibleMenu-menuItem-toggle" title="Anagrafiche" data-placement="right" target="_self" onclick="invia_dati(' + urlpage + ',{\'url_iframe\':\'' + newurl + '\',\'height\': ' + h + ',\'record\':' + recordid + ',\'__csrf_token\':\'' + csrf + '\',\'url_debug\':\'' + url_debug + '\'}, \'post\' )"><div class="collapsibleMenu__text"><span>' + name + '</span></div></a></li>');
		getChildItem.append('<li class="collapsibleMenu__menuItemBox collapsibleMenu__menuItemBox--submenu" id="iframe_' + newname + '"><a class="collapsibleMenu__menuItem" href="#" data-action="collapsibleMenu-menuItem-toggle" title="Anagrafiche" data-placement="right" target="_self" onclick="invia_dati(' + urlpage + ',{\'url_iframe\':\'' + newurl + '\',\'height\': ' + h + ',\'record\':' + recordid + ',\'__csrf_token\':\'' + csrf + '\'}, \'post\' )"><div class="collapsibleMenu__text"><span>' + name + '</span></div></a></li>');

	} else {
		newtd = '<tr><td id="iframe_' + newname + '"><a id="aiframe_' + newname + '" href="#" onclick="invia_dati(' + urlpage + ',{\'url_iframe\':\'' + newurl + '\',\'height\': ' + h + ',\'record\':' + recordid + ',\'__csrf_token\':\'' + csrf + '\'}, \'post\' )" class="drop_down">' + name + '</a></td></tr>';

		//newtd = '<tr><td id="iframe_' + newname + '"><a id="aiframe_' + newname + '" href="#" onclick="invia_dati(' + urlpage + ',{\'url_iframe\':\'' + newurl + '\',\'height\': ' + h + ',\'record\':' + recordid + ',\'__csrf_token\':\'' + csrf + '\',\'url_debug\':\'' + url_debug + '\'}, \'post\' )" class="drop_down">' + name + '</a></td></tr>';
		//onclick="invia_dati('" + urlpage + "', {'url_iframe': '" + newurl + "', 'height': " + h + ", 'record': " + recordid + ", '__csrf_token': '" + csrf + "', 'url_debug': '" + url_debug + "'}, 'post')"
		//newtd = '<tr><td id="iframe_' + newname + '"><a id="aiframe_' + newname + '" href="#" onclick="invia_dati('" + urlpage + "', {'url_iframe': '" + newurl + "', 'height': " + h + ", 'record': " + recordid + ", '__csrf_token': '" + csrf + "', 'url_debug': '" + url_debug + "'}, 'post')" class="drop_down">' + name + '</a></td></tr>';
		//newtd = '<tr><td id="iframe_' + newname + '"><a id="aiframe_' + newname + '" href="#" onclick="invia_dati(' + urlpage + ', {\'url_iframe\': \'' + newurl + '\', \'height\': ' + h + ', \'record\': ' + recordid + ', \'__csrf_token\': \'' + csrf + '\', \'url_debug\': \'' + url_debug + '\'}, \'post\')" class="drop_down">' + name + '</a></td></tr>';
		jQuery('#Analisi_Qlik_sub tbody').append(newtd);
	}
}

function getCSRF() {
	var csrf = '';
	jQuery.ajax({
		type: 'POST',
		url: 'modules/SDK/qlik/src/QlikIframeCsrf.php',
		async: false,
		data: {
			function_name: 'getCSRF',
		}
	}).done(function (response) {
		response = JSON.parse(response);
		if (response.success == true) {
			csrf = response.data.csrf;
		}
	});
	return csrf;
}

function invia_dati(servURL, params, method, url_debug) {

	method = (method || "post"); // il metodo POST  usato di default
	var form = document.createElement("form");
	form.setAttribute("method", method);
	form.setAttribute("action", servURL);
	for (var key in params) {
		var hiddenField = document.createElement("input");
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", key);
		hiddenField.setAttribute("value", params[key]);
		form.appendChild(hiddenField);
	}
	document.body.appendChild(form);
	form.submit();
}

//funzione ricalcata dalla funzione standard chooseType con aggiunta di informazioni a me necessarie -- in homepage
function qlikiframe_chooseType(mytype, niframe, start, end) {
	jQuery('addWidgetsDiv').hide();
	if (start != '##START##' || end < 4) {
		alert(alert_arr.NO_WIDGET_CREATE);
		fnhide('#qlikiframe_addWidgetsDiv');
		jQuery('#qlikiframe_stufftitle_id').val('');
		return false;
	}
	if (niframe != 'No') {
		jQuery('#status').css({ "display": "inline" });
		jQuery('#stufftype_id').value = mytype;

		var typeLabel = mytype;
		if (alert_arr[mytype] != null && alert_arr[mytype] != "" && alert_arr[mytype] != 'undefined') {
			typeLabel = alert_arr[mytype];
		}
		jQuery('#divHeader').innerHTML = "<b>" + alert_arr.LBL_ADD + typeLabel + "</b>";
		jQuery('#qlikiframe_addWidgetsDiv').show();
		jQuery('#status').css({ "display": "none" });
	}
	else {
		alert(alert_arr.NO_ANALYSIS);
		fnhide('qlikiframe_addWidgetsDiv');
		jQuery('#qlikiframe_stufftitle_id').val('');
		return false;
	}
}
//funzione utilizzata per recuperare l'URL da visualizzare ed eventualmente mostrare  messaggi a video.
function qlikiframeloadStuff(stuffid) {
	jQuery.ajax({
		url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeLoadHomeWidgetAjax&stuffid=" + stuffid,
		async: false,
		success: function (res) {
			res = res.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe
			var iframes = JSON.parse(res);

			if (jQuery('#stuff_' + stuffid).find('#qlikiframe_content').length > 0)
				jQuery('#stuff_' + stuffid).find('#qlikiframe_content').remove();
			jQuery('#MainMatrix').css({ "display": "block" });
			//se ci sono stati errori o mancano i dati, mostro cosa non va. (es cancellata l'analisi, non attiva ecc)
			if (iframes[0] == 'ERROR') {
				var content = jQuery('#stuff_' + stuffid).find('.MatrixBorderURL').html();
				content = '<span id="qlikiframe_content" style="display: table;margin-left: auto;margin-right: auto;"> <font style="color:red; font-size:20px;"><b>' + iframes[1] + '</b><br/><br/></font></span>' + content;
				jQuery('#stuff_' + stuffid).find('.MatrixBorderURL').html(content);
				jQuery('#stuff_' + stuffid).find('.MatrixBorderURL').height('150');//150px di default in quanto non mi serve esageratamente grande
			}
			else {
				//altrimenti disegno l'iframe.
				var qlikiframe_values = iframes[1];
				name = qlikiframe_values[0];
				url = qlikiframe_values[1];
				h = qlikiframe_values[2];
				var content = jQuery('#stuff_' + stuffid).find('.MatrixBorderURL').html();
				var url_debug = iframes[2] == 1 ? "<a href='" + url + "' target='_blank' >" + url + "</a>" : '';
				content = url_debug + '<iframe id="qlikiframe_content" style="width:100%;height:' + h + 'px" src=' + url + '></iframe>' + content;
				jQuery('#stuff_' + stuffid).find('.MatrixBorderURL').html(content);
				jQuery('#stuff_' + stuffid).find('.MatrixBorderURL').height(h); //do al widget la dimensione che ho scelto nell'analisi
			}
		}
	});
}
//crea il widget vero e proprio nella homepage
function qlikiframe_draw_widget(stuffid, title) {
	//codice recuperato dal tpl di widget di tipo iframe pulito da quanto non utile.
	var cont = '<div id="stuff_' + stuffid + '" class="MatrixLayerURL" style="float:left;overflow-x:hidden;overflow-y:hidden">' +
		'<table width="100%" cellpadding="0" cellspacing="0" class="small" style="padding-right:0px;padding-left:0px;padding-top:0px;">' +
		'<tr id="headerrow_' + stuffid + '" class="dvInnerHeader headerrow">	' +
		'<td align="left" class="homePageMatrixHdr" style="height:30px;" nowrap width=60%><b>&nbsp;' + title + '</b></td>' +
		'<td align="right" class="homePageMatrixHdr" style="height:30px;" width=5%>' +
		'<span id="refresh_' + stuffid + '" style="position:relative;">&nbsp;&nbsp;</span>' +
		'</td>' +
		'<td align="right" class="homePageMatrixHdr" style="height:30px;" width=35% nowrap>' +
		'<a id="deletelink" style=\'cursor:pointer;\' onclick="qlikiframe_delStuff(' + stuffid + ')">' +
		'<i class="vteicon" title="clear">clear</i>' +
		'</a></td>' +
		'</tr></table>' +
		'<div class="MatrixBorderURL">' +
		'</div></div>';
	return cont;
}
function qlikiframe_delStuff(stuffid) {
	DelStuff(stuffid);
	jQuery.ajax({
		url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeDeleteHomeAjax&qlikiframe_stuffid=" + stuffid,
		success: function (res) {
			/*res = res.replace('<!-- stopscrmprint -->',''); // per evitare che vada in errore e non mostri l'iframe
			var stuffid = JSON.parse(res);
			//se è andato a buon fine, allora aggiungo il nuovo widget
			if(stuffid != "No")
				qlikiframe_loadAddedDiv(stuffid[0],'Qlik',4,stuffid[1]);
			else alert(alert_arr.GENERAL_ERROR_QLIK);*/
		}
	});

}

//aggiungo il nuovo widgtet alla pagina -- nel modulo homepage.
function qlikiframe_loadAddedDiv(stuffid, stufftype, dim, title) { // crmv@30014
	gstuffId = stuffid;
	if (dim == undefined || dim == '') dim = 0; // crmv@30014
	var cont = qlikiframe_draw_widget(stuffid, title); //disegna il widget appena creato
	jQuery('#MainMatrix').innerHTML = cont + jQuery('#MainMatrix').innerHTML;
	positionDivInAccord('stuff_' + gstuffId, '', stufftype, dim); // crmv@30014
	initHomePage();
	qlikiframeloadStuff(stuffid); //carico il contenuto vero e proprio del widget, costruendomi il link 
}

//function used to validate input text. -- salva poi la creazione di un nuovo widget in homepage di qlikiframe.
function qlikiframe_frmHomeVal() {
	if (trim(jQuery('#qlikiframe_stufftitle_id').value) == "") {
		alert(alert_arr.ENTER_VALID + ' ' + alert_arr.LBL_NAME);
		jQuery('#qlikiframe_stufftitle_id').focus();
		return false;
	}

	jQuery('#status').css({ "display": "inline" });
	var qlikiframeid = jQuery('#selanalysis_id').val();
	var stufftitle = jQuery('#qlikiframe_stufftitle_id').val();

	jQuery('#qlikiframe_stufftitle_id').value = '';
	var selFiltername = '';
	var fldname = '';
	var selmodule = '';
	var maxentries = '';
	var txtRss = '';
	var seldashbd = '';
	var selchart = ''; // crmv@30014
	var seldashtype = '';
	var seldeftype = '';
	var txtURL = '';
	//chiamata ajax che salva un widget per l'analisi qlik
	jQuery.ajax({
		url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeSaveHomeAjax&title=" + stufftitle + "&qlikiframeid=" + qlikiframeid,
		success: function (res) {
			res = res.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe
			var stuffid = JSON.parse(res);
			//se è andato a buon fine, allora aggiungo il nuovo widget
			if (stuffid != "No")
				qlikiframe_loadAddedDiv(stuffid[0], 'Qlik', 4, stuffid[1]);
			else alert(alert_arr.GENERAL_ERROR_QLIK);
		}
	});

	hide('qlikiframe_addWidgetsDiv');
	jQuery('#qlikiframe_stufftitle_id').value = '';
	jQuery('#status').css({ "display": "none" });

}
//funzione che crea il popup per creare un nuovo widget di qlikiframe mostrando il campo nome  e la picklist con tutti gli qlikiframe disponibili.
function qlikiframe_create_home_wizard() {
	//codice recuperato dall'elemento addWidgetsDiv
	jQuery('#formStuff').append('<div id="qlikiframe_addWidgetsDiv" class="crmvDiv" style="z-index: 2000; display: none; width: 400px; left: 654px; top: 283.5px; position: absolute;">' +
		'<input name="stufftype" id="stufftype_id" value="WQlik" type="hidden">' +
		'<div class="closebutton" onclick="fnhide(\'qlikiframe_addWidgetsDiv\'); jQuery(\'#qlikiframe_stufftitle_id\').val(\'\');"></div>' +
		'<table cellspacing="0" cellpadding="5" border="0" width="100%">' +
		'<tbody><tr style="cursor:move;" height="34">' +
		'<td id="qlikiframe_addWidgetsDiv_Handle" style="padding:5px" class="level3Bg">' +
		'<table cellspacing="0" cellpadding="0" width="100%">' +
		'<tbody><tr>' +
		'<td id="divHeader" width="50%"><b>' + alert_arr.SELECT_NEW + '</b></td>' +
		'<td align="right" width="50%">' +
		'<input name="save" value="Save" id="savebtn" class="crmbutton small save" onclick="qlikiframe_frmHomeVal()" type="button"><div class="ripple-wrapper"><div class="ripple ripple-on ripple-out" style="left: 1005px; top: 305px; background-color: rgba(0, 0, 0, 0.84); transform: scale(7.75);"></div><div class="ripple ripple-on ripple-out" style="left: 1006px; top: 305px; background-color: rgba(0, 0, 0, 0.84); transform: scale(7.75);"></div></div></input>' +
		'</td>' +
		'</tr>' +
		'</tbody></table>' +
		'</td>' +
		'</tr>' +
		'</tbody></table>' +
		'<table cellspacing="0" cellpadding="5" align="center" border="0" width="100%">' +
		'<tbody><tr>' +
		'<td class="small">' +
		'<table cellspacing="2" cellpadding="3" bgcolor="white" align="center" border="0" width="100%"><tbody>' +
		'<tr id="QlikIframeStuffTitleId" style="display:block;">' +
		'<td class="dvtCellLabel" align="right" width="110">' +
		'	' + alert_arr.TITLE_NEW_OBJECT + '<font color="red">*</font>' +
		'</td>' +
		'<td class="dvtCellInfo" colspan="2" width="300">' +
		'<input name="qlikiframe_stufftitle" id="qlikiframe_stufftitle_id" class="detailedViewTextBox" onfocus="this.className=\'detailedViewTextBoxOn\'" onblur="this.className=\'detailedViewTextBox\'" type="text">' +
		'</td>' +
		'</tr>' +
		'<tr id="moduleNameRow" style="display: block;">' +
		'<td class="dvtCellLabel" align="right" width="110">Analisi <font color="red">*</font></td>' +
		'<td class="dvtCellInfo" colspan="2" width="300">' +
		'<select name="selanalysis" id="selanalysis_id" onchange="setFilter(this)" class="detailedViewTextBox" onfocus="this.className=\'detailedViewTextBoxOn\'" onblur="this.className=\'detailedViewTextBox\'">' +
		'</td>' +
		'</tr>' +

		'</tbody></table>' +
		'</td>' +
		'</tr>' +
		'</tbody></table>' +
		'</div>');
	//mi serve per il drag del popup di creazione analisi.
	var THandle = document.getElementById("qlikiframe_addWidgetsDiv_Handle");
	var TRoot = document.getElementById("qlikiframe_addWidgetsDiv");
	Drag.init(THandle, TRoot);
}

function qlikiframe_create_analysis_tab(qlikiframe_type) { // sistemato per passare dinamicamente il tipo e utilizzare la stessa funzione anche in Settings per la detail
	//chiamata ajax che inserisce in vte_modulehome il nuovo tab e si occupa anche di aggiugnere nella tabella custom la relazione tab --> frameid
	var qlikiframe_tabname = jQuery('#qlikiframe_tab_name').val();
	var qlikiframe_frameid = jQuery('#qlikiframe_analisys').val();
	if (!qlikiframe_tabname) {
		alert(alert_arr.ENTER_VALID + ' ' + alert_arr.LBL_NAME);
	}
	else {
		var module = gVTModule;
		if (qlikiframe_type != 'ListView') {
			jQuery('#vtbusy_info').show();
			module = jQuery('input[name=fld_module]').val();
		}
		jQuery.ajax({
			url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeSaveNewTabAjax&qlikiframe_type=" + qlikiframe_type + "&title=" + qlikiframe_tabname + "&qlikiframeid=" + qlikiframe_frameid + '&qlikiframe_mod=' + module, // sostituito gVTModule con module e parametrizzato il valore di qlikiframe_type
			success: function (res) {
				res = res.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe
				if (qlikiframe_type != "ListView") {
					var homeid = JSON.parse(res);
					if (homeid != "No") {
						jQuery('#vtbusy_info').hide();
						url = 'index.php?module=Settings&action=LayoutBlockList&formodule=' + module;
						window.location.href = url;
					}
					else {
						alert(alert_arr.GENERAL_ERROR_QLIK);
						jQuery('#vtbusy_info').hide();
					}

				}
				else {
					var homeid = JSON.parse(res);
					//se è andato a buon fine, allora aggiungo il nuovo widget
					if (homeid != "No") {
						var newloc = window.location.href.replace(/&modhomeid=[0-9]*/, '');
						var url = newloc.replace(/&editmode=[01]/, '') + '&modhomeid=' + homeid;
						url += '&editmode=1';
						jQuery('#vtbusy_info').hide();
						window.location.href = url;
					}
					else {
						alert(alert_arr.GENERAL_ERROR_QLIK);
						jQuery('#vtbusy_info').hide();
					}

				}
			}
		});
	}
}


//funzione usata per creare il div: separata dal codice per renderlo riutilizzabile e snello. Va bene per detail e list view
function qlikiframe_draw_list_new_tab(qlikiframe_type) {// passato un parametro per utilizzare la funzione anche per le tab in detail
	var qlikiframe_div_new_tab = '<div id="qlikiframe_add_analysis_tab" class="crmvDiv" style="position: absolute; width: 400px; left: 483px; top: 140.5px; display: none;">' +
		'<table style="min-width:240px;width:100%" cellspacing="0" cellpadding="5" border="0">' +
		'<tbody><tr style="cursor:move;" height="34">' +
		'<td id="qlikiframe_add_analysis_tab_Handle" style="padding:5px" class="level3Bg">' +
		'<table cellspacing="0" cellpadding="0" width="100%">' +
		'<tbody><tr>' +
		'<td id="qlikiframe_add_analysis_tab_Handle_Title" width="80%"><b>' + alert_arr.TAB_ANALYSIS + '</b></td>' +
		'<td nowrap="" align="right" width="20%">' +
		'<i class="dataloader" data-loader="circle" id="indicatorModHomeAddView" style="vertical-align:middle;display:none;"></i>' +
		'</td></tr></tbody></table></td>' +
		'</tr></tbody></table>' +
		'<div id="qlikiframe_add_analysis_tab_div" style="padding: 4px;">' +
		'<table cellspacing="2" cellpadding="3" align="center" border="0" width="100%"><tbody><tr>' +
		'<td class="dvtCellLabel" align="right">' + alert_arr.TITLE_NEW_OBJECT + '</td>' +
		'<td class="dvtCellInfo">' +
		'<input name="qlikiframe_tab_name" id="qlikiframe_tab_name" class="detailedViewTextBox" onfocus="this.className=\'detailedViewTextBoxOn\'" onblur="this.className=\'detailedViewTextBox\'" type="text">' +
		'</td></tr>' +
		'<tr>' +
		'<td class="dvtCellLabel" align="right">' + alert_arr.CHOOSE_ANALYSIS + '</td>' +
		'<td class="dvtCellInfo">' +
		'<select class="detailedViewTextBox" name="qlikiframe_analisys" id="qlikiframe_analisys"></select>' +
		'</td></tr>' +
		'<tr>' +
		'<td colspan="2" align="right">' +
		'<button type="button" class="crmbutton save" onclick="qlikiframe_create_analysis_tab(\'' + qlikiframe_type + '\')">' + alert_arr.CREATE_NEW_TAB + '</button>' + // passato paramentro alla funzione in onclick
		'</td></tr>' +
		'</tbody></table></div>' +
		'<div class="closebutton" onclick="hideFloatingDiv(\'qlikiframe_add_analysis_tab\');"></div>' +
		'</div>';
	if (qlikiframe_type != 'ListView') {
		jQuery('#createTab').after(qlikiframe_div_new_tab);
	}
	else
		jQuery('#ModHomeAddViewReport').after(qlikiframe_div_new_tab);

}

//funzione invocata quando si preme il pulsante per creare una nuova tab per analisi qlik
function qlikiframe_add_new_tab(niframe, start, end) {

	if (start != '##START##' || end < 2) {
		alert(alert_arr.NO_WIDGET_CREATE);
		return false;
	}
	if (niframe != 'Si') {
		if (niframe != 'Permission') { //non ci sono analisi disponibili per quel modulo in tab 
			alert(alert_arr.NO_ANALYSIS);
			return false;
		}
		else {//non ho accesso al modulo o modulo inactive
			alert(alert_arr.NO_MODULE_ANALYSIS);
			return false;
		}
	}
	else { //posso mostrare la finestra per la creazione di una nuova tab
		showFloatingDiv('qlikiframe_add_analysis_tab', null, { modal: true });
	}
}
//carica l'iframe nella tab della listview e della detailview
function qlikiframe_load_tab_analysis(qlikiframe_modid, qlikiframe_page_type, p_qlikiframe_module) { // aggiunto terzo paramentro, se vuoto, impostato con gVTModule
	//alert(gVTModule);
	var qlikiframe_params = '';
	var qlikiframe_module = p_qlikiframe_module;
	if (typeof p_qlikiframe_module == 'undefined') {
		qlikiframe_module = gVTModule;
		var qlikiframe_url = "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeLoadCurrentTabAjax&qlikiframe_modid=" + qlikiframe_modid + "&qlikiframe_mod=" + qlikiframe_module + "&qlikiframe_page=" + qlikiframe_page_type;
	}

	else {
		qlikiframe_params = "&qlikiframe_type=DetailView";
		if (typeof gVTModule != 'undefined' && gVTModule != 'Settings') {
			var qlikiframe_recordid = jQuery('[name="record"]').val();
			qlikiframe_url = "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeAjax&qlikiframe_modid=" + qlikiframe_modid + "&mod=" + qlikiframe_module + "&qlikiframe_page=" + qlikiframe_page_type + "&record=" + qlikiframe_recordid + qlikiframe_params;
		}
	}

	//quando sono qui, qlikiframe_modid ha l'id di un tab con un iframe certamente.
	if (typeof gVTModule != 'undefined' && gVTModule == 'Settings')
		var qlikiframe_url = "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeLoadCurrentTabAjax&qlikiframe_modid=" + qlikiframe_modid + "&qlikiframe_mod=" + qlikiframe_module + "&qlikiframe_page=" + qlikiframe_page_type + qlikiframe_params;

	jQuery.ajax({
		url: qlikiframe_url, async: false,
		success: function (result) {
			result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

			var iframes = JSON.parse(result);
			if (jQuery('#ModuleHomeMatrix').find("div").length > 0)
				jQuery('#ModuleHomeMatrix').find("div").remove();
			//se ci sono stati errori o mancano i dati, mostro cosa non va. (es cancellata l'analisi, non attiva ecc)
			//contenuto iframes:
			//iframes[0] contiene ERROR/CORRECT a seconda che ci siano errori o meno nel recupero delle info dell'iframe
			//iframes[1] contiene array con le info dell'iframe se iframes[0] = CORRECT o il messaggio di errore se iframes[0]=ERROR
			if (iframes[0] == 'ERROR') {
				if (typeof p_qlikiframe_module != 'undefined') {
					var content = '<span id="qlikiframe_content" style="display: table;margin-left: auto;margin-right: auto;" class="detailBlock"> <font style="color:red; font-size:20px;"><b>' + iframes[1] + '</b><br/><br/></font></span>';

					if (typeof gVTModule != 'undefined' && gVTModule == 'Settings' && jQuery('#cfList').length > 0) {
						jQuery('#cfList').find('form').append(content);
					}
					else {
						jQuery('#qlikiframe_content').remove();
						jQuery('#DetailViewWidgets').before(content);
					}
				}
				else {

					var content = jQuery('#ModuleHomeMatrix').html();
					content = '<span id="qlikiframe_content" style="display: table;margin-left: auto;margin-right: auto;"> <font style="color:red; font-size:20px;"><b>' + iframes[1] + '</b><br/><br/></font></span>' + content;
					jQuery('#ModuleHomeMatrix').html(content);
				}
			}
			else {
				//altrimenti disegno l'iframe.
				var qlikiframe_values = iframes[1];
				//qlikiframe_values ovvero iframes[1] composto come segue:
				//qlikiframe_values[0] contiene il nome dell'iframe
				//qlikiframe_values[1] contiene l'url da visualizzare nell'iframe
				//qlikiframe_values[2] contiene bottom/top/--- ovvero il posizionamento dell'iframe ma in questo punto non ci serve
				//qlikiframe_values[3] contiene l'altezza in px, che deve avere l'iframe.
				name = qlikiframe_values[0];
				url = qlikiframe_values[1];
				h = qlikiframe_values[3];

				//QUI
				var url_debug = (iframes[2] == 1) || qlikiframe_values[5] == 1 ? "<a href='" + url + "' target='_blank' >" + url + "</a>" : '';
				if (typeof p_qlikiframe_module != 'undefined') { //sono nel caso della detailview
					content = '<div name="qlikiframe_content_settings" >' + url_debug + '<iframe id="qlikiframe_content" style="width:100%;height:' + h + 'px" src=' + url + '></iframe>';
					if (typeof gVTModule != 'undefined' && gVTModule == 'Settings' && jQuery('#cfList').length > 0) { //in questo caso sono nella Settings.

						jQuery('#cfList').find('form').append(content);
					}
					else {

						if (window.current_theme == 'next22') {
							content += jQuery('#DetailViewBlocksTab').children().children()[0].innerHTML;
							jQuery('#DetailViewBlocksTab').children().children()[0].innerHTML = content;
						} else {
							jQuery('#DetailViewWidgets').before(content);
						}

					}
				}
				else {
					var content = jQuery('#ModuleHomeMatrix').html();
					content = url_debug + '<iframe id="qlikiframe_content" style="width:100%;height:' + h + 'px" src=' + url + '></iframe>' + content;
					jQuery('#ModuleHomeMatrix').html(content);
				}
			}
		}
	});
}

function qlikiframe_del_secondary_table(qlikiframe_type, ind) {
	jQuery.ajax({
		url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeDeleteTabSecondaryAjax&qlikiframe_id=" + ind + "&qlikiframe_type=" + qlikiframe_type, async: false,
		success: function (result) {

		}
	});
}
function qlikiframe_show_iframe_detail(ind) {

}


/********************************************************
 * 														*	
 * 					END FUNCTIONS						*
 * 														*
 ********************************************************/

jQuery(document).ready(function () {
	//FIRST, CONTROL IF MODULE IFRAME IS ACTIVE
	var active = 'No';
	jQuery.ajax({
		url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeActiveAjax", async: false,
		success: function (result) {
			result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

			activeres = JSON.parse(result);
			//activeres[0] contiene si/no a seconda se il modulo iframe attivo e permesso per l'utente
			//activeres[1] contiene si/no a seconda se presente almeno un'analisi attiva di tipo modulo. contiene no se activeres[0]=no
			//activeres[2] contiene si/no a seconda che sia presente almeno un'analisi attiva di qualsiasi tipo. contiene no se activeres[0]=no
			//activeres[3] contiene ##START##
			//activeres[4] contiene ##END## se activeres[0] = no; altrimenti contiene un valore formato come segue: <id_iframe> |##| <nome_iframe>
			//activeres[n] contiene ##END## se activeres[2] = si; fino a n-1, si veda activeres[4]
			active = activeres[0];
			iframe = activeres[1];
			//if(active !="No"){//se torna no, il modulo non attivo oppure non permessi dell'utente al modulo. // spostato sotto solo nei singoli casi in cui non devo aggiungere voci per creazione di widget e tab
			// start -- controllo il caso in cui mi trovo in home
			//-----------------------------------------------------------------
			//-					MODULO = HOME		start					-//
			//-----------------------------------------------------------------
			tot_iframe = activeres[2];
			if (typeof gVTModule != 'undefined' && gVTModule == 'Home') {
				if (active != "No") {//se torna no, il modulo non attivo oppure non permessi dell'utente al modulo. // aggiunto controllo presente in testa al ready
					jQuery('#addWidgetDropDown').find('ul').append('<li><a href="javascript:qlik_chooseType(\'QlikIframe\',\'' + tot_iframe + '\',\'' + activeres[3] + '\',\'' + activeres.indexOf("##END##") + '\');fnRemoveWindow();" class="drop_down" id="addqlikanalysis">Analisi Qlik</a></li>');

					if (window.current_theme == 'next22') {
						jQuery("button[data-toggle='dropdown']").parent().find('ul').append('<li><a href="javascript:qlikiframe_chooseType(\'QlikIframe\',\'' + tot_iframe + '\',\'' + activeres[3] + '\',\'' + activeres.indexOf("##END##") + '\');fnRemoveWindow();" class="" id="addqlikanalysis">Analisi Qlik</a></li>');
					} else {
						jQuery('#Buttons_List_Contestual').find('ul').append('<li><a href="javascript:qlikiframe_chooseType(\'QlikIframe\',\'' + tot_iframe + '\',\'' + activeres[3] + '\',\'' + activeres.indexOf("##END##") + '\');fnRemoveWindow();" class="" id="addqlikanalysis">Analisi Qlik</a></li>');
					}

					//dalla chiamata ajax, ho associazione id-nome che mi permette di creare in modo dinamico le option
					//recupero il pulsante con onclick fnAddWindow
					qlikiframe_obj = jQuery('#Buttons_List_Contestual_Container_Table').find('i[onclick^="fnAddWindow"]');
					if (qlikiframe_obj.length > 0) {
						var qlikiframe_click_add = qlikiframe_obj.attr('onclick');
						var qlikiframe_plus_click = 'jQuery("#addWidgetsDiv").hide(); jQuery("#qlikiframe_addWidgetsDiv").hide();'
						qlikiframe_click_add = qlikiframe_click_add.replace(qlikiframe_click_add, qlikiframe_click_add + qlikiframe_plus_click);
						qlikiframe_obj.attr('onclick', qlikiframe_click_add);
					}

					if (tot_iframe != 'No') {
						qlikiframe_create_home_wizard(); //appende il popup da mostrare in home per aggiungere nuovo widget con analisi qlik
						for (index = 4; index < activeres.indexOf('##END##'); index++) {
							val = activeres[index];
							arr_val = val.split(' |##| ');
							id = arr_val[0];
							name = arr_val[1];
							jQuery('[name="selanalysis"]').append('<option value="' + id + '">' + name + '</option>');
						}
					}
				}// close active !="no"	

				//terminata la creazione della voce e del widget per l'aggiunta di widget per le analisi, 
				//recupero tutti i widget di tipo qlik per l'utente corrente, mi porto fuori solo l'id, perchè le altre info
				//le recupero successivamente con il metodo wloadStuff

				jQuery.ajax({
					url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeGetQlikHomeWidget",
					async: false,
					success: function (result) {
						result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

						homewids = JSON.parse(result);
						test = homewids[0];
						ids = homewids[1]; //ho un array --> ora scorro tutti gli elementi della home, 
						//e controllo se trovo l'id nel mio array
						if (test != 'No') {
							jQuery('div[id^=stuff_]').each(function (index, element) {
								var id_compl = jQuery(this).attr('id');
								var ind = id_compl.replace('stuff_', '');
								if (ids.indexOf(ind) != -1) {

									jQuery(this).find('a#editlink').remove();
									jQuery(this).find('.MatrixBorder').removeClass('MatrixBorder').addClass('MatrixBorderURL');

									jQuery(this).find('.MatrixBorderURL').html("");
									qlikiframeloadStuff(ind);

									var fclick = jQuery(this).find('a[onclick^="loadStuff"]');

									if (fclick.attr('onclick') == undefined || fclick.attr('onclick').indexOf('#qlikiframeloadStuff') == -1) { }
									else {
										var qlikiframe_fun = fclick.attr('onclick');
										qlikiframe_fun = qlikiframe_fun.replace("loadStuff", "wloadStuff");
										fclick.attr('onclick', qlikiframe_fun);
									}
								}
							});
						} //chiude test
					} //chiude function
				});//chiude ajax
			} //chiude caso home
			//-----------------------------------------------------------------
			//-					MODULO = HOME		end 					-//
			//-----------------------------------------------------------------
			// 
			//-----------------------------------------------------------------
			//-					ANALISI DI TIPO MODULO  					-//
			//-----------------------------------------------------------------

			if (iframe != 'No') {
				// spostata creazione della voce di menu sotto dopo il controllo se ci sono risultati di qlikiframe validi
				if (typeof gVTModule != 'undefined') {
					jQuery.ajax({
						url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeModAjax&mod=" + gVTModule,
						success: function (result) {
							result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

							var iframes = JSON.parse(result);
							// start per non mostrare il menu se non ci sono risultati (es, autenticazione non riuscita se di tipo qliksense)

							if (iframes.length > 0) {
								//FOR MENU (put in menu iframes where type = "module")
								//*****************DYNAMIC MENU*****************

								if (jQuery('#vte_main_menu').length > 0) {
									//add parenttab in menu
									jQuery('<li class="dropdown" id="Analisi_Qlik_tab"  onclick="fnShowDropDownW(\'Analisi_Qlik_sub\');"><a style="cursor:pointer;valign:center">Analisi Custom<b class="caret"></b></a></li>').insertAfter(jQuery('#vte_main_menu ul:first li:last'));// start
									//add div with hide_submenu
									jQuery('<div class="drop_mnu" id="Analisi_Qlik_sub" onmouseout="fnHideDrop(\'Analisi_Qlik_sub\')" onmouseover="fnShowDrop(\'Analisi_Qlik_sub\')" style="width: 150px; left: 540px; top: 34px; display: none;"><table width="100%" border="0" cellpadding="0" cellspacing="0"><tbody id="iframe_tbody"></tbody></table></div>').insertBefore(jQuery('#vte_menu div#Preferences_sub'));
								} else {	// se non c'è il menù orizzontale del tema SOFTED


									//add parenttab in menu
									jQuery('<li class="dropdown" id="Analisi_Qlik_tab"  onclick="fnShowDropDownW(\'Analisi_Qlik_sub\');"><a style="cursor:pointer;valign:center">Analisi Custom<b class="caret"></b></a></li>').insertAfter(jQuery('#moduleListContainer ul:first li:last'));// start
									//add div with hide_submenu
									jQuery('<div class="drop_mnu" id="Analisi_Qlik_sub" onmouseout="fnHideDrop(\'Analisi_Qlik_sub\')" onmouseover="fnShowDrop(\'Analisi_Qlik_sub\')" style="width: 150px; left: 540px; top: 34px; display: none;"><table width="100%" border="0" cellpadding="0" cellspacing="0"><tbody id="iframe_tbody"></tbody></table></div>').insertAfter(jQuery('#Analisi_Qlik_tab'));
								}

								//**********************************************
								for (index = 0; index < iframes.length; index++) {

									theOne = iframes[index];
									name = theOne[0];
									url = theOne[1];
									h = theOne[2];
									recordid = theOne[3];
									var url_debug = theOne[4];/* == 1 ? "<a href='" + url + "' target='_blank' >" + url + "</a>" : '';*/
									show_iframeLinkDyn(url.trim(), h, name, recordid, url_debug);
									//show_iframeLink(url.trim(), h ,name,recordid); FOR STATIC MENU
								}
							}
						}
					});
				}
			}//close iframe for module	

			//------------- FOR MENU FINISH -------------//
			//-----------------------------------------------------------------
			//-					CASE: DETAILVIEW							-//
			//-----------------------------------------------------------------
			if (jQuery('#turboLiftRelationsContainer').length) {
				if (typeof gVTModule != 'undefined' && gVTModule != 'Settings') {
					if (active != "No") { // aggiunto controllo se active qui, invece che all'inizio
						var qlikiframe_recordid = jQuery('[name="record"]').val();
						jQuery.ajax({
							url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeAjax&mod=" + gVTModule + "&record=" + qlikiframe_recordid,
							success: function (result) {
								result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

								var iframes = JSON.parse(result);
								for (index = 0; index < iframes.length; index++) {
									theOne = iframes[index];

									name = theOne[0];
									url = theOne[1];
									pos = theOne[2];
									h = theOne[3];
									var url_debug = theOne[5]
									//var url_debug = theOne[5] == 1 ? "<a href='" + url + "' target='_blank' >" + url + "</a>" : '';
									qlikiframe_login_qlik = theOne[4];
									if (pos == "Bottom" || pos == "Top") {
										newdiv = '<div class="turboliftEntry turboliftEntryWithImage" onclick="show_iframeDet(\'' + url.trim() + '\',\'' + h + '\',\'' + pos + '\',\'' + name.replace(/ /g, '') + '\',\'' + url_debug + '\');" style="background-color:#C1F28E;width:100%;height:100%">		<div style="display:table-cell;padding:5px;box-sizing:border-box;width:70%;vertical-align:middle;"><div style="display:table;height:100%;width:100%;"><span style="display:table-cell;vertical-align:middle;">' + name + '</span></div></div></div></div>';
										if (qlikiframe_login_qlik != '' && qlikiframe_login_qlik != null)
											jQuery('#turboLiftRelationsContainer').prepend(newdiv);
									}
									else {
										show_iframeDet(url.trim(), h, pos, name.replace(' ', ''), url_debug);
									}
								}
							}
						});
					} // chiudo active 
					//recupero gli id dei panel definiti con iframe. -- per visualizzazione iframe in tab
					jQuery('#qlikiframe_content').remove();
					jQuery.ajax({
						url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeGetTabWithQlikIframe&qlikiframe_mod=" + gVTModule + "&qlikiframe_type=DetailView",
						success: function (result) {
							result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

							var etabs = JSON.parse(result);
							test = etabs[0]; //contiene Si/No se ci sono o meno tab con iframe per il modulo corrente
							ids = etabs[1]; //ids  = array con elementi gli id dei tab che sono custom collegati al modulo analisi

							//e controllo se trovo l'id nel mio array
							if (test != 'No' && ids.length > 0) {
								//scorro tutte le tab, e per ognuna controllo se l'id contenuto in ids.
								jQuery('#DetailViewTabs').find('td[class*="SelectedCell"]').each(function (index, element) {
									var ind = jQuery(this).attr('data-panelid');
									var curr_click = jQuery(this).attr('onclick');
									curr_click = curr_click + "; jQuery('#qlikiframe_content').remove();";
									//se contenuto l'id del pannello corrente nell'array, sostituisco la funzione onclick impostando quella custom.
									if (ids.indexOf(ind) != -1) {
										curr_click = curr_click + " qlikiframe_load_tab_analysis(" + ind + ",'DetailView','" + gVTModule + "')";
									}

									jQuery(this).attr('onclick', curr_click);
								});
							} //chiude test
						} //chiude function
					});

				} //chiude gVTModule != undefined

			} //close DETAILVIEW
			//-----------------------------------------------------------------
			//-					  CASE: LISTVIEW							-//
			//-----------------------------------------------------------------
			if (jQuery('#ListViewContents').length || jQuery('#ModuleHomeMatrix').length) { // aggiunto controllo su ModuleHomeMatrix per gestione quando ci si trova in una tab della listview
				if (parent.jQuery('[id^="fancybox"]').length == 0) {// aggiunto per evitare di aprire l'iframe nel popup di selezione degli uitype10
					if (typeof gVTModule != 'undefined') {
						if (active != "No") { // aggiunto controllo se active qui, invece che all'inizio
							//recupero tutti gli iframe definiti per listview, attivi e da non mostrare in tab. e li appendo ove definito
							jQuery.ajax({
								url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeAjax2&mod=" + gVTModule,
								success: function (result) {
									result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe
									var iframes = JSON.parse(result);

									for (index = 0; index < iframes.length; index++) {
										theOne = iframes[index];

										name = theOne[0];
										url = theOne[1];
										pos = theOne[2];
										h = theOne[3];
										var url_debug = theOne[4] == 1 ? "<a href='" + url + "' target='_blank' >" + url + "</a>" : '';

										show_iframeList(url.trim(), h, pos, name.replace(' ', ''), url_debug);
									}
								}
							});
						} // chiudo active != "no"
						//devo controllare se l'id della tab corrente corrisponde a un iframe.
						//in caso positivo, devo aggiungere l'iframe.
						//faccio un controllo precedente sulle tab che mi interessano, poi controllo se la tab corrente
						//ha la caratteristica che mi interessa allora vado a creare il frame
						//ora devo recuperare tutti i tab, per il modulo corrente, per la listview che sono definiti per l'utente corrente 

						jQuery.ajax({
							url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeGetTabWithQlikIframe&qlikiframe_mod=" + gVTModule + "&qlikiframe_type=ListView",
							success: function (result) {
								result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe
								var etabs = JSON.parse(result);

								test = etabs[0]; //contiene si/no in base alla presenza di tab definite per analisi qlik per la listview
								ids = etabs[1]; //ids array con elementi gli id dei tab che sono custom collegati al modulo analisi 

								//e controllo se trovo l'id nel mio array
								if (test != 'No' && ids.length > 0) {
									jQuery('td[id^=tdViewTab_]').each(function (index, element) {
										var id_compl = jQuery(this).attr('id');
										var ind = id_compl.replace('tdViewTab_', '');
										if (ids.indexOf(ind) != -1) {
											//la tab ha un'analisi qlik, allora devo ridisegnare il menu impostazioni e cambiare il remove
											//recupero l'oggetto del sottomenu:
											var sub_menu = jQuery('#editModHomeBlocks_' + ind);
											sub_menu.find('a[onclick^="ModuleHome.chooseNewBlock"]').remove();
											var curr_del = sub_menu.find('a').attr("onclick");
											curr_del = curr_del + "; qlikiframe_del_secondary_table('modulehome'," + ind + ");";
											sub_menu.find('a').attr("onclick", curr_del);
										}
									});

									var qlikiframe_modid = jQuery('#modhomeid').val();
									//se l'id del tab corrente ha un iframe, allora vado a costruirlo.
									if (ids.indexOf(qlikiframe_modid) != -1)
										qlikiframe_load_tab_analysis(qlikiframe_modid, "ListView");
								} //chiude test	
							} //chiude function
						});
						if (active != "No") {// aggiunto controllo se modulo attivo che evita la creazione della voce per aggiunta tab con analisi
							//recupero le analisi qlik definite in Tab per la listview (paramentro passato direttemente, in modo da poter usare lo stesso procedimento anche per detailview
							jQuery.ajax({
								url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeGetTabAnalysisAjax&qlikiframe_type=ListView&qlikiframe_mod=" + gVTModule, async: false,
								success: function (result) {
									result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

									tabres = JSON.parse(result);

									//tabres[0] contiene permission/si/no rispettivamente per modulo non attivo/non permesso, ci sono tab per analisi, non ci sono tab per analisi
									//tabres[1] contiene ##START##
									//tabres[2] contiene ##END## se tabres[0] = no oppure permission altrimenti contiene un valore formato come segue: <id_iframe> |##| <nome_iframe>
									//tabres[n] contiene ##END## se tabres[0] = si; fino a n-1, si veda tabres[2]

									qlikiframe_find = tabres[0];
									start = tabres[1];
									if (jQuery('#qlikiframe_add_new_tab_li').length > 0) { }//se già presente, non lo aggiungo.
									else {
										//aggiungo la voce per la creazione di una tab per analisi qlik
										var qlikiframe_new_voice = '<li id="qlikiframe_add_new_tab_li"><a href="javascript:void(0);" onclick="qlikiframe_add_new_tab(\'' + qlikiframe_find + '\',\'' + start + '\',\'' + tabres.indexOf("##END##") + '\')" class="">' + alert_arr.TAB_ANALYSIS + '</a></li>';
										jQuery('#editModHomeViews').find("ul").append(qlikiframe_new_voice);
									}
									if (qlikiframe_find != 'No' || qlikiframe_find != 'permission') { //se esistono analisi e il modulo attivo/permesso
										qlikiframe_draw_list_new_tab("ListView"); //si occupa di creare la maschera per creare la tab
										//crea dinamicamente le opzioni della picklist con l'elenco degli iframe disponibili.
										for (index = 2; index < tabres.indexOf('##END##'); index++) {
											val = tabres[index];
											arr_val = val.split(' |##| ');
											id = arr_val[0];

											name = arr_val[1];
											jQuery('[name="qlikiframe_analisys"]').append('<option value="' + id + '">' + name + '</option>');
										}
									}
								}
							});
						}// chiude active != "no"
					}//chiude gvtmodule !=undefined
				}// chiude controllo se su popup
			}

			//-----------------------------------------------------------------
			//-			  CASE: SETTINGS edit blocks						-//
			//-----------------------------------------------------------------
			if (typeof gVTModule != 'undefined' && gVTModule == 'Settings' && jQuery('#cfList').length > 0) {//sono in Settings e sono dentro al gestore di un modulo, allora mostro la nuova voce

				var qlikiframe_module = jQuery('[name="fld_module"]').val();
				var current_panelid = jQuery('[name="panelid"]').val();
				//controllo in primis se il pannello corrente ha un'analisi qlik
				jQuery.ajax({
					url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeGetTabWithQlikIframe&qlikiframe_mod=" + qlikiframe_module + "&qlikiframe_type=DetailView",
					success: function (result) {
						result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

						var etabs = JSON.parse(result);
						test = etabs[0];
						ids = etabs[1]; //ids array con elementi gli id dei tab che sono custom collegati al modulo analisi
						//e controllo se trovo l'id nel mio array
						if (test != 'No') {
							//se l'id del pannello ha un'analisi qlik: 
							//A: modifico il delete del popup di modifica, dicendo di rimuovere anche dalla tabella di supporto
							//B: carico la preview (vedere se si riesce ad utilizzare il codice fatto apposta per la preview.
							if (ids.indexOf(current_panelid) != -1) {
								var curr_click = jQuery('#editTab').find('[name="delete"]').attr("onclick");
								curr_click = curr_click + "; qlikiframe_del_secondary_table('panels','" + current_panelid + "')";
								jQuery('#editTab').find('[name="delete"]').attr('onclick', curr_click);
								qlikiframe_load_tab_analysis(current_panelid, "DetailView", qlikiframe_module);
							}
						}
					}
				});
				if (active != "No") { // aggiunto controllo su active del modulo
					jQuery.ajax({
						url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeGetTabAnalysisAjax&qlikiframe_type=DetailView&qlikiframe_mod=" + qlikiframe_module, async: false,
						success: function (result) {
							result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

							tabres = JSON.parse(result);
							qlikiframe_find = tabres[0];
							start = tabres[1];
							var qlikiframe_new_function = '<td class="dvtTabCache" nowrap="" align="right">' +
								'<a href="javascript:;" onclick="qlikiframe_add_new_tab(\'' + qlikiframe_find + '\',\'' + start + '\',\'' + tabres.indexOf("##END##") + '\')">' +
								'<i class="vteicon md-link" style="vertical-align:middle" title="Add Analysis tab">add</i>' +
								'<span>' + alert_arr.ADD_TAB_ANALYSIS + '</span>' +
								'</a>' +
								'</td>';
							jQuery('#LayoutEditTabs').find('tbody tr td:last').before(qlikiframe_new_function);
							if (qlikiframe_find != 'No') {
								qlikiframe_draw_list_new_tab("DetailView"); //si occupa di creare la maschera per creare la tab
								for (index = 2; index < tabres.indexOf('##END##'); index++) {
									val = tabres[index];
									arr_val = val.split(' |##| ');
									id = arr_val[0];
									name = arr_val[1];
									jQuery('[name="qlikiframe_analisys"]').append('<option value="' + id + '">' + name + '</option>');
								}
							}
						}
					});
				}// chiudo if active != no	
			}

			//-----------------------------------------------------------------
			//-					  CASE: QLIKIFRAME								-//
			//-----------------------------------------------------------------
			// start -- rivista la logica e accorpate alcune chiamate ajax 
			//CASO EDIT
			if (jQuery('[name="EditView"]').children('input[name="module"]').val() == 'QlikIframe') {
				//if (jQuery('[name="qlikiframe_module_related"]').length >0 || jQuery('[name="qlikiframe_txt_mod_name"]').length >0 ) { //se � presente il campo qlikiframe_module_related o il campo qlikiframe_txt_mod_name 
				var qlikiframe_recordid = jQuery('[name="record"]').val(); // spostato su in modo da non doverlo ripetere + volte
				if (jQuery('[name="qlikiframe_module_related"]').length > 0) {
					jQuery.ajax({
						url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframeModulesAjax&rid=" + qlikiframe_recordid,
						success: function (result) {
							result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

							var modules = JSON.parse(result);

							var mod_sel_k = modules[0][0];
							var mod_sel = modules[0][1];
							var ind_start = 0;
							if (mod_sel_k == 'mod_selected') {
								ind_start = 1;
							}
							for (index = ind_start; index < modules.length; index++) {
								val = modules[index];
								name = val[0];
								label = val[1];
								jQuery('[name="qlikiframe_module_related"]').append('<option value="' + name + '">' + label + '</option>');
								//jQuery('#dtlview_Modulo').text(label); 
							}
							if (mod_sel_k == 'mod_selected') {

								if (mod_sel == 'nothing') mod_sel = '-- Nessuno --';
								jQuery("[name='qlikiframe_module_related'] option[value='" + mod_sel + "']").attr("selected", "selected");
							}
						}
					});
				} //close gestione campo picklist qlikiframe_module_related
			}

			//CASO DETAIL: mostro la preview dell'iframe
			if (jQuery('[name="DetailView"]').children('input[name="module"]').val() == 'QlikIframe') { // per evitare di visualizzarla anche da altre parti, ma solo in detailview
				jQuery('<div id="qlikiframe_iframe_prev"></div>').insertBefore(jQuery('#RelatedLists'));
				var recordid = jQuery('[name="record"]').val();
				jQuery.ajax({
					url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikIframePreviewAjax&record=" + recordid,
					success: function (result) {
						result = result.replace('<!-- stopscrmprint -->', ''); // per evitare che vada in errore e non mostri l'iframe

						var preview = JSON.parse(result);
						for (index = 0; index < preview.length; index++) {
							val = preview[index];
							name = val[0];
							url = val[1];
							pos = val[2];
							h = val[3];
							var url_debug = val[4] == 1 ? "<a href='" + url + "' target='_blank' >" + url + "</a>" : '';
							show_iframePrev(url.trim(), h, pos, name.replace(' ', ''), url_debug);
						}
						// start - aggiunta finezza per mostrare comunque il blocco ma dando un messaggio di preview non disponibile
						if (preview.length == 0) {
							newdiv = '<div id="preview"><table class="small" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="dvInnerHeader"><div style="float:left;font-weight:bold;width:100%;"><div style="float:left;"><b>PREVIEW</b></div><div style="float:right;"><div style="float:right;"></div></div></td></tr></tbody></table></div>';
							jQuery('#qlikiframe_iframe_prev').html(newdiv + "<span align='center'><strong> Preview Not Available</strong></span>");
						}
						// end
					}
				});
			}
			//}//close active!= no  
		}
	});
});