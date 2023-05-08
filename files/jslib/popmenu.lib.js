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

LOTGD.loadLibrary( 'menu' );
var POP_HIDE 		= 0;
var POP_SHOW_MOUSE 	= 1;
var POP_SHOW_AT		= 2;

function JSLIB_DEFINE_POPMENU(){
	LOTGD.popMenu = LOTGD.Menu.extend(
	{
		m_onShow		: [],
		m_isOpen		: false,
		m_isDocOpen		: false,
		construct : function(){
			arguments.callee.$.construct.call(this);
			this.__LOI = 'popMenu';
		},
		
		show : function( p, xy ){
			if( !isNull(this.m_subopen) ){
				this.m_subopen.setVisibility(false);
			}
			p = isSet(p) ? (p==POP_SHOW_MOUSE&&!isSet(xy)?POP_SHOW_MOUSE:p) : POP_SHOW_MOUSE;
			var xy = Point();
			this.setVisibility( true );
			if( p==POP_SHOW_MOUSE ){
				var  o = this.m_container;
				while( o.parenNode ){
					o = o.parentNode;
					xy.set( xy.x + o.scrollLeft, xy.y + o.scrollTop);
				}
				
				var h  = getDivHeight(this.m_container),
					w  = getDivWidth(this.m_container),
				//mauspos
					ly = LOTGD.m_MousePos.y,
					lx = LOTGD.m_MousePos.x,
				//scrolloffset
					ox = parseInt(window.pageXOffset||document.body.scrollLeft||document.documentElement.scrollLeft),
					oy = parseInt(window.pageYOffset||document.body.scrollTop||document.documentElement.scrollTop);
				// bloß nach oben / links ausweichen, wenn genug Platz!
				if( h+ly > (getDivHeight(document.body)+oy) && -h+ly > 0 ){
					h = -(h);
				}else{h=0;}
				if( w+lx > (getDivWidth(document.body)+ox) && -w+lx > 0 ){
					w = -(w);
				}else{w=0;}
				xy.set( xy.x+lx+w, xy.y+ly+h);
			}
			else if( p == POP_HIDE ){
				this.setVisibility( false );
				return;
			}
			else if( p == POP_SHOW_AT ){
				
			}
			
			with( this.m_container.style ){
				left = xy.x + 'px';
				top  = xy.y + 'px';
			}
			
			for( var i = 0; i<this.m_onShow.length; i++ ){
				try{
					this.m_onShow[ i ]();
				}catch(e){}
			}
			this.m_isOpen = true;
			this.m_isDocOpen = false;
		},
		
		hide : function( doc ){
			if( this.m_isOpen ){
				if( doc ){
					if( this.m_isDocOpen ){
						this.setVisibility( false );
						this.m_isOpen = !this.m_isOpen;
					}
					this.m_isDocOpen = !this.m_isDocOpen;
				}
				else if( !doc ){
					this.setVisibility( false );
					this.m_isOpen = !this.m_isOpen;
				}
			}
		},
		
		showAt : function( obj, cN, cT ){
			arguments.callee.$.showAt.call(this, (isNone(obj)? document.body : obj), cN, cT);
			this.m_container.style.position = 'absolute';
			this.setVisibility( false );
			LOTGD.addEvent(document, 'click', 'hide', this, [1]);
		}
		
		//end class def
	});//end extend
	LOTGD.libIsLoaded( 'popmenu' );
}
new libLoadWaiter('menu', JSLIB_DEFINE_POPMENU);



