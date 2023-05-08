/**********************************************************************************/
/* LOTGD-Libsystem fÜr atrahor.de											       */
/* scripted + (c) by Alucard <diablo3-clan[at]web.de>							   */
/* jegliche unautorisierte Nutzung ist untersagt und wird strafrechtlich verfolgt! */
/***********************************************************************************/
/*
	LOTGD-Libsystem für atrahor.de
	scripted + (c) by Alucard <diablo3-clan[at]web.de>
	*Plumi
	*Zusammenklappen und so :>
	*sunny knuddel*
	<script type="text/javascript"> //das is nur für meinen Editor, damit ich syntaxhighlighting hab :>
*/
LOTGD.loadLibrary("httprequest");
var PLU_MI_VALUES  = new (Class.extend())();

function PLU_MI_display( obj, show){
	if(!show){ return "none"; }
	else if(Browser.isIe){
		return "block";
	}
	switch( obj.tagName.toLowerCase() ){
		case "img": return "inline"; break;
		case "tr": return "table-row"; break;							
		case "table": return "table"; break;
		case "td":
		case "th": return "table-cell"; break;
		case "tbody": return "table-row-group"; break;
		
		default:
			return "block";
		break;
	}
}

function PLU_MI(id, show, bReq ){
	var pmp = document.getElementById("plu_mi_plu_"+id);
	var pmm = document.getElementById("plu_mi_mi_"+id);
	if( pmp && pmm ){
		if( !show ){
			pmp.style.display = "inline";
			pmm.style.display = "none";	
		}
		else{
			pmp.style.display = "none";
			pmm.style.display = "inline";
		}
	}

	var o=document.getElementById(id);;
	if( o ){
		o.style.display = PLU_MI_display(o,show);
	}
	for(var i=0;(o=document.getElementById(id+i));++i){
		o.style.display = PLU_MI_display(o,show);	
	}
	if( bReq ){
		var r = new LOTGD.HTTPRequest();
		var p = new LOTGD.HTTPPostVars();
		p.addVar("field",id);
		r.send(	"httpreq.php?op=switch_plu_mi",
				function (req) {LOTGD.parseCommand(LOTGD.getCommandFromRequest(req));},
				null,
				p);
	}
	try{
		eval("onPLUMI_"+id+"(show)");
	}
	catch(e){}
}
