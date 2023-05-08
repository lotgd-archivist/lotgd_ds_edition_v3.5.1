<?php

/**
* Man begegnet einem blinden Waldgeist. Ehrlichkeit wird belohnt.
* @author Laulajatar für atrahor.de
*/

if (!isset($session))
{
	exit();
} 

$session['user']['specialinc']=basename(__FILE__);
$str_output = '';

switch ($_GET['op'])
{ 
	case 'run': 
	{
		$str_output .= '`hDir ist das ganze absolut nicht geheuer und statt der Fremden eine Antwort zu geben nimmst du die Beine in die Hand und rennst davon. Als du nach einer ganzen Weile völlig außer Atem wieder wagst, dich umzudrehen ist von dem seltsamen Wesen keine Spur mehr zu sehen.';
		$session['user']['specialinc']='';
		break;
	}

	case 'question':
	{
$c=CRPChat::make_color($Char->prefs['commenttalkcolor'],'3');
$e=CRPChat::make_color($Char->prefs['commentemotecolor'],'7');
		addnav('Welcher Monat ist es?');
		$str_output .= $e.'\'Was kann an einer Frage schon so schlimm sein?\'`h, denkst du dir und nickst, ein wenig neugierig vielleicht sogar, was die Fremde denn von dir wissen will.
		`n'.$c.'`i"Natürlich!"`i`h, ist deine Antwort, woraufhin die Fremde erfreut lächelt.
		`n`K`i"Ich habe lange geschlafen, sehr lange. Doch ich weiß nicht, wie lange. Welchen Monat haben wir?"`i`h
		`nDu fragst dich zwar, wie man nur so lange schlafen kann, dass man nicht mehr weiß, welcher Monat es ist, doch das ist ja nicht das einzig Seltsame hier und wenigstens ist das eine leichte Frage. Denn natürlich ist es...
		`n`n'.create_lnk('Januar','forest.php?op=answer&mon=1',true,true,'',false,'',1).'
		`n'.create_lnk('Februar','forest.php?op=answer&mon=2',true,true,'',false,'',1).'
		`n'.create_lnk('März','forest.php?op=answer&mon=3',true,true,'',false,'',1).'
		`n'.create_lnk('April','forest.php?op=answer&mon=4',true,true,'',false,'',1).'
		`n'.create_lnk('Mai','forest.php?op=answer&mon=5',true,true,'',false,'',1).'
		`n'.create_lnk('Juni','forest.php?op=answer&mon=6',true,true,'',false,'',1).'
		`n'.create_lnk('Juli','forest.php?op=answer&mon=7',true,true,'',false,'',1).'
		`n'.create_lnk('August','forest.php?op=answer&mon=8',true,true,'',false,'',1).'
		`n'.create_lnk('September','forest.php?op=answer&mon=9',true,true,'',false,'',1).'
		`n'.create_lnk('Oktober','forest.php?op=answer&mon=10',true,true,'',false,'',1).'
		`n'.create_lnk('November','forest.php?op=answer&mon=11',true,true,'',false,'',1).'
		`n'.create_lnk('Dezember','forest.php?op=answer&mon=12',true,true,'',false,'',1).'
		`n`n';
		break;
	}

	case 'answer':
	{
		$session['user']['specialinc']='';
		$indate = getsetting('gamedate','0005-01-01');
		$date = explode('-',$indate);
		$monat = $date[1]; 
		$answer = $_GET['mon'];
		$str_output .= '`hAuf deine Antwort hin neigt die Fremde leicht den Kopf und streckt dir dann eine Hand entgegen.
		`n`i`K"Habt vielen Dank! Nehmt das für Eure Hilfsbereitschaft."`i`h
		`nDu greifst nach dem Gegenstand und stellst fest, dass sie dir einen `#Edelstein `hgeschenkt hat. Als du aufblickst um dich zu bedanken, ist sie jedoch mit einem Mal spurlos verschwunden.`n`n';
		$session['user']['gems']++;
		if ($answer != $monat)
		{
			$str_output .= '`hNun bekommst du doch ein schlechtes Gewissen, weil du sie angelogen hast. Um genau zu sein kommst du dir richtig `ihässlich`i vor...`n`n';
			$session['user']['charm']=max(0,$session['user']['charm']-1);
		} 
		
		break;
	}

	default:
	{
		$str_output .= '`hAuf der Suche nach den Monstern, die es zu bekämpfen gilt, ziehst du durch den Wald, als auf einmal eine Gestalt vor dir steht, ohne dass du Schritte vernommen hättest. Auf den ersten Blick wirkt die Gestalt wie ein Mädchen oder eine junge Frau, schweigend hat sie dir ihr Gesicht zugewandt, wenn du auch das Gefühl hast, dass sie dich gar nicht ansieht und ihr Blick an dir vorbei ins Leere geht. Sie macht keine Anstalten, dich anzugreifen, weswegen du dir auch die Zeit nimmst, das fremde Wesen etwas genauer zu betrachten. Nun erst bemerkst du, dass sie keine Schuhe trägt und nur in ein dünnes, moosgrünes Kleid gekleidet ist, an dem sich Blätter und kleine Äste verfangen haben - oder sind sie gar daran befestigt? Auch die braunen Locken sind mit Blättern gespickt und fast könntest du schwören, dass an einer Stelle ein wenig Moos darin wächst. Gerade als dir die Sache wirklich unheimlich zu werden beginnt, erhebt die Frau die Stimme:
		`n`K`i"Seid gegrüßt, Fremde'.($session['user']['sex']?'':'r').'. Werdet Ihr mir eine Frage beantworten?"`i`h
		`n`nDir kommt das ganze sehr merkwürdig vor; noch kannst du einfach verschwinden, ehe du die Frage auch nur gehört hast.`n`n';
		addnav('Frage anhören?');
		addnav('Aber sicher!','forest.php?op=question');
		addnav('Verschwinden','forest.php?op=run');
		break;
	}
} // Ende von groooßer switch

output ($str_output);
?>
