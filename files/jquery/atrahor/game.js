
function loadCSS(url){
    if(document.createStyleSheet) {
        try { document.createStyleSheet(url); } catch (e) { }
    }
    else {
        var css;
        css         = document.createElement('link');
        css.rel     = 'stylesheet';
        css.type    = 'text/css';
        css.media   = "all";
        css.href    = url;
        document.getElementsByTagName("head")[0].appendChild(css);
    }
}
atrajQ(document).ready(function () {
    atrajQ('.nojs').css('display','block');
    loadCSS('./jquery/jquery-ui-1.10.4.custom/css/custom-theme/jquery-ui-1.10.4.custom.min.css');
    loadCSS('./jquery/colorpicker/jquery.minicolors.css');
    loadCSS('./jquery/gradient/plugin/jquery.gradientPicker.css');
    loadCSS('./jquery/gradient/colorpicker/css/colorpicker.css');
    loadCSS('./jquery/jqueryui-editable/css/jqueryui-editable.css');
	loadCSS('./jquery/select2/select2.css');
    loadCSS('./jquery/fullcalendar/fullcalendar.css');
    loadCSS('./jquery/qtip/jquery.qtip.min.css');
    loadCSS('./jquery/confirm/jquery-confirm.min.css');
    loadCSS('./jquery/datetimepicker/jquery.datetimepicker.css');
});

atrajQ(function () {
	atrajQ(".jqueryuserautocompletename").autocomplete({
		source   : "httpreq_autocomplete_name.php",
		minLength: 3
	});
	atrajQ( ".jqui_accordion" ).accordion({
		heightStyle: "content",
		header: "> div > h3",
		collapsible: true
	}).sortable({
		axis: "y",
		handle: "h3",
		stop: function( event, ui ) {
			ui.item.children( "h3" ).triggerHandler( "focusout" );
			atrajQ( this ).accordion( "refresh" );

			var sorting = [];
			atrajQ('.jqui_accordion > div.group').each(function () {
				sorting.push(atrajQ(this).data('tid'));
			});
			var sortJSON = JSON.stringify(sorting);
			atrajQ.post('bathorys_popups.php?mod=todo&ajax=1', { lejson: sortJSON}, function (data) {
				if (data == 'fail') {
					atrajQ("#dialog").html("Speichern fehlgeschlagen. Bitte diese Seite neu laden. Sollte das Problem weiter bestehen, bitte eine Anfrage schreiben. Danke.");
					atrajQ("#dialog").dialog();
				}
			});


		}
	});
	atrajQ( ".jqui_tabs" ).tabs();
    atrajQ('.hex_pick').minicolors({
    });

    atrajQ('.hex_pick_top').minicolors({
        position: 'top left'
    });

	atrajQ('#selecctall').click(function(event) {
		atrajQ('input:checkbox').not(this).prop('checked', this.checked);
		if (typeof chk == 'function') { chk(); }
	});
	atrajQ(".jqueryuserautocompletelogin").autocomplete({
		source   : function (request, response) {
			atrajQ.ajax({
				url     : "httpreq_autocomplete_name.php",
				dataType: "json",
				data    : {
					term: request.term,
					do  : 'login'
				},
				success : function (data) {
					response(data);
				}
			});
		},
		minLength: 3
	});
});
atrajQ(document).ready(function () {
	shortcut.add("Ctrl+S", function () {
		var link = atrajQ("form#rpbioajax").attr('action');
		if (link.length != 0) {
			atrajQ.post(link, atrajQ("form#rpbioajax").serialize(), function (data) {
					if (data == 'done') {
						atrajQ("#ajaxresponse").fadeIn(1).empty().append('<span style="color: #00ff00;">Erfolgreich gespeichert!</span>').fadeOut(3000);
					} else {
						atrajQ("#ajaxresponse").fadeIn(1).empty().append('<span style="color: #ff0000;">Speichern fehlgeschlagen!</span>').fadeOut(15000);
					}
				});
			return false;
		}
	});
	atrajQ("form#rpbioajax").submit(function (event) {
		event.preventDefault();
		atrajQ.post(atrajQ("form#rpbioajax").attr('action'), atrajQ("form#rpbioajax").serialize(), function (data) {
				if (data == 'done') {
					atrajQ("#ajaxresponse").fadeIn(1).empty().append('<span style="color: #00ff00;">Erfolgreich gespeichert!</span>').fadeOut(3000);
				} else {
					atrajQ("#ajaxresponse").fadeIn(1).empty().append('<span style="color: #ff0000;">Speichern fehlgeschlagen!</span>').fadeOut(15000);
				}
			});
	});
	atrajQ(".sortabletabs").nestedSortable({
		disableNesting      : "no-nest",
		forcePlaceholderSize: true,
		handle              : "div",
		helper              : "clone",
		items               : "li",
		maxLevels           : 1,
		opacity             : .6,
		placeholder         : "placeholder",
		revert              : 250,
		tabSize             : 25,
		tolerance           : "pointer",
		toleranceElement    : "> div",
		update              : function (event, ui) {
			var sorting = [];
			atrajQ('.sortabletabs > li').each(function () {
				var lesort = {};
				lesort.id = atrajQ(this).attr('id');
				lesort.subs = [];
				atrajQ(this).find('ol li').each(function () {
					var lesub = {};
					lesub = atrajQ(this).attr('id');
					lesort.subs.push(lesub);
				});
				sorting.push(lesort);
			});
			var sortJSON = JSON.stringify(sorting);
			//alert(sortJSON);
			atrajQ.post('prefs_bio.php?do=ajax&sdo=sorttabssave', { lejson: sortJSON}, function (data) {
					if (data == 'fail') {
						atrajQ("#dialog").html("Speichern fehlgeschlagen. Bitte diese Seite neu laden. Sollte das Problem weiter bestehen, bitte eine Anfrage schreiben. Danke.");
						atrajQ("#dialog").dialog();
					}
				});
		}
	});
	atrajQ(".sortable").nestedSortable({
		disableNesting      : "no-nest",
		forcePlaceholderSize: true,
		handle              : "div",
		helper              : "clone",
		items               : "li",
		maxLevels           : 2,
		opacity             : .6,
		placeholder         : "placeholder",
		revert              : 250,
		tabSize             : 25,
		tolerance           : "pointer",
		toleranceElement    : "> div",
		update              : function (event, ui) {
			var sorting = [];
			atrajQ('.sortable > li').each(function () {
				var lesort = {};
				lesort.id = atrajQ(this).attr('id');
				lesort.subs = [];
				atrajQ(this).find('ol li').each(function () {
					var lesub = {};
					lesub = atrajQ(this).attr('id');
					lesort.subs.push(lesub);
				});
				sorting.push(lesort);
			});
			var sortJSON = JSON.stringify(sorting);
			//alert(sortJSON);
			atrajQ.post('prefs_bio.php?do=ajax&sdo=sortsave', { lejson: sortJSON}, function (data) {
					if (data == 'fail') {
						atrajQ("#dialog").html("Speichern fehlgeschlagen. Bitte diese Seite neu laden. Sollte das Problem weiter bestehen, bitte eine Anfrage schreiben. Danke.");
						atrajQ("#dialog").dialog();
					}
				});
		}
	});
	atrajQ("#newrpbiopage").click(function () {
		atrajQ.post('prefs_bio.php?do=ajax&sdo=newpage', function (data) {
				if (data == 'fail') {
					atrajQ("#dialog").html("Neue Seite laden fehlgeschlagen. Bitte diese Seite neu laden. Sollte das Problem weiter bestehen, bitte eine Anfrage schreiben. Danke.");
					atrajQ("#dialog").dialog();
				} else if (data == 'full') {
					atrajQ("#dialog").html("Du hast bereits die max. Azahl an Seiten. Danke.");
					atrajQ("#dialog").dialog();
				} else {
					var randomnumber = Math.floor(Math.random() * (90000 - 10000 + 1)) + 10000;
					atrajQ(".sortable").append("<li id='" + data + "'><div>Neue Seite" + '<a href="javascript:addsubbiopage(' + data + ',' + randomnumber + ')" class="edit">&nbsp;<img alt="add" title="Unterseite hinzufügen" src="/images/icons/add.gif" />&nbsp;</a>' + '<a href="javascript:delbiopage(' + data + ')" class="edit">&nbsp;<img alt="del" title="löschen" src="/images/icons/del.gif">&nbsp;</a>' + '<a href="prefs_bio.php?do=edit&amp;sdo=edit&amp;p=' + data + '" class="edit">&nbsp;<img alt="edit" title="bearbeiten" src="/images/icons/edit.gif">&nbsp;</a>' + '<a href="prefs_bio.php?do=deak&amp;p=' + data + '" class="edit">&nbsp;<img alt="deakt" title="deaktivieren" src="/images/icons/invisible.gif">&nbsp;</a>' + "</div>" + "<ol id='ober" + randomnumber + "'></ol>" + "</li>");
				}
			});
	});
});
function addsubbiopage(id, ober) {
	atrajQ.post('prefs_bio.php?do=ajax&sdo=newpage&p=' + id, function (data) {
			if (data == 'fail') {
				atrajQ("#dialog").html("Neue Seite laden fehlgeschlagen. Bitte diese Seite neu laden. Sollte das Problem weiter bestehen, bitte eine Anfrage schreiben. Danke.");
				atrajQ("#dialog").dialog();
			} else if (data == 'full') {
				atrajQ("#dialog").html("Du hast bereits die max. Azahl an Seiten. Danke.");
				atrajQ("#dialog").dialog();
			} else {
				atrajQ("#ober" + ober).append("<li id='" + data + "'><div>Neue Seite" + '<a href="javascript:delbiopage(' + data + ')" class="edit">&nbsp;<img alt="del" title="löschen" src="/images/icons/del.gif">&nbsp;</a>' + '<a href="prefs_bio.php?do=edit&amp;sdo=edit&amp;p=' + data + '" class="edit">&nbsp;<img alt="edit" title="bearbeiten" src="/images/icons/edit.gif">&nbsp;</a>' + '<a href="prefs_bio.php?do=deak&amp;p=' + data + '" class="edit">&nbsp;<img alt="deakt" title="deaktivieren" src="/images/icons/invisible.gif">&nbsp;</a>' + "</div></li>");
			}
		});
}
function delbiopage(id) {
	atrajQ.post('prefs_bio.php?do=ajax&sdo=del&p=' + id, function (data) {
			atrajQ("#" + id).remove();
		});
}
if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function (searchElement /*, fromIndex */) {
		"use strict";
		if (this == null) {
			throw new TypeError();
		}
		var t = Object(this);
		var len = t.length >>> 0;
		if (len === 0) {
			return -1;
		}
		var n = 0;
		if (arguments.length > 1) {
			n = Number(arguments[1]);
			if (n != n) { // shortcut for verifying if it's NaN
				n = 0;
			} else if (n != 0 && n != Infinity && n != -Infinity) {
				n = (n > 0 || -1) * Math.floor(Math.abs(n));
			}
		}
		if (n >= len) {
			return -1;
		}
		var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
		for (; k < len; k++) {
			if (k in t && t[k] === searchElement) {
				return k;
			}
		}
		return -1;
	}
}


