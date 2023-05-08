<?php
/**
 * Schnetzle einen Baum!
 * Überarbeitet von Salator und Dragonslayer
 * Date: 30.05.2007
 * @author Baras  for Atrahor.de
 */

$out="`2";
switch ($_GET['op'])
{
	case '':
		{
			$out.='Du läufst durch den Wald und entdeckst auf einer Lichtung einen einsam stehenden Baum, der irgendwie seltsam ausschaut. ';
			$out.='Als du näher trittst siehst du, dass seine Rinde seltsam ramponiert ist, als hätten schon einige auf seinen Stamm eingeschlagen.`n`n';
			$out.='Was wirst du tun?';

			addnav('Auf den Baum einschlagen','forest.php?op=hit');
			addnav('Klettern','forest.php?op=climb');
			addnav('Weitergehen','forest.php?op=go');
			$session['user']['specialinc']='lumberjack.php';
			break;

		}
	case 'go':
		{
			$out.='Der Baum ist dir nicht ganz geheuer und so beschließt du besser das Weite zu suchen.';
			$session['user']['specialinc']='';
			break;

		}
	case 'climb':
		{
			$out.='Trotz der Gefahr, dass der Baum nach den vielen Schwerthieben vergangener Kämpfer zusammenbrechen könnte, entschließt du dich, auf den Baum zu klettern. ';

			$rand=e_rand(1,10);
			switch($rand) {
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
					$out.='Du schwingst dich geschickt auf den Baum, ziehst dich gerade an den ersten paar Ästen gekonnt hoch, als plötzlich ein Ast bricht und du wieder nach unten saust.`n';

					switch (e_rand(1,4)) {
						case 1:
						case 2:
							$out.='`4Du verlierst fast alle deiner Lebenspunkte, aber du hast überlebt!`2';
							$session['user']['hitpoints']=1;

							break;

						case 3:
						case 4:
							$out.='`4Leider landest du äußerst ungünstig und brichst dir den Hals... somit bist du leider TOOOT.`2';

                            /** @noinspection PhpUndefinedVariableInspection */
                            addnews($session['user']['name'].'`7 fiel im Wald von einem Baum!');
							killplayer();
							$session['user']['specialinc']='';
							break;
					}

					$session['user']['specialinc']='';
					break;

				case 6:
				case 7:
				case 8:
				case 9:
				case 10:
					/** @noinspection PhpUndefinedVariableInspection */
                    if($session['user']['dragonkills']<15) {
						$gems=e_rand(1,3);
					}
					elseif($session['user']['gems']>500) {
						$gems=0;
					}
					else {
						$gems=e_rand(0,2);
					}
					if($gems==0) {
						$gold=e_rand(2,30);
					}
					$session['user']['gems']+=$gems;
					$session['user']['gold']+=$gold;
					$out.='Du erreichst die Baumkrone und entdeckst das Nest einer Elster. Dort findest du `%'.$gems.' Edelstein'.($gems==1?'':'e').($gold?' `2 und `%'.$gold.' Goldstücke':'').'`2.';


					break;
			}
			$session['user']['specialinc']='';
			break;
		}
	case 'hit':
		{
        /** @noinspection PhpUndefinedVariableInspection */
        $out.='Du nimmst dein `%'.$session['user']['weapon'].'`2 und fängst an, damit auf den Baum einzuschlagen. ';

			$done=((mb_strpos($session['user']['weapon']," +1")!==false || mb_strpos($session['user']['weapon']," -1")!==false || $session['user']['weapondmg']<1)?true:false);
			if ($done)
			{
				$out.='Solange du auch auf den Baum einschlägst, es passiert leider gar nichts, außer dass du einen Waldkampf verlierst.';
				$session['user']['turns']--;
			}
			else
			{
				switch (e_rand(1,3)) {
					case 1:
						$out.='Je mehr du auf den Baum einschlägst, desto mehr fängt dein `%'.$session['user']['weapon'].'`2 an zu leuchten. ';
						$out.='Du stellst fest, dass deine Waffe besser als je zuvor ist.';
						item_set_weapon($session['user']['weapon']." +1",$session['user']['weapondmg']+1,round($session['user']['weaponvalue']*1.33),0,0,1);

						break;

					case 2:
						$out.='Je mehr du auf den Baum einschlägst, desto krummer und stumpfer wird dein `%'.$session['user']['weapon'].'`2. ';
						$out.='Du stellst fest, das deine Waffe fast nicht mehr zu gebrauchen ist.';
						item_set_weapon($session['user']['weapon']." -1",$session['user']['weapondmg']-1,round($session['user']['weaponvalue']*0.66),0,0,1);

						break;

					case 3:
						$out.='Solange du auch auf den Baum einschlägst, es passiert leider gar nichts, außer dass du einen Waldkampf verlierst.';
						$session['user']['turns']--;

						break;
				}
			}

			$session['user']['specialinc']='';
			break;
		}
}
$out.='`n`n';
output($out);
?>