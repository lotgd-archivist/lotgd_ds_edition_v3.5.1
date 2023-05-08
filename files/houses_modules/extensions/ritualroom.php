<?php
// Ritualkammer
// Umsetzung by talion, Idee by Valas


// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_ritualroom ($str_case, $arr_ext, $arr_house) {
	
	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner,$arr_content;
				
	_rooms_common_set_env($arr_ext,$arr_house);
		
	switch($str_case) {
		
		// Innen
		case 'in':

			$arr_to_serve = array(1=>array('Wildbret',10),2=>array('Schweinebraten',5),3=>array('Rotwein aus den südlichen Landen',20),4=>array('Eber aus den Dunklen Landen',30),5=>array('Waldbeerengelee',3),6=>array('Karaffe Wasser',1));
					
			switch($_GET['act']) {
				
				case 'ritual':
					
					$str_out .= house_get_title('Ritualkammer');
					
					$str_out .= 'Du betrittst einen kleinen, eher abgelegenen Raum des Hauses, welcher durch die etwas kleinen Fenster ein wenig abgedunkelt ist. Der Boden ist zusammengesetzt aus Mosaiksteinen, welche in der Mitte des Raumes ein großes Pentagramm bilden, dessen Kanten mit Kerzen in den Elementarfarben geschmückt sind. In der Mitte dieses Symboles steht ein kleiner Schrein, auf welchem die verschiedensten magischen Utensilien zu finden sind. Unter anderem einen Dolch, einen Kelch mit Weihwasser, einen Topf mit Erde, eine Feder, einen Kessel mit Asche und Räucherwerk verschiedenster Art.
								Du beschließt, ein kleines Ritual zu Ehren der Götter abzuhalten und entzündest eine betörende Räuchermischung...`n`n';
					
					switch(e_rand(1,4)) {
						case 1:
							$str_out .= '`2Ein wohliger Duft steigt dir in die Nase und du spürst sofort, dass du das Richtige getan hast. Die Götter zeigen dir ihren Dank und segnen dich.`0';
							$session['bufflist']['ritualroom_segen'] = array("name"=>"`2Der Segen der Götter",
													            "rounds"=>10,
													            "wearoff"=>"`2Der Segen der Götter lässt nach.",
													            "defmod"=>1.1,
													            "atkmod"=>1,
													            "roundmsg"=>"`2Die Götter wachen über dich!",
													            "activate"=>"defense");
						break;
						case 2:
							$str_out .= '`$Ein höllischer Gestank macht sich im gesamten Raum breit und dir wird klar, dass du dich hier wohl gehörig vergriffen hast. Nicht nur du, sondern auch die Götter sind zornig darüber und verfluchen dich für dein unüberlegtes Verhalten.`0';
							$session['bufflist']['ritualroom_fluch'] = array("name"=>"`\$Der Fluch der Götter",
													            "rounds"=>10,
													            "wearoff"=>"`\$Der Fluch der Götter lässt nach.",
													            "defmod"=>0.9,
													            "atkmod"=>1,
													            "roundmsg"=>"`\$Die Götter haben dich verflucht!",
													            "activate"=>"defense");
						break;						
						default:
							$str_out .= 'Jau, riecht ganz gut! Diese feine Kopfnote aus Tannenharz.. köstlich. Plötzlich überkommt dich die befremdliche Lust, in einer kleinen, dunklen Höhle auf einem hohen, abgelegenen Berg zu hausen, dich von Insekten zu ernähren und nur noch für deine Düfte zu leben`n`n....`n`nWelch ein Unsinn! Das Zeug hat dich ganz schön benommen gemacht.. nichts wie raus.';
						break;
					}
			    
				    addnav('Zurück',$str_base_file);
					
				break;
				case 'analloni':
					{
						if($_GET['subact'] == '')
						{
							$arr_item = item_get('owner='.$session['user']['acctid'].' AND tpl_id="analloni_f"');
							$arr_item['content'] = utf8_unserialize($arr_item['content']);
							$str_out = house_get_title('`tDie Anallôni-Pflanzen');
							$str_out .= '`tDu gehst zu deinem kleinen Räucher-Stöfchen und setzt dich davor nieder. Dir fällt es leicht dich an das Ritual, mit dem man die Kräfte der Anallôni-Pflanzen frei setzen kann, zu erinnern, denn es kommt dir vor als wäre es gestern gewesen, als du das erste mal davon gehört hast:`n`n
							`ySammle die zwölf Blüten der Anallôni-Pflanzen und braue ihren Sud.`n
							Die Schwaden ihres Dampfes geben deinem Geiste Mut`n`n
							`tZiemlich lustige Umschreibung für diese Droge, aber solange es wirkt... Zu schade dass die Blüten extrem selten sind. Du schaust auf die Blüten in deinem Beutel und zählst. ';

							$int_count = $arr_item['content']['count'];
							
							if($int_count<12)
							{
								$str_out .= 'Momentan hast du bereits '.$int_count.' Blüten gesammelt. Um genau zu sein fehlen dir noch folgende Blüten:`n`n';
								$arr_months = array(1=>'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
								$int_i = 0;
								foreach($arr_item['content']['blossoms'] as $key=>$val)
								{
									$int_i++;
									if($val == true)
									{
										continue;
									}
									else 
									{
										$str_out .= '`y'.$key . '`t (blüht nur im '.$arr_months[$int_i].')`n';
									}
								}
								$str_out .= '`nBevor du nicht alle Blüten zusammen hast, würde es sich also nicht lohnen den Sud zu brauen.';
							}
							else 
							{
								$str_out .= 'Tatsächlich, du hast alle zwölf Anallôni-Blüten beisammen. Somit könntest du nun eigentlich den Sud herstellen.';
								addnav('Sud aufkochen',$str_base_file.'&act=analloni&subact=brew');
							}
						}
						elseif($_GET['subact'] == 'brew')
						{
							$arr_item = item_get('owner='.$session['user']['acctid'].' AND tpl_id="analloni_f"');
							$arr_item['content'] = utf8_unserialize($arr_item['content']);
							$str_out = get_title('Den Anallôni-Sud kochen.');
							$str_out .= '`tVorsichtig lässt du das Wasser in deinem kleinen Stöfchen aufkochen und wirfst nach und nach die gesammelten Blüten hinein. Mit jeder einzelnen Blüte verfärbt sich das Wasser in einer anderen leuchtenden Farbe und taucht das Gemach in ein lebendiges Licht, während das Wasser selbst immer dickflüssiger wird. Nach einigen Minuten hängt ein schwerer, süßlicher und sehr belebender Duft in der Kammer, der deine Sinne erweitert. Du fühlst dich losgelöst von allem Übel dieser Welt und entschwebst auf einer interessanten Reise durch dein eigenes Ich, dass dir die Augen öffnet und viel über dich erfahren lässt.';
							foreach($arr_item['content']['blossoms'] as $key=>$val)
							{
								$arr_item['content']['blossoms'][$key] = false;								
							}
							$arr_item['content']['count'] = 0;
							$arr_item['content'] = db_real_escape_string(utf8_serialize($arr_item['content']));
							item_set('id='.$arr_item['id'],$arr_item);
							
							//Erfahrung verdoppeln
							$session['user']['experience'] *=2;
							user_set_stats(array('analloni_rituals'=>'analloni_rituals+1'));
							
							debuglog('Anallôni-Ritual durchgeführt');
							
						}
						addnav('Zurück',$str_base_file);
						break;
					}
				case 'magvglkfg':
					{					
						addnav('Zurück',$str_base_file);
						include_once(LIB_PATH.'boss.lib.php');
						$str_out .= 'Neugierig betrachtest du den magischen Käfig und fragst dich, für was für Wesen man wohl einen solchen brauchen könnte, zumal ein normaler Vogel von dem rötlichen Schimmer der Gitterstäbe wohl eher verschreckt werden würde.';
						$bool_boss_loadable = boss_get_nav('fenris');
						if($bool_boss_loadable)
						{
							$str_out .= '`nWährend du den Käfig so anblickst, fällt dir auf, dass die beiden Rabenfedern geradezu vor Magie vibrieren und dir wird klar, dass sie irgendwie mit dem Käfig zusammenhängen müssen.`n
							Welche Mächte wirst du wohl anrufen, wenn du die Federn in den Käfig legst?';
						}
						else 
						{
							$str_out .= '`nDu kannst jedoch auch bei näherer Untersuchung keinen Hinweis auf die zugehörigen Insassen finden.';
						}
						break;
					}
																						
				case '':
															
					addnav('Ritual abhalten!',$str_base_file.'&act=ritual');
					if(item_count('owner='.$session['user']['acctid'].' AND tpl_id="analloni_f"')>0)
					{
						addnav('Anallôni-Ritual abhalten',$str_base_file.'&act=analloni');
					}
					if(house_has_item($arr_ext['houseid'],$arr_ext['id'],'magvglkfg'))
					{
						addnav('Den Vogelkäfig betrachten',$str_base_file.'&act=magvglkfg');
					}
					
				break;
				default:
															
				break; 
			}
			
			output($str_out);
			
			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);
			
		break;
		// END case in
		
		// Bau gestartet
		case 'build_start':
			
			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);
			
		break;
		
		// Bau fertig
		case 'build_finished':
			
			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);
			
		break;
				
		// Abreißen
		case 'rip':
						
			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);
									
		break;
			
	}	// END Main switch		
}	


?>
