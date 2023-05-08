<?php
/**
* @desc Königliche Goldlieferung
* @copyright Asgarath (delta_romeo@hotmail.de) for Atrahor.de
* August 2008
*/
$str_output ='';

if ($_GET['op']=="")
{
	$str_output = "`nAuf deinen gewohnten Streifzügen durch die Wälder hörst du plötzlich einen ungewohnten Lärm durch das Dickicht. Du näherst dich den
			Geräuschen und mit der Zeit erkennst du, dass es sich dabei um mehrere Stimmen handelt. Du schiebst ein paar Büsche zur Seite und siehst,
			dass es sich dabei um einige königliche Soldaten handelt. Dein Blick fällt auf einen großen Wagen, welcher eine Truhe transportiert.
			Sofort weißt du, was das hier ist: `^Die königliche Schatzlieferung`e, welche wohl gerade von der Steuereintreibung kommt und eine Panne
			hatte, denn eines der hölzernen Räder liegt neben dem Wagen. Die Soldaten scheinen abgelenkt, denn sie diskutieren wohl darüber, wer Schuld
			an dieser Panne hat.`n`n
			Was willst du nun tun?`n`n
			Willst du den Soldaten deine Hilfe anbieten? Oder willst du die Chance nutzen und versuchen sie zu überfallen? Du kannst dem Ganzen
			natürlich auch den Rücken zudrehen...";

	addnav("Hilfe anbieten","forest.php?op=help");
	addnav("Überfallen","forest.php?op=attack");
	addnav("Ignorieren","forest.php?op=ignore");

	$session['user']['specialinc']="kingsgold.php";
}
else
{
	$session['user']['specialinc']="";

	if($_GET['op']=="help")
	{
		$str_output .= "`nDu beschließt, den Soldaten deine Hilfe anzubieten, also trittst du langsam aus den Büschen hervor und bewegst dich auf die Gruppe zu.
						Als du näher kommst, erkennen die Soldaten dich und greifen zu ihren Waffen. Schnell hebst du die Hände und erklärst ihnen, dass du
						nur helfen willst. Sie sehen, dass du keine feindlichen Absichten hast";
		switch(e_rand(1,2))
		{
			//Hilfe wird angenommen
			case 1:
				$str_output .= " und nehmen deine Hilfe an. Gemeinsam schafft ihr es, denn Wagen so anzuheben, dass einer der Soldaten das Rad wieder am Wagen
								befestigen kann.`n`n";

				switch(e_rand(1,2))
				{
					//Belohung
					case 1:
						$str_output .= "Als Dank für deine Hilfe geben sie dir ein kleines Säckchen mit Gold und versprechen dir, dem König von deiner
										 	 ehrenhaften Tat zu berichten! Dies wirkt sich natürlich gut auf deinen Ruf in der Stadt aus.`n`n";

						$goldamount = e_rand(200,$session['user']['level']*200);
						$str_output .= "`^Du erhältst $goldamount Gold!`n";
						$session['user']['gold'] += $goldamount;
						$str_output .= "`^Dein Ansehen in der Stadt steigt!";
						$session['user']['reputation'] += 5;
						break;

						//Keine Belohnung
					case 2:
						$str_output .= "`eSie danken dir für deine Hilfe und ziehen dann weiter. Du fühlst dich gut bei dem, was du getan hast.`n`n";
						$str_output .= "`%Du erhältst einen Charmepunkt!";
						$session['user']['charm']++;
						break;
				}

				break;
				//Hilfe wird abgelehnt
			case 2:
				$str_output .= ", doch sie lehnen deine Hilfe ab. Wenigstens hattest du gute Absichten und fühlst dich deshalb bereit einen weiteren Kampf
								 bestreiten zu können!`n`n";
				$str_output .= "`&Du erhältst einen Waldkampf!";
				$session['user']['turns']++;

				break;
		}
	}
	else if($_GET['op']=="attack")
	{
		$str_output .= "`nOhne lange zu zögern greifst du zu deiner Waffe und schleichst dich langsam an die Soldaten heran. Auf einmal stürzt du dich mit
						 tösendem Gebrüll auf sie und nutzt den Überraschungsmoment. ";

		$rand = e_rand(1,4);

		// Angriff gelingt
		if($rand < 2)
		{
			$str_output .= "Bereits nach kurzen Zeit liegen die Soldaten besiegt am Boden und du kannst dich über die Schätze hermachen.`n`n";
			$rand = e_rand(1,11);

			if($rand <= 5)
			{
				$str_output .= "Du schaffst es die Truhe zu öffnen, doch leider hast du nur die Zeit ein Goldsäckchen mitzunehmen, denn aus der Ferne hörst du
								 schon die Verstärkung näher kommen. Schnell fliehst du zurück in die Wälder.`n`n";

				$goldamount = e_rand(500,500 + $session['user']['level']*150);
				$str_output .= "`^In dem Säckchen findest du $goldamount Goldstücke`n";
				$session['user']['gold'] += $goldamount;
			}
			else if($rand > 5 && $rand <= 10)
			{
				$gemsamount = e_rand(2,3);
				$str_output .= "Du schaffst es die Truhe zu öffnen, doch leider hast du nur die Zeit `^$gemsamount `eEdelsteine in die Tasche zu stecken, denn
								aus der Ferne hörst du schon die Verstärkung näher kommen. Schnell fliehst du zurück in die Wälder.";


			}
			else
			{
				$str_output .= "Als du die Truhe öffnest, kannst du deinen Augen nicht trauen. Darin befinden sich doch tatsächlich die Insignien! Du willst
								gerade eine davon in die Tasche stecken, als sie dir zu Boden fällt und zerbricht. Aus der Ferne hörst du die Verstärkung der
								Soldaten näher Rücken und so hast du nur noch die Zeit einen der Splitter in die Tasche zu stecken und schnell in die Wälder zu
								fliehen.`n`n";

				$str_output .= "`&Du erhältst einen Insigniensplitter!";
				item_add($session['user']['acctid'],'insgnteil');
			}

		}
		// Angriff scheitert
		else
		{
			$str_output .= " Doch dieser bringt dir nur einen geringen Vorteil. Du schaffst es ein paar der Soldaten nieder zu strecken, ";


			switch(e_rand(1,3))
			{

				case 1:

					$str_output .= "doch schon kommt die Verstärkung angerückt. Du bist sowieso schon ziemlich mitgenommen, also bleibt dir keine andere
				     				Wahl und du musst fliehen.`n`n
				     				`4Über deine Feigheit bist du so beschämt, dass du einen Charmepunkt verlierst.`n";
					$session['user']['charm'] = max(0,$session['user']['charme']-1);
					$str_output .= "Außerdem musst du dich erst von dem Kampf erholen und solltest dringend einen Heiler aufsuchen!`n`n
					     			`$ Du verlierst drei Waldkämpfe!";
					$session['user']['turns'] = max(0,$session['user']['turns']-3);
					$session['user']['hitpoints'] = 1;
					break;

				case 2:

					$str_output .= "doch sie sind in der Überzahl und schon nach kurzer Zeit ist auch die Verstärkung da. Du hast keine Chance und gehst zu
									Boden.`n`n
									`$ Du bist tot und verlierst alles Gold was du bei dir hattest!`n`n";
					killplayer();
					$str_output .= "`eAußerdem spricht sich dein kläglicher Versuch, die königliche Lieferung zu überfallen, schnell herum.`n`n
									`$ Dein Ansehen sinkt!";
					$session['user']['reputation'] = max(0,$session['user']['reputation']-5);
					addnews("`$".$session['user']['name']."`$ wurde bei seinem erbärmlichen Versuch, die königliche Schatzlieferung zu überfallen, getötet!");
					break;

				case 3:

					$str_output .= "doch nach kurzer Zeit eilt die Verstärkung zur Hilfe und gemeinsam schaffen sie es, dich zu überwältigen. Sie liefern
									dich an die Stadtwache von Atrahor aus und beschlagnahmen dein ganzes Gold.`n`n";
					$session['user']['gold']=0;
					$str_output .= "`$ Du landest im Kerker und verlierst dadurch Ansehen in der Stadt!";
					$session['user']['reputation'] = max(0,$session['user']['reputation']-5);
					$session['user']['imprisoned']=3;
					addnav("In den Kerker","prison.php");
					break;

			}

		}
	}
	else
	{
		$str_output .= "`nDu entschliesst dich, deinen eigenen Weg fortzusetzen. Immerhin bist du beschäftigt und hast besseres zu tun.";
	}
}
output($str_output);
?>