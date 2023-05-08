<?php
/**
 * @author Nysaria
 * @copyright Nysaria for Atrahor.de
 * @desc Man findet ein dickes Gestrüpp im Wald und darin eine Lichtung
 */

if (!isset($session)) exit();

$specialinc_file = 'forest_thick_shrubbery.php';

$str_out = get_title('Das dichte Gebüsch');

switch ($_GET['op'])
{
	case '':
		{
			$session['user']['specialinc'] = $specialinc_file;
			$str_out .= 'Ein paar Schritte vor dir taucht etwas dichteres Gebüsch auf. Du fragst dich was wohl dahinter sein könnte und bist unschlüssig was nun zu tun sei.';
			addnav('Durch das Gebüsch gehen','forest.php?op=go');
			addnav('Einen Bogen drum herum machen','forest.php?op=leave');
			break;
		}
	case 'leave':
		{
			$session['user']['specialinc'] = '';
			$str_out .= 'Du hältst es für besser, dem Dickicht aus dem Weg zu gehen und setzt deinen Weg durch den Wald fort. Wer weiß schon was dahinter auf dich wartet.';
			break;
		}
	case 'go':
		{
			switch (e_rand(0,5))
			{
				case 0:
					{
						$session['user']['specialinc'] = '';
						$str_out .='Verblüfft entdeckst du eine kleine Lichtung, umrahmt von dem dichten Gebüsch, durch das du dich eben erst gekämpft hast. Als du das weiche, einladende Gras siehst, das hier den Boden bedeckt, bemerkst du, dass es nicht schaden könnte, sich ein bisschen auszuruhen. Du legst dich auf den angenehmen Boden.`n`n

						Nach ungefähr einer Stunde schreckst du aus tiefem Schlummer auf. Etwas verstört erinnerst du dich an einen Traum. `iAuf einer Jagd im Wald lief dir ein Riesenkarnikel über den Weg, welches dir einfach mal den Kopf abbiss weil dieser eine knollige Karotte war. Wirr umherlaufend stolpertest du über einen Kürbis, den du dir kurzerhand als neuen Kopf angeeignet hast.`i`n`n
						Verwirrt schüttelst du dir den letzten Rest diesen Blödsinns aus dem Kopf und beschließt, solchen Träumen beim nächsten Mal keine Beachtung mehr zu schenken.`n`n

						`2Du gewinnst 100 Erfahrungspunkte!';
						$session['user']['experience']+=100;
						break;
					}
				case 1:
					{
						$session['user']['specialinc'] = '';
						$str_out .='Verblüfft entdeckst du eine kleine Lichtung, umrahmt von dem dichten Gebüsch, durch das du dich eben erst gekämpft hast. Als du das weiche, einladende Gras siehst, das hier den Boden bedeckt, bemerkst du, dass es nicht schaden könnte, sich ein bisschen auszuruhen. Du legst dich auf den angenehmen Boden.`n`n

						Kurz bevor du einschläfst bemerkst du die Spuren im Gras. "Wird schon nichts bedrohliches hier lauern", denkst du dir und schläfst ein. Erschrocken wachst du nach kurzer Zeit wieder auf, als fieses Kinderlachen durch die Luft schallt und all deine Extremitäten und dein Gesicht mit Honig beschmiert wurden. Nur ein paar Sekunden später stürmt ein riesiger Bär auf dich zu, starr vor Schreck wartest du, bis er den ganzen Honig von dir geleckt hat.`n`n

						`$Du verlierst 5% deiner Lebenspunkte.';
						$session['user']['hitpoints'] *=0.95;
						break;
					}
				case 2:
					{
						$session['user']['specialinc'] = $specialinc_file;
						$str_out .= 'Als du dich endlich durch die Büsche gekämpft hast weiten sich erschrocken deine Augen. Ein Haufen mittelgroßer Riesenspinnen mit gelben Flecken auf den Beinen wurden auf dich aufmerksam und nähern sich dir nun mit merkwürdigen Geräuschen. Hinter dem angsteinflößenden Getier entdeckst du eine Lücke in den Büschen, durch die einfach fliehen könntest. Du hast schon einmal von diesen Spinnen gehört, sie haben eine Vorliebe für glitzernde Dinge, also überlegst du hektisch, was du nun tun könntest, damit sie dir nicht alle Gliedmaßen aus dem Leib reißen.';
						addnav('Versuchen zurück zu gehen','forest.php?op=spider&act=leave');
						addnav('Mit einem Edelstein bestechen','forest.php?op=spider&act=gem');
						break;
					}
				case 3:
					{
						$session['user']['specialinc'] = '';
						$str_out .= 'Verblüfft entdeckst du eine kleine Lichtung, umrahmt von dem dichten Gebüsch, durch das du dich eben erst gekämpft hast. Als du das weiche einladenede Gras siehst, das hier den Boden bedeckt, bemerkst du, dass es nicht schaden könnte, sich ein bisschen auszuruhen. Du legst dich auf den angenehmen Boden.`n`n

						Nach ungefähr einer Stunde schreckst du aus tiefem Schlaf auf, etwas verstört erinnerst du dich an einen Traum.`n
						`yDu sitzt in einem eigenartigen Gebäude, in dem Kinder das Sitzen und Stillsein wieder lernen, nachdem sie erst eben gehen und sprechen gelernt haben. Es muss eine Schule sein, denkst du, als sich das Gebäude schlagartig mit Wasser füllt und alle anderen Kinder mit ihren aus dem nichts aufgetauchten Flossen und Kiemen mühelos weiterlernen können. Du allerdings bekommst keine Luft mehr, da das Wasser alle Räume aufgefüllt hat. Du ertrinkst, mit dem quälenden Gedanken daran, dass du im Unterricht besser aufgepasst hättest wie man Kiemen bekommen kann, statt ein grauenhaftes Liebeslied an einen Esel zu schreiben!`n`n
						`tEtwas verwirrt schüttelst du dir den letzten Rest dieses Blödsinns aus dem Kopf und beschließt, solchen Träumen beim nächsten Mal keine Beachtung zu schenken.`n`n

						`2Du gewinnst 100 Erfahrungspunkte.
						';
						$session['user']['experience']+=100;
						break;
					}
				case 4:
					{
						$session['user']['specialinc'] = '';
						$str_out .= 'Verblüfft entdeckst du eine kleine Lichtung, umrahmt von dem dichten Gebüsch, durch das du dich eben erst gekämpft hast. Als du das weiche einladenede Gras siehst, das hier den Boden bedeckt, bemerkst du, dass es nicht schaden könnte, sich ein bisschen auszuruhen. Du legst dich auf den angenehmen Boden.`n`n

						Nach ungefähr einer Stunde schreckst du aus tiefem Schlaf auf, etwas verstört erinnerst du dich an einen Traum.`n
						`yVor dir erstrecken sich weite Felder, in der Ferne umrahmt von dichten, dunkelgrünen, fast schwarzen Bäumen. Sie schimmern anmutig im rötlichen Licht der untergehenden Sonne. Weit weg, erblickst du vereinzelte Bauernhäuser, schon fast zu klein um sie mit bloßem Auge zu erkennen. Rehe hüpfen aufgeschreckt aus den Wäldern und über die hohen Kornfelder. Sie werden von einem stattlichen Hirsch angeführt, der dich wie magisch anzieht. Plötzlich lösen sich deine Füße von dem fruchtbaren Boden und schwebst über die erntereifen Ähren hinweg in Richtung des majestätischen Tieres. Vorsichtig streckst du die Hand nach seinem glänzenden Fell aus und berührst es für einen kurzen Moment mit deinen Fingerspitzen. Gleißendes Licht blendet deine Sicht und unbeschreibliches Glück durchfährt jede einzelne Faser deines Körpers.`n`n

						`tNoch bevor du weißt, was nun passiert wäre wachst du auf, herausgerissen aus diesem wunderschönen Traum. Zumindest das Glückgefühl hält an und erfüllt dich mit seiner wohligen Wärme.`n`n

						`2Deine Lebenspunkte regenerieren vollständig!';
						$session['user']['hitpoints']=$session['user']['maxhitpoints'];
						break;
					}
				case '5':
					{
						$session['user']['specialinc'] = '';
						$str_out .= 'Verblüfft entdeckst du eine kleine Lichtung, umrahmt von dem dichten Gebüsch, durch das du dich eben erst gekämpft hast. Als du das weiche einladenede Gras siehst, das hier den Boden bedeckt, bemerkst du, dass es nicht schaden könnte, sich ein bisschen auszuruhen. Du legst dich auf den angenehmen Boden.`n`n

						Nach ungefähr einer Stunde schreckst du aus tiefem Schlaf auf, etwas verstört erinnerst du dich an einen Traum.`n
						`yNur sehr wirr kannst du dich an einen zugefrorenen See, eher einen Teich, erinnern, das Wasser in einem eisig strahlenden Blau, wie du es noch nie zuvor gesehen hast. Sachte steigst du mit einem Fuß auf das Eis und merkst, dass es bis zum Grund hinab in Kälte erstarrt ist, obwohl doch gerade Hochsommer herrscht. Als du den Fuß wieder zurück ziehen willst meinst du, dein Blick beginne zu verschwimmen. Schnell bist du dir aber sicher, dass das Eis wabert und Wellen schlägt, als ob es wieder schmelze. Zögernd berührst du es um sicher zu gehen. Ja, es ist trotz allem noch immer gefrorenes Wasser, das erstarrt sein sollte ohne jegliche Bewegung. Völlig verwirrt lässt du dich in den Teich fallen, wirst von weichem, kalten Eis aufgefangen und wartest bis es dich vollkommen aufgenommen hat. Du schlägst ein letztes Mal die Augen auf und erstarrst für die Ewigkeit, so wie hunderte andere rund um dich herum schon seit Äonen hier liegen und warten.

						`tVerstört raffst du dich wieder auf und reibst dir den Schlaf und diesen schrecklichen Traum aus den Augen. Irgendwie fühlst du dich ausgelaugt. Hoffentlich träumst du sowas nicht wieder, denkst du dir. Bevor du weiter ziehst beruhigst du dich noch etwas und trinkst einen Schluck Wasser aus einem nahegelegenen Teich. Wenn er doch bloß nicht so aussehen würde wie der aus deinem Traum!`n`n

						`$Du verlierst einen Waldkampf.';
						$session['user']['turns']--;
						if($session['user']['turns']<0)
						{
							$session['user']['turns']=0;
						}
						break;
					}
			}
			break;
		}
	case 'spider':
		{
			$session['user']['specialinc'] = '';
			if($_GET['act'] == 'leave')
			{
				switch (e_rand(0,1))
				{
					case 0:
						{
							$str_out .= 'Panisch drehst du dich um und versuchst den gleichen Weg, den du gekommen bist wieder zurück zu gehen. Es scheint, als hätten sich die Büsche in der Zwischenzeit verändert und du findest keinen vernünftigen Weg mehr heraus. Als du auch noch mit deiner Rüstung an einem stärkeren Ast hängen bleibst hörst du auch schon die trappelnden Beine der Spinnen. Das letzt was du siehst is ein Spinnenkopf, dessen weit geöffnetes Maul direkt auf Höhe deines Kopfes ist.';
							killplayer();
							addnews('`%'.$session['user']['name'].'`5 wurde von Riesenspinnen gefressen.');
							break;
						}
					case 1:
						{
							$str_out .= 'Glücklicherweise findest du den Weg, den du gekommen bist gleich wieder, dank deiner trampelhaften Fußspuren. Stolpernd läufst du weiter, bis das Dickicht hinter dir ist und du die Spinnen weit hinter dir weißt. Erschöpft ziehst du weiter, so etwas passiert dir nicht noch mal.';
							break;
						}
				}
			}
			elseif($_GET['act'] == 'gem')
			{
				if($session['user']['gems'] == 0)
				{
					$str_out .= 'Zitternd fasst du in deinen Edelsteinbeutel und versuchst einen der glitzernden Steine zu finden, leider findest du nicht mehr als ein paar alte Brotkrümel und einige kleine Kieselsteine. Ob die Spinnen wohl auch einen Stein nehmen würden? Wenn man genau hinsiehst glitzert da auch irgendwas, wenn man ganz genau hinsieht. Als der Stein vor den Beinen der größten aller Spinnen landet macht diese ein komisches Geräusch, das auch ein höhnisches Lachen sein könnte, in das alle anderen Spinnen sofort einstimmen. Plötzlich hat dich das Ungeziefer schon umkreist und beginnt an deinen Gliedmaßen zu zerren. Hättest du blos besser aufgepasst und wärst wegerannt...';
					killplayer();
					addnews('`%'.$session['user']['name'].'`5 wurde von Riesenspinnen gefressen.');
				}
				else
				{
					$str_out .= 'Zitternd fasst du in deinen Edelsteinbeutel und kramst einen der glitzernden Steine hervor, den du zögernd vor die Beine der größten aller Spinnen hier legst, sie muss wohl das Alphatier sein.`n`n';
					$session['user']['gems']--;
					$int_rand = e_rand(1,100);
					if($int_rand<10)
					{
						$str_out .= 'Langsam bilden die Spinnen eine schmale Gasse in ihrer Menge. Vorsichtig gehst du hindurch, der Lücke in den Büschen gegenüber dir geradewegs entgegen, als sich plötzlich eine Wolke gelben, dicken Dunstes bildet, der wohl von den Spinnen kommen muss.`n`n

						Benebelt gehst du weiter und setzt erleichtert deinen Weg fort, als du merkst, dass nichts passiert ist und du endlich hier raus bist. Was war das nur für eine Wolke?`n`n

						`2Du erhältst 1 permanenten Lebenspunkt';

						$session['user']['maxhitpoints']++;
					}
					elseif($int_rand<40)
					{
						$str_out .= 'Langsam bilden die Spinnen eine schmale Gasse in ihrer Menge. Vorsichtig gehst du hindurch, der Lücke in den Büschen gegenüber dir geradewegs entgegen, als sich plötzlich eine Wolke gelben, dicken Dunstes bildet, der wohl von den Spinnen kommen muss.`n`n

						Erfreut entdeckst du den Ausgang hinter dem Nebel und läufst rasch weiter als du ein paar Schritte vor dir etwas Glitzerndes siehst. Fast wärest du darüber gestolpert!`n`n

						`2Du erhältst 2 Edelsteine';
						$session['user']['gems']+=2;
					}
					elseif($int_rand<85)
					{
						$str_out .= 'Langsam bilden die Spinnen eine schmale Gasse in ihrer Menge. Vorsichtig gehst du hindurch, der Lücke in den Büschen gegenüber dir geradewegs entgegen, als sich plötzlich eine Wolke gelben, dicken Dunstes bildet, der wohl von den Spinnen kommen muss.`n`n

						Unbeschadet schreitest du hindurch und setzt erleichtert deinen Weg fort. Irgendwie hast du jetzt den unwiderstehlichen Drang Spinnen zu töten.';
					}
					elseif($int_rand<=100)
					{
						$str_out .= 'Langsam bilden die Spinnen eine schmale Gasse in ihrer Menge. Vorsichtig gehst du hindurch, der Lücke in den Büschen dir gegenüber geradewegs entgegen, als sich plötzlich eine Wolke gelben, dicken Dunstes bildet, der wohl von den Spinnen kommen muss.`n`n

						Gurgelnd gehst du in die Knie und bleibst zuckend am Boden liegen. Es war wohl nicht so gut den Dunst einzuatmen. Hättest du nur die Luft angehalten!';

						killplayer();
						addnews('`%'.$session['user']['name'].'`5 wurde von Riesenspinnen gefressen.');
					}
				}
			}
			break;
		}
}
output($str_out);
?>
