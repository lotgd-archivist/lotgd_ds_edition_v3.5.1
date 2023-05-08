<?php

/* battlesimulator: Einen Gegner mit frei einstellbaren Werten bekämpfen, ohne Abzüge oder Gewinn
* author: Salator (salator@gmx.de)
* requires Dragonslayer-Edition > 2.5 because some new features used
*/

require_once 'common.php';
checkday();
page_header('Blusprings Übungsarena');

function restore_userdata()
{
	global $session;
	$session['bufflist']=array();
	$session['bufflist']=utf8_unserialize($session['user']['buffbackup']);
	if(!is_array($session['bufflist']))
	{
		$session['bufflist']=array();
	}
	$session['user']['hitpoints']=$session['user']['pqtemp'];
	$session['user']['specialtyuses']=utf8_unserialize($session['user']['specialmisc']);
	$session['user']['pqtemp']='';
	$session['user']['badguy']='';
	unset($session['specialsallowed']);
	//nochwas?
}

output('`b`c`IBlusprings Übungsarena`0`c`b`n');
if ($_GET['op']=='')
{
	$badguy=utf8_unserialize(getsetting('battlesimulator','a:8:{s:12:"creaturename";s:14:"`@Minotaurus`0";s:13:"creaturelevel";i:1;s:14:"creatureweapon";s:6:"Hörner";s:14:"creatureattack";i:1;s:15:"creaturedefense";i:40;s:14:"creaturehealth";i:1000;s:4:"maze";i:1;s:9:"diddamage";i:0;}')); //als Init ein Minotaurus
	if($_GET['act']=='save')
	{
		if($_POST['creature']!='0') //Instant-Monster
		{
			$creature=trim($_POST['creature']);
			switch($creature)
			{
				case 'blackman':
				{
				    $badguy = array(
					'creaturename'=>'`4Mysteriöser Mann`0'
					,'creaturelevel'=>$session['user']['level']
					,'creatureweapon'=>'Masamune'
					,'creatureattack'=>$session['user']['attack']
					,'creaturedefense'=>$session['user']['defence']
					,'creaturehealth'=>((int)$session['user']['maxhitpoints']/100)*100+50
					,'diddamage'=>0);

					$atkflux = e_rand(0,$session['user']['dragonkills']/5);
					$defflux = e_rand(0,($session['user']['dragonkills']/5-$atkflux));
					$hpflux = ($session['user']['dragonkills']/5 - ($atkflux+$defflux)) * 5;
					$badguy['creatureattack']+=$atkflux;
					$badguy['creaturedefense']+=$defflux;
					$badguy['creaturehealth']+=$hpflux;
				break;
				}

				case 'bloodchamp':
				{
					if ($session['user']['level']<2)
					{
						$start=0; $span=1;
					}
					elseif ($session['user']['level']<4)
					{
						$start=0; $span=2;
					}
					elseif ($session['user']['level']<6)
					{
						$start=1; $span=2;
					}
					elseif ($session['user']['level']<9)
					{
						$start=2; $span=3;
					}
					elseif ($session['user']['level']<12)
					{
						$start=2; $span=4;
					}
					else
					{
						$start=3; $span=5;
					}
				    $badguy = array(
					'creaturename'=>'`4Champion des Blutgottes`0'
					,'creaturelevel'=>$session['user']['level']+e_rand($start,$span)
					,'creatureweapon'=>'Blutschwert'
					,'creatureattack'=>$session['user']['attack']
					,'creaturedefense'=>$session['user']['defence']
					,'creaturehealth'=>((int)$session['user']['maxhitpoints']/100)*100+50
					,'diddamage'=>0);

					$session['user']['badguy']=createstring($badguy);
					$atkflux = e_rand(0,$session['user']['dragonkills']*2);
					$defflux = e_rand(0,($session['user']['dragonkills']*2-$atkflux));
					$hpflux = ($session['user']['dragonkills']*2 - ($atkflux+$defflux)) * 5;
					$badguy['creatureattack']+=$atkflux;
					$badguy['creaturedefense']+=$defflux;
					$badguy['creaturehealth']+=$hpflux;
					break;
				}

				case 'commander':
				{
			        $badguy = array('creaturename'=>'Kommandant der Dunklen Lande'
			        ,'creaturelevel'=>$session['user']['level']
			        ,'creatureweapon'=>'Schattenklinge'
			        ,'creatureattack'=>$session['user']['attack']*1.2
			        ,'creaturedefense'=>$session['user']['defence']*1.1
			        ,'creaturehealth'=>round($session['user']['maxhitpoints']*0.9)
			        ,'diddamage'=>0);
					break;
				}

				case 'elementar':
				{
					$badguy = array(
					"creaturename"=>"`&Schrein-Wächter`0"
					,"creaturelevel"=>$session['user']['level']+e_rand(1,3)
					,"creatureweapon"=>"Zeigefinger der Unwürdigkeit"
					,"creatureattack"=>$session['user']['attack']*0.75
					,"creaturedefense"=>$session['user']['defence']*0.8
					,"creaturehealth"=>500+$session['user']['dragonkills']*10
					,"diddamage"=>0);

					$atkflux = e_rand(0,$session['user']['dragonkills']*2);
					$defflux = e_rand(0,($session['user']['dragonkills']*2-$atkflux));
					$hpflux = ($session['user']['dragonkills']*2 - ($atkflux+$defflux)) * 5;
					$badguy['creatureattack']+=$atkflux;
					$badguy['creaturedefense']+=$defflux;
					$badguy['creaturehealth']+=$hpflux;
					break;
				}

				case 'god':
				{
			        $badguy = array('creaturename'=>'`4Der Richter'
			        ,'creaturelevel'=>25
			        ,'creatureweapon'=>'`4Der jüngste Tag'
			        ,'creatureattack'=>1000
			        ,'creaturedefense'=>1000
			        ,'creaturehealth'=>10000
			        ,'diddamage'=>0);
					break;
				}

				case 'minotaur':
				{
					$badguy = array(
					'creaturename'=>'`@Minotaurus`0'
					,'creaturelevel'=>0
					,'creatureweapon'=>'Hörner'
					,'creatureattack'=>1
					,'creaturedefense'=>40
					,'creaturehealth'=>1000
					,'maze'=>1
					,'diddamage'=>0);
					$userattack=$session['user']['attack']+e_rand(1,3);
					$userhealth=round($session['user']['hitpoints']/2);
					$userdefense=$session['user']['defense']+e_rand(1,3);
					$badguy['creaturelevel']=$session['user']['level'];
					$badguy['creatureattack']+=($userattack-4);
					$badguy['creaturehealth']+=$userhealth;
					$badguy['creaturedefense']+=$userdefense;
					break;
				}

				case 'orc':
				{
				$badguy=array(
					'creaturename' => '`2Ork-Späher`0', 
					'creaturelevel' => 10, 
					'creatureweapon' => 'Krush-Varrok', 
					'creatureattack' => 100, 
					'creaturedefense' => 100, 
					'creaturehealth' => 1000);
					break;
				}

				case 'pinkdragon':
				{
					$badguy = array("creaturename"=>"`%`bDer Pinke Drache`b`0","creaturelevel"=>18,"creatureweapon"=>"Ein gigantisches Flammenstösschen","creatureattack"=>45,"creaturedefense"=>25,"creaturehealth"=>300, "diddamage"=>0);

					// First, find out how each dragonpoint has been spent and count those
					// used on attack and defense.
					// Coded by JT, based on collaboration with MightyE
					$points = 0;
					if(is_array($session['user']['dragonpoints']))
					{
						foreach($session['user']['dragonpoints'] as $val)
						{
							if ($val=='at' || $val=='de')
							{
								$points++;
							}
						}
					}

					// Now, add points for hitpoint buffs that have been done by the dragon
					// or by potions!
					$points += (int)(($session['user']['maxhitpoints']-150)/5);

					// Okay.. *now* buff the dragon a bit.
					$points = round($points*0.85,0);

					$atkflux = e_rand(0, $points);
					$defflux = e_rand(0,$points-$atkflux);
					$hpflux = ($points - ($atkflux+$defflux)) * 5;
					$badguy['creatureattack']+=$atkflux;
					$badguy['creaturedefense']+=$defflux;
					$badguy['creaturehealth']+=$hpflux;
					$badguy['creaturehealth']*=1.65;

					// Endgegner
					//$badguy['boss'] = true;
					$float_forest_bal = getsetting('forestbal',1.5);
					$badguy['creatureattack'] *= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];
					$badguy['creaturedefense'] *= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];
					$badguy['creaturehealth'] *= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];
					$badguy['creaturehealth'] = round($badguy['creaturehealth']);
					$session['user']['badguy']=createstring($badguy);
					break;
				}

				case 'snakegod':
				{
					$badguy = array(
					'creaturename'=>'`@Wadjet Schlangengöttin`0'
					,'creaturelevel'=>0
					,'creatureweapon'=>'Giftzähne'
					,'creatureattack'=>1
					,'creaturedefense'=>40
					,'creaturehealth'=>1000
					,'maze'=>1
					,'diddamage'=>0);
					$userattack=$session['user']['attack']+e_rand(2,5);
					$userhealth=round($session['user']['hitpoints']/1.25);
					$userdefense=$session['user']['defense']+e_rand(1,4);
					$badguy['creaturelevel']=$session['user']['level'];
					$badguy['creatureattack']+=($userattack-4);
					$badguy['creaturehealth']+=$userhealth;
					$badguy['creaturedefense']+=$userdefense;
					break;
				}

				case 'thive':
				{
				$badguy=array(
					'creaturename' => 'Räuber-Anführer', 
					'creaturelevel' => 7, 
					'creatureweapon' => 'Dolch', 
					'creatureattack' => 170, 
					'creaturedefense' => 30, 
					'creaturehealth' => 5000);
					break;
				}

				default:
				$badguy=array('creaturename' => 'Luft', 'creaturelevel' => 1, 'creatureweapon' => 'Nichts', 'creatureattack' => 1, 'creaturedefense' => 1, 'creaturehealth' => 1);
				break;
			}
		}
		else //selfmade-Monster
		{
			$badguy['creaturename']=($_POST['creaturename']>''?$_POST['creaturename']:$session['user']['name']);
			$badguy['creaturelevel']=((int)$_POST['creaturelevel']>0?intval($_POST['creaturelevel']):$session['user']['level']);
			$badguy['creaturename']=($_POST['creaturename']>''?$_POST['creaturename']:$session['user']['name']);
			$badguy['creatureweapon']=($_POST['creatureweapon']>''?$_POST['creatureweapon']:$session['user']['weapon']);
			$badguy['creatureattack']=((int)$_POST['creatureattack']>0?doubleval($_POST['creatureattack']):$session['user']['attack']);
			$badguy['creaturedefense']=((int)$_POST['creaturedefense']>0?doubleval($_POST['creaturedefense']):$session['user']['defence']);
			$badguy['creaturehealth']=((int)$_POST['creaturehealth']>0?intval($_POST['creaturehealth']):$session['user']['maxhitpoints']);
		}
		$badguy['creaturelevel']=min($badguy['creaturelevel'],25); //Extremwerte machen komische Effekte
		$badguy['creatureattack']=min($badguy['creatureattack'],1000);
		$badguy['creaturedefense']=min($badguy['creaturedefense'],1000);
		$badguy['creaturehealth']=min($badguy['creaturehealth'],10000);
		$badguy['maze']=1; //mazemonster setzen in battle.php nicht die bufflist zurück, das wollen wir ja selbst übernehmen
		savesetting('battlesimulator',utf8_serialize($badguy));
		output('`2Monster gespeichert.`0`n');
	} //end save
	
	//Eingabeformular anzeigen
	$str_enum_monsters = '
		0,keins,
		bloodchamp,Blutchampion,
		pinkdragon,Der Pinke Drache,
		god,Der Richter,
		commander,Kommandant der Dunklen Lande,
		minotaur,Minotaurus,
		blackman,Mysteriöser Mann,
		orc,Ork-Späher,
		thive,Räuber-Anführer,
		snakegod,Wadjet Schlangengöttin,
		elementar,Schrein-Wächter
	';
	$arr_form = array(
		'creature'=>'Fertig-Monster,enum,'.$str_enum_monsters ,
		'creaturename'=>'Monstername',
		'creaturelevel'=>'Monsterlevel,int',
		'creatureweapon'=>'Monsterwaffe',
		'creatureattack'=>'Angriffsstärke,int',
		'creaturedefense'=>'Verteidigungsstärke,int',
		'creaturehealth'=>'Monster-LP,int'
	);
	$str_lnk = 'battlesimulator.php?act=save';
	addnav('',$str_lnk);
	output('Bastle dir dein eigenes Monster zusammen und bekämpfe es ohne Gefahr. Ungültige Werte werden durch deine eigenen Werte ersetzt.
	`nUm ein Fertigmonster zu wählen brauchen die unteren Werte nicht geändert werden, das übernimmt das Script. Die Werte der meisten Fertigmonster sind Char-bezogen und haben zufällige Schwankungen.
	`n`nDu hast 3 Kampf-Arten zur Auswahl:
	`n- wie im Wald, mit allen Fähigkeiten und Zaubern
	`n- wie im Schloss, ohne Tier, aber mit Knappe und Fähigkeiten
	`n- wie beim Meister, ohne alles
	`n`$Achtung! Eingesetzte Zauber werden nicht zurückgegeben.`0`n');
	$str_out = '<form method="POST" action="'.$str_lnk.'">';
	$str_out .= generateform($arr_form,$badguy,false,'Speichern');
	$str_out .= '</form>';
	rawoutput($str_out);
}
		
else if($_GET['op']=='challenge') //Kampf
{
	$session['user']['badguy']=getsetting('battlesimulator','');
	$session['user']['buffbackup']=utf8_serialize($session['bufflist']);
	$session['user']['specialmisc']=utf8_serialize($session['user']['specialtyuses']);
	$session['user']['pqtemp']=$session['user']['hitpoints'];
	$session['specialsallowed']=$_GET['specials'];
	$battle=true;
}
	
if ($_GET['op']=='fight')
{
	$battle=true;
}
if ($_GET['op']=='run')
{
	output('Na, da hast du dich wohl etwas überschätzt. Du ziehst es vor, das Handtuch zu werfen.');
	restore_userdata();
	$battle=false;
}
	
if($battle)
{
	if ($session['specialsallowed']==0 && (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!='')) //jegliche Specials unterbinden
	{
		$_GET['skill']='';
		$session['bufflist']=array();
		output('`&Du hast dich entschieden, während des Kampfes keinen Gebrauch von deinen besonderen Fähigkeiten zu machen!`0');
	}
	elseif ($_GET['specials']==2 && count($session['bufflist'])>0 && is_array($session['bufflist'])) //_GET ist nur beim 1. Aufruf gesetzt
	{
		if (is_array($session['bufflist']['decbuff']))
		{
			$decbuff=$session['bufflist']['decbuff'];
		}
		$session['bufflist']=array();
		if (is_array($decbuff))
		{
			$session['bufflist']['decbuff']=$decbuff;
		}
		output('`&Du hast dich für einen Kampf ohne Tier und Aktionen entschieden!`0');
	}
	if (!$victory) include('battle.php');
	if ($victory)
	{
		headoutput('`c`b`@Sieg!`0`b`c`n`b`$Du hast `%'.$badguy['creaturename'].'`$ bezwungen!`0`b
		`nDu hattest noch '.$session['user']['hitpoints'].' Lebenspunkte übrig.`n`n<hr>`n');
		$battle=false;
		$badguy=array();
		restore_userdata();
	}
	elseif($defeat)
	{
		restore_userdata();
		headoutput('`c`b`$Niederlage!`0`b`c`n`&`bDu wurdest von `%'.$badguy['creaturename'].'`& besiegt!`b`n
		`$Bluespring stoppt `%'.$badguy['creaturename'].'`$ vor dem vernichtenden Schlag und reicht dir seine Hand, um dir auf die Beine zu helfen.`0
		`n'.$badguy['creaturename'].'`0 hatte noch '.$badguy['creaturehealth'].' Lebenspunkte übrig.`n`n<hr>`n');
		$battle=false;
		$badguy=array();
	}
	else
	{
		fightnav(($session['specialsallowed']?true:false),true);
	}
}
if(!$battle)
{
	addnav('Aktionen');
	addnav('Startseite und Einstellung','battlesimulator.php');
	addnav('Kampf');
	addnav('F?Kampf mit allen Fähigkeiten','battlesimulator.php?op=challenge&specials=1');
	addnav('o?Kampf ohne Tier','battlesimulator.php?op=challenge&specials=2');
	addnav('Kampf ohne Fähigkeiten','battlesimulator.php?op=challenge&specials=0');
	addnav('Zurück');
	addnav('T?Zurück zum Trainingslager','train.php');
	addnav('Zurück in die Stadt','village.php');
}

page_footer();
?>
