<?php

//Der Heiler

require_once 'common.php';
$str_output .=  '';
$config = utf8_unserialize($session['user']['donationconfig']);
if ($config['healer'] || $session['user']['marks']>30 || $session['user']['acctid']==getsetting('hasegg',0))
{
	$golinda = true;
}

if ($golinda)
{
	page_header('Golindas Hütte');
	$str_output .= '`c`b`ZG`zo`Rl`rin`&das `&H`rü`Rt`zt`Ze`0`b`c`n';
}
else
{
	page_header('Hütte des Heilers');
	$str_output .= '`c`b`.H`|üt`pte `gdes He`pil`|er`.s`0`b`c`n';
}
$loglev = log($session['user']['level']);
$cost = ($loglev * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])) + ($loglev*10);
if ($golinda)
{
	//Bitshift ist viel schneller als Division durch 2
	$cost = $cost >> 1 ;
}
$cost = round($cost,0);

if ($_GET['op']=='')
{
  	checkday();
	if ($golinda)
	{
		$str_output .= '`ZE`zi`Rn`re s`&ehr zierliche und wunderhübsche Brünette schaut auf, als du eintrittst. `Z"Ah, Du musst '.$session['user']['name'].'`Z  sein. Mir wurde gesagt, dass du kommen würdest. Komm rein... komm rein!"`&, ruft sie.`n`nDu gehst tiefer in die Hütte.`n`n';
	}
	else
	{
		$str_output .=  '`.D`|u g`peh`gst gebückt in die rauchgefüllte Grashütte. Das stechende Aroma lässt dich husten und zieht die Aufmerksamkeit einer uralten grauhaarigen Person auf dich, die den Job, dich an einen Felsen zu erinnern, bemerkenswert gut ausführt. Das erklärt, dass du den kleinen Kerl bis jetzt nicht bemerkt hast. Kann ja nicht dein Fehler sein - als Krieger... Nop, definitiv nicht.`n`n';
	}
	if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
	{
		if ($golinda)
		{
			$str_output .=  '`Z"Nun... lass uns mal sehen. Hmmm. Hmmm. Du siehst ein bisschen angeschlagen aus."`n`n`y"Äh... ja. Ich schätze schon. Was wird mich das kosten?"`&, fragst du betreten, `y"Weißt du, normalerweise werde ich nicht so leicht verletzt."`n`n`Z"Ich weiß, ich weiß. Niemand von euch wird `zjemals`Z verletzt. Aber egal. Für `$`b'.$cost.'`b`Z Goldstücke mache ich dich wieder frisch wie einen Sommerregen. '.($session['user']['prefs']['healerdebit']?'Wenn du nicht genug Gold hast nehme ich den Rest von deinem Bankkonto, oder ich kann dich auch zu einem niedrigeren Preis teilweise heilen.':'Ich kann dich auch zu einem niedrigeren Preis teilweise heilen, wenn du dir die volle Heilung nicht leisten kannst.').'"`&, sagt Golinda mit einem süßen Lä`rch`Re`zl`Zn.';
		}
		else
		{
			$str_output .=  '`|"Sehen kann ich dich. Bevor du sehen konntest mich, hmm?"`g bemerkt das alte Wesen. `|"Ich kenne dich, ja; Heilung du suchst. Bereit zu heilen dich ich bin, wenn bereit zu bezahlen du bist."`n`n
`y"Oh-oh. Wieviel?"`g, fragst du, bereit dich von diesem stinkenden alten Dings ausnehmen zu lassen.`n`n
Das alte Wesen pocht dir mit einem knorrigen Stab auf die Rippen: `|"Für dich... `$`b'.$cost.'`b`| Goldstücke für eine komplette Heilung!!"`g. Dabei krümmt es sich und zieht ein Tonfläschchen hinter einem Haufen Schädel hervor. Der Anblick dieses Dings, das sich über den Schädelhaufen krümmt, um das Fläschchen zu holen, verursacht wohl genug geistigen Schaden, um eine größere Flasche zu verlangen.  `|"'.($session['user']['prefs']['healerdebit']?'Nehmen ich werde das fehlende Gold von deinem Bankkonto, auch ich aber habe einige - ähm... \'günstigere\' Tränke im Angebot.':'Auch ich habe einige - ähm... \'günstigere\' Tränke im Angebot.').'"`g sagt das Wesen, während es auf einen verstaubten Haufen zerbrochener Tonkrüge deutet. `|"Heilen sie werden einen bestimmten Prozentsatz deiner `iBeschädigung`i."`n';
		}
		addnav('Heiltränke');
		addnav('`^Komplette Heilung`0','healer.php?op=buy&pct=100');
		for ($i=90;$i>0;$i-=10){
			addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
		}
		addnav('`bZurück`b');
		addnav('W?..in den Wald','forest.php');
		addnav('d?..in die Stadt','village.php');
		addnav('M?..zum Marktplatz','market.php');
	}
	else if($session['user']['hitpoints'] == $session['user']['maxhitpoints'])
	{
		if ($golinda)
		{
			$str_output .= '`ZG`zo`Rl`rin`&da untersucht dich sehr sorgfältig. `Z"Nun, du hast diesen leicht eingewachsenen Zehennagel hier, aber ansonsten bist du vollkommen gesund. `zIch`Z glaube, du bist nur hier her gekommen, weil du einsam warst."`&, kichert sie.`n`nDu erkennst, dass sie Recht hat und dass du sie von ihren anderen Patienten abhältst. Deswegen gehst du zurück in de`rn W`Ra`zl`Zd.';
		}
		else
		{
			$str_output .= '`.D`|ie `pal`gte Kreatur schaut in deine Richtung und grunzt: `|"Einen Heiltrank du nicht brauchst. Warum du mich störst, ich mich frage."`g Der Geruch seines Atems lässt dich wünschen, du wärst gar nicht erst gekommen. Du denkst, es ist das Beste, einfach wieder zu `pge`|he`.n.';
		}
		output($str_output);
		unset($str_output);
		forest(true);
	}
	else
	{
		if ($golinda)
		{
			$str_output .=  '`ZG`zo`Rl`rin`&da untersucht dich sehr sorgfältig. `Z"Ohje! Du hast nicht einmal einen eingewachsenen Zehennagel, den ich heilen könnte! Du bist ein Prachtexemplar der ' . ($session['user']['sex'] == 1 ? 'Frauenschaft' : 'Männerschaft') . '!  Komm bitte wieder, wenn du verletzt wurdest."`& Damit wendet sie sich wieder ihrer Tränkemischerei zu.`n`n`y"Das werde ich."`&, stammelst du unglaublich verlegen und gehst zurück in de`rn W`Ra`zl`Zd.';
		}
		else
		{
			$str_output .=  '`.D`|ie`p al`gte Kreatur blickt dich an und mit einem `pWirbelwind einer Bewegung`g, die dich völlig unvorbereitet erwischt, bringt sie ihren knorrigen Stab in direkten Kontakt mit deinem Hinterkopf. Du stöhnst und brichst zusammen.`n`nLangsam öffnest du die Augen und bemerkst, dass dieses Biest gerade die letzten Tropfen aus einem Tonkrug in deinen Rachen schüttet.`n`n`|"Kostenlos dieser Trank ist."`g ist alles, was es zu sagen hat. Du hast das dringende Bedürfnis, die Hütte so schnell wie möglich zu verl`pas`|se`.n.';
			$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
		}
		output($str_output);
		unset($str_output);
		forest(true);
	}
}
else
{
	$newcost=round($_GET['pct']*$cost/100,0);
	if ($session['user']['gold']>=$newcost || ($session['user']['prefs']['healerdebit'] == true && ($session['user']['gold'] + $session['user']['goldinbank']) >= $newcost))
	{
		if($session['user']['gold'] < $newcost)
		{
			$session['user']['goldinbank'] -= ($newcost - $session['user']['gold']);
			$session['user']['gold'] = 0;
		}
		else
		{
			$session['user']['gold']-=$newcost;
		}

		//debuglog("spent $newcost gold on healing");
		$diff = round(($session['user']['maxhitpoints']-$session['user']['hitpoints'])*(intval($_GET['pct'])/100),0);
		$session['user']['hitpoints'] += $diff;
		if ($golinda)
		{
			$str_output .= "`ZD`zu `Re`rrw`&artest ein fauliges Gesöff und kippst den Trank herunter, aber als die Flüssigkeit dir den Rachen hinunter läuft, schmeckst du Zimt, Honig und irgendetwas fruchtiges. Du fühlst Wärme durch deinen Körper strömen und deine Muskeln fangen an, sich von selbst zusammenzufügen. Mit klarem Kopf und wieder bei bester Gesundheit gibst du Golinda ihr Gold und verlässt die Hütte in Richtun`rg W`Ra`zl`Zd.";
		}
		else
		{
			$str_output .= "`.M`|it `pve`grzerrtem Gesicht kippst du den Trank, den dir die Kreatur gegeben hat, runter. Trotz des fauligen Geschmacks fühlst du, wie sich Wärme in deinen Adern ausbreitet und deine Muskeln heilen. Leicht taumelnd gibst du der Kreatur ihr Geld und verlässt die `pHü`|tt`.e.";
		}

        CQuest::heal();

		$str_output .=  "`n`n`#Du wurdest um $diff Punkte geheilt!";
		if ($_GET['pct']==100 && $session['user']['dragonkills']>3 && e_rand(1,2)==2 && $session['user']['reputation']>0)
		{
			$session['user']['reputation']--;
		}
		output($str_output);
		unset($str_output);
		forest(true);
	}
	else
	{
		if ($golinda)
		{
			$str_output .= '`Z"Tss, tss!"`&, murmelt Golinda. `Z"Vielleicht solltest du erstmal zur Bank gehen und wiederkommen, sobald du `b`$'.$newcost.'`Z`b Gold hast?"`&`n`nDu fühlst dich ziemlich blöde, weil du ihre kostbare Zeit vergeudet hast.`n`n`Z"Oder vielleicht wäre ein billigerer Trank besser für dich?"`&, schlägt sie freundlich vor.';
		}
		else
		{
			$str_output .=  '`gDie alte Kreatur durchbohrt dich mit einem harten, grausamen Blick. Deine blitzschnellen Reflexe ermöglichen dir, dem Schlag mit seinem knorrigen Stab auszuweichen. Vielleicht solltest du erst etwas Gold besorgen, bevor du versuchst, in den lokalen Handel einzusteigen. `n`nDir fällt ein, dass die Kreatur `b`$'.$newcost.'`g`b Goldmünzen verlangt hat.';
		}
		addnav('Heiltränke');
		addnav('`^Komplette Heilung`0','healer.php?op=buy&pct=100');
		for ($i=90;$i>0;$i-=10)
		{
			addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
		}
		addnav('`bZurück`b');
		addnav('W?..in den Wald','forest.php');
		addnav('d?..in die Stadt','village.php');
		addnav('M?..zum Marktplatz','market.php');
	}
}
if(isset($str_output))
{
	output($str_output);
}
page_footer();
?>
