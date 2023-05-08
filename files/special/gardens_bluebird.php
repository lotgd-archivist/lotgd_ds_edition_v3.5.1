<?php

/*
Kleines Special von Tyndal
Ein geheimnisvolles Lied
Idee und Skript by me ^^
Geschrieben für http://www.atrahor.de
16.12.2007
Wäre sehr verbunden, wenn dieser Textblock drinbleibt xD
*/

if (!isset($session)) exit();

$str_output=("`2");

$str_filename = basename(__FILE__);

switch ($_GET['sop'])
{
	case '' :
	{
		$str_output.="Als du durch den Garten schlenderst, kommt dir plötzlich ein lieblicher Gesang zu Ohren. Was ist das nur für eine Melodie, und woher kommt sie? Getrieben von Neugier suchst du nach dem Ursprung der Klänge und schon nach kurzer Zeit wirst du fündig : Ein `9blauer Vogel`2, der auf einem Stein sitzt und seine wohlklingende Stimme darbietet.";
		addnav("Ignorieren","gardens.php?sop=leave");
		addnav("Heimlich beobachten","gardens.php?sop=watch");
		addnav("Näher ran","gardens.php?sop=closer");
		$session['user']['specialinc'] = $str_filename;
	} 
	break;
	case 'leave' :
	{
		$str_output.="Du beschließt, den Vogel sich selbst zu überlassen und deine Zeit lieber mit wichtigeren Dingen zu verbringen.";
		$session['user']['specialinc']="";
		addnav("Zurück in den Garten","gardens.php");
	}
	break;
	case 'watch' :
	{
		$str_output.="Du versteckst dich im dichten Blattwerk und lauschst dem Gesang des geheimnisvollen Vogels. ";
		if (e_rand(0,99)<50)
		{
			$str_output.="Nach einer Weile beendet das Wesen seinen Gesang und erhebt sich in die Lüfte. ";
			if (e_rand(0,99)<50)
			{
				$session['user']['turns']+=1;
				$str_output.="Von der Melodie des Vogels beflügelt fühlst du dich bereit für einen weiteren Kampf.";
			}
			else
			{
				$str_output.="Leider hat dir das Warten überhaupt nichts gebracht, da du im dichten Blattwerk kaum etwas hören konntest.";
			}
			$session['user']['specialinc'] = "";
			addnav("Zurück in den Garten","gardens.php");
		}
		else
		{
			$str_output.="Nachdem du den Vogel eine Weile lang beobachtet hast, fällt dir eine kaum merkliche Bewegung in der Nähe des Steins auf. Tatsächlich, eine Katze, und auch sie scheint sich für den Vogel zu interessieren - wenn auch vermutlich nicht aus den selben Gründen wie du.";
			$session['user']['specialinc'] = $str_filename;
			addnav("Den Ort verlassen","gardens.php?sop=go");
			addnav("Die Katze vertreiben","gardens.php?sop=chase");
		}
	}
	break;
	case 'closer' :
	{
		$str_output.="Langsam und sehr vorsichtig näherst du dich dem geheimnisvollen Wesen, um es ein wenig genauer zu studieren. ";
		if (e_rand(0,99)<50)
		{
			$str_output.="Der Vogel unterbricht seinen Gesang für einen Moment und beobachtet dich interessiert, bevor er wieder anfängt, sein Lied zu zwitschern. Da dich das Wesen anscheinend akzeptiert hat, lässt du dich auf einem nahen Stein nieder und lässt die Melodie auf dich wirken. ";
			if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
			{
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
				$str_output.="Nach einer Weile beendet der Vogel seine wunderbare Melodie, streckt kurz seine Flügel von sich und erhebt sich nach einem letzten Blick zu dir in die Lüfte. Erst jetzt stellst du fest, dass der Gesang eine heilsame Wirkung auf deinen Körper hatte, denn alle deine Wunden sind verheilt.";
			}
			else
			{
				$str_output.="Leider spürst du trotz der zauberhaften Klänge keinerlei körperliche Veränderung. Etwas enttäuscht kehrst du in den Wald zurück.";
			}
		}
		else
		{
			$str_output.="Leider wohl nicht vorsichtig genug, denn der Vogel flattert einen Moment lang mit den Flügeln und fliegt dann auf und davon. Aber keine Angst, er hat dir - vermutlich aus Nervosität - ein ~Geschenk~ hinterlassen, das nun deinen Nacken ziert. ";
			if ($session['user']['charm']>0)
			{
				$session['user']['charm']-=1;
				$str_output.="Das wirkt sich nun nicht gerade positiv auf dein Aussehen aus, weshalb du einen Charmepunkt verlierst. Etwas verärgert ziehst du wieder deines Weges.";
			}
			else
			{
				$str_output.="Das stört dich allerdings nicht weiter - einmal Waschen und gut. Dennoch ziehst du etwas verärgert wieder deines Weges.";
			}
		}
		$session['user']['specialinc']="";
		addnav("Zurück in den Garten","gardens.php");
	}
	break;
	case 'go' :
	{
		$str_output.="Langsam und für den Vogel das Beste hoffend kehrst du den beiden den Rücken zu. Die Katze war vermutlich ebenso fasziniert von dem Lied wie du - ja, das musste es sein...`n";
		if (e_rand(0,98)<33)
		{
			if ($session['user']['charm']>0)
			{
				$session['user']['charm']-=1;
				$str_output.="Dennoch kommst du nicht umhin, an das zu denken, was wohl viel wahrscheinlicher war. Du hast ein schlechtes Gewissen und fühlst dich grässlich.";
			}
		}
		$session['user']['specialinc']="";
		addnav("Zurück in den Garten","gardens.php");
	}
	break;
	case 'chase' :
	{
		$str_output.="Du kommst aus dem Gebüsch gestürmt und machst dabei ordentlich Lärm. ";
		if (e_rand(0,99)<50)
		{
			$session['user']['charm']+=1;
			$str_output.="Und tatsächlich gerät die Katze in Panik und sucht das Weite. Du hast dem Vogel das Leben gerettet und fühlst dich großartig.";
		}
		else
		{
			if ($session['user']['turns']>0) 
			{
				$session['user']['turns']-=1;
			}
			$str_output.="Doch leider hast du etwas zu spät reagiert. Du schaffst es dennoch, die Katze zu verscheuchen, doch der Vogel ist bereits verletzt. Aus Mitleid verbringst du einige Zeit damit, dem Vogel zu helfen.";
		}
		$session['user']['specialinc']="";
		addnav("Zurück in den Garten","gardens.php");
	}
	break;
}

output($str_output);

page_footer();
	
?>