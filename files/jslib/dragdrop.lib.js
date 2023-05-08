/**********************************************************************************/
/* LOTGD-Libsystem fÜr atrahor.de											       */
/* scripted + (c) by Alucard <diablo3-clan[at]web.de>							   */
/* jegliche unautorisierte Nutzung ist untersagt und wird strafrechtlich verfolgt! */
/***********************************************************************************/
/*
	LOTGD-Libsystem für atrahor.de
	scripted + (c) by Alucard
	*Drag&Droplib
	*Drag & Drop Objekte
	*... knuddel*
	<script type="text/javascript"> //das is nur für meinen Editor, damit ich syntaxhighlighting hab :>
*/
//FÜR DRAGOBJEKTE
var DRAO_FREE_MOVE  = 1,
	DRAO_SNAP		= 2,
	DRAO_EXTENDED_STYLE_CHECKER = 4,
	DRAO_DONT_RESERVE_SPACE = 8,
//FÜR DROPOBJEKTE
	DROO_EXTENDED_STYLE_CHECKER = 1,
	DROO_MULTI_Y 	= 2, //untereinander anordnen
	DROO_MULTI_X 	= 4, //nebeneinander anordnen
	DROO_MULTI_Z	= 8,
	DROO_MULTI_APPEND = 16, //neue objekte anhängen
	DROO_MULTI_INSERT = 32, //neue objekte einfügen

//FÜR DRAG & DROP
	DD_NUMERICADD	= -1337; //wenn 1. param bei addDragObjects || addDropObject 
							 //->   2. param: {start: intValue, end: intValue, prefix: stringValue}
							 //     3. param parameterobject für das entsprechende object


LOTGD.__DragDrop = Class.extend(
{
	m_dragObj: [],
	m_dropObj: [],
	m_actDragObj: null,
	m_dragging  : false,
	m_dragDiff  : 0,
	
	construct : function(){
		this.m_dragObj = new Array();
		this.m_dropObj = new Array();
		this.m_dragDiff = Point();
		LOTGD.addEvent( document, 'mousedown', 'mouseDown', this, [], {cB:false,rV: false} );
		LOTGD.addEvent( document, 'mouseup', 'mouseUp', this );
		LOTGD.addEvent( document, 'mousemove', 'move', this, [], {cB:false,rV: false} );
	},
	
	getDDUnderMouse : function( dd ){
		var mp = LOTGD.m_MousePos;
		var cO = null;		
		for( var i=0;i<dd.length; ++i ){
			cO = dd[i];
			if( pointInRect(LOTGD.m_MousePos, Rect( cO.m_pos, cO.m_size ) ) ){
				return cO;
			}
		}
		return null;
	},
	
	addDragObjects : function(){
		var ret = {seg: 0, off: 0};
		var a 	= arguments;
		var ia 	= a.length;
		if( !ia ){ return ret; }
		ret.seg = this.m_dragObj.length;
		if( a[0] == DD_NUMERICADD && isSet(a[1]) ){
			var o, na = a[1], end=parseInt(na.end), po = a[2] || new Class();
			for(var i=parseInt(na.start);i<=end && (o=document.getElementById(na.prefix+i));++i){
				po.obj = o;
				var r = this.addDragObjects( new LOTGD.DragObj(po) );
				ret.off += r.off;
			}
		}
		else{
			for( var i=0;i<ia;i++ ){
				if( a[i].__LOI == 'DragObj' ){
					ret.off++;
					a[i].m_id = this.m_dragObj.length;
					a[i].m_DD = this;
					this.m_dragObj.push(a[i]);
					LOTGD.addEvent( a[i].m_obj, 'mouseover', 'setDrag', this, [ a[i] ], {cB:false,rV: false} );
					LOTGD.addEvent( a[i].m_obj, 'mouseout', 'setDrag', this, [null], {cB:false,rV: false} );
				}
			}
		}
		return ret;
	},	
	
	
	setDrag : function(drag){
		if( !this.m_dragging ){
			this.m_actDragObj = drag;
		}
	},

	
	addDropObjects : function(){
		var ret = {seg: 0, off: 0};
		var a 	= arguments;
		var ia 	= a.length, ret;
		ret.seg = this.m_dropObj.length;
		if( !ia ){  return ret; }
		if( a[0] == DD_NUMERICADD && isSet(a[1]) ){
			var o, na = a[1], end=parseInt(na.end), po = a[2] || new Class();
			for(var i=parseInt(na.start);i<=end && (o=document.getElementById(na.prefix+i));++i){
				po.obj = o;
				var r = this.addDropObjects( new LOTGD.DropObj(po) );
				ret.off += r.off;
			}
		}
		else{
			for( var i=0;i<ia;i++ ){
				if( a[i].__LOI == 'DropObj' ){
					ret.off++;
					a[i].m_id = this.m_dropObj.length;
					a[i].m_DD = this;
					this.m_dropObj.push(a[i]);
				}
			}
		}
		return ret;
	},
	
	
	mouseDown : function(e, op){
		//this.m_actDragObj = this.getDDUnderMouse(this.m_dragObj);
		if( this.m_actDragObj ){
			var trg = e.target||e.srcElement;
			var nn  = trg.nodeName.toLowerCase();
			if( nn == 'input' || nn == 'textarea' ){
				op.m_ret = {cB:false,rV: true};
				return;
			}
			
			Scroller.setActive( true );
			this.m_dragDiff = Point( LOTGD.m_MousePos.x - this.m_actDragObj.m_pos.x, LOTGD.m_MousePos.y - this.m_actDragObj.m_pos.y );
			if(this.m_actDragObj.m_dropobj){
				this.m_actDragObj.m_oldDrop = this.m_actDragObj.m_dropobj.onDrag( this.m_actDragObj );
			}
			this.m_actDragObj.onDrag();
			this.m_dragging = true;
			op.m_ret = {cB:false,rV: false};
		}
		else{
			op.m_ret = {cB:false,rV: true};
		}
	},
	
	mouseUp : function(e){
		this.m_dragging = false;
		Scroller.setActive( false );
		if( this.m_actDragObj ){
			if( 0==(this.m_actDragObj.m_options & DRAO_FREE_MOVE) ){
				this.setDragToDrop(this.m_actDragObj, this.getDDUnderMouse(this.m_dropObj));
			}
			else{
				this.m_actDragObj.onDrop();
				this.m_actDragObj.m_pos.set(LOTGD.m_MousePos.x-this.m_dragDiff.x, LOTGD.m_MousePos.y-this.m_dragDiff.y);
			}
		}
	},
	
	move : function(e){
		if( this.m_dragging ){
			with( this.m_actDragObj.m_obj.style ){
				left= (LOTGD.m_MousePos.x-this.m_dragDiff.x) + 'px';
				top = (LOTGD.m_MousePos.y-this.m_dragDiff.y) + 'px';
			}
		}
	},
	
	setDragToDrop : function( drag, drop, sB ){
		var bSnBk = isSet(sB) ? sB : true;
		if( drop ){
			if( drag.m_options & DRAO_SNAP && drop.accepts(drag) ){
				bSnBk = false;
			}
		}
		if( bSnBk ){
			return drag.snapBack();
		}
		
		if( drop ){
			drag.m_pos.set(0,0);
			drop.onDrop( drag ); 
			drag.m_pos.setP(drop.getPosForDrag( drag, drop.getDragId(drag) ));
		}
				
		drag.onDrop( drop );
		with( drag.m_obj.style ){
			left  = drag.m_pos.x+'px';
			top   = drag.m_pos.y+'px';			
		}
		
	},
	
	hasDragDroped : function( drag, not ){
		for(var i=0, len=this.m_dropObj.length;i<len;++i){
			if( not != this.m_dropObj[i].m_id ){
				if( this.m_dropObj[i].getDragId(drag) != -1 ){
					return i;
				}
			}
		}		
		return -1;
	}
});


LOTGD.DragObj = Class.extend(
{
	m_id		: 0,	//ID im master-drag
	m_DD		: null, //master
	m_dropobj	: null, //dropobjekt auf dem dieses OBJ liegt
	m_obj		: null,	//htmlobjekt
	m_data		: null, //Datenfeld für nutzer
	m_type		: null, //typ
	m_options	: null, //optionen
	m_pos		: null,
	m_size		: null,
	m_active    : true,
	
	m_ondrag	: null, //function bei Drag
	m_ondrop	: null, //function bei Drop
	
	m_oldDrop   : null, //altes dropobj-param
		
	construct : function( __a ){
		this.__LOI 		= 'DragObj';
		
		this.m_obj	   	= (isString(__a.obj)?document.getElementById(__a.obj):__a.obj);	
		this.m_data	   	= __a.data || null;
		
		this.m_options 	= __a.options || 0;
		if( 0==(this.m_options & DRAO_SNAP) ){
			this.m_options |= DRAO_FREE_MOVE;
		}
		this.m_pos	   	= getPos( this.m_obj );	
		this.m_size		= Point( getDivWidth(this.m_obj), getDivHeight(this.m_obj));
		this.m_active  	= isSet(__a.active) ? __a.active :true;
		this.m_ondrag  	= (isFunction(__a.ondrag) ? __a.ondrag : null);
		this.m_ondrop  	= (isFunction(__a.ondrop) ? __a.ondrop : null);
		
		var pos=''; 
		with( this.m_obj.style ){
			if( this.m_options & DRAO_EXTENDED_STYLE_CHECKER ){
				pos = (Browser.isIe ? this.m_obj.currentStyle : window.getComputedStyle(this.m_obj,null)).position;
			}
			else{
				pos			= position;
			}
			cursor		= 'move';
			position 	= 'absolute';
		}
		
		if( this.m_obj.parentNode && 0==(DRAO_DONT_RESERVE_SPACE & this.m_options) ){
			if( isEmpty(pos) || pos.toLowerCase()=='static' ){ 
				var div = document.createElement('img');
				div.src    = LOTGD.m_dir+"img/transparent.gif";
				div.height = this.m_size.y;
				div.width  = this.m_size.x;				
				div.style.visibility = 'hidden';
				if( this.m_obj.nextSibling ){
					this.m_obj.parentNode.insertBefore(div, this.m_obj.nextSibling);
				}
				else{
					this.m_obj.parentNode.appendChild(div);
				}
			}
		}
	},
	
	onDrag : function(){
		this.m_obj.style.zIndex = 200;
		if( this.m_ondrag ){
			try{
				this.m_ondrag( this );
			}catch(e){}
		}
		this.m_dropobj = null;
	},
	
	onDrop : function( __drop ){
		this.m_obj.style.zIndex = 0;
		this.m_dropobj = __drop;
		if( this.m_ondrop ){
			try{
				this.m_ondrop( this, __drop );
			}catch(e){}
		}
	},
	
	snapBack : function(){
		var o   = this.m_oldDrop, drop=null;
		if( o ){
			o._SnBk = true;
			drop    = o._drop
		}
		
		var ret = this.m_DD.setDragToDrop( this, drop, false );
		this.m_oldDrop = null;
		return ret;
	},
	
	moveBy : function( p ){
		this.m_pos.set( this.m_pos.x+p.x, this.m_pos.y+p.y );
		with( this.m_obj.style ){
			left  = this.m_pos.x+'px';
			top   = this.m_pos.y+'px';			
		}
	}
});


LOTGD.DropObj = Class.extend(
{
	m_id	  : null,
	m_DD	  : null,
	m_obj	  : null,
	m_dragObjs: [],
	m_maxDrag : 0,
	m_accepts : null,
	m_pos	  : null,
	m_size	  : null,	
	m_options : 0,
	m_border_offset : null,
	
	m_ondrag  : null,
	m_ondrop  : null,
	

	
	
	
	construct : function( __a ){
		this.__LOI 		= 'DropObj'; 
		this.m_obj	   	= (isString(__a.obj)?document.getElementById(__a.obj):__a.obj);
		this.m_pos 		= getPos(this.m_obj);
		this.m_size		= Point( getDivWidth(this.m_obj), getDivHeight(this.m_obj));
		this.m_options	= __a.options || 0;
		this.m_border_offset = Point();
		this.m_dragObjs = new Array();
		this.m_maxDrag  = __a.maxdrag || 1;
		this.m_ondrag  	= (isFunction(__a.ondrag) ? __a.ondrag : null);
		this.m_ondrop  	= (isFunction(__a.ondrop) ? __a.ondrop : null);
		this.m_accepts	= (isSet(__a.accepts) ? __a.accepts : null);
		
		
		
		if( this.m_options & DROO_EXTENDED_STYLE_CHECKER ){
			var cs = (Browser.isIe ? this.m_obj.currentStyle : window.getComputedStyle(this.m_obj,null));
			this.m_border_offset.set(parseInt(cs.borderLeftWidth), parseInt(cs.borderTopWidth));
		}
		else{
			with( this.m_obj.style ){
				this.m_border_offset.set(parseInt(borderLeftWidth || borderWidth),
										 parseInt(borderTopWidth  || borderWidth));
			}
		}
		
	},
	
	
	
	
	accepts : function ( dragObj ){
		var ret = true;
		if( this.m_dragObjs.length >= this.m_maxDrag ){
			return false;
		}
		if( this.m_accepts ){
			if( isFunction(this.m_accepts) ){
				ret = this.m_accepts( this, dragObj );
			}
			else if( isArray(this.m_accepts) ){
				for( var i=0;i<this.m_accepts.length;++i ){
					if( this.m_accepts[i] == dragObj.m_type ){
						ret = true;
						break;
					}
				}
				ret = false;
			}
			else if( isString(this.m_accepts) ){
				ret = this.m_accepts == dragObj.m_type;
			}
		}
		//ret = (ret && !this.m_DD.hasDragDroped(dragObj));
		return ret;
	},
	
	onDrag : function( __drag ){
		if( this.m_ondrag ){
			try{
				this.m_ondrag( this );
			}catch(e){}
		}
		return this.removeDrag( __drag );
	},
	
	onDrop : function( __drag ){
		if( this.getDragId(__drag) == -1 ){
			if( __drag.m_oldDrop && __drag.m_oldDrop._SnBk){//das is bei snapback
				this.insertDrag(__drag, __drag.m_oldDrop._id );
			}
			else if( this.m_options & DROO_MULTI_INSERT ){
				this.insertDrag(__drag, this.getInsertDragId());
			}
			else{
				this.m_dragObjs.push( __drag ); //das is APPEND
			}
		}
		if( this.m_ondrop ){
			try{
				this.m_ondrop( this, __drag );
			}catch(e){}
		}
	},
	
	getPos : function(){
		return (new Point()).setP(this.m_pos);
	},
	
	getPosForDrag : function( drag, len ){
		var pOff = drag.m_pos;
		var p = this.getPos();
		p.x += pOff.x;
		p.y += pOff.y;
		//var len = ( drag.m_oldDrop && drag.m_oldDrop._SnBk ? drag.m_oldDrop._id : this.m_dragObjs.length-1);
		if( 0==(this.m_options & DROO_MULTI_Z) ){
			for( var i=0; i<len; ++i ){
				if( this.m_options & DROO_MULTI_X ){
					p.x += this.m_dragObjs[i].m_size.x;
				}
				else{
					p.y += this.m_dragObjs[i].m_size.y;
				}
			}
		}

		return p;
	},
	
	removeDrag : function( drag ){
		var id = this.getDragId(drag);
		if( id != -1 ){
			var op = (this.m_options & DROO_MULTI_X);
			this.m_dragObjs.splice(id,1);
			if( 0==(this.m_options & DROO_MULTI_Z) ){
				this.moveDragsBy( id, 0, Point( (op ? -drag.m_size.x : 0), (op ? 0 : -drag.m_size.y) ) );
			}
			return {_drop: this, _id: id, _SnBk: false};
		}
		/*
HINWEIS		hier die anderen elemente nachrücken lassen
		*/
	},
	
	insertDrag : function( drag, pos ){
		var op = (this.m_options & DROO_MULTI_X);
		this.m_dragObjs.splice( pos, 0, drag );
		if( 0==(this.m_options & DROO_MULTI_Z) ){
			this.moveDragsBy( pos+1, 0, Point( (op ? drag.m_size.x : 0), (op ? 0 : drag.m_size.y) ) );
		}
	},
	
	moveDragsBy : function( seg, off, p ){
		off = ( !off ? this.m_dragObjs.length : seg+off);
		for( var i=seg; i<off; ++i ){
			this.m_dragObjs[i].moveBy( p );
		}
	},
	
	getDragId : function( drag ){
		var len = this.m_dragObjs.length;
		for( var i=0; i<len; ++i ){
			if( this.m_dragObjs[i].m_id == drag.m_id ){
				return i;
			}
		}
		return -1;
	},
	
	getInsertDragId : function(/*....*/){
		var len = this.m_dragObjs.length;
		var i, p = LOTGD.m_MousePos, o;
		var op = (this.m_options & DROO_MULTI_X);
		for(i=0; i<len; ++i ){
			o = this.m_dragObjs[i];
			if( (op ? p.x < (o.m_pos.x+o.m_size.x/2) : p.y < (o.m_pos.y+o.m_size.x/2)) ){
				return i;
			}
		}
		return i;
	}
});

var DragDrop = new LOTGD.__DragDrop();


LOTGD.libIsLoaded( 'dragdrop' );
