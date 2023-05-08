/**********************************************************************************/
/* LOTGD-Libsystem fÜr atrahor.de											       */
/* scripted + (c) by Alucard <diablo3-clan[at]web.de>							   */
/* jegliche unautorisierte Nutzung ist untersagt und wird strafrechtlich verfolgt! */
/***********************************************************************************/
/*
	LOTGD-Libsystem für atrahor.de
	scripted + (c) by Alucard <diablo3-clan[at]web.de>
	*ViewHideib
	*ViewHide
	*Salator knuddel*
	<script type="text/javascript"> //das is nur für meinen Editor, damit ich syntaxhighlighting hab :>
*/

LOTGD.ViewHideViewtype = function( show, hide ){
	return{
		visible: show || 'block',
		hidden:  hide || 'none'
	};
}

LOTGD.ViewHide = function() {
	return{
		m_img: [],
		m_label : [],
		m_main_obj: null,
		m_data_obj: null,
		m_img_obj : null,
		m_label_obj: null,
		m_visibility: false,
		m_eventhandler: null,
		m_viewtype: null,
		
		
		//img (false=kein bild, null=standardbilder, ['view.bild', 'hide.bild'])
		initialize : function(label, data, img, parent, show, viewtype){with(this){
			m_viewtype		= viewtype || LOTGD.ViewHideViewtype();
			m_main_obj 		= document.createElement('span');
			m_main_obj.style.margin = '2px';
			m_img_obj 		= document.createElement('img');
			m_label_obj 	= document.createTextNode('');
			m_eventhandler 	= new LOTGD.EventHandler();
			m_eventhandler.initialize();
			addInto( parent );
			if( typeof( label ) == 'string' ){
				m_label = [ label, label ];
			}
			else{
				m_label = label;
			}
			
			m_data_obj = (data.length && !data.tagName?data:[data]);
			m_img_obj.border = 0;
			var a = document.createElement('a');
			a.href = 'javascript:void(0);';
			
			if( img == false ){}
			else if( img == null ){
				m_img = [ LOTGD.m_config.dir+'img/view.gif', LOTGD.m_config.dir+'img/hide.gif' ];
				a.appendChild( m_img_obj );
			}
			else{
				m_img = img;
				a.appendChild( m_img_obj );
			}
			LOTGD.loadImages( m_img );
			
			a.appendChild( m_label_obj );
			m_main_obj.appendChild( a );
			m_eventhandler.addEvent( a, m_eventhandler.createEvent('click', 'switchVisibility', null, this));
			setVisibility( show );
		}},
		
		addInto : function( HTMLparent ){with(this){
			if( HTMLparent ){
				HTMLparent.appendChild( m_main_obj );
			}
		}},
		
		setVisibility : function( visi ){with(this){
			m_visibility = visi;
			for( var i=0; i<m_data_obj.length; ++i){
				m_data_obj[i].style.display = (visi ? m_viewtype.visible: m_viewtype.hidden);
			}
			m_img_obj.src = m_img[ (visi ? 1 : 0) ];
			m_label_obj.nodeValue = m_label[(visi ? 1 : 0)];
		}},
		
		switchVisibility  : function(){with(this){
			setVisibility( !m_visibility );
		}}	
	};
}

LOTGD.libIsLoaded( 'viewhide' );
