<?php

function gftcurse_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook ) {
					
		case 'gift':	// Nach Versenden des "Geschenks"

            $sql = "SELECT name,sex FROM accounts WHERE acctid=".$item_hook_info['acctid'];
            $result = db_query($sql);
            $victim = db_fetch_assoc($result);
            
            switch (e_rand(1,30))
            {
                case 1:
                    $actor = " Ramius ";
                break;
                case 2:
                    $actor = "n die Götter ";
                break;
                case 3:
                    $actor = " ein Wesen aus fremden Welten ";
                break;
                case 4:
                    $actor = " der grüne Drache ";
                break;
                case 5:
                    $actor = " ein hengstiger Greselle ";
                break;
                case 6:
                    $actor = "n wilde Vögel ";
                break;
                case 7:
                    $actor = " die Pest ";
                break;
                case 8:
                    $actor = "n streunende Hunde ";
                break;
                case 9:
                    $actor = " dein schlimmster Alptraum ";
                break;
                case 10:
                    $actor = " Old Drawl ";
                break;
                case 11:
                    $actor = "n die Dämonen ";
                break;
                case 12:
                    $actor = " Dodo, der Kuscheldämon, ";
                break;
                case 13:
                    $actor = "n Audreys Katzen ";
                break;
                case 14:
                    $actor = "n die Schrecken der Finsternis ";
                break;
                case 15:
                    $actor = "n barbarische Nordmänner ";
                break;
                case 16:
                    $actor = " deine Mudda ";
                break;
                case 17:
                    $actor = " ein Rudel Zwergpinseläffchen ";
                break;
                case 18:
                    $actor = "st du ";
                break;
                case 19:
                    $actor = " ein Racheengel ";
                break;
                case 20:
                    $actor = " der Bösewicht aus einem Bong-Film ";
                break;
                case 21:
                    $actor = "n süße Schäfchen von der Weide ";
                break;
                case 22:
                    $actor = " einer dieser Knuddel-Vampire ";
                break;
                case 23:
                    $actor = " die Gerechtigkeit ";
                break;
                case 24:
                case 25:
                case 26:
                    $sql = "SELECT name FROM accounts WHERE acctid<>".$session['user']['acctid']." ORDER BY RAND()";
                    $result = db_query($sql);
                    $name = db_fetch_assoc($result);
                    $actor = " ".$name['name']." ";
                break;
                case 27:
                    $actor = "n die Sänger von \"Peking Herberge\" ";
                break;
                case 28:
                    $actor = " ein Erdhörnchen ";
                break;
                case 29:
                    $actor = " ein NmuN ";
                break;
                case 30:
                    $actor = " die kosmische Urstrahlung ";
                break;
            }
            
            switch (e_rand(1,20))
            {
                case 1:
                    $subject = "deine Eingeweide ";
                break;
                case 2:
                    $subject = "deinen Besitz ";
                break;
                case 3:
                    $subject = "deine Ahnen ";
                break;
                case 4:
                    $subject = "dein Grab ";
                break;
                case 5:
                    $subject = "deine Nachkommen ";
                break;
                case 6:
                    $subject = "deinen Allerwertesten ";
                break;
                case 7:
                    $subject = "deine Zunge ";
                break;
                case 8:
                    $subject = "deine Seele ";
                break;
                case 9:
                    $subject = "deine Knochen ";
                break;
                case 10:
                    $subject = "dein Gesicht ";
                break;
                case 11:
                    $subject = ($victim['sex']?"deinen Geliebten ":"deine Geliebte ");
                break;
                case 12:
                    $subject = ($victim['sex']?"deine Weiblichkeit ":"deine Männlichkeit ");
                break;
                case 13:
                    $subject = "dich ";
                break;
                case 14:
                    $subject = "deinen Kadaver ";
                break;
                case 15;
                    $subject = "deine Hände ";
                break;
                case 16:
                    $subject = "deine Freunde ";
                break;
                case 17:
                    $subject = "dein Haus ";
                break;
                case 18:
                    $subject = "deine Füße ";
                break;
                case 19;
                    $subject = "deinen Kopf ";
                break;
                case 20:
                    $subject = "deinen Hals ";
                break;
            }
            
            switch (e_rand(1,20))
            {
                case 1:
                    $action = " fressen!";
                break;
                case 2:
                    $action = " zerstören!";
                break;
                case 3:
                    $action = " zerreissen!";
                break;
                case 4:
                    $action = " verstümmeln!";
                break;
                case 5:
                    $action = " verspeisen!";
                break;
                case 6:
                    $action = " infizieren!";
                break;
                case 7:
                    $action = " schänden!";
                break;
                case 8:
                    $action = " stehlen!";
                break;
                case 9:
                    $action = " quälen!";
                break;
                case 10:
                    $action = " verbrennen!";
                break;
                case 11:
                    $action = " verprügeln!";
                break;
                case 12:
                    $action = " in Stein verwandeln!";
                break;
                case 13:
                    $action = " in glühende Kohlen betten!";
                break;
                case 14:
                    $action = " verschandeln!";
                break;
                case 15:
                    $action = " schrumpfen lassen!";
                break;
                case 16:
                    $action = " zerbeissen!";
                break;
                case 17:
                    $action = " als Latrine missbrauchen!";
                break;
                case 18:
                    $action = " verschwinden lassen!";
                break;
                case 19:
                    $action = " zerplatzen lassen!";
                break;
                case 20:
                    $action = " im Moor versenken!";
                break;
            }
			
            $phrase = "`^Möge".$actor."`^".$subject."`^".$action;

            output("`2Folgender Fluch wurde für ".$victim['name']."`2 geschaffen:`n ".$phrase."`n`n");
            $item_hook_info['effect'] = "`nDer Fluch hat folgenden Wortlaut:`n`^".$phrase;
            $item['tpl_description'] = "`2Folgenden Fluch schickte dir ".$session['user']['name']."`2:`^".$phrase;

            $session['user']['gold'] -= $item['tpl_gold'];
			$session['user']['gems'] -= $item['tpl_gems'];
			$item['tpl_gold']=0;
			$item['tpl_gems']=0;
            item_add($item_hook_info['acctid'],0,$item);
            $item_hook_info['hookstop'] = true;
            
			break;
					
	}
		
	
}

?>
