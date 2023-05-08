var lastcolor = null;
var _ncol = false;
var _nc = false;
var _nb = false;
var _ni = false;
function parse(text) {
    lastcolor = null;
    _ncol = false;
    _nc = false;
    _nb = false;
    _ni = false;
	text = text.replace(new RegExp("³[^<>³]+³", "g"), do_verlauf_parse);
	return '<span>' + text + '</span>';
}
function hexdec(h) {
	return parseInt(h, 16);
}
function do_verlauf_parse(str) {
	str = str.toString().replace(new RegExp("²", "g"), "").replace(new RegExp("³", "g"), "");
	var matches = str.match(new RegExp("#([a-fA-F0-9]{3,6});([^`#]*)", "g"));
	if (matches) {
		str = "";
		var count = matches.length - 1;
		$.each(matches, function (i, val) {
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