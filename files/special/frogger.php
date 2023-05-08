<?php
// Der verwandelte Frosch
// In Anlehnung an "fairy1.php"
// by Maris (Maraxxus@gmx.de)

if (!isset($session))
{
    exit();
}


if ($_GET['op']=="kiss")
{
    output("`2Du nimmst ".($session['user']['sex']?"den Frosch ":"die Kröte ")."auf deine Hand und gibst ".($session['user']['sex']?"ihm":"ihr")." einen dicken Kuss.`n`n");
    
    switch (e_rand(1,10))
    {
    case 1:
    case 2:
        output(($session['user']['sex']?"Der Frosch ":"Die Kröte ")."verwandelt sich auch augenblicklich, allerdings jedoch nicht in ".($session['user']['sex']?"den erwarteten Prinzen":"die erwartete Prinzessin").", sondern in ".($session['user']['sex']?"einen Waldgeist, der seinen ":"eine Fee, die ihren ")."Schabernack mit dir trieb.
		`nZornig über die ausgebliebene Belohnung und deine Beschämung steigert sich deine Motivation zu kämpfen.
		`n`@Du bekommst einen zusätzlichen Waldkampf!");
        $session['user']['turns']++;
        break;
    case 3:
    case 4:
        output(($session['user']['sex']?"Der Frosch ":"Die Kröte ")."verwandelt sich augenblicklich, und vor dir steht ".($session['user']['sex']?"ein schmucker Prinz":"eine hübsche Prinzessin").".
		`n".($session['user']['sex']?"Er ":"Sie ")."bedankt sich höflich bei dir und überreicht dir ".($session['user']['sex']?"seine ":"ihre ")."Halskette. Den `^Edelstein`2, der sich daran befindet, löst du natürlich sofort mit deinem Dolch ab und lässt ihn in deiner Tasche verschwinden, den wertlosen Rest wirfst du fort.`0`n");
        $session['user']['gems']+=1;
        break;
    case 5:
        output(($session['user']['sex']?"Der Frosch ":"Die Kröte ")."verwandelt sich tatsächlich, und vor dir steht ".($session['user']['sex']?"ein strahlender Prinz":"eine bildhübsche Prinzessin").".
		`n".($session['user']['sex']?"Er ":"Sie ")."bedankt sich überschwänglich bei dir und verkündet deine heldenhafte Tat im ganzen Reich, was dir `^maximales Ansehen`2 einbringt!`0`n");
        addnews("`@".$session['user']['name']."`# hat ".($session['user']['sex']?"einen Prinzen ":"eine Prinzessin ")."von einem Fluch erlöst und sich damit ein hohes Ansehen verdient.");
        $session['user']['reputation']=50;
        break;
    case 6:
    case 7:
        output(($session['user']['sex']?"Der Frosch ":"Die Kröte ")."verwandelt sich tatsächlich, und vor dir steht ".($session['user']['sex']?"ein Prinz":"eine Prinzessin").".
		`n".($session['user']['sex']?"Er ":"Sie ")."bedankt sich aufrichtig bei dir und belohnt dich mit `^2500 Goldmünzen`2!`0`n");
        $session['user']['gold']+=2500;
        break;
    case 8:
    case 9:
    case 10:
        if (e_rand(1,6)!=4)
        {
            output("Doch die dumme Kreatur denkt ja gar nicht daran sich zu verwandeln.
			`nLangsam aber sicher musst du dir eingestehen, dass du von ".($session['user']['sex']?"einem sprechenden Frosch ":"einer sprechenden Kröte ")."hereingelegt wurdest, ".($session['user']['sex']?"der ":"die ")."nun eiligst davon hüpft.
			`nDu verbringst die nächste Zeit damit, dir fluchend den Mund auszuspülen. Pfui!
			`n`4Du verlierst einen Waldkampf!`0`n");
            $session['user']['turns']--;
            if ($session['user']['turns']<0)
            {
                $session['user']['turns']=0;
            }
        }
        else
        {	
			
            if ($session['user']['title']!="`2Kröte`0" && $session['user']['title']!="`2Frosch`0" )
            {
				$oldweaponname=$session['user']['weapon'];
				$oldarmorname=$session['user']['armor'];
            	
            	item_set_weapon('Klebrige Zunge', 1, 1);
				item_set_armor('Schleimige Haut', 1, 1);
            	
                output(($session['user']['sex']?'Der Frosch ':'Die Kröte ').'verwandelt sich tatsächlich, und vor dir steht '.($session['user']['sex']?'ein Prinz':'eine Prinzessin').'.
				`nDoch irgendwie hat nicht nur '.($session['user']['sex']?'er ':'sie ').'sich verwandelt, sondern auch du veränderst deine Gestalt!
				`n`#Du wurdest in ein'.($session['user']['sex']?'e Kröte ':'en Frosch ').'verwandelt und musst jetzt in dieser Form dein Dasein fristen, während '.($session['user']['sex']?'der Prinz ':'die Prinzessin ').'von dannen eilt, glücklich darüber jemanden gefunden zu haben, der '.($session['user']['sex']?'ihn ':'sie ').'aus diesem Schicksal ablöst.
			`n`n`n`0Die Welt kommt dir plötzlich so groß vor. Auch dein '.$oldweaponname.'`0 und dein '.$oldarmorname.'`0 sind nicht mehr da wo sie eben noch waren. Wenigstens hast du deinen Inventarbeutel noch bei dir, in den du bald mal reingucken solltest.`n');
                addnews("`@".$session['user']['name']."`@ hat heute einen Imagewandel erfahren.");
                
                $session['user']['title'] = ($session['user']['sex']?"`2Kröte`0":"`2Frosch`0");
                user_set_name($session['user']['acctid']);
                
            }
            else
            {
                output("`2Ihr beide turtelt eine Weile, aber keiner von euch beiden verwandelt sich.
				`nDu bist weiterhin ".($session['user']['sex']?"eine Kröte ":"ein Frosch ")."!`0`n");
            }
            
            
        }
        break;
        
    }
    
	$session['user']['specialinc']="";    
}
else if($_GET['op'] == 'dont') {
	output("`2Du willst dich nicht auf so ein Spiel einlassen und zertrittst die Kreatur auf dem Boden.`0");
    $session['user']['specialinc']="";
}
else
{

	output("`2Dir hüpft ein".($session['user']['sex']?" Frosch ":"e Kröte ")."vor die Füße. \"`^So helft mir, ".($session['user']['sex']?"edle Dame!":"edler Recke!")." Ich bin ".($session['user']['sex']?"ein verzauberter Prinz ":"eine verzauberte Prinzessin ")."und kann nur durch einen Kuss zurückverwandelt werden!`2\", klagt ".($session['user']['sex']?"er":"sie").".
	`nWas wirst du tun?`0`n");
    addnav("Küssen","forest.php?op=kiss");
    addnav("Vergiss es!","forest.php?op=dont");
    $session['user']['specialinc']="frogger.php";
    
}


?>