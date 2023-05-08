<?php
/*

The Inn Lottery by unknown
Found at sourceforge project page
Modifications and translation by anpera
Salator 22.12.2008: Code überarbeitet, kaputtes JS ersetzt, echte Losmenge statt Zufallszahl

*/

require_once 'common.php';
page_header('Lotterie');

$myname = $session['user']['name'];
$jackpot = getsetting('jackpot',100);
$winnumber = getsetting('lottonumber',123);
$cost = $session['user']['level']*5;

$rowe = user_get_aei('lottery');

function fillstack()
{
	global $stack;
	for ($i=1;$i<=500;$i++)
	{
		$stack[$i]=$i;
		shuffle($stack);
	}
}

if($_GET['op']=='buy')
{
	if($session['user']['gold']<$cost)
	{
		output('`^Ein Los kostet '.$cost.' Gold! Soviel hast du nicht dabei.`n');
	}
	else
	{
		$stack=getsetting('lottery_stack','');
		$stack=explode(',',$stack);
		$count=count($stack);
		if(count($stack)<=1)
		{
			fillstack();
		}
		$lot = e_rand(1,$count);
		user_set_aei(array('lottery'=>$lot));
		$session['user']['gold']-=$cost;
		savesetting('jackpot',(string)(getsetting('jackpot',0)+ ($cost)));
		output("`^Die Nummer auf deinem Los ist `0`b`@".$stack[$lot]."`0`b`^...`n");
		if ($stack[$lot] == $winnumber)
		{
			output('`0`c`^DU HAST GEWONNEN!!!!! DIE NUMMERN STIMMEN ÜBEREIN! DU GEWINNST `b'.$jackpot.' GOLD!`b`0`c`n');
			$session['user']['gold']+=getsetting('jackpot',0);
			fillstack();
			savesetting('jackpot',100);
			savesetting('lottonumber',e_rand(100,500));
			addnews($session['user']['name'].' `^hat den Jackpot geknackt und '.$jackpot.' Gold gewonnen.');
			
// eins der Bilder hat nen Link, versucht den mal zu treffen! Könnte man ja fast n Spiel draus bauen...
			output(JS::encapsulate('

/******************************************
* Snow Effect Script- By Altan d.o.o. (http://www.altan.hr/snow/index.html)
* Visit Dynamic Drive DHTML code library (http://www.dynamicdrive.com/) for full source code
* Last updated Nov 9th, 05 by DD. This notice must stay intact for use
******************************************/
  
  //Configure below to change URL path to the snow image
  var snowsrc="./images/muenze_2.png"
  // Configure below to change number of snow to render
  var no = 30;
  // Configure whether snow should disappear after x seconds (0=never):
  var hidesnowtime = 0;
  // Configure how much snow should drop down before fading ("windowheight" or "pageheight")
  var snowdistance = "pageheight";

///////////Stop Config//////////////////////////////////

  var ie4up = (document.all) ? 1 : 0;
  var ns6up = (document.getElementById&&!document.all) ? 1 : 0;

  function iecompattest(){
  return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
  }

  var dx, xp, yp;    // coordinate and position variables
  var am, stx, sty;  // amplitude and step variables
  var i, doc_width = 800, doc_height = 600; 
  
  if (ns6up) {
    doc_width = self.innerWidth;
    doc_height = self.innerHeight;
  } else if (ie4up) {
    doc_width = iecompattest().clientWidth;
    doc_height = iecompattest().clientHeight;
  }

  dx = new Array();
  xp = new Array();
  yp = new Array();
  am = new Array();
  stx = new Array();
  sty = new Array();
  snowsrc=(snowsrc.indexOf("dynamicdrive.com")!=-1)? "snow.gif" : snowsrc
  for (i = 0; i < no; ++ i) {  
    dx[i] = 0;                        // set coordinate variables
    xp[i] = Math.random()*(doc_width-50);  // set position variables
    yp[i] = Math.random()*doc_height;
    am[i] = Math.random()*20;         // set amplitude variables
    stx[i] = 0.02 + Math.random()/10; // set step variables
    sty[i] = 3.7 + Math.random();     // set step variables
    if (ie4up||ns6up) {
      if (i == 0) {
        document.write("<div id=\"dot"+ i +"\" style=\"POSITION: absolute; Z-INDEX: "+ i +"; VISIBILITY: visible; TOP: 15px; LEFT: 15px;\"><a href=\"http://dynamicdrive.com\" target=\"_blank\"><img src=\'"+snowsrc+"\' border=\"0\"><\/a><\/div>");
      } else {
        document.write("<div id=\"dot"+ i +"\" style=\"POSITION: absolute; Z-INDEX: "+ i +"; VISIBILITY: visible; TOP: 15px; LEFT: 15px;\"><img src=\'"+snowsrc+"\' border=\"0\"><\/div>");
      }
    }
  }

  function snowIE_NS6() {  // IE and NS6 main animation function
    doc_width = ns6up?window.innerWidth-10 : iecompattest().clientWidth-10;
    doc_height=(window.innerHeight && snowdistance=="windowheight")? window.innerHeight : (ie4up && snowdistance=="windowheight")?  iecompattest().clientHeight : (ie4up && !window.opera && snowdistance=="pageheight")? iecompattest().scrollHeight : iecompattest().offsetHeight;
    for (i = 0; i < no; ++ i) {  // iterate for every dot
      yp[i] += sty[i];
      if (yp[i] > doc_height-50) {
        xp[i] = Math.random()*(doc_width-am[i]-30);
        yp[i] = 0;
        stx[i] = 0.02 + Math.random()/10;
        sty[i] = 2.7 + Math.random();
      }
      dx[i] += stx[i];
      document.getElementById("dot"+i).style.top=yp[i]+"px";
      document.getElementById("dot"+i).style.left=xp[i] + am[i]*Math.sin(dx[i])+"px";  
    }
    snowtimer=setTimeout("snowIE_NS6()", 10);
  }

  function hidesnow(){
    if (window.snowtimer) clearTimeout(snowtimer)
    for (i=0; i<no; i++) document.getElementById("dot"+i).style.visibility="hidden"
  }


if (ie4up||ns6up){
    snowIE_NS6();
    if (hidesnowtime>0)
    setTimeout("hidesnow()", hidesnowtime*1000)
    }
'));

		}
		else
		{
			output('`^Schade, diesmal hast du kein Glück gehabt...`n');
		}
		unset($stack[$lot]);
		savesetting('lottery_stack',implode(',',$stack));
	}
}
else
{
	if($rowe['lottery']<1)
	{
		addnav('Los kaufen','lottery.php?op=buy');
		output('`^Du kannst jeden Tag ein Los kaufen und dein Glück damit versuchen, den Jackpot zu knacken.
		Um zu gewinnen, muss die Zahl auf deinem Los mit der Gewinn-Nummer übereinstimmen.
		Ein Los kostet dich '.$cost.' Gold.
		Je mehr Leute Lose kaufen, umso höher steigt der Jackpot.
		Sobald der Jackpot geknackt ist, wird eine neue Gewinn-Nummer festgelegt.
		`n`n`0 &nbsp; &nbsp; `i`7Jackpot: `^'.$jackpot.'`7 Gold!`0`i
		`n &nbsp; &nbsp; `i`7Gewinn-Nummer: `@'.$winnumber.'`0`i`n`n');
	}
	else
	{
		output('`7Du hast heute schon dein Glück versucht. Bitte warte bis morgen.`n');
	}
}

addnav('Zurück zur Bar','inn.php');
page_footer();
?>