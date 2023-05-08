<?php
/**
 * @desc Prügelei mit dem Barkeeper Cedrick
 * @author Maris
 * @copyright Maris for Atrahor.de
 */

require_once('common.php');
checkday();

$str_filename = basename(__FILE__);
$str_backlink = 'inn.php';
$str_backtext = 'Zurück zur Schenke';

page_header('Die Prügelei mit Cedrick');

switch ($_GET['op'])
{
	case "boxing": {
		$row_extra = user_get_aei('spittoday');
		if ($row_extra['spittoday']==0)
		{
			$str_output .= "<span style='color: #9900FF'>";
			if ($session['user']['hitpoints']>=$session['user']['maxhitpoints'])
			{
				$str_output .= "Du schleichst mit verschlagenem Blick zum Tresen und es scheint, als wisse Cedrik ganz genau was du vorhast. Sein Grinsen verrät dir, dass er nur darauf wartet.`nNoch hast du die Möglichkeit umzukehren. Weißt du, was du da tust?";
				addnav("Ja");
				addnav("Los gehts...",$str_filename."?op=boxing2&dam=0");
				addnav("Kneifen");
				addnav($str_backtext,$str_backlink);
			}
			else
			{
				$str_output .= "Bist du wahnsinnig ?`nWenn du schon Streit suchst solltest du zumindest in bester körperlicher Verfassung sein!`n";
				addnav('Zurück');
				addnav($str_backtext,$str_backlink);
			}
		}
		else
		{
			$str_output .= "Cedrik grinst dich mit geschwollenem Auge an \"`%Das war ne herrliche Keilerei, aber ich hab zu tun... viele Gäste. Komm doch einfach morgen wieder.`0\"`n`n";
			addnav('Zurück');
			addnav($str_backtext,$str_backlink);
		}
		break;
	}
	case "boxing2": {
		$what=$_GET['what'];
		$ced_dam=$_GET['dam'];

		if (!$what)
		{
			$str_output .= "`4Damit hast du Cedrik sehr, sehr wütend gemacht!`0`n`n";
		}
		else
		{
			$str_output .= "`&Du holst zu einem`^ ";
			switch ($what)
			{
				case 1:
					$str_output .= "Schlag gegen den Kopf ";
					$chance=2;
					break;
				case 2:
					$str_output .= "Kinnhaken ";
					$chance=5;
					break;
				case 3:
					$str_output .= "Schlag gegen die Brust ";
					$chance=1;
					break;
				case 4:
					$str_output .= "Schlag in den Magen ";
					$chance=3;
					break;
				case 5:
					$str_output .= "Tiefschlag ";
					$chance=4;
					break;
			}

			if (e_rand(0,5)>=$chance)
			{
				$str_output .= "`&aus und landest einen Treffer!`n";
				if ($what==1)
				{
					$str_output .= "`#Das klingt aber dumpf...";
				}
				if ($what==2)
				{
					$str_output .= "`#Cedrik taumelt einige Schritte zurück und prallt gegen ein Regal.";
				}
				if ($what==3)
				{
					$str_output .= "`#Cedrik tut so, als habe er es nicht bemerkt.";
				}
				if ($what==4)
				{
					$str_output .= "`#Cedrik wird blass im Gesicht und hält sich eine Hand vor den Mund.";
				}
				if ($what==5)
				{
					$str_output .= "`#Cedrik verdreht die Augen und schreit mit hoher Stimme.";
				}
				$ced_dam+=$chance;
			}
			else
			{
				$str_output .= "`&aus, doch Cedrik blockt ihn gekonnt.`n`n";
			}
			if (e_rand(1,2)==2 && $ced_dam<=15)
			{
				$str_output .= "`4`n`nCedrik trifft dich hart!`0`n`n";
				$punch=0.1*e_rand(1,3);
				$damage=$session['user']['maxhitpoints']*$punch;
				$session['user']['hitpoints']-=$damage;
				$session['user']['hitpoints']-=5;
			}
		}
		if ($session['user']['hitpoints']<=0)
		{
			$str_output .= "`&`nCedrik hat dich windelweich geprügelt und stößt dich zum abkühlen in die Pferdetränke.`0`n`n";
			$session['user']['hitpoints']=1;
			addnav("Erwachen","village.php");
			user_set_aei(array('spittoday'=>1) );
			addnews("`^".$session['user']['name']."`# wurde von `^Cedrik`# verprügelt und in die Pferdetränke gestoßen.");
		}
		else
		{
			if ($ced_dam<=15)
			{
				addnav("Ziele auf seinen Körper!");
				$str_output .= '

                <div><map name="Cedrik">
				<area shape="circle" alt="Kopfnuss" coords="133,25,25" href="'.$str_filename.'?op=boxing2&what=1&dam='.$ced_dam.'" title="Kopfnuss">
				<area shape="circle" alt="Kinnhaken" coords="133,69,19" href="'.$str_filename.'?op=boxing2&what=2&dam='.$ced_dam.'" title="Kinnhaken">
				<area shape="poly" alt="Brustschlag" coords="114,88, 46,139, 43,174, 124,196, 233,179, 197,101, 177,88" href="'.$str_filename.'?op=boxing2&what=3&dam='.$ced_dam.'" title="Brustschlag">
				<area shape="poly" alt="In den Magen" coords="43,173, 43,248, 124,276, 218,270, 242,232, 233,196, 233,181, 124,196" href="'.$str_filename.'?op=boxing2&what=4&dam='.$ced_dam.'" title="In den Magen">
				<area shape="rect" alt="Tiefschlag" coords="89,284,151,373" href="'.$str_filename.'?op=boxing2&what=5&dam='.$ced_dam.'" title="Tiefschlag">
				';

				addpregnav('/'.$str_filename.'\?op=boxing2&what=[1-5]&dam='.$ced_dam.'/');

				$str_output .= '</map></div>`n<p><center><img border="0" src="./images/cedrik.gif" usemap="#Cedrik"></center></p>`n';
				switch ($ced_dam)
				{
					case 0:
					case 1:
						$str_output .= '`@Cedrik geht es blendend.`n`&';
						break;
					case 2:
					case 3:
						$str_output .= '`2Cedrick geht es recht gut.`n`&';
						break;
					case 4:
					case 5:
						$str_output .= '`1Cedrik hält sich gut auf den Beinen.`n`&';
						break;
					case 6:
					case 7:
						$str_output .= '`#Cedrik geht es den Umständen entsprechend gut.`n`&';
						break;
					case 8:
					case 9:
						$str_output .= '`#Cedrik taumelt ein wenig.`n`&';
						break;
					case 10:
					case 11:
						$str_output .= '`^Cedrik geht es gar nicht mehr so gut.`n`&';
						break;
					case 12:
					case 13:
						$str_output .= '`4Cedrik ist recht übel zugerichtet.`n`&';
						break;
					case 14:
					case 15:
						$str_output .= '`$Cedrik steht kurz vor dem k.o.`n`&';
						break;
				}

			}
			else
			{
				$str_output .= "`@`nCedrik geht zu Boden!`nDu schnappst dir ein kleines Fässchen seines hausgebrauten Spezialbieres und machst dich davon.";
				item_add($session['user']['acctid'],'klfale');
				user_set_aei(array('spittoday'=>1) );
				addnav("Zurück",$str_backlink);
			}
		}
		break;
	}
}
output($str_output);
page_footer();
?>