var parseconfig = [];
var chatconfig = [];
var c_chat_id = -1;
var c_chatconfig = [];
var c_writeas = 0;
var c_chatsubmit = false;
var c_edit = false;

var chatsubmit = false;
var writeas = 0;
var last = 0;
var edit = false;
var comscroll = 0;
var comperpage = 25;
var newChat = 0;
var focus = 1;
var secs = -999;
var inlinedit = false;
var lastcolor = null;
var nestedtags = [];
function dialog(msg) {
	var dial = atrajQ("#dialog-message");
	dial.html(msg);
	dial.dialog({
		modal  : false,
		buttons: {
			Ok: function () {
				atrajQ(this).dialog("close");
			}
		}
	});
}
function smallrefresh() {
	atrajQ("[data-tooltip]").tooltip();
    atrajQ('#rpchat .button').button();
    atrajQ('.c_rpchat .button').button();
}
function refresh(data) {
	if (parseconfig.length == 0) {
        atrajQ.ajax({
            type: "POST",
            url: 'httpreq_chat.php?do=parseconfig',
            success: function (data) {
                parseconfig = data;
            },
            dataType: 'json'
        });
	}
	chatconfig = [];

    c_chat_id = -1;
    c_chatconfig = [];
    c_writeas = 0;
    c_chatsubmit = false;
    c_edit = false;

	last = 0;
	edit = false;
	writeas = 0;
	chatsubmit = false;
	comscroll = 0;
	newChat = 0;
	focus = 1;
	smallrefresh();
}
function closetags(string, tags) {
	atrajQ.each(tags, function (i, val) {
		if ((string.split('`' + val).length - 1) % 2) {
			string += '`' + val;
		}
	});
	return string;
}
function parse(text) {
    lastcolor = null;
	text = closetags(text, ['c','b','i']);
	text = text.replace(new RegExp("³[^<>³]+³", "g"), do_verlauf_parse);
    text = text.replace(new RegExp("`(.{1})|²(#[a-fA-F0-9]{6};)|²(#[a-fA-F0-9]{3};)", "g"), do_appo_parse);
	return '<span>' + text + '</span>';
}
function hexdec(h) {
	return parseInt(h, 16);
}
function do_appo_parse(str) {
    var out = "";
    switch(str[1]){
        case '0':{
            if('color' in nestedtags){
                out = '</span>';
                delete nestedtags.color;
            }
            lastcolor = null;
        }break;
        case '`': out = "`";break;
        case '>': out = ">";break;
        case '<': out = "<";break;
        case ' ': out = " ";break;
        default:
        {
            var tag = str[1];
            var type = str[0];
            var a = parseconfig['a'];
            if(type == '²' || (tag in a && a[tag]['color'] != null && a[tag]['color'] != '' && a[tag]['color'] != undefined) ){
                if('color' in nestedtags){
                    out += '</span>';
                }else{
                    nestedtags['color']=true;
                }
                var tout = "";
                if(type == '²'){
                    tout = '<span style="color:' + str.substr(1) + '">';
                }else{
                    tout = '<span style="color:#' + a[tag]['color'] + '">';
                }
                out += tout;
                lastcolor = tout;
            }else{
                var tagrow = a[tag];
                if(lastcolor != null){
                    out+='</span>';
                }
                if (tagrow['tag'] in nestedtags && (tagrow['tag'].indexOf(" /")==-1)) {
                    out+='</' + tagrow['tag'] + '>';
                    delete nestedtags[tagrow['tag']];
                } else if (tagrow['tag'].indexOf(" /")!=-1) {
                    out+='<' + tagrow['tag'] + ">\n";
                } else {
                    out+='<' + tagrow['tag'] + ' ' + tagrow['style'] + '>';
                    nestedtags[tagrow['tag']] = true;
                }
                if(lastcolor != null){
                    out+=lastcolor;
                }
            }
        } break;
    }
    return out;
}
function do_verlauf_parse(str) {
	str = str.toString().replace(new RegExp("²", "g"), "").replace(new RegExp("³", "g"), "");
	var matches = str.match(new RegExp("(`([" + parseconfig.regex + "]{1})|#([a-fA-F0-9]{3,6});)([^`#]*)", "g"));
	if (matches) {
		str = "";
		var count = matches.length - 1;
		atrajQ.each(matches, function (i, val) {
			matches[i] = val.replace(new RegExp("`([" + parseconfig.regex + "]{1})(.*)", "g"), function (match, p1, p2) {
				return "#" + parseconfig["m_hexcol"][p1] + ";" + p2;
			});
		});
		atrajQ.each(matches, function (i, val) {
			var nextcolor = null;
			if ((i + 1) <= count) {
				nextcolor = matches[i + 1].replace(new RegExp("#([a-fA-F0-9]{3,6});(.*)", "g"), function (match, p1, p2) {
					return p1;
				});
			}
			matches[i] = val.replace(new RegExp("#([a-fA-F0-9]{3,6});(.*)", "g"), function (match, p1, p2) {
				if (nextcolor == null) nextcolor = p1;
				str += calc_verlauf(p2, p1, nextcolor, false, ( (i == 0) ? 'first' : ( (i == (count-1)) ? 'last' : '')));
				return '';
			});
		});
	}
	return str;
}
function calc_verlauf(text, color1, color2, offset, type) {
	var r1, b1, g1, r2, b2, g2;
	var len1 = color1.length;
	var len2 = color2.length;
	var steps = text.length;
	if (len1 == 3) {
		r1 = hexdec(color1.substr(0, 1) + color1.substr(0, 1));
		g1 = hexdec(color1.substr(1, 1) + color1.substr(1, 1));
		b1 = hexdec(color1.substr(2, 1) + color1.substr(2, 1));
	} else {
		r1 = hexdec(color1.substr(0, 2));
		g1 = hexdec(color1.substr(2, 2));
		b1 = hexdec(color1.substr(4, 2));
	}
	if (len2 == 3) {
		r2 = hexdec(color2.substr(0, 1) + color2.substr(0, 1));
		g2 = hexdec(color2.substr(1, 1) + color2.substr(1, 1));
		b2 = hexdec(color2.substr(2, 1) + color2.substr(2, 1));
	} else {
		r2 = hexdec(color2.substr(0, 2));
		g2 = hexdec(color2.substr(2, 2));
		b2 = hexdec(color2.substr(4, 2));
	}
	var diff_r = r2 - r1;
	var diff_g = g2 - g1;
	var diff_b = b2 - b1;
	var str = "";
	var ct = (type=='first' || type=='last') ? steps : (steps+1);
	if (ct == 0)ct = 1;
	for (var i = 0; i < steps; i++) {
		var factor = ( (type=='first') ? (i) : (i+1) ) / ct;
		var r = Math.round(r1 + diff_r * factor);
		var g = Math.round(g1 + diff_g * factor);
		var b = Math.round(b1 + diff_b * factor);
		var color = "color:rgb(" + r + "," + g + "," + b + ");";
		str += "<span style='" + color + "'>" + text.charAt(i) + "</span>";
	}
	return str;
}
function parseChat(str) {
	if (str.length == 0) return "";
	var cache = str;
    var usecchat = (c_chat_id > -1);
	var sa = usecchat ? c_chatconfig : chatconfig;
	var name = sa.name;
	var max = sa.max;
	str = (str + "").replace(/</g, "&lt;");
	str = (str + "").replace(/>/g, "&gt;");
	str = (str + "").replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, "$1" + "<br>" + "$2");
	if (str.length > 40) {
		//str = str.replace(/([\S]{20,39})([\S]{20,39})/g, "$1 $2");
		str = str.substr(0, max);
	}
	if (str != "") {
		atrajQ.each(sa.shortcodes, function (index, value) {
			var re = new RegExp(index, "g");
			str = str.replace(re, value);
		});
		atrajQ.each(sa.emotes, function (index, emote) {
			var regex = new RegExp("^" + emote.regex.replace(/\(\.\*\)/g, "([\\s\\S]*)"), "m");
			var match = str.match(regex);
			if (match) {
				if (emote.right != 0 && !sa.m_rights[emote.right]) {
					str = ": " + str.substr(emote.lgt);
				} else {
					cache = "`0`&" + emote.parse;
					var em_name = emote.name;
                    var em_must = emote.must;
                    var em_type = emote.type;
					atrajQ.each(match, function (index2, value2) {
						if (index2 > 0) {
							var regM = new RegExp("<\\$m" + index2 + ">", "g");
							cache = cache.replace(regM, value2);
							em_must = em_must.replace(regM, value2);
                            em_name = em_name.replace(regM, value2);
                            em_type = em_type.replace(regM, value2);
						}
					});
					if (em_must != "" && !sa[em_must]) {
						str = ": " + str.substr(emote.lgt);
					} else {
						atrajQ.each(sa, function (index3, value3) {
							var re = new RegExp("<" + index3 + ">", "g");
							cache = cache.replace(re, value3);
						});
                        //autocol
                        if(!sa['noccol']){
                            cbeg = sa[em_type+'cbeg'];
                            cend = sa[em_type+'cend'];
                            cbeg = (cbeg + "").replace(/</g, "&lt;");
                            cend = (cend + "").replace(/>/g, "&gt;");
                            cbeg_s = sa['simccol'] ? '"' : sa[em_type+'cbeg_s'];
                            cend_s = sa['simccol'] ? '"' : sa[em_type+'cend_s'];
                            cbeg_s = (cbeg_s + "").replace(/</g, "&lt;");
                            cend_s = (cend_s + "").replace(/>/g, "&gt;");
                            ecol = sa[em_type+'ecol'];
                            tcol = sa[em_type+'tcol'];
                            cout = sa[em_type+'cout'];
                            ccol = sa[em_type+'ccol'];
                            cache = cache.replace(new RegExp(cbeg_s.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1")+"(.*?)"+cend_s.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1"), "g"), function (match, p1) {
                                if(false){//cache.match(new RegExp("(`[^"+ecol.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1")+tcol.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1")+"bciHn0]{1}|²#[a-fA-F0-9]{6};|²#[a-fA-F0-9]{3};)\\s*"+match.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1"), "g")) || cache.match(new RegExp("³[^³]*"+match.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1")+"[^³]*³", "g"))){
                                    return sa['simccol'] ? cbeg+p1+cend : match;
                                }else{
                                    if(!cout && ccol){
                                        return ecol+cbeg+tcol+p1+ecol+cend+ecol;
                                    }else if (cout){
                                        return tcol+p1+ecol;
                                    } else{
                                        return tcol+cbeg+tcol+p1+tcol+cend+ecol;
                                    }
                                }
                            });
                            cache = closetags(cache, ['c','b','i']);
                        }
						name = (emote.issa == 0) ? em_name : sa[em_name];
						return false;
					}
				}
			}
		});
	}
	return parse(name + cache);
}
function loadChatConfig() {
    var usecchat = (c_chat_id > -1);
    if(usecchat){
        if (c_chatconfig.length == 0) {
            atrajQ.ajax({
                type: "POST",
                url: 'httpreq_chat.php?do=chatconfig&was='+c_writeas+'&cc='+c_chat_id,
                success: function (data) {
                    c_chatconfig = data;
                    atrajQ("#c_"+c_chat_id+"_chat_text_preview").html(parseChat(atrajQ('#c_'+c_chat_id+'_chat_text').val()));
                },
                dataType: 'json'
            });
        } else {
            atrajQ("#c_"+c_chat_id+"_chat_text_preview").html(parseChat(atrajQ('#c_'+c_chat_id+'_chat_text').val()));
        }
    }else{
        if (chatconfig.length == 0) {
            atrajQ.ajax({
                type: "POST",
                url: 'httpreq_chat.php?do=chatconfig&was='+writeas,
                success: function (data) {
                    chatconfig = data;
                    comperpage = data.comperpage;
                    // atrajQ("#chat_text_preview_hidden").show("normal");
                    atrajQ("#chat_text_preview").html(parseChat(atrajQ('#chat_text').val()));
                },
                dataType: 'json'
            });
        } else {
            // atrajQ("#chat_text_preview_hidden").show("normal");
            atrajQ("#chat_text_preview").html(parseChat(atrajQ('#chat_text').val()));
        }
    }
}
function editstop() {
    var usecchat = (c_chat_id > -1);
    if(usecchat){
        c_edit = false;
        atrajQ('#c_'+c_chat_id+'_chat_edit').val('Letzten Post editieren');
    }else{
        edit = false;
        atrajQ('#chat_edit').val('Letzten Post editieren (Strg+E)');
    }
}
function editit() {
    var usecchat = (c_chat_id > -1);
    if(usecchat){
        var area = atrajQ('#c_'+c_chat_id+'_chat_text');
        if (c_edit) {
            editstop();
            area.val('');
            atrajQ("#c_"+c_chat_id+"_chat_text_preview").html('');
        } else {
            if (area.val().length == 0) {
                atrajQ.post("httpreq_chat.php?cc="+c_chat_id, {do: "edit_get"}, function (data) {
                    if (data.length) {
                        area.val(data);
                        c_edit = true;
                        atrajQ('#c_'+c_chat_id+'_chat_edit').val('Editieren abbrechen...');
                        area.focus();
                        loadChatConfig();
                    } else {
                        dialog('Kein Beitrag zum editieren gefunden!');
                    }
                });
            } else {
                dialog('Textfeld muss leer sein um Datenverlust zu vermeiden!');
            }
        }

    }else{
        var area = atrajQ('#chat_text');
        if (edit) {
            editstop();
            area.val('');
            atrajQ("#chat_text_preview").html('');
        } else {
            if (area.val().length == 0) {
                atrajQ.post("httpreq_chat.php", {do: "edit_get"}, function (data) {
                    if (data.length) {
                        area.val(data);
                        edit = true;
                        atrajQ('#chat_edit').val('Editieren abbrechen... (Strg+E)');
                        area.focus();
                        loadChatConfig();
                    } else {
                        dialog('Kein Beitrag zum editieren gefunden!');
                    }
                });
            } else {
                dialog('Textfeld muss leer sein um Datenverlust zu vermeiden!');
            }
        }
    }
}
function setCookie(c_name, value, exdays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value = encodeURI(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
	document.cookie = c_name + "=" + c_value;
}
function getCookie(c_name) {
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1) {
		c_start = c_value.indexOf(c_name + "=");
	}
	if (c_start == -1) {
		c_value = null;
	} else {
		c_start = c_value.indexOf("=", c_start) + 1;
		var c_end = c_value.indexOf(";", c_start);
		if (c_end == -1) {
			c_end = c_value.length;
		}
		c_value = decodeURI(c_value.substring(c_start, c_end));
	}
	return c_value;
}
function setdata(name, data) {
	try {
		localStorage.setItem(name, data);
	} catch (e) {
		setCookie(name, data, 5);
	}
}
function getdata(name) {
	try {
		return localStorage.getItem(name);
	} catch (e) {
		return getCookie(name);
	}
}
function saveChat() {
    var usecchat = (c_chat_id > -1);
    if(usecchat){
        var area = atrajQ('#c_'+c_chat_id+'_chat_text');
        if (area.val().length) {
            pd = {};
            pd.chat_text = area.val();
            pd.do = "chatsave";
            pd.cc = c_chat_id;
            if (c_edit) {
                pd.edit = 'true';
            }
            pd.was = c_writeas;
            atrajQ.post("httpreq_chat.php", pd, function (data) {
                if (data == 'done') {
                    editstop();
                    area.val('');
                    loadChatConfig();
                    atrajQ('#c_'+c_chat_id+'_chat_rest').html('');
                    refreshCChat();
                } else {
                    dialog(data);
                }
            });
        }
    }else{
        var area = atrajQ('#chat_text');
        if (area.val().length) {
            pd = {};
            pd.chat_text = area.val();
            pd.do = "chatsave";
            if (edit) {
                pd.edit = 'true';
            }
            pd.was = writeas;
            atrajQ.post("httpreq_chat.php", pd, function (data) {
                if(data.substr(0,4) == '/go '){
                    editstop();
                    area.val('');
                    last = 0;
                    atrajQ('#chat_rest').html('');
                    window.location.href = data.substr(4);
                }
                else if (data == 'done') {
                    editstop();
                    area.val('');
                    last = 0;
                    loadChatConfig();
                    atrajQ('#chat_rest').html('');
                    if (comscroll == 0) {
                        refreshChat();
                    } else {
                        comscroll = 0;
                        refreshChatFull();
                    }
                } else {
                    dialog(data);
                }
            });
        }
    }
}
var timeoutId = undefined;
var original = document.title;
var newMsg = '';
function flashTitle(newM) {
	newMsg = newM;
	if (newMsg == false) {
		clearTimeout(timeoutId);
		document.title = original;
	} else {
		timeoutId = setTimeout(flashTitlecallback, 1000);
	}
}
function flashTitlecallback() {
	clearTimeout(timeoutId);
	document.title = (document.title == original) ? newMsg : original;
	timeoutId = setTimeout(flashTitlecallback, 1000);
}
function refreshCounter(){
    var area = atrajQ('#chat_newday');
    var timea = atrajQ('#time');
    var c_area = atrajQ('#c_chat_newday');
    if(c_area.length){
        if(secs == -999){
            secs = c_area.html();
        }
        var h = Math.floor(secs / 3600);
        var d = secs % 3600;
        var m = Math.floor(d / 60);
        var s = Math.ceil(d % 60);
        var col = '';
        if(secs < 120)col = '`$';
        atrajQ(".c_chat_newday").each(function() {
            if(secs>0)atrajQ(this).html(parse(col+'Nächster Tag: '+h+'h, '+m+'m, '+s+'s'));
            else atrajQ(this).html(parse('`$Neuer Tag!'));
        });
        secs--;
    }else if(area.length){
        if(secs == -999){
            secs = area.html();
            area.show();
            timea.show();
        }
        var h = Math.floor(secs / 3600);
        var d = secs % 3600;
        var m = Math.floor(d / 60);
        var s = Math.ceil(d % 60);
        var col = '';
        if(secs < 120)col = '`$';
        if(secs>0)area.html(parse(col+'Nächster Tag: '+h+'h, '+m+'m, '+s+'s'));
        else area.html(parse('`$Neuer Tag!'));
        if(secs>0)timea.html(parse(col+''+h+'h, '+m+'m, '+s+'s'));
        else timea.html(parse('`$Neuer Tag!'));
        secs--;
    }else{
        if(secs == -999){
            secs = timea.html();
            timea.show();
        }
        var h = Math.floor(secs / 3600);
        var d = secs % 3600;
        var m = Math.floor(d / 60);
        var s = Math.ceil(d % 60);
        var col = '';
        if(secs < 120)col = '`$';
        if(secs>0)timea.html(parse(col+''+h+'h, '+m+'m, '+s+'s'));
        else timea.html(parse('`$Neuer Tag!'));
        secs--;
    }
}

function refreshChat() {
	var area = atrajQ('#chat_text');
	var typing = 0;
    var lokal_new = 0;
	if (area.length && area.val().length != last) {
		last = area.val().length;
		typing = 1;
	}
    atrajQ.post("httpreq_chat.php", {do: "chatpage", typer: typing, was: writeas}, function (data) {

        atrajQ("#ool_status_div").html(data.ool_status);
        if (area.length)
        {
            atrajQ("#show_online_on_location").html(data.ool);
            if (data.newmail == 1) {
                atrajQ('#chat_newmail').show().fadeIn(1000);
            }
            if (comscroll == 0) {
                atrajQ.each(data.posts.new, function (i, val) {
                    atrajQ(val).hide().appendTo("#chat_page0").fadeIn(1000);
                    if (atrajQ('.chathovermenu').length > comperpage) {
                        atrajQ('#chat_page0').find('div').first().remove();
                    }
                    newChat++;
                });
            }else{
                atrajQ.each(data.posts.new, function (i, val) {
                    newChat++;
                    lokal_new++;
                });
            }
            if (newChat > 0 && focus == 0) {
                flashTitle(false);
                flashTitle(newChat + " neue" + ( (newChat == 1) ? "r" : "" ) + " " + ( (newChat == 1) ? "Beitrag" : "Beiträge" ) + "!");
            }
            atrajQ.each(data.posts.edited, function (i, val) {
                atrajQ("#comment" + i).hide().html(val).fadeIn(1000);
            });
            if (lokal_new > 0 && comscroll > 0) {
                refreshChatFull();
            } else {
                smallrefresh();
            }
        }
        if(data.newday){
            window.location.href = 'newday.php';
        }
    }, "json");
}
function refreshCChat() {
    atrajQ.post("httpreq_chat.php", {do: "cchatposts"}, function (data) {
        atrajQ.each(data, function (i, val) {
            atrajQ('#c_'+i+'_coms').html(val);
            atrajQ('#c_'+i+'_v_area').show();
            atrajQ('#c_'+i+'_v_coms').show();
            atrajQ('#c_'+i+'_v_read').show();
        });
        smallrefresh();
    },'json'
);
}
function refreshChatFull() {
	atrajQ.post("httpreq_chat.php", {do: "chatpagefull", coms: comscroll}, function (data) {
		atrajQ('#chat_out').hide().html(data).fadeIn(1000);
		smallrefresh();
	});
}
function loadEditConfig() {
    if (chatconfig.length == 0) {
        var writedt = atrajQ('#chat_edit_was').val();
        atrajQ.ajax({
            type: "POST",
            url: 'httpreq_chat.php?do=chatconfig&was='+writedt,
            success: function (data) {
                chatconfig = data;
                atrajQ("#chat_edit_preview").html(parseChat(atrajQ('#chat_text_edit').val()));
            },
            dataType: 'json'
        });
    } else {
        atrajQ("#chat_edit_preview").html(parseChat(atrajQ('#chat_text_edit').val()));
    }
}
atrajQ(document).ready(function () {
	refresh(false);

    atrajQ(document).on("click", ".c_rpchat_link", function (event) {
        event.preventDefault();
        event.stopPropagation();
        var link = atrajQ(this);
        c_chat_id = link.data('cc');
        c_chatconfig = [];
        c_writeas = 0;
        c_chatsubmit = false;
        c_edit = false;
        loadChatConfig();
        atrajQ(".c_chat_box").hide();
        atrajQ(".c_rpchat_link").show();
        link.hide();
        var box = atrajQ("#c_"+c_chat_id+"_chat_box");
        box.show();
    });
    atrajQ(document).on("keydown", ".c_chat_text", function (event) {
        if(atrajQ(this).val().length > 0)setdata('c_recover'+c_chat_id, atrajQ(this).val());
        var area = atrajQ(this);
        atrajQ('#c_'+c_chat_id+'_chat_rest').html(' (Noch ' + (area.attr("maxlength") - area.val().length) + ' Zeichen übrig) ');
    });
    atrajQ(document).on("keyup", ".c_chat_text", function (event) {
        if(atrajQ(this).val().length > 0)setdata('c_recover'+c_chat_id, atrajQ(this).val());
        var area = atrajQ(this);
        atrajQ('#c_'+c_chat_id+'_chat_rest').html(' (Noch ' + (area.attr("maxlength") - area.val().length) + ' Zeichen übrig) ');
        loadChatConfig();
    });
    atrajQ(document).on("focusout", ".c_chat_text", function (event) {
        if (atrajQ(this).val().length == 0) {
            atrajQ('#c_'+c_chat_id+'_chat_rest').html('');
        }
        c_chatsubmit = false;
    });
    atrajQ(document).on("focusin", ".c_chat_text", function (event) {
        loadChatConfig();
        c_chatsubmit = true;
        var area = atrajQ('#c_'+c_chat_id+'_chat_text');
        atrajQ('#c_'+c_chat_id+'_chat_rest').html(' (Noch ' + (area.attr("maxlength") - area.val().length) + ' Zeichen übrig) ');
    });
    atrajQ(document).on("click", ".c_chat_recover", function (event) {
        var area = atrajQ('#c_'+c_chat_id+'_chat_text');
        if (area.val().length == 0) {
            data = getdata('c_recover'+c_chat_id);
            editstop();
            area.val(data);
            area.focus();
            loadChatConfig();
        } else {
            dialog('Textfeld muss leer sein um Datenverlust zu vermeiden!');
        }
    });
    atrajQ(document).on("change", ".c_chat_was", function (event) {
        c_writeas = atrajQ(this).val();
        c_chatconfig = [];
        loadChatConfig();
    });
    atrajQ(document).on("click", ".c_chat_edit", function (event) {
        editit();
    });
    atrajQ(document).on("click", ".c_chat_comsend", function (event) {
        event.preventDefault();
        saveChat();
        return false;
    });
    atrajQ(document).on("submit", ".c_rpchat", function (event) {
        event.preventDefault();
        saveChat();
        return false;
    });

    if (atrajQ("#chat_out").length) {
       comperpage = atrajQ('#comperpage').val();
    }
	atrajQ(window).on('focus', function (e) {
		focus = 1;
		newChat = 0;
		flashTitle(false);
	});
	atrajQ(window).on('blur', function (e) {
		focus = 0;
		newChat = 0;
		flashTitle(false);
	});
    atrajQ(document).on("keydown", "#chat_text", function (event) {
        if(atrajQ(this).val().length > 0)setdata('recover', atrajQ(this).val());
        var area = atrajQ(this);
        atrajQ('#chat_rest').html(' (Noch ' + (area.attr("maxlength") - area.val().length) + ' Zeichen übrig) ');
    });
    atrajQ(document).on("keyup", "#chat_text", function (event) {
        if(atrajQ(this).val().length > 0)setdata('recover', atrajQ(this).val());
        var area = atrajQ(this);
        atrajQ('#chat_rest').html(' (Noch ' + (area.attr("maxlength") - area.val().length) + ' Zeichen übrig) ');
        loadChatConfig();
    });
    atrajQ(document).on("focusout", "#chat_text", function (event) {
        if (atrajQ(this).val().length == 0) {
            // atrajQ("#chat_text_preview_hidden").hide("normal");
            atrajQ('#chat_rest').html('');
        }

        chatsubmit = false;
    });
    atrajQ(document).on("focusin", "#chat_text", function (event) {
        c_chat_id = -1;
        c_chatconfig = [];
        c_writeas = 0;
        c_chatsubmit = false;
        c_edit = false;
        loadChatConfig();
        chatsubmit = true;
        var area = atrajQ('#chat_text');
        atrajQ('#chat_rest').html(' (Noch ' + (area.attr("maxlength") - area.val().length) + ' Zeichen übrig) ');
    });
    atrajQ(document).on("click", "#chat_recover", function (event) {
        var area = atrajQ('#chat_text');
        if (area.val().length == 0) {
            data = getdata('recover');
            editstop();
            area.val(data);
            last = area.val().length;
            area.focus();
            loadChatConfig();
        } else {
            dialog('Textfeld muss leer sein um Datenverlust zu vermeiden!');
        }
    });
    atrajQ(document).on("change", "#chat_was", function (event) {
        writeas = atrajQ(this).val();
        chatconfig = [];
        loadChatConfig();
    });
    atrajQ(document).on("click", "#chat_edit", function (event) {
        editit();
    });
    atrajQ(document).on("click", "#chat_comsend", function (event) {
		event.preventDefault();
		saveChat();
		return false;
	});
	atrajQ(document).on("submit", "#rpchat", function (event) {
		event.preventDefault();
		saveChat();
		return false;
	});
    atrajQ(document).on("click", "#chat_abo", function (event) {
        atrajQ.post("httpreq_chat.php", {do: "abo"}, function (data) {
        });
        if (atrajQ(this).val() == 'Abo ein!') {
            atrajQ(this).val('Abo aus!')
        } else {
            atrajQ(this).val('Abo ein!')
        }
    });
    atrajQ(document).on("click", "#chat_newmail", function (event) {
        atrajQ(this).hide();
    });
    atrajQ(document).on("click", "#userbarmail", function (event) {
        atrajQ("#chat_newmail").hide();
    });
    atrajQ(document).on("change", "#comperpage", function (event) {
        comperpage = atrajQ(this).val();
        atrajQ.post("httpreq_chat.php", {do: "comperpage", com: comperpage}, function (data) {
            comscroll = 0;
            refreshChatFull();
        });
    });
    atrajQ(document).on("click", "#chat_nonrpg", function (event) {
        atrajQ.post("httpreq_chat.php", {do: "nonrpg"}, function (data) {
            refreshChatFull();
        });
        if (atrajQ(this).val() == 'NichtRPG ein!') {
            atrajQ(this).val('NichtRPG aus!')
        } else {
            atrajQ(this).val('NichtRPG ein!')
        }
    });
    shortcut.add("Ctrl+E", function () {
        editit();
    });
    shortcut.add("Ctrl+Enter", function () {
        if (chatsubmit) {
            saveChat();
            last = 0;
            return false;
        }
        last = 0;
        return false;
    });
    atrajQ(document).on("click touchstart", "#ool_status", function (event) {
        event.preventDefault();
        event.stopPropagation();

        var link = atrajQ(this);
        atrajQ.post("httpreq_chat.php", {do: "oolmenu"}, function (data) {
            atrajQ("#ajax_temp").html(data);
            var menu = atrajQ("ul#menu");
            menu.css('display', 'block');
            menu.css('position', 'absolute');
            var offset;
            if (event.pageY == undefined || event.pageX == undefined) {
                offset = link.position();
                offset.top += 13;
            } else {
                offset = {top: event.pageY + 12, left: event.pageX - 75};
                var bottom = atrajQ(window).scrollTop() + atrajQ(window).height(), right = atrajQ(window).scrollLeft() + atrajQ(window).width(), height = menu.height(), width = menu.width();
                if (offset.top + height > bottom) {
                    offset.top -= height - 2;
                }
                if (offset.left + width > right) {
                    offset.left -= width;
                }
            }
            menu.css(offset);
            menu.css('display', 'none');
            menu.width(150).menu().show("normal");
            atrajQ(document).one("click", function () {
                menu.hide("normal");
            });
        });
        return true;
    });
    atrajQ(document).on("mouseover", "#ool_status", function (event) {
        atrajQ(this).css("cursor", "pointer");
    });
	atrajQ(document).on("mouseover", "#coms_m", function (event) {
		atrajQ(this).css("cursor", "pointer");
	});
	atrajQ(document).on("mouseover", "#coms_f", function (event) {
		atrajQ(this).css("cursor", "pointer");
	});
	atrajQ(document).on("mouseover", "#coms_p", function (event) {
		atrajQ(this).css("cursor", "pointer");
	});
	atrajQ(document).on("click touchstart", "#coms_m", function (event) {
		comscroll--;
		if (comscroll <= 0) {
			comscroll = 0;
			atrajQ(this).hide();
			atrajQ("#coms_f").hide();
		}
		refreshChatFull();
	});
	atrajQ(document).on("click touchstart", "#coms_f", function (event) {
		comscroll = 0;
		atrajQ(this).hide();
		atrajQ("#coms_m").hide();
		refreshChatFull();
	});
	atrajQ(document).on("click touchstart", "#coms_p", function (event) {
		atrajQ("#coms_m").show();
		atrajQ("#coms_f").show();
		comscroll++;
		refreshChatFull();
	});
	atrajQ(document).on("click", "a", function (event) {
		if (atrajQ(this).attr("data-httpreq")) {
            event.preventDefault();
            event.stopPropagation();
			var link = atrajQ(this).attr("data-httpreq-link");
			var self = atrajQ(this);
			atrajQ.post(link, {type: "user"}, function (data) {
				if (data == "done") {
					if(self.attr("data-httpreq-msg"))dialog(self.attr("data-httpreq-msg"));
                    refreshChat();
                    atrajQ("ul#menu").hide();
				} else {
					dialog(data);
				}
			});
		}
		return true;
	});
	atrajQ(document).on("click touchstart dblclick", ".usermenu", function (event) {
		event.preventDefault();
		event.stopPropagation();
        var id = atrajQ(this).attr("data-id");
        var xtra = atrajQ(this).attr("data-xtra");
		var link = atrajQ(this);

        atrajQ.ajax({
            type: "POST",
            url: 'httpreq_chat.php?do=usermenu&id='+id+'&xtra='+xtra,
            success: function (data) {
                atrajQ("#ajax_temp").html(data);
                var menu = atrajQ("ul#menu");
                menu.css('display', 'block');
                menu.css('position', 'absolute');
                var offset;
                if (event.pageY == undefined || event.pageX == undefined) {
                    offset = link.offset();
                    offset.top += 15;
                } else {
                    offset = {top: event.pageY + 12, left: event.pageX - 75};
                    menu.menu().show();
                    var bottom = atrajQ(window).scrollTop() + atrajQ(window).height(), right = atrajQ(window).scrollLeft() + atrajQ(window).width(), height = menu.height(), width = 150;
                    menu.menu().hide();
                    if (offset.top + height > bottom) {
                        offset.top -= height + 22;
                    }
                    if (offset.left + width > right) {
                        offset.left -= width;
                    }
                }
                menu.css(offset);
                menu.css('display', 'none');
                menu.width(width).menu().show("normal");
                atrajQ(document).one("click", function () {
                    menu.hide("normal");
                });
            },
            dataType: 'html'
        });

		return true;
	});
	atrajQ(document).on("mouseover", ".usermenu", function (event) {
		atrajQ(this).css("cursor", "pointer");
	});
	atrajQ(document).on("mouseover", ".chathovermenu", function (event) {
		atrajQ(this).css("background-color", "#090909");
		// atrajQ(this).attr('title','Postmenu mit Rechtsklick öffnen.');
		var id = atrajQ(this).attr("data-id");
		var parent = atrajQ('#comment' + id);
		var postmenu = atrajQ('#postmenu' + id);
		var post = atrajQ('#post' + id);
		if (postmenu.length) {
            postmenu.css('display','block');
		}
	});
	atrajQ(document).on("mouseout", ".chathovermenu", function (event) {
		atrajQ(this).css("background-color", "#000000");
		var id = atrajQ(this).attr("data-id");
		var parent = atrajQ('#comment' + id);
		var postmenu = atrajQ('#postmenu' + id);
		var post = atrajQ('#post' + id);
		if (postmenu.length) {
            postmenu.css('display','none');
		}
	});
	atrajQ(document).on("mouseover", ".editpost", function (event) {
		atrajQ(this).css("cursor", "pointer");
	});
    atrajQ(document).on("change", "#chat_edit_was", function (event) {
        chatconfig = [];
        loadEditConfig();
    });
    atrajQ(document).on("keydown", "#chat_text_edit", function (event) {
        var area = atrajQ(this);
        atrajQ('#chat_edit_rest').html(' (Noch ' + (area.attr("maxlength") - area.val().length) + ' Zeichen übrig) ');
    });
    atrajQ(document).on("keyup", "#chat_text_edit", function (event) {
        var area = atrajQ(this);
        atrajQ('#chat_edit_rest').html(' (Noch ' + (area.attr("maxlength") - area.val().length) + ' Zeichen übrig) ');
        loadEditConfig();
    });
    atrajQ(document).on("focusin", "#chat_text_edit", function (event) {
        loadEditConfig();
        var area = atrajQ(this);
        atrajQ('#chat_edit_rest').html(' (Noch ' + (area.attr("maxlength") - area.val().length) + ' Zeichen übrig) ');
    });
    atrajQ(document).on("click touchstart", ".editpost", function (event) {
        event.preventDefault();
        if(!inlinedit)
        {
            inlinedit = true;

            var id = atrajQ(this).data('id');
            var link = atrajQ('#chathovermenu'+id);
            var back = link.html();

            atrajQ.post("httpreq_chat.php", {do: "edit_get_form", id: id}, function (data) {
                if (data.length) {
                    chatconfig = [];
                    atrajQ('#chat_write').hide();
                    atrajQ('#comscroll_nav').hide();
                    atrajQ('#rpchat').hide();
                    atrajQ('#comperpage_div').hide();
                    link.html(data);
                    atrajQ('#rpchatedit .button').button();
                    loadEditConfig();

                    atrajQ('#chat_edit_comsend').one("click", function () {
                        event.preventDefault();
                        event.stopPropagation();

                        var area = atrajQ('#chat_text_edit');
                        if (area.val().length) {
                            pd = {};
                            pd.chat_text = area.val();
                            pd.do = "chatsave";
                            pd.edit = 'true';
                            pd.was = atrajQ('#chat_edit_was').val();
                            pd.pk = id;
                            atrajQ.post("httpreq_chat.php", pd, function (data) {
                                if (data == 'done') {
                                    refreshChat();
                                    inlinedit = false;
                                } else {
                                    dialog(data);
                                }
                            });
                        }

                        link.html(back);
                        var postmenu = atrajQ('.postmenu');
                        if (postmenu.length) {
                            postmenu.css('display','none');
                        }
                        atrajQ('#chat_write').show();
                        atrajQ('#rpchat').show();
                        atrajQ('#comscroll_nav').show();
                        atrajQ('#comperpage_div').show();
                        chatconfig = [];

                        return false;
                    });

                    atrajQ('#chat_edit_end').one("click", function () {
                        event.preventDefault();
                        event.stopPropagation();
                        link.html(back);
                        var postmenu = atrajQ('.postmenu');
                        if (postmenu.length) {
                            postmenu.css('display','none');
                        }
                        atrajQ('#chat_write').show();
                        atrajQ('#rpchat').show();
                        atrajQ('#comscroll_nav').show();
                        atrajQ('#comperpage_div').show();
                        chatconfig = [];
                        inlinedit = false;
                        return false;
                    });

                } else {
                    dialog('Kein Beitrag zum editieren gefunden!');
                    inlinedit = false;
                }
            });
        }

    });
    atrajQ(document).on("submit", "#rpchatedit", function (event) {
        event.preventDefault();
        return false;
    });
    atrajQ(document).on("dblclick", ".chathovermenu", function (event) {
        event.preventDefault();
        if(!inlinedit)
        {
            inlinedit = true;

            var id = atrajQ(this).data('id');
            var link = atrajQ(this);
            var back = link.html();

            atrajQ.post("httpreq_chat.php", {do: "edit_get_form", id: id}, function (data) {
                if (data.length) {
                    chatconfig = [];
                    atrajQ('#chat_write').hide();
                    atrajQ('#comscroll_nav').hide();
                    atrajQ('#rpchat').hide();
                    atrajQ('#comperpage_div').hide();
                    link.html(data);
                    atrajQ('#rpchatedit .button').button();
                    loadEditConfig();

                    atrajQ('#chat_edit_comsend').one("click", function () {
                        event.preventDefault();
                        event.stopPropagation();

                        var area = atrajQ('#chat_text_edit');
                        if (area.val().length) {
                            pd = {};
                            pd.chat_text = area.val();
                            pd.do = "chatsave";
                            pd.edit = 'true';
                            pd.was = atrajQ('#chat_edit_was').val();
                            pd.pk = id;
                            atrajQ.post("httpreq_chat.php", pd, function (data) {
                                if (data == 'done') {
                                    refreshChat();
                                    inlinedit = false;
                                } else {
                                    dialog(data);
                                }
                            });
                        }

                        link.html(back);
                        var postmenu = atrajQ('.postmenu');
                        if (postmenu.length) {
                            postmenu.css('display','none');
                        }
                        atrajQ('#chat_write').show();
                        atrajQ('#rpchat').show();
                        atrajQ('#comscroll_nav').show();
                        atrajQ('#comperpage_div').show();
                        chatconfig = [];

                        return false;
                    });

                    atrajQ('#chat_edit_end').one("click", function () {
                        event.preventDefault();
                        event.stopPropagation();
                        link.html(back);
                        var postmenu = atrajQ('.postmenu');
                        if (postmenu.length) {
                            postmenu.css('display','none');
                        }
                        atrajQ('#chat_write').show();
                        atrajQ('#rpchat').show();
                        atrajQ('#comscroll_nav').show();
                        atrajQ('#comperpage_div').show();
                        chatconfig = [];
                        inlinedit = false;
                        return false;
                    });

                } else {
                    dialog('Kein Beitrag zum editieren gefunden!');
                    inlinedit = false;
                }
            });
        }

    });
});

