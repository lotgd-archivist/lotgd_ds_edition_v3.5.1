<?php
//--------------------------------------------------------------------------------------------------------
//| Written by:  DeathDragon
//| Version:    1.2 - 06/11/2004
//| Translated by: Beleggrodion
//| Thanks Talisman for help with stupid mistakes :D
//| Revisions by: Odyssey
//| About:  A tree that randomly gives a user something
//| make sure you include the image that comes with the file which can be found at..
//| http://images.google.com/images?q=tree&ie=UTF-8&hl=en
//|
//| SQL: ALTER TABLE `accounts` ADD `treepick` INT(11 ) UNSIGNED DEFAULT '0' NOT null ;
//| in newday add $session['user']['treepick']=0;
//| small modifycations by hadriel @ www.hadrielnet.ch
//| corrected the whole bunch of stupid errors that survived and cleaned up the code, by talion
//--------------------------------------------------------------------------------------------------------

require_once('common.php');
page_header('Der Baum des Lebens');
$str_output = get_title('`GDer Baum des Lebens');

$row=user_get_aei('treepick');

switch ($_GET['op'])
{
	case '':
		$str_output .= '
			`gDu schlenderst in abgelegenen Teilen des Gartens umher, als du eine Stelle bemerkst, die scheinbar immer von Sonnenlicht umgeben ist, obwohl ringsherum hohe Büsche und viele Bäume wachsen.
			`nDeine Neugier kommt wieder einmal zum Vorschein und du läufst einen ausgetretenen Pfad entlang, der dich direkt auf die kleine Lichtung führt und findest den `kBaum `gdes Lebens.
			`nVor Ehrfurcht über seine Schönheit fällst du einen Entschluss...';

		addnav('Optionen');
		addnav('Nimm was vom Baum','treeoflife.php?op=pickfruit');
	break;

	case 'pickfruit':
		if ($row['treepick'] <1)
		{
			$str_output .= '
				`gDu versuchst, etwas vom `kBaum `gdes Lebens zu nehmen und findest...`n`n';

			switch (e_rand(1,16))
			{
				case 1:
					$str_output .= 'dass der Baum noch keine reifen Früchte hat!';
				break;
				case 2:
					$str_output .= '`&Einen Edelstein!';
					$session['user']['gems']+=1;
				break;
				case 3:
					$str_output .= '
						Eine charmante Elfe, welche zwischen zwei Ästen feststeckt.
						Immer bereit jemandem zu helfen, befreist du die Elfe und sie ist dafür sehr dankbar.
						Sie schwingt ihren Stab und du stellst fest, dass du besser aussiehst.';
					$session['user']['charm']+=2;
				break;
				case 4:
					$str_output .= 'Nichts! Du verfluchst die Vögel und gehst zurück in die Stadt.';
				break;
				case 5:
					$str_output .= 'Eine kleine Tasche voller Gold!!';
					$session['user']['gold']+=200;
				break;
				case 6:
					$str_output .= '`&Zwei Edelsteine!!!';
					$session['user']['gems']+=2;
				break;
				case 7:
					$str_output .= '
						Eine faulige Frucht fällt vom Baum.
						`nDer Hunger überkommt deine Klugheit und du beschließt, einen Bissen von der verfaulten Frucht zu nehmen.
						`nGerade als du in die Stadt zurück gehen willst, bemerkst du einen starken Schmerz und du fällst auf den Boden.
						`nUnd es wird schwarz vor deinen Augen, du beginnst die Seelen gefallener Krieger zu sehen.
						`nErst jetzt bemerkst du, dass du tot bist!';
					addnews('`&'.$session['user']['name'].'`5 ist gestorben an einer verdorbenen Frucht, beim `2 `@Baum `2des Lebens ');
					killplayer(0,0);
				break;
				case 8:
					$str_output .= 'Nichts! Du verfluchst die Eichhörnchen und gehst zurück in die Stadt.';
				break;
				case 9:
					$str_output .= '`&3 Edelsteine!!!';
					$session['user']['gems']+=3;
				break;
				case 10:
					$str_output .= '
						Als du auf den Baum klettern willst, fühlst du etwas Glitschiges auf deiner Hand.
						`nUm herauszufinden was das ist, schaust du wie wild umher. Schlussendlich schaust du genau in die Augen einer riesigen Schlange!
						`nDas ist das Letzte, an das du dich erinnern kannst...';
					addnews('`%'.$session['user']['name'].'`5 wurde beim `2 `@Baum `2des Lebens`5 von einer Schlange gebissen und getötet.');
					killplayer(0,0);
				break;
				case 11:
					$str_output .= 'Nichts! Du verfluchst die Schlangen und gehst zurück in die Stadt';
				break;
				case 12:
					$str_output .= '`&2 Edelsteine!!!';
					$session['user']['gems']+=2;
				break;
				case 13:
					$str_output .= 'dass  der `kBaum `gbeschlossen hat, dich für den Kampf zu Segnen!';
					$segen_des_baumes = array(
						'name'		=> '`2Der Segen des `@Baumes',
						'rounds'	=> 10,
						'wearoff'	=> '`2Der `@Baum `2hat dir genug geholfen.',
						'defmod'	=> 1,
						'atkmod'	=> 2,
						'roundmsg'	=> '`2Der `@Baum `2gibt dir seinen Segen!',
						'activate'	=> 'defense'
					);
					buff_add($segen_des_baumes);
				break;
				case 14:
					$str_output .= '
						Einige Früchte fallen zu Boden.
						`n"Die Früchte sehen etwas seltsam aus", denkst du dir.
						Da du aber Hunger hast beschließt du, dass es es die Konsequenzen Wert sind.`n`n
						Allerdings musst du feststellen, dass du dir an den Früchten gründlich den Magen verdorben hast, und musst dich erst einmal eine ganze Weile hinsetzen. Du verlierst 10 Waldkämpfe!';
					$session['user']['drunkenness']=66;
					$session['user']['turns']=max(0,$session['user']['turns']-10);
				break;
				case 15:
					$str_output .= '
						Du beginnst, den `GBaum `gdes Lebens hochzuklettern, als ein Ast bricht!!
						`nDer `GBaum `gbeginnt `4dunkelrot`g zu glühen!
						`nDu fühlst dich, als hättest du nun eine schwere Bürde auf deinen Schultern zu tragen und als ob dein Kampfstil von nun an etwas schlechter sein wird!';
					$fluch_des_baumes = array(
						'name'		=> '`4Fluch `7des `@Baumes',
						'rounds'	=> 10,
						'wearoff'	=> '`^Deine Bürde ist verschwunden!',
						'defmod'	=> 0.7,
						'atkmod'	=> 0.3,
						'roundmsg'	=> '`4Die Bürde erschwert es dir, dich zu Verteidigen!',
						'activate'	=> 'roundstart'
					);
					buff_add($fluch_des_baumes);
				break;
				case 16:
					$str_output .= 'auf dem obersten Ast des Baumes eine Schale voll Gold!';
					$session['user']['gold']+=100;
				break;
				default: //sollte nicht auftreten
					$str_output .= 'auf dem obersten Ast des Baumes eine kleine Schale voll Gold!';
					$session['user']['gold']+=42;
				break;
			}
			user_set_aei(array('treepick'=>1));
		}
		else
		{
			$str_output .= '`gDu beschließt, den Anderen auch eine Chance zu geben...';
		}
	break;
}

output($str_output);

if ($session['user']['alive'])
{
	addnav('Zurück');
	addnav('G?Zum Garten','gardens.php');
	addnav('Zum Stadtzentrum','village.php');
}
// else Schatten-Nav in killplayer-Funktion

page_footer();
?>