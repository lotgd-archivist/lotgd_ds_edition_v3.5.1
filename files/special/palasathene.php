<?php
/**
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor.de
 * @desc Special um an ein paar Gegenstände für einen Bossgegner zu gelangen
 */

/** @noinspection PhpUndefinedVariableInspection */
if (!$Char instanceof CCharacter)
{
	exit();
}
$str_output ='';
$specialinc_file = 'palasathene.php';

$athenes_badguys = array(
array(
	"creaturename"=>"`6Minitaurus`0"
	,"creaturelevel"=>(min(1,$Char->level-2))
	,"creatureweapon"=>"Hörnchen"
	,"creatureattack"=>($Char->attack+e_rand(0,1))
	,"creaturedefense"=>($Char->defdense+e_rand(0,1))
	,"creaturehealth"=>($Char->maxhitpoints)
	,"diddamage"=>0),
array(
	"creaturename"=>"`6Einhörnchen`0"
	,"creaturelevel"=>$Char->level+2
	,"creatureweapon"=>"Hörnchen"
	,"creatureattack"=>($Char->attack+e_rand(0,10))
	,"creaturedefense"=>($Char->defdense+e_rand(0,10))
	,"creaturehealth"=>($Char->maxhitpoints)
	,"diddamage"=>0),
array(
	"creaturename"=>"`6Minitaurus`0"
	,"creaturelevel"=>$Char->level+3
	,"creatureweapon"=>"Hörnchen"
	,"creatureattack"=>($Char->attack+10)
	,"creaturedefense"=>($Char->defdense+10)
	,"creaturehealth"=>($Char->maxhitpoints+round(log10($Char->maxhitpoints)*100))
	,"diddamage"=>0),
);

$bool_has_blessing = false;
$bool_has_shield = false;
$bool_has_boots = false;
$bool_has_cloak_of_invisibility = false;

if(item_count('i.owner='.$Char->acctid .' AND i.tpl_id = ""')>0)
{
	$bool_has_blessing = true;
}
if(item_count('i.owner='.$Char->acctid .' AND i.tpl_id = ""')>0)
{
	$bool_has_cloak_of_invisibility = true;
}
if(item_count('i.owner='.$Char->acctid .' AND i.tpl_id = ""')>0)
{
	$bool_has_boots = true;
}
if(item_count('i.owner='.$Char->acctid .' AND i.tpl_id = ""')>0)
{
	$bool_has_shield = true;
}

$int_step = 0;
if($bool_has_boots)
{
	$int_step = 1;
}
if($bool_has_cloak_of_invisibility && $bool_has_boots)
{
	$int_step = 2;
}
if($bool_has_cloak_of_invisibility && $bool_has_boots && $bool_has_shield)
{
	$int_step = 3;
}

function pa_write_navs()
{
	global $bool_has_cloak_of_invisibility, $bool_has_boots, $bool_has_shield;

	if($bool_has_cloak_of_invisibility == false)
	{
		addnav('1. Aufgabe:Die Trophäe','forest.php?op=trophy');
	}
	if($bool_has_shield == false)
	{
		addnav('2. Aufgabe:Der Scharfsinn','forest.php?op=wisdom');
	}
	if($bool_has_boots == false)
	{
		addnav('3. Aufgabe:Der Kampf','forest.php?op=fighting');
	}
	addnav('Zurück in den Wald','forest.php?op=leave');
}
if(!isset( $str_out))$str_out = '';
switch ($_GET['op'])
{

	case '':
		{
			if($bool_has_blessing == false)
			{
				$str_out .= words_by_sex('`tAls du aus dem Dickicht hervor brichst, stehst du auf einer lang gezogenen Lichtung. An ihrem Ende kannst du eine Frau erkennen. Sie steht dir zugewandt und scheint schwer gerüstet zu sein, denn ihr Harnisch und Helm blinken im Sonnenlicht. Als du näher trittst spricht sie dich an.`n
				`y"Hallo [mein Lieber|meine Liebe]. Man nennt mich Palas Athene und ich habe eine Aufgabe für dich."`t`n
				Nun? Wie sieht es aus? Hast Du Lust für die Göttin des Krieges und der Jagd zu arbeiten?');
				addnav('Ja, natürlich!','forest.php?op=continue');
				addnav('Nein, danke!','forest.php?op=leave');
			}
			else 
			{
				$str_out .= words_by_sex($Char->name.'`t[mein Champion|meine Kriegerin]! Du bist gekommen um deine Aufgabe für mich, Athene fortzuführen? Welche meiner Prüfungen möchtest du bestehen?');
				pa_write_navs();
			}
			break;
		}
	case 'continue':
		{			
			$str_out .= words_by_sex('`tWissend lächelt Athene dich an. `y"So habe ich mich doch nicht in dir getäuscht, das freut mich. Nun höre mich an"`t, und mit einer gebieterischen Stimme, die keinen Widerspruch duldet, erzählt sie dir ihre Geschichte. `y"Vor ewigen Zeiten bestrafte ich eine junge Gorgone für einen Frevel sondergleichen an mir. Mein Gatte Poseidon buhlte mit Medusa, einer hübschen, aber falschen Schlange und ich bestrafte sie für dieses vergehen. Ich nahm ihr ihre Schönheit und kehrte ihr Innerstes nach außen, auf dass sie ihren unsterblichen Schwestern gleiche und fortan in Schande lebe. Doch Medusa verspottet mich und meine Ehre verbietet es mir sie ein weiteres mal zu bestrafen. Dennoch Sinne ich auf Rache und bitte dich [mein Champion|meine Kriegerin] für mich die Gorgonen aufzusuchen und mir den Kopf der Medusa zu bringen. Doch um der Gorgo gegenüber treten zu können, musst du zunächst meine drei Prüfungen bestehen:`n
			1. Bringe mir eine wahrhaftig mächtige Jagdtrophäe`n
			2. Beweise mir deinen Scharfsinn
			3. Besiege meine drei ausgesuchten Monstrositäten`t`n`n
			Solltest du diese Prüfungen bestehen, werde ich dir mächtige Artefakte geben, die deine Aufgabe erst erfüllbar machen. Nun [mein Champion|meine Kriegerin]? Womit möchtest du beginnen?"`n');
		
			item_add($Char->acctid,'');	
			pa_write_navs();
			break;
		}
	case 'trophy':
		{
				if(empty($_GET['item_id']) || intval($_GET['item_id']) == 0)
				{
					$str_output .= '`tPalas Athene sieht dich erwartungsvoll an. `y"So? Du hast eine Trophäe für mich?"';
					$sql_res = item_list_get('owner = "'.$Char->acctid.'" AND tpl_id = "trph" AND value2="7"');

					if(db_num_rows($sql_res)<1)
					{
						$str_output .= '`yLeider nicht, meine Göttin. Aber sobald ich eine habe, werde ich gewiss wieder zu Euch kommen und sie überreichen!`n';
					}
					else
					{
						$str_output .= '`yIch denke schon, meine Göttin.`n
						`tDu greifst in deinen Beutel und ziehst eine deiner Trophäen hervor:';
						while ($arr_item = db_fetch_assoc($sql_res))
						{
							$str_output .= '`n'.create_lnk($arr_item['name'],'forest.php?op=trophy&item_id='.$arr_item['id']);
						}
					}
					addnav('Zu den Prüfungen','forest.php');
				}
				else
				{
					$arr_item = item_get('id='.$_GET['item_id']);
					if($arr_item === false || mb_strpos($arr_item['name'],'Der Kopf von Stier')===false)
					{
						$str_output .= '`tAthene schüttelt den Kopf und betrachtet "'.$arr_item['name'].'" von allen Seiten. `n`y "Nein, dies ist nicht was ich suche, du musst mir schon etwas bringen was du im Kampfe erlegt hast, z.B. den Kopf eines Stieres"`t`n
						Sie gibt dir die Trophäe wieder."';
						addnav('Eine andere Trophäe anbieten','forest.php?op=trophy');
						addnav('Zu den Prüfungen','forest.php');
					}
					else 
					{
						$str_output .= '`tAthene betrachtet die Trophäe mit interessiertem Blick und sagt dir dann "`y'.$arr_item['name'].'`t ist eine angemessene Trophäe. Ich sehe, du meinst es ernst. Auch ich werde nun meinen teil der Abmachung einhalten und dir eines der Artefakte übergeben."`t Sie übergibt dir eine unscheinbare kleine Kappe für deinen Kopf und erklärt:`y"Dies ist eine Tarnkappe. Sie wird dir gute Dienste leisten, denn solange du sie trägst bist du von sterbliche Augen nicht wahrnehmbar. Nutze diese Gabe Weise"`t';
						item_add($Char->acctid,'');
						item_delete('id='.$_GET['item_id']);
						addnav('Zu den Prüfungen','forest.php');
					}
					
				}
			break;
		}
	case 'fighting':
		{
			if( is_null_or_empty($_GET['fighter']) )
			{
				$str_output .= '`tAthene mustert dich von oben bis unten und meint dann: `y"Ja, du könntest es schaffen. Also höre: '.count($athenes_badguys).' meiner Getreuen werden dich nacheinander fordern. Besiege sie in einem ehrlichen Kampf und das nächste Artefakt sei dein. Solltest du sterben, so bereite dich das nächste mal besser vor."`t';
				$Char->specialmisc = 0;
				addnav('Kämpfe','forest.php?op=fighting&fighter=0');
			}
			else 
			{
				//Nächster Kämpfer
				if( isset($athenes_badguys[(int)$_GET['fighter']]) )
				{
					$str_output .= '`tSehr gut, nun den nächsten!`n`n';
					$Char->badguy = $athenes_badguys[(int)$_GET['fighter']];
				}
				//Gewonnen
				elseif(count($athenes_badguys) < (int)$_GET['fighter'])
				{
					
				}
			}
			break;
		}

	case 'fight':
		{			
			$battle = true;
			break;
		}
	case 'leave':
		{
			$Char->specialinc = '';
			$Char->specialmisc = '';
			$str_out .= '`tDu verabschiedest dich höflich und wendest dich dann ab. Mit ein paar Schritten verlässt du die Lichtung und lässt Athene zurück.';
			break;
		}
	default:
		{

			break;
		}
}


//Kämpfööön
if ($battle)
{
	include ("battle.php");
	if ($victory)
	{
		$str_out = "`n`0Du hast `^".$badguy['creaturename']." geschlagen und das auch noch ganz ohne Malzbier!
		Schnell begibst du dich zu der Truhe und öffnest sie, wobei ein Schatz von 5.000 Gold und 5 Edelsteinen zum Vorschein kommt. Zufrieden sammelst du die Beute ein und kehrst in den Wald zurück.";
		$badguy=array();
		$Char->badguy="";
		addnav('Weiter','forest.php?op=fighting&fighter='.$Char->specialmisc+1);
	}
	elseif($defeat)
	{
		$str_out = '`0Du wurdest getötet...`n`n
		So lange, wie du nun schon in dieser Stadt lebst, kannst du dir denken was jetzt kommt, richtig? Richtig! Schöne Grüße an Ramius!';

		killplayer();
		$Char->specialinc='';
		$Char->specialmisc='';
		$Char->reputation--;
	}
	else
	{
		fightnav();
	}
}

output($str_out);

?>