<?php

/*
Der Fremde
© Fingolfin
29.04.2007
*/

if (!isset($session))
{
	exit();
}

$session['user']['specialinc']=basename(__FILE__);

page_header('Der Fremde');

if($_GET['op']=='lesson')
{
	if($session['user']['gold']<100)
	{
		output('`0Der Fremde steht langsam auf und wendet sich in einem freundlichen aber eindeutigem Tonfall an dich `KDas Gold hätte ich gerne jetzt. Es gibt genug Betrüger die gerne die Kunst des Atems erlernen würden. `0Ruhig streckt er dir seine Hand entgegen und schaut dich mit einem auffordernden Blick an.`n`n
		Du kramst in deinen Taschen und musst leider feststellen das du nicht genug Gold bei dir trägst um dich unterrichten zu lassen. Etwas beschämt antwortest du `$Es tut mir leid werter Lanthir, aber ich habe wohl nicht genug Gold bei mir. `0Nicht mehr ganz so freundlich antwortet dieser `KWie gut das ich dich davor gefragt habe. Ich wünsche einen schönen Tag im Wald. `0Und mit dieser fast schon Aufforderung setzt er sich wieder in das Gras...');
		
		//addnav('In den Wald','forest.php');
			
	}
	else
	{
		output('`0Du gibts Lanthir die `^100 Goldstücke `0und er beginnt mit seiner Lektion. Er macht dir komische Atemübungen vor, die du nachmachen musst und mit der Zeit kommst du dir schon fast lächerlich vor. Insgeheim hoffst du, dass euch keiner von den Bäumen aus zuschaut. Schon bald ist Lanthir aber mit seinen Übungen fertig und du willst schon fast gehen, als er dir noch etwas hinterher ruft `KIch habe gehört, dass es hier in der Gegend einen Waldsee hat, vielleicht kannst du dort ja ein wenig deinen Atem im Tauchen üben.`n`n
		`0Du lächelst dem fremden Mann einmal kurz zu und verschwindest dann im Wald, nicht ohne den Tipp mit dem Waldsee zu vergessen. Du solltest nicht zu arg trödeln.');
		
		$session['user']['gold'] -= 100;
		
		$atembuff = array('name'=>'`FLanger Atem','rounds'=>35,'wearoff'=>'`FDu bist aus der Puste!`0','atkmod'=>1.1,'roundmsg'=>'`FDu bekommst mehr Luft und schlägst härter zu!`0','activate'=>'offense');
		buff_add($atembuff);
		
		//addnav('Weiter','forest.php');
	}
	
	$session['user']['specialinc']='';
	
}

elseif($_GET['op']=='quit')
{
	output('`0Du überlegst kurz und verkündest dem Fremden deine Entscheidung: `$Nein Danke, ich komme auch ohne aus. Einen schönen Tag noch. `0Und ohne ein weiteres Wort drehst du dich um und verschwindest wieder zwischen den Bäumen...');
	
	$session['user']['specialinc']='';
	
	//addnav('Weiter','forest.php');
}

else
{
	output('`c`b`4Der Fremde`0`b`c`n
	Du läufst zwischen dicht stehenden Bäumen umher auf der Suche nach einem Gegner, als du plötzlich auf eine Lichtung kommst, die du noch nie zuvor gesehen hast. Mit einem raschen Blick überfliegst du sie und entdeckst einen fremden Mann, der dort auf der Lichtung sitzt und dich freundlich anlächelt.`n`n
	Misstrauisch näherst du dich ihm und bleibst in sicherem Abstand stehen. `$Wer seid ihr und was macht ihr hier? `0Der Fremde verzieht keine Miene und antwortet genauso freundlich wie er dreinschaut: `KIch komme von weit her, mein Name ist Lanthir und genauso könnte ich dich fragen - Was machst du hier? `0Er zwinkert dir zu und du kommst dir in deiner Position schon nicht mehr so sicher vor wie gerade zuvor.`n
	Da du kein Wort mehr sagst nimmt Lanthir wieder das Wort an sich und meint `KDort wo ich herkomme gibt es viel, viel Wasser. Ich könnte dir beibringen wie man am besten den Atem anhalten kann. Das kostet dich aber `^100 Gold`0.`n`n
	Unverzagt schaut er dich weiter an und wartet auf deine Antwort.');
	
	addnav('Unterricht nehmen (100 Gold)','forest.php?op=lesson');
	addnav('Zurück');
	addnav('Kein Interesse..','forest.php?op=quit');
}

?>