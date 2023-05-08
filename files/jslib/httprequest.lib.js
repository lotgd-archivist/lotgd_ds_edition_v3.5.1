/**********************************************************************************/
/* LOTGD-Libsystem fÜr atrahor.de											       */
/* scripted + (c) by Alucard <diablo3-clan[at]web.de>							   */
/* jegliche unautorisierte Nutzung ist untersagt und wird strafrechtlich verfolgt! */
/***********************************************************************************/
/*
	LOTGD-Libsystem für atrahor.de
	scripted + (c) by Alucard <diablo3-clan[at]web.de>
	*HTTPRequestlib
	*HTTP anfragen tätigen
	*maris knuddel*
	<script type="text/javascript"> //das is nur für meinen Editor, damit ich syntaxhighlighting hab :>
*/


//Klasse für die POSTVars
LOTGD.HTTPPostVars = Class.extend(
{
	m_vars : null,
	
	construct : function(){
		this.__LOI  = 'HTTPPostVars';
		this.m_vars = [];
	},
	
	checkVarName : function( name ){
		var len = this.m_vars.length-1;
		for(;len!=-1;len--){
			if( this.m_vars[ len ].varname == name ){
				return len;
			}
		}
		return -1;
	},
	
	
	addVar : function( name, value ){
		var id = this.checkVarName( name );
		if( id != -1 ){
			this.m_vars[ id ].varname = escape( value );
		}
		else{
			this.m_vars[ this.m_vars.length ] = { varname: name, val: value };
		}
	},
	
	removeVar : function( name ){
		var id = this.checkVarName( name );
		if( id != -1 ){
			this.m_vars.splice( id, 1 );
		}
	},
	
	
	getBodyString : function(){
		var len = this.m_vars.length;
		var val = null;
		var i;
		var str = '';
		for(i=0;i<len;++i){
			if( i ){
				str += '&';
			}
			val = this.m_vars[ i ].val;
			if( isArray(val) ){
				for( var j=0; j<val.length; ++j ){
					if( j ){
						str += '&';
					}
					str += this.m_vars[ i ].varname + '%5B%5D=' + escape(val[j]);
				}
			}
			else{
				str += this.m_vars[ i ].varname + '=' + escape(this.m_vars[ i ].val);
			}
		}
		return str;		
	}
});


//Klasse für die Anfrage
LOTGD.HTTPRequest = Class.extend(
{
	m_req: false,
	m_loading: false,
	m_onError: function(){},
	m_onSuccess: function(){},
	m_onStart: function(){},
	m_onEnd:	function(){},
	m_obj: null,
	m_error: 'unknown',
	m_lastsend: null,
	m_errlog:	[],
	m_retrycount: 0,
	m_max_retrycount: 0,
	
	construct : function(){with(this){
		__LOI = 'HTTPRequest';
		m_onStart = null;
		m_onEnd	  = null;
		m_errlog  = [];	
		m_retrycount = 0;
		m_max_retrycount = 0;
	}},
	
	initRequest : function(){with(this){
		m_req = (window.XMLHttpRequest ? new XMLHttpRequest() : false);
		if( !m_req ){
			var xml = [	'MSXML2.XMLHTTP.7.0',
						'MSXML2.XMLHTTP.6.0',
						'MSXML2.XMLHTTP.5.0',
						'MSXML2.XMLHTTP.4.0',
						'MSXML2.XMLHTTP.3.0',
						'MSXML2.XMLHTTP',
						'Microsoft.XMLHTTP',false];
			if( window.ActiveXObject ){
				
				for( var i=0; xml[ i ]; i++ ){
					try{
						m_req = new ActiveXObject(xml[i]);
						if( m_req ){
							break;
						}
					}
					catch(e){}
				}
			}	
		}
/*::DEBUG
		if( !m_req ){
			...
		}
DEBUG::*/
		m_error = 'unknown';
		return m_req;
	}},
	
	__onError : function( err ){
		this.__onEnd();
		if( isEmpty(this.m_onError) ){ return; }
		this.m_error = isSet(err)?err:'unknown';
		if( this.m_obj   && this.m_obj[this.m_onError] ){ this.m_obj[this.m_onError]( this.m_req, this ); }
		else{ this.m_onError( this.m_req, this ); }
/*::DEBUG
		LOTGD.debug('Fehler beim senden des Requests!', 'HTTPRequest.__onError');
*/
	},
	
	__onSuccess : function(){
		this.__onEnd();
		if( isEmpty(this.m_onSuccess) ){ return; }
		if( this.m_obj  && this.m_obj[this.m_onSuccess] ){ this.m_obj[this.m_onSuccess]( this.m_req, this ); }
		else{ this.m_onSuccess( this.m_req, this ); }
/*::DEBUG
		LOTGD.debug('Requests erfolgreich Ausgeführt!', 'HTTPRequest.__onSuccess');
*/		
	},
	
	__onStart : function(){
		if( isEmpty(this.m_onStart) ){ return; }
		if( this.m_obj && this.m_obj[this.m_onStart] ){ this.m_obj[this.m_onStart]( this.m_req, this ); }
		else{ this.m_onStart( this.m_req, this ); }
/*::DEBUG
		LOTGD.debug('Anfang des Requests!', 'HTTPRequest.__onStart');
*/	
	},
	
	__onEnd : function(){
		if( isEmpty(this.m_onEnd) ){ return; }
		if( this.m_obj && this.m_obj[this.m_onEnd] ){ this.m_obj[this.m_onEnd]( this.m_req, this ); }
		else{ this.m_onEnd( this.m_req, this ); }
/*::DEBUG
		LOTGD.debug('Ende des Requests!', 'HTTPRequest.__onEnd');
*/	
	},
	
	setHandler : function( onSuccess, onError, obj ){
		this.m_onSuccess = isFunction(onSuccess)? onSuccess : (isString(onSuccess) 	&& !isNone(obj) ? onSuccess : function(){});
		this.m_onError 	 = isFunction(onError) 	? onError 	: (isString(onError) 	&& !isNone(obj) ? onError 	: function(){});
		this.m_obj 		 = !isNone(obj) ? obj : null;
	},
	
	send : function( url, 		//url der Anfrage
					 onSuccess, //funtion, die aufgerufen wird, wenn alles ok ist (function(param))
					 onError, 	//funtion, die aufgerufen wird, wenn ein fehler auftritt
					 postvar,   //wenn es per POST übergeben werden soll hier rein (LOTGD.HTTPPostvars)
					 obj){with(this){ 		//wenn events objektfunktionensind
		setHandler(onSuccess, onError, obj);
		m_lastsend = arguments;
		this.__onStart();		
		if( !initRequest() ){
			this.__onError( 'Initialize failed!' );
			return false;
		}		
		
		if( m_req.overrideMimeType ){
			 m_req.overrideMimeType( 'text/xml' );
		}
		
		m_req.onreadystatechange = function()
		{
			if( m_req.readyState == 4 ){
				m_loading = false;
				var status = (LOTGD.debug ? 12029 : m_req.status );
				switch( status ){
					case 200:
						__onSuccess();
						m_retrycount = 0;
					break;
					
					case 12029:
					case 12030:
					case 12031:
					case 12152:
					case 12159:
						if( m_retrycount >= m_max_retrycount ){
							m_retrycount = 0;
							__onError(  'Nach '+(m_max_retrycount+1)+' Sendeversuchen abgebrochen!' );
						}
						else{
							retry(true);
						}
					break;
					
					default:
						m_retrycount = 0;
						__onError(  'ready, but Status: '+ m_req.status +', URL: '+url);
					break;
				}
				/*if( m_req.status == 200 ){
					__onSuccess();			
				}
				else{
					__onError(  'ready, but Status: '+ m_req.status );
				}*/
			}		
			
		}	
		
		var met = 'GET';
		if( postvar ){
			met = 'POST';
		}
		//alert('send->'+url);
		try{
			this.m_loading = true;
			
			m_req.open(met, url, true);
			m_req.setRequestHeader('Method', met + ' ' + url + 'HTTP/1.1');
			if( postvar ){
				m_req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=UTF-8');
				m_req.send( postvar.getBodyString() );
			}
			else{
				m_req.setRequestHeader('Content-Type', 'text/html; charset=UTF-8');
				m_req.send(null);
			}
		}
		catch(e){
			this.m_loading = false;
			this.__onError( 'exeption: ' + e );
		}
	}},
	
	retry : function( set ){with(this){ 
		if( set===true ){
			LOTGD.setTimeout('retry',2000,this);
		}
		else{
			m_retrycount++;
			send( m_lastsend[0], m_lastsend[1], m_lastsend[2], m_lastsend[3], m_lastsend[4] );
		}
	}},
	
	getJSON : function(){with(this){ 
		var ret = {};
		if( m_req ){
			if( m_req.responseXML ){
				var json = m_req.responseXML.getElementsByTagName('json')[0];
				if( json ){
					try{
						ret = eval('(' + json.firstChild.nodeValue + ')');
					}
					catch(e){
						alert("EXCEPTION!\n"+json.firstChild.nodeValue);
						ret = {};
					}
				}
				else{
					alert("Kein JSON-OBJEKT");
				}
			}
			else if( m_req.responseText != "" ){
				try{
					ret = eval(m_req.responseText);
				}
				catch(e){
					ret = {};
				}
			}			
		}
		return ret;
	}}
});


LOTGD.libIsLoaded( 'httprequest' );


