<?php
include_once(LIB_PATH.'runes.lib.php');


$arr_sentences = array(
	array( 	'text'  => 'Erkunde die innere Kraft durch Konzentration und du wirst im Einklang mit der Welt sein!',
			'runes'	=> array(R_INGWAZ, R_OTHALA, R_LAGUZ) ),
	array( 	'text'  => 'Licht schafft Neubeginn im Lebenslauf!',
			'runes'	=> array(R_DAGAZ, R_BERKANA, R_MANNAZ) ),
	array( 	'text'  => 'Sprache und Wort schützen vor Stillstand!',
			'runes'	=> array(R_ANSUZ, R_ALGIZ, R_ISA) ),
	array( 	'text'  => 'Glück beim Neubeginn führt zu Wohlstand!',
			'runes'	=> array(R_SOWILO, R_BERKANA, R_FEHU) ),
	array( 	'text'  => 'Ernte das Licht und du erhältst Erleuchtung!',
			'runes'	=> array(R_JERA, R_DAGAZ, R_KENAZ) ),
	array( 	'text'  => 'Die Ausgewogenheit der Lebens- und Weltenzyklen benötigt Schutz!',
			'runes'	=> array(R_WUNJO, R_RAIDHO, R_ALGIZ) ),
	array( 	'text'  => 'Wohlstand und Stärke sind Glück!',
			'runes'	=> array(R_FEHU, R_URUZ, R_SOWILO) ),
	array( 	'text'  => 'Die Entscheidung der Autorität ist eine Herausforderung!',
			'runes'	=> array(R_PETHRO, R_THURISAZ, R_HAGALAZ) ),
	array( 	'text'  => 'Der Stillstand der Ernte verlangt eine Entscheidung!',
			'runes'	=> array(R_ISA, R_JERA, R_PETHRO) ),
	array( 	'text'  => 'Licht schafft Neubeginn im Lebenslauf',
			'runes'	=> array(R_DAGAZ, R_BERKANA, R_MANNAZ) ),
	array( 	'text'  => 'Die Transformation von Neubeginn zu Fortschritt ist essentiell!',
			'runes'	=> array(R_EIWAZ, R_BERKANA, R_EHWAZ) ),
	array( 	'text'  => 'Schütze deinen Wohlstand durch deine Stärke!',
			'runes'	=> array(R_ALGIZ, R_FEHU, R_URUZ) ),
	array( 	'text'  => 'Die Autorität hat das Geschenk der Entscheidung!',
			'runes'	=> array(R_THURISAZ, R_GEBO, R_PETHRO) ),
	array( 	'text'  => 'Schätze die Einweihung, denn sie ist Herausforderung und Glück zugleich!',
			'runes'	=> array(R_TEIWAZ, R_HAGALAZ, R_SOWILO) ),
	array( 	'text'  => 'Licht schafft Neubeginn im Lebenslauf!',
			'runes'	=> array(R_DAGAZ, R_BERKANA, R_MANNAZ) )
);

$str_out 						= '`q';
$str_self 						= basename(__FILE__);
$session['user']['specialinc']	= $str_self;


switch($_GET['op']){
	
	case 'go':
		$str_out .= 'Du entscheidest dich, den Runenmeister doch lieber in Ruhe zu lassen und gehst.';
		$session['user']['specialinc'] = '';
	break;
	
	case 'no':
		$str_out .= 'Du entscheidest dich, dem Runenmeister nicht zu helfen und verabschiedest dich von ihm.';
		$session['user']['specialinc'] = '';
	break;
	
	case 'talk':
		$str_out .= 'Du fasst all deinen Mut zusammen und sprichst den Runenmeister an.`n
					`&"Seid gegrüßt '.$session['user']['name'].'`&! Ich habe hier ein Problem. Könnt ihr mir helfen?"`n`n
					`qDu antwortest Ihm: `n';
		$known = runes_get_known();
		if( count($known) < 3 ){
			$str_out .= create_lnk('Tut mir leid. Ich bin zu Unwissend.','forest.php?op=no',true,true,'',false,'Nein',CREATE_LINK_LEFT_NAV_HOTKEY).'`n`';
		}
		else{
			$str_out .= create_lnk('Tut mir leid. Ich habe keine Zeit.','forest.php?op=no',true,true,'',false,'Nein',CREATE_LINK_LEFT_NAV_HOTKEY).'`n`';
			$str_out .= create_lnk('Ja, gern! Worum geht es denn?','forest.php?op=help',true,true,'',false,'Ja',CREATE_LINK_LEFT_NAV_HOTKEY).'`n`';
		}
	break;
	
	case 'help':
		$str_out .= 'Du sagst: `&"Ja, gern! Worum geht es denn?"`n
					`qEr nickt und fängt an zu erklären: `&"Ich habe hier einen Spruch, den ich mit Runen nachbilden muss, jedoch werde ich nicht jünger und deshalb setzt mein Gedächtnis manchmal aus.`n
					Hilf mir bitte, den Spruch nachzubilden! Doch hüte dich, manche Kombinationen beschwören böse Geister!"`n
					"Nichts leichter als das!"`q, antwortest du und machst dich ans Werk.`n`n';

		$optns 	= '';
		$runes 	= runes_get_ei( array_keys(runes_get_known()) );
		$num  	= db_num_rows($runes);
		for( $i=0; $i<$num; ++$i ){
			$r = db_fetch_assoc($runes);
			$optns .= '<option value="'.$r['id'].'" '.($i ? '' : 'selected').'>'.$r['name'].'</option>';
		}
		
		$riddle_id 	= array_rand($arr_sentences);
		$riddle 	= $arr_sentences[$riddle_id];
		$link 		= 'forest.php?op=say&id='.((int)$riddle_id);
		addnav('', $link);
		$str_out .= '`c`b`&<span style="font-size: 20px;">'.$riddle['text'].'</span>`q`b`n`n
					<form method="POST" action="'.$link .'">
					<select name="rune1">'.$optns.'</select>  <select name="rune2">'.$optns.'</select>  <select name="rune3">'.$optns.'</select>`n`n
					<input type="submit" value="Sagen" />`c';
	break;
	
	
	case 'say':
		$session['user']['specialinc'] = '';
		$riddle = $arr_sentences[$_GET['id']];
		$right	= 	($riddle['runes'][0] == (int)$_POST['rune1']) &&
					($riddle['runes'][1] == (int)$_POST['rune2']) &&
					($riddle['runes'][2] == (int)$_POST['rune3']);
		$str_out .= 'Du sagst ihm deine Lösung und er ordnet die Runen in dieser Reihenfolge an!`n';

		if( $right ){
			$rnd = e_rand(0,11);
			if( $rnd < 4 ){
				$str_what = '3 Edelsteine';
				$session['user']['gems'] += 3;
			}
			elseif( $rnd < 8 ){
				$str_what = '2000 Gold';
				$session['user']['gold'] += 2000;
			}
			else{
				$arr_what = runes_give( true );
				debuglog('Bekam eine `%'.$arr_what['name'].' (ID: '.$arr_what['id'].')`0 beim Runenspecial');
				$str_what = 'eine '.$arr_what['name'];
			}
			
			$str_out .= 'Er schaut es sich kurz an, überlegt und spricht dann zu dir:`n
			`&"Ja, genau! Habt Dank. Zur Belohnung, will ich Euch `%'.$str_what.'`& geben."`n
			Lächelnd verstaust du dein Geschenk und verabschiedest dich.';
			
		}
		else{
			$rnd = e_rand(0,11);
			if( $rnd < 4 ){
				$str_out .= 'Er schaut es sich kurz an, überlegt und spricht dann zu dir:`n
				`&"Nein, das kann nicht richtig sein! Habt trotzdem Dank."`n
				Du entschuldigst dich für deinen Fehler und verabschiedest dich.';
			}
			elseif( $rnd < 8 ){
				$str_out .= 'Plötzlich fährt ein Blitz auf dich hernieder.`n
				Schnell flüchtest du von diesem Ort.`n';
				$session['user']['reputation'] -= 5;
				$str_out .= '`$Du verlierst Ansehen!';
			}
			else{
				$str_out .= 'Plötzlich fährt ein Blitz auf dich hernieder.`n
				Schnell flüchtest du von diesem Ort.`n';
				$lose = round($session['user']['hitpoints']*0.1);
				$session['user']['hitpoints'] -= $lose;
				$str_out .= '`$Du verlierst '.$lose.' Lebenspunkte!';
			}
		}
	break;
	
	case 'go_fight':
		$int_weak = !(e_rand(1,100)%10);
		$str_out .= 'Du stürmst aus deinem Versteck hervor';
		if( $int_weak ){
			$str_out .= ' und hast Glück, dass dich der Runenmeister nicht bemerkt und deshalb geschwächt in den Kampf geht!';
		}
		else{
			$str_out .= ', jedoch bemerkt dich der Runenmeister und kann sich mit seiner vollen Stärke verteidigen!`n
						 `&"Das wirst du noch bereuen!"';
		}
		$badguy = array("creaturename"=>"Der Runenmeister",
						"creaturelevel"=>15-e_rand(0,4),
						"creatureweapon"=>"Gewaltige Runenkräfte",
						"creatureattack"=>round($session['user']['attack'])+($int_weak ? -1 : e_rand(0,6)),
						"creaturedefense"=>round($session['user']['defense'])+($int_weak ? -1 : e_rand(0,6)),
						"creaturehealth"=>round($session['user']['maxhitpoints']*($int_weak ? 1 : 2)),
						"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$battle = true;
	break;
	
	case 'fight':
		$battle = true;
	break;
	
	
	
	default:
		$str_god = runes_rand_god();
		$str_out .= 'Als du durch den Wald streifst, um neue Gegner zu finden, hörst du plötzlich eine Stimme: `&"Bei '.$str_god.'! Wieso ....?"`n
					`qDu näherst dich und erblickst den Runenmeister. Schnell versteckst du dich hinter einem Gebüsch, fragst dich, ob er wohl eine der legendären Othala-Runen dabei hat und überlegst, was du nun tust.`n`n';
		
		$str_out .= create_lnk('Weggehen','forest.php?op=go',true,true,'',false,'Weggehen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n`n';
		$str_out .= create_lnk('Zu ihm gehen und ansprechen.','forest.php?op=talk',true,true,'',false,'Ansprechen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n`n';
		$str_out .= create_lnk('Überraschungsangriff starten.','forest.php?op=go_fight',true,true,'',false,'g?Angreifen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n`n';
}
output($str_out);

if ($battle)
{
	include("battle.php");
	if ($victory)
	{
		$str_out = '`q`nKurz vor deinem letzten Schlag ruft der Runenmeister: `&"Halte ein! Ich werde Dich belohnen!"`n
					`qDu steckst deine Waffe weg und ';
		switch( e_rand(0,10) ){
			case 0:
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				$int_es = e_rand(5,10);
				$session['user']['gems'] += $int_es;
				$str_out .= 'erhältst `#'.$int_es.' Edelsteine`q.`n';
				break;
				
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
				$arr_what = runes_give( true );
				debuglog('Bekam eine `%'.$arr_what['name'].' (ID: '.$arr_what['id'].')`0 beim Runenspecial');
				$str_out .= 'erhältst eine `#'.$arr_what['name'].'`q.`n';
				break;
		}
		
		$str_out .= 'Schnell machst du dich aus dem Staub, um der Rache der Götter zu entgehen!';
		output($str_out);
		$session['user']['specialinc'] = '';
	}
	else if ($defeat)
	{
		$session['user']['specialinc'] = '';
		killplayer();
		addnews('`q'.$session['user']['name'].' wurde vom Runenmeister getötet und bestraft!');
		output('`n`n`qDer Runenmeister lacht dich aus.`n');
		switch( e_rand(0,10) ){
			case 0:
			case 1:
			case 2:
			case 3:
			case 4:
				output('Du verlierst Ansehen und bist tot.`n');
				if( $session['user']['reputation'] > 0 ){
					$session['user']['reputation'] = 0;
				}
				else{
					$session['user']['reputation'] = -50;
				}
				break;
			
			case 5:
			case 6:
			case 7:
				output('Doch bevor er dich in das Reich der Toten entgleiten lässt, betet er zu '.runes_rand_god().', als plötzlich ein Blitz hernieder fährt und dir etwas raubt...`n');
				$runes = runes_get(false,false,'RAND()');
				$int_runes = db_num_rows($runes);
				$str_del = '`&';
				if( $int_runes ){
					for( $i = 0; $i<$int_runes && $int_del<10; ++$i ){
						$r = db_fetch_assoc($runes);
						if( e_rand(0,1) ){
							
							$str_del .= ($int_del ? ', ' : '').$r['name'];
							$int_del++;
							item_delete('id='.((int)$r['id']));
						}
					}
					if( $int_del == 0 ){
						$str_del .= 'Nichts';
					}
					else{
						debuglog('Verlor '.$str_del.' weil er den Runenmeister nicht besiegte!');
					}
				}
				else{
					if( $session['user']['gems'] > 0 ){
						$session['user']['gems']--;
						$str_del .= '1 Edelstein';
					}
					else{
						$str_del .= 'Nichts';
					}
				}
				output('Du verlierst: '.$str_del.'.');
				break;
			
			default:
				output('Du bist tot!');
		}
		
	}
	else
	{
		fightnav(true,false);
	}
}


?>