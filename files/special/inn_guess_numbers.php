<?php
/**
 * @desc Bam Bam der Oger will spielen, eine langweilige Zugfahrt wird versinnvollt...oder so
 * @longdesc Man muss nicht immer gewinnen um aus einem Spiel erfolgreich hervorzutreten
 * @author Dragonslayer for Atrahor http://www.atrahor.de
 * @copyright Atrahor, DS V3.42
 */


page_header('Bam Bam will spielen');

$str_backlink = 'inn.php';
$str_backtext = 'Zurück zur Kneipe';
$session['user']['specialinc'] = basename(__FILE__);
$str_filename = basename($_SERVER['SCRIPT_FILENAME']);

switch($_GET['sop'])
{
	case '':
		{
			$str_output = '
			`tAls dir mit einem Male von hinten ein freundlicher Oger auf die Schulter tippt, zuckst du zusammen. Nicht weil du dich erschrocken hättest, oh nein. Wenn Oger freundlich antippen kann das mitunter zu tagelangen dunkelblauen Blutergüssen führen.
			`nHart wie du bist, lässt du dir jedoch nichts anmerken und antwortest mit deinem gequälte- ich meine breitesten Lächeln: `yJa bitte? Was gibt es denn?
			`n`tSichtlich erfreut stellt sich dir der Oger als "Bam Bam" vor. Ein trefflicher Name, wie dir deine Schulter zuflüstert. `y"Bam bam hat ein neues Spiel gelernt. Und du spielst mit mir!"
			`n`tNun, eine Frage klingt zwar anders, aber mal ehrlich, kann man einem so liebevollen und offensichtlich betrunkenen Geschöpf etwas abschlagen?   
			';
			addnav('Gern spiele ich mit dir',$str_filename.'?sop=play_1');
			addnav('Natürlich gern, Bam Bam',$str_filename.'?sop=play_1');
			addnav('Jederzeit, gerne Bam Bam',$str_filename.'?sop=play_1');
			addnav('Nichts lieber als das',$str_filename.'?sop=play_1');
			addnav('Ähm...nein?',$str_filename.'?sop=play_2');
			break;
		}
	case 'play_1':
		{
			$session['user']['specialmisc'] = array('int_guessed_number'=>e_rand(1,100),'int_guesses'=>0);
			$str_output = '`yUUUUUH, toll, Bam Bam spielt mit Dir!
			`n`t Gerade will er dir noch einen freundlichen Klapps auf den Rücken geben, doch du wiegelst gerade noch freundlich ab. `ySpielen wir lieber!
			`n`n`tBam Bams Spiel ist nicht besonders schwer (wie sollte er sich auch die ganzen Regeln merken). Er denkt sich eine Zahl zwischen 1 und 100 aus und du musst sie in maximal 10 Versuchen erraten. Liegst du daneben sagt dir Bam Bam ob die Zahl an die er dachte höher oder tiefer liegt und du darfst erneut raten.
			`nAn welche Zahl denkt Bam Bam denn nun?
			`n`n`0<form action="'.$str_filename.'?sop=guess" method="post">
			Gib eine Nummer zwischen 1 und 100 ein:
			<input name="inn_number_guess" id="inn_number_guess" value="" size="5">
			`n`c<input type="submit" class="button" value="Raten">`c
			</form>
			'.focus_form_element('inn_number_guess');
			addnav('',$str_filename.'?sop=guess');
			addnav('Habs mir anders überlegt',$str_filename.'?sop=play_2');
			break;
		}
	case 'guess':
		{
			$session['user']['specialmisc'] = utf8_unserialize($session['user']['specialmisc']);
			if($_GET['act'] == 'giveup' && $session['user']['specialmisc']['int_guesses']<2)
			{
				$str_output = '`y`bGRRRRRR!`b Erst willst du spielen und dann hörst du sofort wieder auf? Das findet Bam Bam gar nicht lustig!
				`n`n`t`bWHAMM`b hast du dir eine geschmeidige Backpfeife eingefangen. Jedem anderen wäre wohl der Kopf abgeflogen, aber nicht dir, du bist schließlich hart im Nehmen... Oh, hallo Ramius. Mitkommen? Ich mit dir? Na gut...';
				addnews($session['user']['name'].' `tbekam eine Backpfeife und flog direkt bis vor Ramius\' Haustür.');
				$session['user']['specialinc'] = '';
				$session['user']['specialmisc'] = '';
				killplayer(20);
			} 
			elseif($session['user']['specialmisc']['int_guesses'] == 10 || ($_GET['act'] == 'giveup' && $session['user']['specialmisc']['int_guesses']>2))
			{
				$str_output = '`y HAA HAAH HAAA! `tBam Bams Lachen klingt langgezogen, tief und ziemlich beknackt, aber er scheint sich wirklich zu freuen.
				`n`yBam Bam hat gewonnen. Bam Bam gewinnt immer! Bam Bam lädt dich auf ein Ale ein.
				`n`tEhe du dich versiehst, hast du auch schon ein schaumiges, leckeres, kühles, frisches...und auch schon das Zweite und das *hicks* Dritte uuund daaan noch ein *burrps* Viaaates...
				`n`nAls Du wieder zu dir kommst, liegst du in der Gosse. Reichlich schmutzig, dein Schädel brummt wie doll, aber wenigstens hast du zwei schöne blaue Flecken auf der Schulter... Wenn du dich doch nur erinnern könntest, woher die kommen...';
				addnav('Aufrappeln','slums.php');
				$session['user']['specialinc'] = '';
				$session['user']['specialmisc'] = '';
			}
			elseif ($session['user']['specialmisc']['int_guessed_number'] == (int)$_POST['inn_number_guess'] && $session['user']['specialmisc']['int_guesses'] < 10)
			{
				$str_output = '
				`y HAA HAAH HAAA! Nicht richtig, Bam Bam hat an die '.$session['user']['specialmisc']['int_guessed_number'].' gedenkt und nicht an die '.(int)$_POST['inn_number_guess'].'... Oh...OH...OOOH! Bam Bam verliert nie, Bam Bam gewinnt immer! Du hast gemogelt! Du hast betrogen! Du bist ein Lüger und Betrüger, Bam Bam wird dir zeigen sich zu benehmen!
				`n`tDie letzten Worte schreit Bam Bam förmlich und das bedeutet schon einiges bei einem Oger. Zum Glück hörst du davon nicht mehr viel, denn bereits beim ersten "oh" wurdest du gepackt und zärtlich gerüttelt und geschüttelt, so dass du dankbar grinsend die Ohnmacht begrüßt, die dich umfängt.
				`nAls du wieder erwachst, liegst du immernoch in der Bar, allerdings in einem etwas anderen Zustand als zuvor. Du magst, nein falsch, du KANNST dich kaum rühren und rappelst dich nur langsam wieder auf.';
				$session['user']['hitpoints'] 	= max(1,$session['user']['hitpoints']-10);
				$session['user']['turns'] 		= max(1,$session['user']['turns']-10);
				$session['user']['specialinc'] = '';
				$session['user']['specialmisc'] = '';
				addnav('Au...', $str_backlink);
			}
			else 
			{
				$session['user']['specialmisc']['int_guesses']++;
				$str_output = '
				`y HAA HAAH HAAA! Nicht richtig, Bam Bam hat an an eine andere Zahl gedenkt. Meine Zahl war '.($session['user']['specialmisc']['int_guessed_number']>(int)$_POST['inn_number_guess']?'größer':'kleiner').'.`n
				`tVersuchs noch einmal, du darfst noch '.(10-$session['user']['specialmisc']['int_guesses']).'x raten.
				`n`n`0<form action="'.$str_filename.'?sop=guess" method="post">
				Gib eine Nummer zwischen 1 und 100 ein:
				<input name="inn_number_guess" id="inn_number_guess" value="" size="5">
				`n`c<input type="submit" class="button" value="Raten">`c
				</form>
				'.focus_form_element('inn_number_guess');
				addnav('',$str_filename.'?sop=guess');
				addnav('Keine Lust mehr',$str_filename.'?sop=guess&act=giveup');
			}
			break;
		}
	case 'play_2':
		{
			$str_output = '`tNa ob das so ne tolle Idee war, lässt sich aus Expertensicht anzweifeln. Jedefalls kann gerade sehr gut die Gesichtsanatomie eines männlichen Ogers betrachtet werden, der zu tiefst beleidigt seine Unterlippe nach vorne schiebt. Das dabei die fingerdicken Hauer des Unterkiefers hervortreten sei nur am Rande erwähnt.
			`n`yWieso spielst du nicht mit Bam Bam? Bam Bams Psytschiata hat gesagt spielen hilft gegen Agresifitet...
			`n`tOk, als unbeteiligter Erzähler rate ich in dieser Situation folgendes: VERDAMMT SPIEL MIT IHM...';
			addnav('Ich meinte natürlich ja',$str_filename.'?sop=play_1');
			addnav('Ich hab mich versprochen..ja',$str_filename.'?sop=play_1');
			addnav('War nur ein Spass, JA!',$str_filename.'?sop=play_1');
			addnav('Nahaaain!',$str_filename.'?sop=play_3');
			break;
		}
	case 'play_3':
		{
			$str_output = '`tOk, ich halt mich jetzt da raus, du machst ja eh was du willst...`n`n
			`yWarum willst du nicht mit Bam Bam spielen?`n`n'.
			'`tJa, das würd mich jetzt aber auch mal interessieren...`';
			addnav('Pazifist',$str_filename.'?sop=play_4&act=pazifist');
			addnav('Buddhist',$str_filename.'?sop=play_4&act=buddhist');
			addnav('Chamäleon',$str_filename.'?sop=play_4&act=chamaeleon');
			addnav('Buttercremetorte',$str_filename.'?sop=play_4&act=kuchen');
			addnav('3-köpfiger Affe',$str_filename.'?sop=play_4&act=affe');
			break;
		}
	case 'play_4':
		{
			$str_output = '`tBam Bam sieht dich erwartungsvoll an als du Luft holst um zu antworten: `n`n';
			switch ($_GET['act'])
			{
				case 'pazifist':
					{
						$str_output .= '`yDer in mir wohnenende Pazifismus hindert mich zwar nicht daran, Kreaturen jeglicher Form, Coleur oder Gesinnung sinnlos zu zerstören, um mein Ziel zu erreichen, oder große Lindwürmer zu erschagen und zu hohem Ruhme aufzusteigen, aber dieses martialische Verhalten ist nur auf eine unzureichende Aufklärung in meiner frühesten Kindheit zurückzuführen. Ich denke da an Dr F. Roid´s Thesen, der dies mit einer unnatürlichen Aversion gegenüber jeglicher Art von Glücksspiel mit größeren Säugetieren gleich setzt.`n`n
						`t Bam Bam überlegt kurz und meint dann nachdenklich `y Wenn Dr F. Roid das sagt, dann hat es wohl einen guten Grund. Schließlich erklärte er auch Bam Bam den Zusammenhang zwischen dem essentiellen Sein eines Körper zerfetzenden Monstrums als Es und der Findung des Ichs in einer wenig geliebten äußeren Form...`n`n
						`tIhr diskutiert noch eine Weile über Ich und über Ichs, bis ihr euch freundschaftlich verabschiedet.';
						break;
					}
				case 'buddhist':
					{
						$str_output .= '`y Ein weiser Mann wunderte sich über die Menschen. Sie setzen ihre Gesundheit aufs Spiel um Reichtümer anzuhäufen und benutzen dann das erworbene Geld, um ihre Gesundheit wiederherzustellen. Sie denken ängstlich in die Zukunft und vergessen dabei die Gegenwart, so leben sie weder in der Gegenwart noch in der Zukunft. Sie leben, als ob sie nie sterben würden, und sterben, ohne je gelebt zu haben.`n
						`t Bam Bam überlegt kurz und meint dann nachdenklich `y Also wenn dir das die Butter gesagt hat, dann muss das ja stimmen. Mit Bam Bam hat die Butter noch nie gesprochen... aber Bam Bams Füße können Qualm machen, magst du mal sehen?`n`n
						`tIhr diskutiert noch eine Weile über Körperausscheidungen und deren Geruch, bis ihr euch freundschaftlich verabschiedet.';
						break;
					}
				case 'chamaeleon':
					{
						$str_output .= '`yWusstest Du eigentlich das Chamäleons die Farbe ihrer Haut verändern können?`n
						`tBam bam überlegt kurz und antwortet schließlich `yNein, aber wusstest du, dass das Herz eines Kolibris etwa 300x die Sekunde schlägt aber ein einzelner Ogerpfurz dies auf null reduzieren kann?`n`n
						`tIhr diskutiert noch eine Weile über die Marotten des Tierreichs und Gase im Besonderen bis ihr euch freundschaftlich verabschiedet.';
						break;
					}
				case 'kuchen':
					{
						$str_output .= '`y Dazu werden 400g Zucker, etwas Vanillezucker, acht Eigelb und vier Esslöffel Wasser miteinander vermengt und solange schaumig geschlagen, bis sich der Zucker vollständig aufgelöst hat. Anschließend vermischt man 300g Mehl und ein halbes Päckchen Backpulver miteinander und streut es fein über die Eigelb-Zucker-Masse. Nun schlägt man das übrig gebliebene Eiweiß bis es steif ist und hebt es gemeinsam mit dem Backpulver und dem Mehl unter die Eigelb-Zucker-Masse. Abschließend gibt man den fertigen Teig in eine Springform und backt ihn bei 180° auf der unteren Schiene etwa 45 Minuten.`n`n
						
						Wenn der Biskuitboden fertig ist, schneidet man ihn zweimal durch. Den untersten bestreicht man mit Marmelade (beispielsweise Kirsch), die anderen beiden werden mit einer Pudding-Buttercreme versehen. Nun werden alle drei Teile zusammengesetzt und die Torte außen ebenfalls mit Buttercreme bestrichen.`n`n
						Nun kann die Torte noch nach Belieben verziert werden, beispielsweise mit Schokoraspeln bestreut und mit Kirschen belegt werden.`n`n`n
						
						`t Bam Bam überlegt kurz und antwortet schließlich `yMehl also, keine Mehlwürmer...das erklärt so einiges.`n`n
						`tIhr diskutiert noch eine Weile über die verschiedensten Kochrezepte bis ihr euch freundschaftlich voneinander verabschiedet.';
						break;
					}
				case 'affe':
					{
						$str_output .= '`y`bHinter Dir`b, ein dreiköpfiger Affe...';
						break;
					}
			}
			
			$str_output .= '`n`n`tIch bin beeindruckt. Also nein, wirklich. Ich bin schwer beeindruckt.';
			$session['user']['specialinc'] = '';
			$session['user']['specialmisc'] = '';
			addnav('Puh...',$str_backlink);
			break;
		}
}
output($str_output);
?>