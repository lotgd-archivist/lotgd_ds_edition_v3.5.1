/**********************************************************************************/
/* LOTGD-Libsystem fÜr atrahor.de											       */
/* scripted + (c) by Alucard <diablo3-clan[at]web.de>							   */
/* jegliche unautorisierte Nutzung ist untersagt und wird strafrechtlich verfolgt! */
/***********************************************************************************/
function isString(obj)    { return typeof(obj) == 'string'; }
function isNumber(obj)    { return typeof(obj) == 'number'; }
function isBoolean(obj)   { return typeof(obj) == 'boolean'; }
function isFunction(obj)  { return typeof(obj) == 'function'; }
function isObject(obj)    { return typeof(obj) == 'object' || isFunction(obj); }
function isArray(obj)     { return isObject(obj) && obj instanceof Array }
function isDate(obj)      { return isObject(obj) && obj instanceof Date; }
function isError(obj)     { return isObject(obj) && obj instanceof Error; }
function isUndefined(obj) { return typeof(obj) == 'undefined'; }
function isDefined(varname){ return typeof(window[varname]) != 'undefined'; }
function isNull(obj)      { return obj === null; }
function isNone(obj)      { return isUndefined(obj) || isNull(obj); }
function isSet(obj)       { return !isNone(obj); }
function isTrue(obj)      { return isSet(obj) && !!obj; }
function isFalse(obj)     { return !isTrue(obj); }
function isEmpty(obj) {
    switch (typeof(obj)) {
        case 'undefined': return true;
        case 'string':    return obj == '';
        case 'number':    return obj == 0;
        case 'boolean':   return obj == false;
        case 'function':
            for (var i in obj) { if (!(isSet(Function.prototype[i]))) return false; }
            return obj.toString() == (function(){}).toString();
        case 'object':
            if (obj === null) return true;
            var pt = Object.prototype;
            if (isArray(obj)) { pt = Array.prototype; }
            if (isError(obj)) { pt = Error.prototype; }
            for (var i in obj) { if (!(isSet(pt[i]))) return false; }
            return true;
        default:
            return false;
    }
}


//LOTGD wird nun auch bequem
//Ersatz für document.getElementById
function $() {
	var elements = new Array();
  	for (var i = 0; i < arguments.length; i++) {
    	var element = arguments[i];
    	if (typeof element == 'string'){
      		element = document.getElementById(element);
    	}
    	if (arguments.length == 1){ 
      		return element;
    	}
    	elements.push(element);
  	}
  	return elements;
}

function isChildFrom( ch, p ){
	var f = p.firstChild;
	while( f ){
		if( f == ch ){
			return true;
		}
		if( isChildFrom( ch, f ) ){
			return true;
		}
		f = f.nextSibling;
	}
	return false;
}

//Gibt den UNIX-Timestamp zurück
function unixTime(){
	return (new Date()).getTime();
}
//Setzt attribute in einem html-element
function setAttributesToHTML( obj, at ){
	for( var a in at ){
		obj.setAttribute( a, at[a] );
	}
}
function getXpos(element) {
	return (element.offsetParent) ? element.offsetLeft + getXpos(element.offsetParent) : element.offsetLeft;
}

function getYpos(element) {
	return (element.offsetParent) ? element.offsetTop + getYpos(element.offsetParent) : element.offsetTop;
}

function getPos(element)  {
	return Point( getXpos(element), getYpos(element) );
}

function getDivHeight( element ){
	return parseInt(element.style.pixelHeight || element.offsetHeight);
}

function getDivWidth( element ){
	return parseInt(element.style.pixelWidth || element.offsetWidth);
}

//f = [0....1] 
function setScrollOfDiv( element, f ){
	w 	= getDivWidth( element );
	sw 	= element.scrollWidth;
	if( sw > w ){
		element.scrollLeft = (element.scrollWidth - w) * f;
	}
}

function getElementsByAttribute( name, value, obj, array ){
	obj = (isSet(obj) ? obj : document);
	arr = (isArray(array) ? array : []);
	if( obj.getAttribute && obj.getAttribute( name ) == value ){
		array.push( obj );
	}
	for( var i=0; i<obj.childNodes.length; ++i ){
		getElementsByAttribute( name, value, obj.childNodes[i], arr );
	}
	return arr;
}


function php_serialize(obj){
    var string = '';

    if (typeof(obj) == 'object') {
        if (obj instanceof Array) {
            string = 'a:';
            tmpstring = '';
            count = 0;
            for (var key in obj) {
				if( isFunction( obj[key] ) ){
					continue;
				}
                tmpstring += php_serialize(key);
                tmpstring += php_serialize(obj[key]);
                count++;
            }
			count = obj.length;
            string += count + ':{';
            string += tmpstring;
            string += '}';
        } else if (obj instanceof Object) {
            classname = obj.toString();

            if (classname == '[object Object]') {
                classname = 'StdClass';
            }

            string = 'O:' + classname.length + ':"' + classname + '":';
            tmpstring = '';
            count = 0;
            for (var key in obj) {
                tmpstring += php_serialize(key);
                if (obj[key]) {
                    tmpstring += php_serialize(obj[key]);
                } else {
                    tmpstring += php_serialize('');
                }
                count++;
            }
            string += count + ':{' + tmpstring + '}';
        }
    } else {
        switch (typeof(obj)) {
            case 'number':
                if (obj - Math.floor(obj) != 0) {
                    string += 'd:' + obj + ';';
                } else {
                    string += 'i:' + obj + ';';
                }
                break;
            case 'string':
                string += 's:' + obj.length + ':"' + obj + '";';
                break;
            case 'boolean':
                if (obj) {
                    string += 'b:1;';
                } else {
                    string += 'b:0;';
                }
                break;
        }
    }

    return string;
}

function pointInRect( p, r ){
	return 	p.x>r.x &&
			p.y>r.y &&
			p.x<(r.x+r.w)&&
			p.y<(r.y+r.h);
}

function fireEvent(eventType, elementID)
{
    if (document.createEvent)
    {
        var evt = document.createEvent("Events");
        evt.initEvent(eventType, true, true);
        o.dispatchEvent(evt);
    } else if (document.createEventObject) {
        var evt = document.createEventObject();
        o.fireEvent('on' + eventType, evt);
    }
}


/******************************************************************************/
/*								BASISKLASSEN								  */
/******************************************************************************/
Point = function( _x, _y ){
	return { x: _x||0, y: _y||0, set: function(__x, __y){this.x=__x; this.y=__y;return this;},
			 setP : function(p){this.x=p.x; this.y=p.y; return this;},
			 toString : function(){return 'x: '+this.x+' y: '+this.y;} };
};

Rect = function( segP, offP ){
	segP = isSet( segP ) ? segP : Point();
	offP = isSet( offP ) ? offP : Point();
	return { x:segP.x, y:segP.y, w:offP.x, h:offP.y};
};

function Class() {}
Class.prototype.construct = function() {};
Class.extend = function(def) {    
	var classDef = function() {        
		if (arguments[0] !== Class) { 
			this.construct.apply(this, arguments); 
		}    
	};       
	
	var proto = new this(Class);    
	var superClass = this.prototype;        
	for (var n in def) {        
		var item = def[n];                               
		if (item instanceof Function){ 
			item.$ = superClass;   
		}
		proto[n] = item;    
	}    
	classDef.prototype = proto;        //Give this new class the same static extend method        
	classDef.extend = this.extend;            
	return classDef;
};
Class.prototype.__LOI = 'Class';

/******************************************************************************/
/*							ERWEITERUNGEN									  */
/******************************************************************************/
Array.prototype.fromArguments = function( arg ){
	var al = arg.length;
	for( var i=0;i<al;++i ){
		this.push(arg[i]);
	}
};


Array.prototype.getFree = function(){
	var al = this.length;
	for( var i=0;i<al;++i ){
		if( isNull( this[i] ) ){
			return i;
		}
	}
	return al;
};


Array.prototype.getIndexFromID = function( ID, value ){
	var al = this.length;
	for( var i=0;i<al;++i ){
		if( this[i][ID] == value ){
			return i;
		}
	}
	return -1;
};

Array.prototype.hasInside = function( val ){
	var len = this.length;
	for( var i=0;i<len; i++ ){
		if( this[i] == val ){
			return true;
		}
	}
	return false;
};


/******************************************************************************/
/*							STRINGEXTENSION									  */
/******************************************************************************/
var strString_html   	= '<>'; 
var strString_replace	= [	'&lt;', '&gt;'];
var PARSE_NO_LIMIT 		= -1;

String.prototype.m_max  = 999;
//löscht farbtags
String.prototype.clear  =
	function (){with(this){
		return replace(/`./g,'');
	}};
//trimmen
String.prototype.trim  = 
	function(){with(this){
		return trimR().trimL();
	}};
	
//rechts trimmen
String.prototype.trimR = 
	function(){with(this){
		var i;
		for(i=length-1; charAt(i)== ' '; --i);
		return substr( 0, i+1 );
	}};
//links trimmen
String.prototype.trimL = 
	function( str ){with(this){
		var i;
		for( i=0; charAt(i)== ' '; ++i);
		return  substr( i, length );
	}};
//ersetzt Zeichen durch HTML-Zeichencode
String.prototype.htmlspecialchars = 
	function ( dont ){with(this){
		var str = this;
		dont = (isNone(dont) ? '' : dont);
		for( var i=0; i<strString_html.length; ++i){
			if( dont.indexOf( strString_html.charAt(i) ) == -1 ){
				str = str.replace(new RegExp(strString_html.charAt(i),'g'), strString_replace[ i ]);
			}
		}
		return str;
	}};	
//doppelte farben (zb `v`b -> `b) entfernen
String.prototype.removeDubbleColor = 
	function(){with(this){
		return replace(/(`.)(`.)/g, '$2');
	}};


/******************************************************************************/
/*									BROWSER									  */
/******************************************************************************/
var Browser = {
	knowsDom 	: false,
	knowsAjax 	: false,
	isOpera 	: false,
	isIe		: false,
	isKhtml 	: false,
	isGecko 	: false,
	isFirefox 	: false,
	isMozilla 	: false,
	isSafari	: false,
	sVersion	: '0',
	iVersion	: 0,
	fVersion	: 0.0,
	
	toString 	: function(){ 
		return navigator.userAgent; 
	},
	
    inName : function(part, caseSensitive) {
        if (isSet(caseSensitive) && caseSensitive) {
            return !!(navigator.userAgent.indexOf(part)+1);
        }
        return !!(navigator.userAgent.toLowerCase().indexOf(part.toLowerCase())+1);
    },
	
	initialize : function(){with(this){
		knowsDom	= isSet(document.getElementById);
		knowsAjax	= knowsDom && isSet(document.createElement) && (isSet(window.XMLHttpRequest)||isSet(window.ActiveXObject));
		isKhtml 	= knowsDom && inName('khtml');
		isGecko 	= inName('Gecko/');
		isOpera 	= isSet(window.opera);
		isIe		= !isGecko && isSet(document.all) && !isOpera && inName('MSIE');
		isFirefox 	= inName('Firefox/') && isGecko;
		isMozilla 	= isGecko && !isFirefox;
		isSafari	= isKhtml && inName('Safari/');
		sVersion 	= getVersion();
		iVersion 	= (isNaN(iVersion = parseInt( sVersion )) ? 0 : iVersion);
		fVersion 	= (isNaN(fVersion = parseFloat( sVersion )) ? 0.0 : fVersion);
	}},
	
	
	getVersion : function(){with(this){
		return 9999;
	}}
};

Browser.initialize();


var __Scroller = Class.extend(
{
	m_active: false,
	m_window: null,
	m_timer : -1,
	m_delay : 1000,
	m_scrolOffset: null,
	m_scrollRect : null,
	m_in : false,
	m_obj: [],
	m_bobj: false,
	construct : function(){
		this.m_window = window;
		this.m_active = false;
		this.m_scrollOffset = Point();
		this.m_scrollRect   = [ Rect(), Rect(), Rect(), Rect() ];
		
		this.m_obj = [];
		LOTGD.addEvent(document, 'mousemove', 'mouseMove', this);
		LOTGD.addEvent(window, 'resize', 'onResize', this);
		var o=null;
		//alert('ie:'+Browser.isIe+'__v: '+);
		if( !(Browser.isIe/* && Browser.iVersion < 7*/) ){
			for( var i = 0; i<4 && (o=document.getElementById('LOTGD_scroll_obj'+i)); ++i){
				this.m_obj.push(o);
				this.m_bobj = i==3;
			}
		}
		this.calcRects();
		
	},
	
	calcRects : function (){
		var sr = this.m_scrollRect;
		var win = Point((Browser.isIe ? document.documentElement.clientWidth || document.body.clientWidth : window.innerWidth), (Browser.isIe ? document.documentElement.clientHeight || document.body.clientHeight : window.innerHeight));
		var size = Point(Math.round( 0.15 * win.x ),Math.round( 0.15 * win.y ));
		/**************************
		 * *       RECT 0       * *
		 **************************
		 *R*					*R*
		 *E*					*E*
		 *C*					*C*
		 *T*					*T*
		 * *					* *
		 *1*					*2*
		 **************************
		 * *       RECT 3       * *
		 **************************/
		sr[0].x = sr[0].y = 0;
		sr[0].w = win.x;
		sr[0].h = size.y;
		
		sr[1].x = sr[1].y = 0;
		sr[1].w = size.x;
		sr[1].h = win.y
		
		sr[2].x = win.x - size.x;
		sr[2].y = 0;
		sr[2].w = size.x;
		sr[2].h = win.y;
		
		sr[3].x = 0;
		sr[3].y = win.y - size.y;
		sr[3].w = win.x;
		sr[3].h = size.y;
		
		if( this.m_bobj ){
			this.m_obj[0].style.left = '21px';
			this.m_obj[0].style.top  = '0px';
			
			this.m_obj[1].style.left = '0px';
			this.m_obj[1].style.top  = '21px';
			
			this.m_obj[2].style.left = '42px';
			this.m_obj[2].style.top  = '21px';
			
			this.m_obj[3].style.left = '21px';
			this.m_obj[3].style.top  = '42px';			
		}
	},
	
	setActive : function( act ){
		this.m_active = !!act;
		if( this.m_bobj ){
			for(var i=0;i<4;++i){
				this.m_obj[i].style.display = 'none';
			}
		}
	},
	
	scroll : function(){
		this.m_window.scrollBy(this.m_scrollOffset.x, this.m_scrollOffset.y);
		LOTGD.scrollerCallBack();
		this.startTimer();
	},
	
	startTimer : function(){
		if( this.m_in && this.m_active){
			this.m_timer = LOTGD.setTimeout( 'scroll', this.m_delay, this );
		}
	},
	
	stopTimer : function(){
		LOTGD.clearTimeout( this.m_timer, true );
		this.m_timer = -1;
	},
	
	checkBoundaries : function(){
		this.m_in = false;
		this.m_scrollOffset.set(0,0);
		if( this.m_bobj ){
			for(var i=0;i<4;++i){
				this.m_obj[i].style.display = 'none';
			}
		}
		if( pointInRect( LOTGD.m_globalMousePos, this.m_scrollRect[0] ) ){
			this.m_delay = 100;
			this.m_scrollOffset.y = -2;
			this.m_in = true;
			if( this.m_bobj ){
				this.m_obj[0].style.display = 'block';
			}
		}
		else if( pointInRect( LOTGD.m_globalMousePos, this.m_scrollRect[3] ) ){
			this.m_delay = 100;
			this.m_scrollOffset.y = 2;
			this.m_in = true;
			if( this.m_bobj ){
				this.m_obj[3].style.display = 'block';
			}
		}
		if( pointInRect( LOTGD.m_globalMousePos, this.m_scrollRect[1] ) ){
			this.m_delay = 100;
			this.m_scrollOffset.x = -2;
			this.m_in = true;
			if( this.m_bobj ){
				this.m_obj[1].style.display = 'block';
			}
		}
		else if( pointInRect( LOTGD.m_globalMousePos, this.m_scrollRect[2] ) ){
			this.m_delay = 100;
			this.m_scrollOffset.x = 2;
			this.m_in = true;
			if( this.m_bobj ){
				this.m_obj[2].style.display = 'block';
			}
		}
		return this.m_in;
	},
	
	mouseMove : function( e ){
		if( this.m_active ){
			if( this.checkBoundaries() ){
				this.startTimer();
			}
			else{
				this.stopTimer();
			}
		}
	},
	
	onResize : function( e ){
		if( this.m_active ){
			this.calcRects();
		}
	}
	
});


var oopEvent = Class.extend(
{
	m_object: null,
	m_param	: null,
	m_fkt	: '',
	m_ret	: true,
	construct : function( obj, fkt, param, ret){
		if(isNone(param)){
			param = [];	
		}
		param.push(null);
		this.m_object  	= obj;
		this.m_fkt	  	= fkt;
		this.m_param   	= param;
		this.m_ret		= isSet(ret) ? ret : null;
	}	
});


var oopTimeout = Class.extend(
{
	m_object: null,
	m_param	: null,
	m_fkt	: '',
	m_set	: 0,
	construct : function( obj, fkt, param ){
		this.m_object  = obj;
		this.m_fkt     = fkt;
		this.m_param   = isNone(param) ? [] : param;
		this.m_id	   = -1;
		this.m_set	   = unixTime();	
	}
});


/******************************************************************************/
/*							LIBLOADWAITER									  */
/******************************************************************************/
var ALL_LOADED = -1;
var libLoadWaiter = Class.extend(
{
	m_fkt : [],
	m_lib : [],
	m_doc : false,
	
	construct : function( lib, fkt, doc ){
		if( isNone(lib) || isNone(fkt) ){
			return; //wär ja auch sinnlos, wenn eins der beiden nicht gesetzt ist
		}
		this.m_doc = isBoolean(doc) ? doc : false;
		this.m_fkt = (isArray(fkt) ? fkt : [fkt]);
		this.m_lib = (lib==ALL_LOADED ? ALL_LOADED : 
						(isArray(lib) ? lib : [lib])
					 ); 
		this.setTimeout();
	},
	
	setTimeout : function(){
		LOTGD.setTimeout( 'check', 100, this );
	},
	
	check : function(){with(this){
		var len;
		var i;
		if( this.m_doc && !LOTGD.m_document_loaded ){
			i = 0;
			len = 1;
		}
		else if( m_lib == ALL_LOADED ){
			i   = LOTGD.m_loadedLibs.length
			len = LOTGD.m_ltl;
			 
		}
		else{
			len = m_lib.length;
			for(i=0;i<len && LOTGD.m_loadedLibs.hasInside( m_lib[i] ); ++i);
		}
		if( i == len ){
			callFunction();
		}
		else{
			setTimeout();
		}
	}},
	
	callFunction : function(){with(this){
		len = m_fkt.length;
		for( var i=0; i<len; i++ ){
			try{
				m_fkt[i]();
			}
			catch(e){}
		}
	}}
});

/*::DEBUG*/
function getAttList( obj, h ){
	var ss = '';
	var i = 1;
	for( var x in obj ){
		//if( !isNone(obj[x]) && !isEmpty(obj[x]) ){
			var str = ''+obj[x];
			if( isFunction(obj[x]) ){
				var li = str.indexOf('{');
				str = str.substr(0,li==-1?str.indexOf('\n'):li);
			}
			ss += x + ' = '+ str +'\n';
		//}
	}
	alert(ss);
}

function getArgList( arg ){
	var ss = '';
	for( var i=0; i<arg.length; ++i ){
		ss += arg[i] +'\n';
	}
	alert(ss);
}
/*DEBUG::*/



/******************************************************************************/
/*									LOTGD									  */
/******************************************************************************/
var STYLE = function(){
	return {m_id: '', m_link: null, m_style: ''};
}
var LOTGD_NO_CMD = '__NO_CMD__';
var MessageBox;
var LOTGD = {
	m_version 		: '0.9a',
//Account
	m_su			: 0,
	m_acctid		: 0,
	m_login			: "",
//EVENTHANDLING
	m_oopEvents		: [],
	m_oopTimeouts	: [],
//libladezeug
	m_images		: [],
	m_hexcol		: Array(),
	m_loadedLibs	: [], //Libs, die geladen sind
	m_ltl			: 0, //count der zu ladenden libs
	m_MousePos		: Point(),
	m_globalMousePos: Point(),
	m_colors		: '',
	m_dir 			: './jslib/',
	m_initialized	: 0,
	//m_eventhandlers	: [],
	m_document_loaded: false,
	m_on_document_loaded: [],
	
	
	
	
	getCommandFromRequest : function( r ){
		var cmd;
		try{
			cmd = r.responseXML.getElementsByTagName('command')[0];
			cmd = cmd.firstChild.nodeValue;
		}
		catch( e ){
			return LOTGD_NO_CMD;
		}
		return isEmpty( cmd ) ? LOTGD_NO_CMD : cmd;
	},
	
	//commandparser
	parseCommand : function( txt ){
		if( !isString(txt) ){
			return false;
		}
		if( txt == LOTGD_NO_CMD ){
			return false;
		}
		else if( txt == 'newday' ){
			window.location.href = 'newday.php';
			return true;
		}
		else if( txt == 'timeout' ){
			window.location.href = 'index.php?op=timeout';
			return true;
		}
		else if( txt == 'prison' ){
			window.location.href = 'prison.php';
			return true;
		}
		else if( txt == 'badnav' ){
			window.location.href = 'badnav.php';
			return true;
		}
		else{
			var cmd = txt.match(/^\/[a-z]{1,}\s?/);
			if( cmd ){
				var param = txt.replace(/^\/[a-z]{1,}\s+/, '').trim();
				cmd = cmd[0].trim();
				switch(cmd){
					case '/mb':
						MessageBox.show(param);
					break;
					
					case '/go':
						window.location.href = param;
					break;
					
					case '/exec':
						eval(param);
					
					break;
					
					default: return false;
				}
				return true;
			}
			
		}
		return false;
	},
		
	//teilt dem system mit, dass die lib geladen ist
	libIsLoaded : function( lib ){with(this){
		m_loadedLibs.push(lib);
	}},	
	
	//prüft, ob eine lib schon hinzugefügt ist
	libAdded : function( lib ){with(this){		
		return !isNull(document.getElementById( 'JS_LIB_' + lib ));		
	}},
	
	//läd eine lib
	loadLibrary : function( lib ){with(this){	
	
		var lotlib 	= document.getElementById( 'LOTGD_JS_LIBS' );
		if( isNull(lotlib) ){
			lotlib 	= document.body;
		}
		if( !libAdded( lib ) && lotlib){
			var js 	= document.createElement('script');
			js.src 	= m_dir + lib + '.lib.js';
			js.id  	= 'JS_LIB_' + lib;
			js.type = 'text/javascript';
			lotlib.appendChild(js);
			m_ltl++;
			return true;
		}
		return false;
	}},
	
	//läd ein style
	loadStyle : function( style, id ){with(this){
		var lotlib 	= document.getElementById( 'LOTGD_JS_LIBS' );
		var s 		= document.getElementsByTagName('link');
		if( isString(id) ){
			var i = getElementsByAttribute( 'styleID', id, lotlib );
			if( i.length ){
				return i[0];
			}
		}
		for( var i=0;i<s.length; ++i ){
			var f = s[i].href.indexOf(style);
			if( f > -1 ){
				if( s[i].href.length - f == style.length ){
					return s[i];
				}
			}
		}
		
		if( lotlib ){
			var s = document.createElement('link');
			s.href = style; 
			s.rel = 'stylesheet';
			s.type = 'text/css';
			s.media = 'all';
			if( isString(id) ){
				s.setAttribute('styleID',id);
			}
			lotlib.appendChild(s);
			return s;
		}
		alert('LOTGD_LIB Err: Invalid HTML> No Body-Tag found!');
		return false;
	}},
	
	
	callObjectFunction : function ( o_p ){with(this){
		try{
			var len 			= o_p.m_param.length;
			var str 			= "o_p.m_object[ o_p.m_fkt ]( ";
			if( o_p.m_param ){
				var i=0;
				for(; i<len; ++i ){
					str += (i?",":"") + "o_p.m_param["+i+"]";
				}
				if( i ){
					str += ",";
				}
			}
			str += "o_p);";
			return eval(str);
		}
		finally{
			return false;
		}
	}},
	
	addOOPEvent :function ( obj, param, fkt, ret ){with(this){
		var id 			= m_oopEvents.getFree();
		m_oopEvents[id] = new oopEvent(obj, param, fkt, ret);
		return id;
	}},
	
	executeOOPEvent : function( objid, event ){with(this){
		var o_p = m_oopEvents[ objid ]; 
		o_p.m_param[o_p.m_param.length-1] = event; 
		callObjectFunction( o_p );
		return o_p.m_ret;
	}},
	
	addEvent : function(obj, 		//zielobjekt
						evt, 		//eventname (ohne 'on')
						fkt, 		//funktion oder bei OOP funktionsname
						obj_fkt, 	//wenn oop, dann zeiger auf objekt
						param,		//parameter für oop funktion (array)
						ret){
		if( isSet(param) && obj_fkt==null ){
			obj_fkt = window;
			fkt = fkt.name;
		}
		if( isSet(obj_fkt) ){
			str = 	"e=(window.event?window.event:e);"+
					"var r = LOTGD.executeOOPEvent(" + this.addOOPEvent( obj_fkt, fkt, param, ret ) + ",e);"+
					"if( r ){ if(Browser.isIe){ e.cancelBubble= r.cB; e.returnValue = r.rV;}else if(!r.rV){e.preventDefault();e.stopPropagation();} return r.rV;}"+
					"else{return true;}";
			fkt = new Function( "e", str );
		}
	
		if (obj.addEventListener) {
			obj.addEventListener( evt, fkt, false );
		}
		else if (obj.attachEvent) {
			obj["e"+evt+fkt] = fkt;
			obj[evt+fkt] = function() { obj["e"+evt+fkt]( window.event ); }
			obj.attachEvent( "on"+evt, obj[evt+fkt] );
		}
		else {
			obj["on"+evt] = obj["e"+evt+fkt];
		}
		
	},	
	
	
	//Timeoutzeug
	
	setTimeout : function( fkt, timeout, obj, param ){with(this){
		if( !isSet(param) && !isSet(obj) ){
			if( isFunction(fkt) ){
				return window.setTimeout( fkt, timeout );
			}
			else if(isString(fkt)){
				return window.setTimeout( fkt, timeout );
			}
		}
		else{
			return setOOPTimeout( fkt, timeout, obj, param );
		}
	}},
	
	clearTimeout : function( id, oop ){with(this){
		if( oop ){
			if( m_oopTimeouts[id] ){
				window.clearTimeout( m_oopTimeouts[id].m_id );	
				m_oopTimeouts[id] = null;
			}
		}
		else{
			window.clearTimeout( id );
		}
	}},
	
	
	setOOPTimeout : function( fkt, timeout, obj, param ){with(this){
		var id 					 = m_oopTimeouts.getFree();
		m_oopTimeouts[ id ] 	 = new oopTimeout( obj, fkt, param );
		m_oopTimeouts[ id ].m_id = window.setTimeout('LOTGD.callOOPTimeout('+id+');', timeout);
		return id;
	}},
	
	callOOPTimeout : function( ID ){with(this){
		var o_p = m_oopTimeouts[ ID ];
		if( o_p ){
			callObjectFunction( o_p );	
		}
/*::DEBUG
		else{
			//...keine gültige ID
			LOTGD.debug( 'Call eines null-Timeouts\nID: '+ID, 'LOTGD.callOOPTimeout');
		}
DEBUG::*/
		if( m_oopTimeouts[ ID ] && o_p.m_set == m_oopTimeouts[ ID ].m_set ){//wenn in der zwischenzeit gecleared + neu gesetzt nicht nullen
			m_oopTimeouts[ ID ] = null;
		}
	}},
	
	loadImages : function( imgs ){with(this){
		var ret = [];
		for(var i=0; i<imgs.length; ++i){
			var j = m_images.length;
			m_images[ j ] = new Image();
			m_images[ j ].src = imgs[ i ];
			ret.push(m_images[ j ]);
		}
		return ret;
	}},
	
	//kompatibilitätscheck
	compCheck : function(){
		return  Browser.knowsAjax 	&&
				Browser.knowsDom 	&&
				(Browser.isIe ? Browser.iVersion >= 6 : true);
	},
	
	
	scrollerCallBack : function(){
		if (Browser.isIe) {
			LOTGD.m_MousePos.set( LOTGD.m_globalMousePos.x+(document.documentElement.scrollLeft || document.body.scrollLeft), LOTGD.m_globalMousePos.y+(document.documentElement.scrollTop || document.body.scrollTop));
		}
		else{
			LOTGD.m_MousePos.set( LOTGD.m_globalMousePos.x+window.pageXOffset, LOTGD.m_globalMousePos.y+window.pageYOffset);
		}
		if( DragDrop ){
			DragDrop.move();
		}
	}

/*::DEBUG
	,debug : function( msg, fn, e ){
		var d = new Date();
		var ds = '['+ d.getHours() +':'+ d.getMinutes()+':'+ d.getSeconds() +':'+ d.getMilliseconds() +'] @';
		ds += isString(fn) ? fn : LOTGD.debug.caller;
		ds += '\n'+msg;
		alert( ds );		
	}
DEBUG::*/
};

/******************************************************************************/
/*							EVENTS FÜR DIE LIB								  */
/******************************************************************************/
LOTGD.addEvent( document, 'mousemove', function( e ){ 
											//var trg = isSet(e.srcElement) ? e.srcElement : e.originalTarget;
											//if( trg && trg.tagName.toLowerCase() != 'body' ){MessageBox.show(trg.tagName);return;}
											var x,y,g_x,g_y;
											if (window.event != null) {
												x= e.x + (document.documentElement.scrollLeft || document.body.scrollLeft);
												y= e.y + (document.documentElement.scrollTop || document.body.scrollTop);
												g_x = e.x;
												g_y = e.y;
											}
											else{
												x= e.clientX + window.pageXOffset;
												y= e.clientY + window.pageYOffset;
												g_x = e.clientX;
												g_y = e.clientY;
											}
											LOTGD.m_globalMousePos = Point(g_x,g_y);
											LOTGD.m_MousePos = Point(x,y); 
										} 
				);
				
LOTGD.addEvent( window, 'load', 
	function(){ 
		LOTGD.m_document_loaded = true;
		for(var i=0;i<LOTGD.m_on_document_loaded.length;++i){
			try{
				LOTGD.m_on_document_loaded[i]();
			}catch(e){}
		}
	} 
);


/******************************************************************************/
/*									MESSAGEBOX								  */
/******************************************************************************/
var MB_ICON_NONE 	= 0;
var MB_ICON_INFO 	= 1;
var MB_ICON_ERROR 	= 2;
var MB_ICON_WARNING = 3;
var MB_PG_NEXT 		= -1;
var MB_PG_PREV 		= -2;
var MB_PG_FIRST 	= 0;
var MB_PG_LAST		= -4;

var MessagePage = Class.extend({
	m_header : '',
	m_msg	 : '',
	m_icon	 : MB_ICON_NONE,
	m_isHTML : false,
	
	construct : function(msg, header,icon, isHTML){
		this.m_header = (isSet(header) ? header : 'Messagebox');
		this.m_msg	  = (isSet(msg) ? msg : 'nix!');
		this.m_icon	  = (isSet(icon) ? icon : MB_ICON_NONE);
		this.m_isHTML = (isSet(isHTML) ? isHTML : false);
	}
	
});

var __MessageBox = Class.extend({
	m_pages		: [],
	m_actualID	: -1,
	m_div		: null,
	m_header	: null,
	m_text		: null,
	m_pic		: null,
	m_msgnumber : null,
	a_first 	: null,


	a_prev 		: null,
	a_next 		: null,
	a_last 		: null,
	
	
	construct : function(){
		LOTGD.loadStyle(LOTGD.m_dir + 'mb.lib.css', 'LOTGD_MESSAGEBOX');
		this.m_actualID 	= -1;
		this.m_pages		= [];
		
		
		this.m_div = document.getElementById('LOTGD_MESSAGEBOX_DIV');
		if( this.m_div ){
			this.m_header 		= document.getElementById('LOTGD_MESSAGEBOX_HEADER');
			this.m_middle 		= document.getElementById('LOTGD_MESSAGEBOX_MIDDLE');
			this.m_msgnumber 	= document.getElementById('LOTGD_MESSAGEBOX_NUMBER');
			this.a_first		= document.getElementById('LOTGD_MESSAGEBOX_FIRST');
			this.a_prev 		= document.getElementById('LOTGD_MESSAGEBOX_PREV');
			this.a_next 		= document.getElementById('LOTGD_MESSAGEBOX_NEXT');
			this.a_last 		= document.getElementById('LOTGD_MESSAGEBOX_LAST');
			LOTGD.addEvent( document.getElementById('LOTGD_MESSAGEBOX_CLOSE'), 'click', 'close', this );
			LOTGD.addEvent( this.a_first, 'click', 'showPage', this, [MB_PG_FIRST,this.a_first] );
			LOTGD.addEvent( this.a_prev,  'click', 'showPage', this, [MB_PG_PREV, this.a_prev] );
			LOTGD.addEvent( this.a_next,  'click', 'showPage', this, [MB_PG_NEXT, this.a_next] );
			LOTGD.addEvent( this.a_last,  'click', 'showPage', this, [MB_PG_LAST, this.a_last] );
		}
		
		if( Browser.isIe ){
			this.m_div.style.position = 'absolute';
		}	
	},
	
	show : function(msg, header, icon, isHTML){
		this.m_pages.push(new MessagePage(msg, header,icon, isHTML));
		this.showPage(MB_PG_LAST);
	},
	
	showPage : function( id, a ){
		if( a && a.className=='inactive'){
			return;
		}
		var len = this.m_pages.length;
		if( id < 0 ){
			switch( id ){
				case MB_PG_NEXT: id = (this.m_actualID+1<len ? this.m_actualID+1 : id);break;
				case MB_PG_PREV: id = (this.m_actualID-1>=0 ? this.m_actualID-1 : id);break;
				case MB_PG_LAST: id = len-1;
			}
		}
		if( id > -1 && len ){
			this.m_actualID = id;
			var p = this.m_pages[id];
			this.m_header.innerHTML = parse(p.m_header);
			this.m_middle.innerHTML = parse(p.m_msg);
			this.m_msgnumber.innerHTML = '[ '+ (id+1) + ' / ' + len + ' ]';
			
			this.a_first.className = (id!=0 ? 'active' : 'inactive');
			this.a_prev.className  = (id!=0 ? 'active' : 'inactive');
			this.a_next.className  = (id<(len-1) ? 'active' : 'inactive');
			this.a_last.className  = (id<(len-1) ? 'active' : 'inactive');
			this.m_div.style.display = 'block';
			var ox = 0;//parseInt(window.pageXOffset||document.body.scrollLeft||document.documentElement.scrollLeft),
				oy = 0;// parseInt(window.pageYOffset||document.body.scrollTop||document.documentElement.scrollTop);
			var mx = getDivWidth(this.m_div)/2,
				my = getDivHeight(this.m_div)/2;
			var x  = parseInt(window.innerWidth||document.body.clientWidth||document.documentElement.clientWidth)/2,
				y  = parseInt(window.innerHeight||document.body.clientHeight||document.documentElement.clientHeight)/2;	
			with(this.m_div.style){
				left = Math.floor(x-mx+ox)+'px';
				top  = Math.floor(y-my+oy)+'px';
			}
		}
	},
	
	close : function(){
		this.m_div.style.display = 'none';
		this.m_pages = [];
	}
	
});
 
var __Hint = Class.extend({
	m_container: null,
	m_visible : false,
	m_addpos  : false,
	
	construct : function(){
		this.m_container = document.getElementById('LOTGD_HINT');//document.createElement('div');
		//this.m_container.className = 'lotgdHint';
		//document.body.appendChild(this.m_container);
		LOTGD.addEvent( document.body, 'mousemove', 'move', this );
		this.hide();
	},
	
	show : function( text, action, add, __width, className ){
		this.m_visible = true;
		this.m_container.innerHTML = (action ? eval(text) : parse(text));
		this.m_container.className = !isEmpty(className) ? className : 'lotgdHint';
		this.m_addpos = add && Browser.isIe;
		var cw = (Browser.isIe ? document.documentElement.clientWidth || document.body.clientWidth : window.innerWidth)/2;
		cw = __width ? Math.min(cw,__width) : cw;
		with( this.m_container.style ){
			display = 'block';
			width   = '';
		}
		var w  = getDivWidth(this.m_container);
		if( w > cw){
			this.m_container.style.width = cw+'px';
		}
		this.move();
	},
	
	hide : function(){
		this.m_visible = false;
		this.m_container.style.display = 'none';
	},
	
	move : function(){
		if( this.m_visible ){
			//elementhöhe
			var h  = getDivHeight(this.m_container),
				w  = getDivWidth(this.m_container),
			//mauspos
			    ly = LOTGD.m_MousePos.y+10 +(this.m_addpos ? getYpos(window.event.srcElement):0),
			    lx = LOTGD.m_MousePos.x+10 +(this.m_addpos ? getXpos(window.event.srcElement):0),
			//scrolloffset
				ox = parseInt(window.pageXOffset||document.body.scrollLeft||document.documentElement.scrollLeft),
				oy = parseInt(window.pageYOffset||document.body.scrollTop||document.documentElement.scrollTop);
			if( h+ly > (getDivHeight(document.body)+oy) ){
				h = -(h+20);
			}else{h=0;}
			if( w+lx > (getDivWidth(document.body)+ox) ){
				w = -(w+20);
			}else{w=0;}
			this.m_container.style.left = (lx + w) + 'px';
			this.m_container.style.top  = (ly + h) + 'px';
			//this.m_container.style.display  = 'block';
		}
	},
	
	add  : function( htmlObj, text, action, add, width, className ){
		LOTGD.addEvent( htmlObj, 'mouseover', 'show', this, [text,action,add,width, className]);
		LOTGD.addEvent( htmlObj, 'mouseout', 'hide', this );
	}
});
 
var MessageBox = new __MessageBox();
var Scroller   = new __Scroller();
LOTGD.Hint = new __Hint();

/*function JSLIB_INITMESSAGEBOX(){
	//MessageBox = new __MessageBox();
	
}
LOTGD.m_on_document_loaded.push(JSLIB_INITMESSAGEBOX);*/
LOTGD.m_initialized = unixTime();


var G_JSLIB_BUILDID=22;