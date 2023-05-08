/**********************************************************************************/
/* LOTGD-Libsystem fÜr atrahor.de											       */
/* scripted + (c) by Alucard <diablo3-clan[at]web.de>							   */
/* jegliche unautorisierte Nutzung ist untersagt und wird strafrechtlich verfolgt! */
/***********************************************************************************/
LOTGD.loadLibrary("dragdrop");
new libLoadWaiter("dragdrop", RUNES_INIT, true);
function RUNES_INIT(){
	var drag = {seg: 0, off: 0};
	var drop;
	for(var i=0,o=null;(o=document.getElementById("drag"+i));++i){
		drag.off += (DragDrop.addDragObjects( new LOTGD.DragObj({obj: o, data: g_rune_data[i], options: DRAO_SNAP}) )).off;
	}
	g_rune_drop = DragDrop.addDropObjects( DD_NUMERICADD, {start: 0, end:3, prefix: "drop_rune"}, {maxdrag: 1, ondrop: RUNES_DROP, ondrag: RUNES_DRAG, accepts: RUNES_ACCEPT, options: DROO_MULTI_Z}); 
	drop = DragDrop.addDropObjects( DD_NUMERICADD, {start: 1, end:99999999, prefix: "drop"}, {maxdrag: 20000, ondrop: RUNES_DROP, ondrag: RUNES_DRAG, accepts: RUNES_ACCEPT, options: DROO_MULTI_Z}); 
	
	var len = drag.seg + drag.off;
	var d, end = drop.seg+drop.off, dro=DragDrop.m_dropObj[ drop.seg ];
	for( var i=drag.seg;i<len; ++i ){
		d = DragDrop.m_dragObj[i];
		while( drop.seg<end && !dro.accepts(d) ){ drop.seg++; dro=DragDrop.m_dropObj[ drop.seg ]; }
		if( dro ){
			DragDrop.setDragToDrop( d, dro );
		}
	}
	document.getElementById('runes_preload').style.visibility = 'hidden';
	document.getElementById('runes_content').style.visibility = 'visible';
}

function RUNES_ACCEPT( drop, drag ){
	return drop.m_dragObjs.length==0 || drag.m_data.name == drop.m_dragObjs[0].m_data.name;
}

function RUNES_DROP( drop, drag ){
	var n = "text_"+drop.m_obj.id;
	var c = drop.m_dragObjs.length;
	if( drop.m_id >= g_rune_drop.seg && drop.m_id < g_rune_drop.seg+g_rune_drop.off ){
		drag.m_pos.set(13,10);
	}
	else{
		drag.m_pos.set(2,2);
		with(drop.m_obj.style){
			borderStyle = "";
		}
	}		
	
	document.getElementById(n).innerHTML = drag.m_data.name + (c>1?" ("+c+")":"");

}

function RUNES_DRAG( drop ){
	var n = "text_"+drop.m_obj.id;
	var c = drop.m_dragObjs.length-1;
	if( !c && (drop.m_id < g_rune_drop.seg || drop.m_id >= g_rune_drop.seg+g_rune_drop.off)){
		with(drop.m_obj.style){
			borderColor = "#FFFFFF";
			borderWidth = "1px";
			borderStyle = "dotted";
		} 
	}
	document.getElementById(n).innerHTML = (c ? (drop.m_dragObjs[0].m_data.name + (c>1?" ("+c+")" :"")): "&nbsp;" );
}

function RUNES_CHECK(){
	for( var i=g_rune_drop.seg, end=g_rune_drop.seg+g_rune_drop.off;i<end;++i ){
		var dro = DragDrop.m_dropObj[i];
		var d = dro.m_dragObjs[0];
		var o_id = document.getElementById("drop_"+i+"_id"),
			o_tpl = document.getElementById("drop_"+i+"_tpl");
		o_id.value = "";
		o_tpl.value = "";
		if( d ){
			if( -1!=DragDrop.hasDragDroped( d, dro.m_id ) ){
				alert("Mutation entdeckt! Bitte lade die Seite neu.");
				return false;
			}
			else{
				o_id.value = d.m_data.id;
				o_tpl.value = d.m_data.tpl_id;
			}
		}
	}
	return true;
}	

function IE_DEBUG(){
	var s="";
	for( var i=g_rune_drop.seg, len = DragDrop.m_dropObj.length; i<len; ++i ){
		var o=DragDrop.m_dropObj[i];
		s += "\nDrop "+o.m_id+"\n";
		for( var j=0; j<o.m_dragObjs.length; ++j ){
			s += "ID:"+o.m_dragObjs[j].m_id+ " | ";
		}
	}
	alert(s);
}
