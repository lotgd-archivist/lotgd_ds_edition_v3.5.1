<?php

require_once("common.php");

page_header("Goldschrein");

switch($_GET["op"])
{
	case "waste":
		$opfer = (int) $_POST["waste"];
		if($opfer == 0)
		{
			$str_out = "`-Du mieser kleiner Geizhals, also wirklich. Da bieten dir die Götter schon eine Möglichkeit, dein Gold sinnvoll sinnlos zu verschwenden, und du willst das nichtmal wahrnehmen. Husch, verschwinde !";
			#$str_out .= "`n`n`n(Hier könnte eventuell eine \"Geizhals\"-Trophäe verliehen werden.)";
		}
		elseif($opfer<0)
		{
			$str_out = "`-Also... das ist doch... da versucht doch tatsächlich jemand, die Götter über den Tisch zu ziehen und sich Gold zu erschummeln. Also wirklich. `n`nAber du hast Glück, die Götter sind gut drauf und lachen herzlich über deine Art zu denken.";
			#$str_out .= "`n`n (Hier könnte eventuell die \"Genie und Wahnsinn\"-Trophäe verliehen werden.)";
		}
		else
		{
			$opfer = min($opfer,$Char->gold);
			if($opfer > 1)
			{
				$str_out = "`-Du wirfst also $opfer Goldmünzen in den Schrein, doch keine einzige Münze berührt je dessen Boden.";
			}
			else
			{
				$str_out = "`-Du wirfst also eine Goldmünze in den Schrein, doch sie berührt nie dessen Boden.";
			}
			$str_out .= " Scheinbar haben die Götter deine ".($opfer == 1 ? "jämmerliche" : "" )." Gabe angenommen und du bekommst, was dir versprochen wurde: Nichts. Hast du etwa wirklich was Anderes erwartet, du Goldverschwender?";
			$Char->gold -= $opfer;
			db_query("UPDATE account_extra_info SET wastedgold = wastedgold + $opfer WHERE acctid = ".$Char->acctid);
			debuglog("hat $opfer Goldmünzen im Goldschrein geopfert.");
		}
		addnav("Zurück","downthedrain.php");
		break;
	default:
		$str_out = get_title("`yG`/o`-l`^dschr`-e`/i`yn").
		"`-Auf einem schlichten Tischchen steht ein geöffneter Schrein aus poliertem Gold, sorgsam bewacht, damit auch ja niemand auf böse Gedanken kommt und versucht diesen Ort durch Habgier zu entweihen. Ein Schild verkündet der Welt den Zweck des Schreins:`n
		`i`/„Opfert den Göttern euer Gold und erhaltet zum Lohn rein gar `bnichts`b dafür!“`i`n
		`n
		`-Diese Aussage wird wohl viele dazu bringen wieder zu gehen, andere werfen vielleicht dennoch ihr hart verdientes Gold in den Schrein mit der Gewissheit, es nie wieder zu sehen.`n
		`n
		`yWie viele Goldmünzen möchtest du geben?`0`n";
		if ($Char->gold > 0)
		{
			$str_out .= "`n<form action='downthedrain.php?op=waste' method='POST'><input name='waste' value='".($Char->gold)."'> <input value='Gold opfern' type='submit' onClick='if(confirm(\"Willst du wirklich \"+this.form.waste.value+\" Gold opfern ?\")) this.form.submit(); else return false;'></form>";
			addnav("","downthedrain.php?op=waste");
		}
		else
		{
			$str_out .= "Allerdings solltest du ein wenig Gold bei dir haben, wenn du gedenkst, hier etwas zu opfern.";
		}
		
}

output ($str_out);

addnav("Zurück zum Marktplatz","market.php");
addnav("d?Zum Stadtzentrum","village.php");
addnav("Liste in der Ruhmeshalle", "hof.php?op=wasterofgolds&subop=most");
page_footer();

?>