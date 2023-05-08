<?php
require_once "common.php";

$infos=array(
"owner"=>"Donols Haus der Qualen",
"file" => "".basename(__FILE__),
"creator" => "Hadriel",
"editor" => "Tiger313",
"homepage" => "http://www.hadrielnet.ch",
"homepage-editor" => "http://www.mlcrew.de",
"version" => "1.3 Beta"
);
//für Atrahor angepasst und Code optimiert von Salator

page_header('Kräftemessen');
$gold_total=$session['user']['goldinbank']+$session['user']['gold'];
$gold_win1=min(1000,round($gold_total*0.15));
$gold_win2=min(2500,round($gold_total*0.2));
$gold_win3=min(5000,round($gold_total*0.25));
$gold_lose1=min(3000,round($gold_total/10));
$gold_lose2=min(7500,round($gold_total/8));
$gold_lose3=min(9000,round($gold_total/6));
switch ($_GET['op'])
{
	case 1:
	{
		$session['user']['turns']--;
		output('`m2Du wählst Jirok aus. Ihr setzt euch an einen Tisch, legt einen eurer Arme auf den Tisch und beginnt.`n`n`mAktueller Status:`n'.grafbar(100,50,100,20).'',true);
		addnav('Weiter',''.$infos['file'].'?op=11');
		break;
	}
	case 11:
	{
		$rand=e_rand(1,100);
		$session['try']++;
		output('`mDu versuchst mit ganzer Kraft den Arm von Jirok auf den Tisch zu schmettern.`n`n`mAktueller Status:`n'.grafbar(100,$rand,100,20));
		if($rand<=10)
		{
			output('`n`mDu hast in '.$session['try'].' Zügen `4verloren!`m Jirok verlangt '.$gold_lose1.' Gold von dir.');
			$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'pressarm',".$session['user']['acctid'].",': `\$verliert`7 an `%Jirok`7 ".$gold_lose1." Gold!')";
			db_query($sql);
			if ($session['user']['gold'] >=$gold_lose1)
			{
				$session['user']['gold']-=$gold_lose1;
			}
			else
			{
				$session['user']['goldinbank']-=$gold_lose1;
				output('`n`mDa du nicht genug bei dir hast, wurde der Betrag von deinem Konto abgezogen..');
				debuglog('verlor '.$gold_lose1.' Gold beim Armdrücken (Bankeinzug)');
			}
			addnav('Zurück',$infos['file']);
		}
		elseif($rand>=90)
		{
			output('`n`mDu hast in '.$session['try'].' Zügen `4gewonnen!`m Du schnappst dir '.$gold_win1.' Gold von Jirok.');
			$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'pressarm',".$session['user']['acctid'].",': `@gewinnt`7 von `%Jirok`7 ".$gold_win1." Gold!')";
			db_query($sql);
			$session['user']['gold']+=$gold_win1;
			addnav('Zurück',$infos['file']);
		}
		else
		{
			addnav('Weiter',''.$infos['file'].'?op=11');
		}
		break;
	}

	case 2:
	{
		$session['user']['turns']--;
		output('`mDu wählst Kolop aus. Ihr setzt euch an einen Tisch, legt einen eurer Arme auf den Tisch und beginnt.`n`n`mAktueller Status:`n'.grafbar(200,100,100,20));
		addnav('Weiter',''.$infos['file'].'?op=21');
		break;
	}
	case 21:
	{
		$rand=e_rand(1,200);
		$session['try']++;
		output("`mDu versuchst mit ganzer Kraft den Arm von Kolop auf den Tisch zu schmettern.`n`n`mAktueller Status:`n".grafbar(200,$rand,100,20));
		if($rand<=30)
		{
			output('`n`mDu hast in '.$session['try'].' Zügen `4verloren!`m Kolop verlangt '.$gold_lose2.' Gold von dir.');
			$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'pressarm',".$session['user']['acctid'].",': `\$verliert`7 an `QKolop`7 ".$gold_lose2." Gold!')";
			db_query($sql);
			if ($session['user']['gold'] >=$gold_lose2)
			{
				$session['user']['gold']-=$gold_lose2;
			}
			else
			{
				$session['user']['goldinbank']-=$gold_lose2;
				output('`n`mDa du nicht genug bei dir hast wurde der Betrag von deinem Konto abgezogen..');
				debuglog('verlor '.$gold_lose2.' Gold beim Armdrücken (Bankeinzug)');
			}
			addnav("Zurück",$infos['file']);
			$session['try']=0;
		}
		elseif($rand>=185)
		{
			$session['user']['gold']+=$gold_win2;
			output('`n`mDu hast in '.$session['try'].' Zügen `4gewonnen!`m Du schnappst dir '.$gold_win2.' Gold von Kolop.');
			$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'pressarm',".$session['user']['acctid'].",': `@gewinnt`7 von `QKolop`7 ".$gold_win2." Gold!')";
			db_query($sql);
			addnav('Zurück',$infos['file']);
		}
		else
		{
			addnav('Weiter',''.$infos['file'].'?op=21');
		}
		break;
	}

	case 3:
	{
		$session['user']['turns']--;
		output('`mDu wählst Faler aus. Ihr setzt euch an einen Tisch, legt einen eurer Arme auf den Tisch und beginnt.`n`n`mAktueller Status:`n'.grafbar(300,150,100,20).'',true);
		addnav('Weiter',''.$infos['file'].'?op=31');
		break;
	}
	case 31:
	{
		$rand=e_rand(1,300);
		$session['try']++;
		output('`mDu versuchst mit ganzer Kraft den Arm von Faler auf den Tisch zu schmettern.`n`n`mAktueller Status:`n'.grafbar(300,$rand,100,20).'',true);
		if($rand<=50)
		{
			output('`n`mDu hast in '.$session['try'].' Zügen `4verloren!`m Faler verlangt '.$gold_lose3.' Gold von dir.');
			$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'pressarm',".$session['user']['acctid'].",': `\$verliert`7 an `gFaler`7 ".$gold_lose3." Gold!')";
			db_query($sql);
			if ($session['user']['gold'] >=$gold_lose3)
			{
				$session['user']['gold']-=$gold_lose3;
			}
			else
			{
				$session['user']['goldinbank']-=$gold_lose3;
				output('`n`mDa du nicht genug bei dir hast wurde der Betrag von deinem Konto abgezogen..');
				debuglog('verlor '.$gold_lose3.' Gold beim Armdrücken (Bankeinzug)');
			}
			addnav('Zurück',$infos['file']);
		}
		elseif($rand>=275)
		{
			$session['user']['gold']+=$gold_win3;
			output('`n`mDu hast in '.$session['try'].' Zügen `4gewonnen!`m Du schnappst dir '.$gold_win3.' Gold von Faler.');
			$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'pressarm',".$session['user']['acctid'].",': `@gewinnt`7 von `gFaler`7 ".$gold_win3." Gold!')";
			db_query($sql);
			addnav('Zurück',$infos['file']);
			$session['try']=0;
		}
		else
		{
			addnav('Weiter',''.$infos['file'].'?op=31');
		}
		break;
	}
	case 'info':
	{
		output('`c`b`mDie heutige Quote für '.$session['user']['name'].'`b`n`n`n
		`mDein Gesamtvermögen beträgt `4'.$gold_total.' `mGoldstücke`n`n
		`mQuote gegen `b`QJirok`b`n`6--->`mVerlust: `('.$gold_lose1.'`m Goldstücke<---`n--->Gewinn: `C'.$gold_win1.' `mGoldstücke<---`n`n
		`mQuote gegen `b`QKolop`b`n`6--->`mVerlust: `('.$gold_lose2.'`m Goldstücke<---`n--->Gewinn: `C'.$gold_win2.' `mGoldstücke<---`n`n
		`mQuote gegen `b`QFaler`b`n`6--->`mVerlust: `('.$gold_lose3.'`m Goldstücke<---`n--->Gewinn: `C'.$gold_win3.' `mGoldstücke<---`c`n`n');
		addnav('Zurück',$infos['file']);
		break;
	}
	case 'viewfights':
	{
		output('`c`mDie Ergebnisse der letzten Wettkämpfe:`n`n');
		viewcommentary('pressarm','',10,'',false,false,false,false,false,false);
		addnav('Zurück',$infos['file']);
		break;
	}
	default:
	{
		$session['try']=0;
		output('`mEtwas abseits des Bar-Geschehens findest du eine Unterhaltung anderer Art: Hier kannst du dich mit Jirok, Kolop oder Faler im Armdrücken messen.`n Jeder der drei hat andere Stärken und Schwächen. Der Gewinner bekommt einen Goldbetrag, welcher auf der Tafel niedergeschrieben ist.`n`n`4Jeder Kampf wird dich die Zeit einer Waldrunde kosten.');
		if ($gold_total >=500 && $session['user']['turns']>0)
		{
			output('`n`n`QJirok`m ist ein Mensch, der dir in Größe und Statur ebenbürtig ist.
			`n`QKolop`m ist ein unscheinbarer Troll, seine Technik soll jedoch sehr ausgefeilt sein.
			`n`QFaler`m ist ein muskelbepackter Ork. Schon der Anblick flößt dir Respekt ein.');
			addnav('Infotafel');
			addnav('Heutige Quote',''.$infos['file'].'?op=info');
			addnav('Letzte Ergebnisse',''.$infos['file'].'?op=viewfights');
			addnav('Die Gegner');
			addnav('Jirok',''.$infos['file'].'?op=1');
			addnav('Kolop',''.$infos['file'].'?op=2');
			addnav('Faler',''.$infos['file'].'?op=3');
		}
		else output('`n`4Du benötigst ein Gesamtvermögen von mindestens 500 Goldstücken, um hier kämpfen zu dürfen.');
		addnav('Zurück');
		addnav('B?In die Bar','tittytwister.php');
		addnav('G?Zur dunklen Gasse','slums.php');
		break;
	}
}
//copyright
//output("`n`n`n`n`n `^".$infos['owner']."`2 by <a href='".$infos['homepage']."' target='_blank'>".$infos['creator']."</a> `2 Edit by <a href='".$infos['homepage-editor']."' target='_blank'>".$infos['editor']."</a> Version ".$infos['version']."`n`n",true);
page_footer();
?>