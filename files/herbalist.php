<?php
/************************************************
* Die Kräuterhexe
* Verkauf von Zutaten wie im Zauberladen, hier mit Multiselect
* Autor: Salator (salator@gmx.de)
* für lotgd Dragonslayer Version 3.23
* Dieses Script widme ich meiner Mutter, Heilpraktikerin und Kräuterhexe Johanna R.
*************************************************/

require_once('common.php');
require_once(LIB_PATH.'board.lib.php');
checkday();
page_header('Die Kräuterfrau');
$str_filename=basename(__FILE__);
define('DP_KOSTEN_SPECIAL_ITEM',100);

$str_out=get_title('`tA`yl`8lerley Kräuter und Tinktur`ye`tn');

if(!empty($_POST['ids']) && is_array($_POST['ids'])) //ETWAS KAUFEN
{
	$str_out.='Du gibst Johanna eine Liste mit den Dingen, die du kaufen möchtest:`n';
	foreach($_POST['ids'] as $tpl_id => $anz)
	{
		if(intval($anz)>0)
		{
			$item_tpl=item_get_tpl('tpl_id="'.$tpl_id.'"');
			$gold=($item_tpl['tpl_gold'] * $anz);
			$gems=($item_tpl['tpl_gems'] * $anz);
			$str_out.='`n'.$anz.'x '.$item_tpl['tpl_name'];
			
			if($session['user']['gold'] - $gold >=0
			&& $session['user']['gems'] - $gems >=0)
			{
				for($i=0;$i<$anz;$i++)
				{
					item_add($session['user']['acctid'],$tpl_id);
					$counter++;
				}
				$session['user']['gold'] -= $gold;
				$session['user']['gems'] -= $gems;
				$goldsum+=$gold;
				$gemssum+=$gems;
			}
			else
			{
				$str_out.='`$ zu teuer!`0';
				$counter+=0;
			}
		}
	}
	
	if($counter>0)
	{
		$str_out.='`0`n"`@Das macht dann zusammen `^'.$goldsum.'`0 <img src="./images/icons/gold.gif" alt="Gold"> '.($gemssum>0?'`@und `#'.$gemssum.'`0 <img src="./images/icons/gem.gif" alt="ES">':'').'`0", sagt Johanna zu dir, während sie dir die Ware '.($counter>15?'in einen größeren Weidenkorb':'in einen kleinen Beutel').' packt.';
	}
	else if (!isset($counter))
	{
		$str_out.='`n1 Flasche Absinth
		`n`ndoch Johanna weist dich freundlich darauf hin, dass sie `bdiese Art Kräuter`b nicht im Angebot hat.';
	}
} //END KAUFEN

else //LADEN BETRETEN
{
	$str_out.='`tA`yl`8s du den Laden betrittst sticht dir sofort ein markanter, aber doch angenehmer Kräuterduft in die Nase. Du genießt es jedes Mal aufs Neue, erstmal einen tiefen Atemzug zu nehmen.
	`nEinen Moment lang hast du Zeit, dich umzusehen. Der ganze Raum ist mit Leinen durchzogen, an denen Kräuter zum Trocknen hängen. Darunter liegen in vielen breiten Kisten allerlei Pilze und kleinere Pflanzen, die nicht für eine hängende Trocknung geeignet sind. An der hinteren Wand steht ein Regal, worin sich allerlei hölzerne, irdene und gläserne Behältnisse finden.
	`nJohanna, eine Menschenfrau mittleren Alters, unterbricht das Sortieren ihrer Waren, als sie dich bemerkt. Sie begrüßt dich herzlich und erkundigt sich nach deinem Befinden.
	`n';
	if($session['user']['spirits']==RP_RESURRECTION)
		$str_out.='Du klagst dein Leid, dass du dich hundeelend fühlst. Doch Johanna beruhigt dich, dieser Zustand ist völlig normal wenn Körper und Geist keine Einheit bilden. Morgen früh, zum ersten Hahnenschrei, werden deine Beschwerden vergessen se`yi`tn.';
	if($session['user']['drunkenness']>60)
		$str_out.='Du erzählst davon, dass du öfter unter Kopfschmerzen leidest. Johanna schaut dich an und empfiehlt dir, mit dem Ale sparsamer zu sein. Und wenn es doch mal eins zuviel war hilft viel frisches, klares Wasser - Am besten noch vor dem Schlafengehen getrunken- gegen den Kat`ye`tr.';
	elseif($session['user']['hitpoints']<5)
		$str_out.='Du klagst über starke Schmerzen in allen Gliedern. Johanna empfiehlt dir dringend, die Sache von einem Heiler behandeln zu lassen. Kräuter allein sind hier wenig erfolgrei`yc`th.';
	elseif($session['user']['hitpoints']<$session['user']['maxhitpoints']*0.5)
		$str_out.='Du klagst, dass deine Wunden, die du dir im Kampf zugezogen hast, schmerzen. Johanna empfiehlt dir, deine Wunden mit Ringelblumenextrakt zu versorgen. Dies fördert die Heilu`yn`tg.';
	elseif($session['user']['hitpoints']<$session['user']['maxhitpoints']*0.95)
		$str_out.='Du klagst über deine Erkältung. Johanna empfiehlt dir, einen heißen Aufguss aus Kamillenblüten zu machen und die Dämpfe zu inhalieren. Gegen den ständigen Hustenreiz wirkt ein Tee aus Salbeiblättern oder Spitzwegerich. Und eine halbe Zwiebel neben dem Kopfkissen hält die Atemwege im Schlaf fr`ye`ti.';
	elseif($session['user']['hitpoints']>$session['user']['maxhitpoints'])
		$str_out.='Du erzählst, dass du dich blendend fühlst und Bäume ausreißen könntest. Johanna ist erfreut, das zu hören. Sie empfiehlt jedoch, das mit den Bäumen nicht wörtlich zu nehmen, da bekommt man nur Ärger mit den Elf`ye`tn.';
	else
		$str_out.='Du erzählst von diesen und jenen kleinen Zipperlein, man wird ja auch nicht jünger... Johanna empfiehlt dir ein Kirschkernkissen. Im Kamin gut angewärmt speichert es die Wärme lange. Das ist nicht nur angenehm sondern hilft auch z. B. bei Bauch- und Rückenschmerz`ye`tn.';
	$str_out.='`n`n`tI`yh`8r unterhaltet euch eine ganze Weile so angeregt,  du merkst gar nicht wie die Zeit vergeht. Schließlich fällt dir wieder ein, du wolltest ja ein paar Dinge kaufen. Also durchstöberst du Johannas War`ye`tn...';
}

$rowc['id']=24; //db-Abfrage sparen
/*
$sql = 'SELECT id FROM items_classes WHERE class_name="Zutaten"';
$result = db_query($sql);
$rowc = db_fetch_assoc($result);
*/

//ANGEBOTSLISTE
$sql = 'SELECT tpl_id,tpl_name,tpl_description,tpl_gold,tpl_gems
	FROM items_tpl
	WHERE tpl_class='.$rowc['id'].'
	AND (spellshop = 1 OR spellshop = 3)
	ORDER BY tpl_id ASC';
$result = db_query($sql);

$str_out.='`n`n`tD`yi`8e Kräuterfrau kann dir diese Dinge verkauf`ye`tn:`0
<form action="'.$str_filename.'" method="post">
`n`n`c<table border="0" cellpadding="0" width="70%">
<tr class="trhead">
<th>Kräuter und Zutaten</th>
</tr>';

for ($i=0;$i<db_num_rows($result);$i++)
{
	$row = db_fetch_assoc($result);
	$bgcolor=($bgcolor=='trdark'?'trlight':'trdark');
	$kosten_gold = $row['tpl_gold'];
	$kosten_es = $row['tpl_gems'];
	$kosten = ($kosten_gold>0 ? "`^".$kosten_gold." <img src='./images/icons/gold.gif' alt='Gold'>`0 " : "" ).($kosten_es>0 ? "`7".$kosten_es." <img src='./images/icons/gem.gif' alt='Edelsteine'>`0" : "");
	$str_out.='<tr class="'.$bgcolor.'">
		<td>`b'.$row['tpl_name'].'`0`b</td>
	</tr>
	<tr class="'.$bgcolor.'">
		<td>`&'.$row['tpl_description'].'
		`n`n'.$kosten.'`0</td>
	</tr>
	<tr class="'.$bgcolor.'">
		<td>Menge: <input type="text" size="3" maxlength="2" name="ids['.$row['tpl_id'].']"></td>
	</tr>';
}
$str_out.='</table>`c
`nAusgewählte Produkte <input type="submit" class="button" value="Kaufen">
</form>';

addnav('',$str_filename);
$show_invent = true;

output($str_out);
addnav('Zurück zum Markt','market.php');
page_footer();
?>