/**********************************************************************************/
/* LOTGD-Libsystem fÜr atrahor.de											       */
/* scripted + (c) by Alucard <diablo3-clan[at]web.de>							   */
/* jegliche unautorisierte Nutzung ist untersagt und wird strafrechtlich verfolgt! */
/***********************************************************************************/
/*
	LOTGD-Libsystem für atrahor.de
	scripted + (c) by Alucard <diablo3-clan[at]web.de>
	*Menulib
	*Menu
	*mich selbst knuddel*
	*vielen DANK an http://www.css-play.org für die Hilfe beim css des menus*
	<script type="text/javascript"> //das is nur für meinen Editor, damit ich syntaxhighlighting hab :>
*/

if( Browser.isIE && Browser.fVersion < 5.6 ){
	alert('LOTGD-Warnung:\nBrowser unterstützt nicht alle Funktionen von Menu.lib');
}

//MenuItemTypes
MIT_NORMAL 	= 0;
MIT_LABEL	= 1;
MIT_BREAK	= 2;


/*
m:
-classes:	{tr: '', td:'', ico:'', text:''}
-icon:		bla.jpg
-type:		MIT_NORMAL
-label:		'hure'
-submenu: 	[ LOTGD.MenuItem({label: 'abc'})]
-link		'http://muhkuh.de' ODER {href: 'http://muhkuh.de', target: '', ...}
-action		function(){}
*/

LOTGD.MenuItem  = Class.extend(
{
	m_li 	: false,
	m_class	: false,		
	m_icon 	: false,		
	m_type	: false,
	m_label : false,
	m_link	: false,
	m_action: false,
	m_sub	: null,
	m_owner : null,
	m_data	: null,
	m_id	: -1,
	m_disabled : false,
	m_atag  : false,
	
	construct : function( m ){
		this.__LOI 		= 'MenuItem';
		this.m_li 		= document.createElement( 'li' );
		this.m_class	= (isSet(m.classes) ? m.classes : false);		
		this.m_icon 	= (isSet(m.icon) ? m.icon : false);			
		this.m_type		= (isSet(m.type) ? m.type : MIT_NORMAL);
		this.m_label 	= (isString(m.label) ? m.label : ' ');
		this.m_link		= (isObject(m.link) || isString(m.link) ? m.link : 'javascript:void(0);');
		this.m_action	= (isFunction(m.action) ? m.action : false );
		this.m_sub		= (isArray(m.submenu)&&this.m_type==MIT_NORMAL ? m.submenu : new LOTGD.Menu(this));
		this.m_data		= (isSet(m.data) ? m.data : null);
		this.m_atag		= document.createElement( 'a' );
		
		if( isString(m.hint) ){
			LOTGD.Hint.add( this.m_atag, m.hint, false, Browser.isIe || Browser.isOpera );
		}
	},
	
	setLabel	: function( str ){
		if( isSet( this.m_label.nodeValue ) ){
			this.m_label.nodeValue = str;
		}
	},
	
	setVisibility : function( v ){
		this.m_li.style.display = (v?'block':'none');
		this.m_li.style.visibility = (v?'visible':'hidden');
	},
	
	disable	: function(){
		this.m_disabled 		= true;
		this.m_li.className 	= 'disabled';
	},
	
	enable		: function(){
		this.m_disabled 		= false;
		this.m_li.className 	= '';
	},
	
	execute 	: function(){
		if( !this.m_disabled && this.m_action ){
			this.m_action( this );
		}
		return false;
	},
	
	getPrimary : function(){
		p = this;
		while( p.m_owner ){
			p = p.m_owner;
		}
		return p;
	}
});

LOTGD.Menu = Class.extend(
{
	m_items		: [],
	m_ul		: null,
	m_visible	: true,
	m_subopen	: null,
	//m_style		: null,
	m_container	: null,
	m_owner		: null,
	
	construct 	: function( owner ){
		this.__LOI	 	= 'Menu';
		this.m_ul 	 	= document.createElement( 'ul' );
		//this.m_style 	= LOTGD.loadStyle(LOTGD.m_dir + 'menu.lib.css', 'menu');
		this.m_items 	= [];
		this.m_visible 	= (isSet( owner )?false:true);
		this.m_subopen 	= null;
		this.m_owner	= (isSet( owner )?owner:null);
	},
	
	
	length 		: function(){
		return this.m_items.length;
	},
	
	
	makeItem 	: function( mi, __icn ){
		var icn = (isSet(__icn) ? __icn : this.menuHasIcons());
		mi.m_owner 	= this;
		mi.m_id 	= this.m_items.getFree();
		this.m_items[mi.m_id] = mi;
		

		switch( mi.m_type ){
			case MIT_NORMAL:
				
				var ae = mi.m_atag;
				if( mi.m_action ){
					LOTGD.addEvent( ae, 'click', 'execute', mi, null, {rV: false, cB: true});
				}
				
				if( isString(mi.m_link) ){
					ae.href = mi.m_link;
				}
				else{
					setAttributesToHTML( ae, mi.m_link );
				}
				if( icn ){
					var sp  = document.createElement( 'div' );
					sp.style.width = '20px';
					sp.style.height = '18px';
					sp.style.overflow = 'hidden';
					sp.style.cssFloat = 'left';
					sp.style.styleFloat = 'left';
					sp.style.padding ='0px';
					sp.style.margin = '0px';
					var img = document.createElement( 'img' );
					img.style.margin = '0px';
					img.style.padding ='0px';
					if( isString(mi.m_icon) ){
						img.src = mi.m_icon;
					}
					else{
						img.src = LOTGD.m_dir+'img/transparent.gif';
					}
					img.border = 0;
					sp.appendChild( img );
					ae.appendChild( sp );
				}

				
				var lbl = document.createTextNode(mi.m_label);
				ae.appendChild( lbl );
				mi.m_label = lbl;
				
				var nm = null; 
				if( mi.m_sub.__LOI != 'Menu' ){
					mi.m_atag.className = 'fly';
					nm = new LOTGD.Menu(mi);
					for(var i=0;i<mi.m_sub.length;i++){
						nm.addItem( mi.m_sub[ i ] );
					}
					mi.m_sub = nm;
					nm.setVisibility(false);
				}	
				mi.m_li.appendChild( ae );
				
			break;
			
			case MIT_LABEL:
				var lbl = document.createTextNode(mi.m_label);
				mi.m_li.appendChild( lbl );
				mi.m_label = lbl;		
				mi.m_li.className = 'label';
			break;
			
			
			case MIT_BREAK:
				mi.m_li.className = 'break';
			break;				
		}
		return mi.m_id;
	},
	
	menuHasIcons : function( mi ){
		if( isSet(mi) ){
			for( k=0;k<mi.length;++k){
				if( mi[ k ].m_icon || mi[ k ].icon ){
					return true;
				}
			}
			return false;
		}
		else{
			return this.menuHasIcons(this.m_items);
		}
	},
	
	getPrimary : function(){
		p = this;
		while( p.m_owner ){
			p = p.m_owner;
		}
		return p;
	},
	
	addItem 	: function(){
		var a 	= arguments;
		var ia 	= a.length;
		var icn = this.menuHasIcons(a) || this.menuHasIcons();
		var k;
		for( k=0;k<ia;++k){
			var mi 		= a[ k ];
			this.m_ul.appendChild( mi.m_li );
			var needevt = (k==0 && isTrue(this.m_owner) && this.length()==0);
			this.makeItem( mi, icn );
			if( needevt ){
				var ae = this.m_owner.m_atag;
				ae.className = 'fly';
				ae.appendChild( this.m_ul );
				LOTGD.addEvent( ae, 'mouseover', 'showSubmenu', this.m_owner.m_owner, [this, this.m_owner]);
				LOTGD.addEvent( ae, 'mouseout', 'hideSubmenu', this.m_owner.m_owner);
			}
		}			
	},
	
	removeItem 	: function(){
		var a = new Array();
		a.fromArguments(arguments);
		var ia = a.length;
		a.sort();
		a.reverse();
		for( k=0;k<ia;++k){
			if( isNumber(a[k]) ){
				var id = this.m_items.getIndexFromID( 'm_id', k );
				this.m_ul.removeChild( this.m_items[id].m_li );
				a[id] = null;
			}
			else if( isArray(a[k]) ){
				//....
			}
		}
	},
	
	insertItem : function( pos, it ){
		if( !(it instanceof LOTGD.MenuItem) ){
			return;
		}
		if( pos >= this.m_items.length ){
			addItem( it );
			return;
		}
		
		this.makeItem( it );
		this.m_items.splice( pos, 0, it );
		this.m_ul.insertBefore( it.m_li, this.m_items[pos+1].m_li );
		
	},
	
	showSubmenu : function( sm, mi ){
		if( !mi.m_disabled && isNull(this.m_subopen)){
			sm.setVisibility(true); 
			this.m_subopen = sm;
		}
	},
	
	hideSubmenu : function( e ){
		//e = (window.event ? e.srcElement : e.originalTarget);
		/*if( !isChildFrom(e, this.m_subopen.m_owner) ){
			alert( e.tagName + '\n' + e.innerHTML  );
		}*/
		/*str = '';
		for( var x in e ){
			str += x +'\n';
		}
		alert( str );*/
		if( !isNull(this.m_subopen) ){
			this.m_subopen.setVisibility(false); 
			this.m_subopen = null;
		}
	},
	
	
	setVisibility : function( v ){
		if( this.m_visible != v ){
			if( !isNull(this.m_subopen) && !v){
				this.m_subopen.setVisibility( v );
			}
			this.m_ul.style.display = (v?'block':'none');
			this.m_ul.style.visibility = (v?'visible':'hidden');
			this.m_visible = v;
		}
	},
	
	
	showAt : function( obj, cN, cT ){
		if( isObject(obj) && isSet(obj.appendChild) ){
			this.m_container = document.createElement( isString(cT) ? cT : 'div');
			this.m_container.appendChild( this.m_ul );
			if( !isString(cN) ){
				cN = 'menu';
			}
			this.m_container.className = cN;
			obj.appendChild( this.m_container );
		}
	}/*,
	
	
	changeStyle : function( cN, s ){
		if( !Browser.isIe ){
		//this.m_style.setAttribute('href', s);
			
			if( isSet(cN.tagName) && cN.tagName.toLowerCase() == 'link' ){
				var len = this.length();
				for( var i=0; i<len; ++i ){
					this.m_items[ i ].m_sub.changeStyle( cN );
				}
			}
			else if( this.m_owner ){
				this.getPrimary().changeStyle( cN, s );
			}
			else if( !isNone(this.m_container) ){
				this.m_container.className = cN;
				this.m_style.parentNode.removeChild( this.m_style );
				this.changeStyle( LOTGD.loadStyle(s, 'menu') );
			}
		}
		
	}*/
});

LOTGD.libIsLoaded( 'menu' );



