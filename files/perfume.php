<?php

	//@author Eleya für atrahor.de, Texte by Dériel

	require_once 'common.php';

	$show_invent = true;

	addcommentary();
	checkday();

	page_header('Die Parfümerie');

	if ($Char->alive==0)
	{
		redirect('shades.php');
	}
	if($Char->prangerdays>0){
		redirect("pranger.php");
	}

	$str_out = get_title ('`8Die `8Pa`pr`.f`|üm`.e`pri`8e');

	//Parfüm-Idee  -Tyndal
	switch($_GET['op'])
	{
		case 'perfume':
			//falls der Charakter keine Edelsteine im Beutel hat, einen von der Bank nehmen -Tyndal
			if ($session['user']['gems'] == 0)
			{
				$session['user']['gemsinbank']--;
				$session['user']['gems']++;
			}
			switch(mt_rand(0,6))
			{
				case 0:
				case 1:
				case 2:
				case 3:
					$str_out .= "Du testest das Parfüm, aber so wirklich der Renner ist es leider nicht.";
					break;
				case 4:
				case 5:
					$str_out .= "Du verteilst ein paar Spritzer auf deinem Gesicht. Wow, das war ein Volltreffer, etwas Angenehmeres hast du noch nie gerochen !`nDu erhältst `#einen Charmepunkt`0.";
					$session['user']['charm']++;
					break;
				case 6:
					$str_out .= "Du verteilst ein paar Spritzer auf deinem Gesicht, was du gleich darauf sehr bereust. Dem Geruch nach würdest du dich nicht wundern, wenn sich das Parfüm gerade durch deine Haut ätzt.`nDu verlierst `4einen Charmepunkt`0.";
					$session['user']['charm'] = max($session['user']['charm']-1,0);
					if (mt_rand(0,9) < 9)
					{
						$str_out .= "`nImmerhin bekommst du als Entschädigung deinen Edelstein zurück.";
						$session['user']['gems']++;
					}
					break;
			}
			$session['user']['gems']--;
			addnav("Zurück","perfume.php");
			break;
		default:
			$str_out .= words_by_sex('`)I`en`sm`&itten der noblen Geschäftsstrassen Atrahors hat der sich der leicht großtuerische Parfumeur `8St`pan`.isl`|as`&, ein Freund pompös gefertigter Gewandungen und geistloser Konversation, niedergelassen. Seine Parfümerie, problemlos aufgrund des unharmonischen Duftbreis auszumachen, welcher das Gebäude unsichtbar umnebelt, besticht Innen wie Aussen mit leicht kitschigem Ambiente. Gülden bepinselte Fensterrahmen und das Wappen des Parfumeurs - ein ebenfalls goldener Flacon - ergänzen rote Teppiche und nusshölzerne Regalwände nach dem ästhetischen Empfinden des Geschäftsführers ganz vorzüglich.`n`n
			Hier bedient zwischen allerhand `OÖlen`&, `ITinkturen`&,`Y Harzen`&,`F Pomaden`&, `GPudern`&,`* Seifen`&, `/Cremes`& und natürlich `zParfüms`&, der Inhaber mit affektierter Höflichkeit seine verwöhnte Kundschaft mit seinen Exklusivitäten. Wuselig und aufgeregt erlebt man den kleinen Mann mit den glänzenden Schnallenschuhen, wenn er hinter seinem Thresen hervortritt und mit schmeichelnder Verbeugung das Wort ergreift:`n`n
			`i`|"[Monsieur|Madame]... Bitte erlaubt mir, Euch mit der Vorführung erlesener Düfte und Kosmetika den Tag zu versüßen."`i`n`n`0');
			
			if (($session['user']['gems']+$session['user']['gemsinbank']>0)) addnav("Parfüm (1 Edelstein)","perfume.php?op=perfume");
	}
	//

	output($str_out);

	viewcommentary('perfume','Hinzufügen',25,'sagt');

	addnav('M?Zurück zum Markt','market.php');

	page_footer();
?>