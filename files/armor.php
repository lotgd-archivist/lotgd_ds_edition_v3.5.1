<?php

// 21072004

// modifications by anpera:
// stealing enabled with 1:15 success (thieves have 2:12 chance)
// Talion: Anpassungen ans Gildensystem (Rabatte)
// Salator: Rüstung in eigener Farbe (Definition der Bitflags von $rename_weapons siehe weapons.php)

require_once "common.php";
checkday();
$rename_armor=($session['user']['rename_weapons']&2);
$player = user_get_aei('job');
$p_job = $player['job'];

require_once(LIB_PATH.'dg_funcs.lib.php');
if($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT) {
        $rebate = dg_calc_boni($session['user']['guildid'],'rebates_armor',0);
}

page_header('Phaedras Rüstungen');
output('`c`b`(P`)h`7a`ee`fd`0ras Rüst`fu`en`7g`)e`(n`0`b`c`n');
$tradeinvalue = round(($session['user']['armorvalue']*0.75),0);

// 10%iger Händlerbones (Preis modifizieren)
if ($p_job==6)
{
        $tradeinvalue = round($tradeinvalue*1.1);
}

if ($_GET['op']=='duel') // Duell für das Rüstungsfärben
{
        $pointsavailable=$session['user']['donation']-$session['user']['donationspent'];
        if ($pointsavailable<500) {
                output('Phaedra lacht aus vollem Halse, als du ihr entgegentrittst und wendet sich dann auch wieder ihrer Arbeit zu, nachdem sie etwas Unverständliches gemurmelt hat. Was immer es war, es klang nicht sehr freundlich.');
                addnav('Zurück zum Marktplatz','market.php');
        }
        else {
                output('Phaedra legt ihre Sachen bei Seite und mustert dich eindringlich und nickt. Ihr sitzt euch nun gegenüber. Einige Schaulustige haben sich bereits vor dem Wagen versammelt und Vessa bietet ihre Dienste als Schiedsrichter an - natürlich vollkommen unparteiisch.
                `nNoch kannst du weglaufen. Der Kampf kostet dich, egal wie er ausgeht, 100 Donation Punkte und weitere 400, wenn du gewinnst.
                `n`nWenn du gewinnst, wird dir Phaedra ihre Rüstungen in deinen Lieblingsfarben färben.');
                addnav('Auf zum Wettstreit!','armor.php?op=duel2');
                addnav('Zurück zum Marktplatz','market.php');
        }
}

else if ($_GET['op']=='duel2')
{
        $session['user']['donationspent']+=100;

        $battle=true;
        $badguy = array("creaturename"=>"`#Phaedra`0","creaturelevel"=>$session['user']['level'],"creatureweapon"=>"Gestickte Ornamente","creatureattack"=>$session['user']['attack'],"creaturedefense"=>$session['user']['defence'],"creaturehealth"=>$session['user']['hitpoints'], "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
}

elseif ($_GET['op']=='browse' || $_GET['op']=='')
{
        if($session['user']['armordef']>0 || $session['user']['armor']=='Straßenkleidung' || $session['user']['specialmisc']=='sellarmor') //User hat kein Luxusgewand an
        {
                $sql = 'SELECT max(level) AS level FROM armor WHERE level<='.$session['user']['dragonkills'];
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                $maxlevel=$row['level'];
                $choicelevel=(!empty($_POST['choicelevel'])?$_POST['choicelevel']:$maxlevel);
				//fix by bathi
                $sql = 'SELECT * FROM armor WHERE level='.intval($choicelevel).' ORDER BY value';
                $result = db_query($sql);
                $str_out='`(U`)nw`7e`ei`ft`0 von Thorims Waffenladen entfernt wurde ein Zigeunerkarren aufgestellt, der mit zahlreichen bunten Tüchern geschmückt ist und mit seiner Größe besticht. Kleine Glöckchen an jenem Wagen klirren leise im Wind auf und erzeugen angenehme, dezente Klänge.
                Über eine dreistufige Treppe gelangt man in das Innere des Karren, wo es ebenso bunt ist, wie schon außerhalb. Allerdings lässt sich hier noch mehr finden: Zahlreiche Rüstungen werden hier ausgestellt und die Besitzerin, Phaedra, welche sie verkauft, ist in ein gleichermaßen farbenfrohes Kleid gehüllt, das um die Taille mit einer Art Kordel zusammen gebunden ist.
                Etliche Flicken säumen besagtes Kleidungsstück, dennoch kann es ihrer Schönheit nicht schaden. Mit einer ausschweifenden Geste und einem warmen Lächeln wird den Käufern gedeutet, dass sie sich ruhig umsehen können.
                Dennoch sollte man sich nicht in falscher Sicherheit wiegen, dass diese Waren hier ungeschützt sind, denn der Zwergenstolz von Thorim veranlasst ihn dazu, auch für den Schutz Phaedras zu `fs`eo`7r`)ge`(n...`0
                `n`nDu folgst ihrer Einladung gern und siehst dir interessiert die Rüstungen an, die feinsäuberlich im Wagen geordnet sind. Bei vielen von diesen Rüstungen zweifelst du, dass sie auch wirklich ihren Zweck erfüllen, doch du wirst wohl der Meisterin vertrauen müssen. Diese allerdings ist bereits wieder in ihre Arbeit an einer neuen Rüstung vertieft.
                Als sie kurz wahrnimmt, dass du ihre Waren durchstöberst, blickt sie auf dein(e/n) `z'.$session['user']['armor'].' `0und bietet dir dafür im Tausch `^'.$tradeinvalue.'`0 Gold'.($rebate?' und einen Rabatt in Höhe von `^'.$rebate.' `0% dank deiner Gildenmitgliedschaft':'').' an.';
                //Hier könnte ein Text hin wenn User Rüstung färben darf
                if($rename_armor)
                {
                        $str_out.='`n`0Als Stammkunde weißt du, dass Phaedra deine bevorzugte Rüstung auch in '.color_from_name('individuellen Farben').' bereithält.';
                }
                elseif($session['user']['reputation']<=-10)
                {
                        $str_out.='`n`0Sie sieht dich misstrauisch an, als ob sie wüsste, dass du hier hin und wieder versuchst, ihr ihre schönen Rüstungen zu klauen.';
                }

                $str_out.='`n`n`n<table border="0" cellpadding="0">
                <tr class="trhead">
                <th>Name</th>
                <th>Verteidigung</th>
                <th>Preis</th>
                </tr>';
                for ($i=0;$i<db_num_rows($result);$i++)
                {
                        $row = db_fetch_assoc($result);
                        $row['value'] = ceil( $row['value'] * (100 - $rebate) * 0.01);

                        // 10%iger Händlerbones (Preis modifizieren)
                        $oldvalue = '';
                        if ($p_job==6)
                        {
                                $oldvalue = '`i('.$row['value'].')`i';
                                $row['value'] = round ($row['value'] * 0.9);
                        }

                        $bgcolor=($i%2==1?"trlight":"trdark");
                        if ($row['value']<=($session['user']['gold']+$tradeinvalue))
                        {
                                $str_out.='<tr class="'.$bgcolor.'">
                                <td>Kaufe '.create_lnk($row['armorname'],'armor.php?op=buy&id='.$row['armorid'],true,false,($row['defense']<=$session['user']['armordef']?'Du bist nicht besser geschützt, wenn du '.$row['armorname'].' kaufst. Willst du wirklich neu kaufen?':'')).'</td>
                                <td align="center">'.$row['defense'].'</td>
                                <td align="right">'.$row['value'].' '.$oldvalue.'</td>
                                </tr>';
                        }
                        else
                        {
                                $str_out.='<tr class="'.$bgcolor.'">
                                <td>- - - - '.create_lnk($row['armorname'],'armor.php?op=buy&id='.$row['armorid'],true,false,'Möchtest du tatsächlich versuchen, diese Rüstung zu stehlen?').'</td>
                                <td align="center">'.$row['defense'].'</td>
                                <td align="right">'.$row['value'].' '.$oldvalue.'</td>
                                </tr>';
                        }
                }
                $str_out.='</table>';

                if($session['user']['dragonkills']>0)
                {
                        //$arr_desc=array('Fundstücke','Einfaches Leder','Holzfällerkleidung','Wolfsfell','Kettenschutz','Drachenkrieger-Kleidung','Bronze-Rüstung','Zwergenrüstung','Zauber-Ringe','Magische Ringe','Phaedras Kreationen','Krimskram','Drachen-Rüstungen','Importware','Yazata-Ware','undefiniert');
                        $arr_desc=utf8_unserialize(getsetting('armorclasses','a:0:{}'));
                        $str_out.='<br>`0Oder willst du vielleicht einen anderen Bereich wählen?
                        <form action="armor.php?op=browse" method="post">
                        <select name="choicelevel" onchange="this.form.submit();">';
                        for($i=0;$i<=$maxlevel;$i++)
                        {
                                if(empty($arr_desc[$i]))
                                {
                                        $arr_desc[$i]=$i.'DK-Rüstungen';
                                }
                                $str_out.='<option '.($i==$choicelevel?'selected ':'').'value="0'.$i.'">'.stripslashes($arr_desc[$i]).'</option>';
                        }
                        $str_out.='
                        </select>
                        <input type="submit" value="Wählen">
                        </form>';
                        addnav('','armor.php?op=browse');
                }
        }
        else //User hat Luxusgewand an
        {
                $str_out.='Gerade noch rechtzeitig merkst du, dass du noch dein '.$session['user']['armor'].'`0 an hast. Du weißt, dass Phaedra nicht lange fragt und sich dein Gewand grapscht wenn du etwas Neues kaufst.
                `nWäre es nicht besser, jetzt schnell umzukehren und das teure Gewand abzulegen?';
                $session['user']['specialmisc']='sellarmor';
                addnav('Egal, weiter!','armor.php');
        }
        output($str_out);
        $show_invent = true;
        if (!$rename_armor)
        {
                addnav('Phaedra zum Stickwettbewerb herausfordern (500 DP)','armor.php?op=duel');
        }
        addnav('Zurück zum Marktplatz','market.php');
}

else if ($_GET['op']=='buy')
{
        $sql = 'SELECT * FROM armor WHERE armorid='.$_GET['id'];
        $result = db_query($sql);
        if (db_num_rows($result)==0)
        {
                output('`0Phaedra schaut dich ein paar Sekunden verwirrt an, entschließt sich dann aber zu glauben, dass du wohl ein paar Schläge zu viel auf den Kopf bekommen hast und nickt lächelnd.');
                addnav('Nochmal?','armor.php');
                addnav('Zurück zum Marktplatz','market.php');
        }
        else
        {
                $row = db_fetch_assoc($result);
                $row['value'] = ceil( $row['value'] * (100 - $rebate) * 0.01);
                // 10%iger Händlerbonus (Preis modifizieren)
                if ($p_job==6)
                {
                        $row['value'] = round($row['value'] *0.9);
                }

                if ($row['value']>($session['user']['gold']+$tradeinvalue))
                {
					if ($session['user']['spirits']==RP_RESURRECTION)
					{
						output('`0Du bist beim besten Willen nicht in der richtigen Verfassung, um noch etwas klauen zu können!');
						addnav('Zurück','armor.php');
						addnav('Zurück zum Marktplatz','market.php');
					}
					else
					{
                        if ($session['user']['specialtyuses']['thievery']>=2)
                        {
                                $klau=e_rand(1,15);
                        }
                        else
                        {
                                $klau=e_rand(2,18);
                        }
                        if ($session['user']['reputation']<=-10)
                        {
                                if ($session['user']['reputation']<=-20) $klau=10;
                                $session['user']['reputation']-=10;
                                if ($klau==1)
                                { // Fall nur für Diebe
                                        output('`0Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `z'.$row['armorname'].'`0 gegen `z'.$session['user']['armor'].'`0 aus und verlässt fröhlich pfeifend den Laden.
                                        `bGlück gehabt!`b `0Phaedra ist immer noch in ihre Handarbeit vertieft und hat nichts bemerkt. Aber nochmal wird ihr das nicht passieren! Stolz auf deine fette Beute stolzierst du über den Marktplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!');

                                        $session['user']['charm']=max(0,$session['user']['charm']-1);
                                        $arr_arm['tpl_name'] = $row['armorname'];
                                        $arr_arm['tpl_value1'] = $row['defense'];
                                        $arr_arm['tpl_gold'] = round($row['value'] * 0.75);

                                        addnav('Zurück zum Marktplatz','market.php');
                                }
                                else if ($klau==2 || $klau==3)
                                { // Diebstahl gelingt perfekt
                                        output('`0Du grapschst dir `z'.$row['armorname'].'`0 und tauschst `z'.$session['user']['armor'].'`z unauffällig dagegen aus. `bGlück gehabt!`b `0Phaedra ist immer noch in ihre Handarbeit vertieft und hat nichts bemerkt. Aber nochmal wird ihr das nicht passieren! Stolz auf deine fette Beute stolzierst du über den Marktplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!');

                                        $session['user']['charm']=max(0,$session['user']['charm']-1);
                                        $arr_arm['tpl_name'] = $row['armorname'];
                                        $arr_arm['tpl_value1'] = $row['defense'];
                                        $arr_arm['tpl_gold'] = round($row['value'] * 0.75);
                                        addnav('Zurück zum Marktplatz','market.php');
                                }
                                else if ($klau==4 || $klau==5)
                                { // Diebstahl gelingt, aber nachher erwischt
                                        output('`0Du grapschst dir `z'.$row['armorname'].'`0 und tauschst `z'.$session['user']['armor'].'`0 unauffällig dagegen aus. So schnell und unauffällig wie du kannst, verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Marktplatz stolzierst, siehst du aus dem Augenwinkel `4Phaedra`0 knapp an dir vorbei Richtung Stadtbank laufen. Im Vorbeigehen reißt sie das Preisschild ab, das noch immer von deiner neuen Rüstung baumelt...`n`n');
                                        if ($session['user']['goldinbank']<0)
                                        {
                                                output('`0Da du jedoch schon Schulden bei der Bank hast, bekam Phaedra von dort nicht, was sie verlangte.`n
                                                `0Als du dein(e/n) `z'.$row['armorname'].'`0 stolz auf dem Marktplatz präsentierst, packt dich von hinten `4Thorims`0 starke Hand. Er entreißt dir '.$row['armorname'].' gewaltsam, drückt dir dein(e/n) alte(n/s) '.$session['user']['armor'].' in die Hand und schlägt dich nieder. Er raunzt noch etwas, daß du Glück hast, so arm zu sein, sonst hätte er er dich umgebracht und daß er dich beim nächsten Diebstahl ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n
                                                Phaedra wird dir sowas nicht nochmal durchgehen lassen!');
                                                $session['user']['hitpoints']=round($session['user']['hitpoints']/2);
                                        }
                                        else
                                        {
                                                output('`0Phaedra hat sich die `^'.($row['value']-$tradeinvalue).' `0Gold, die du ihr schuldest, von der Bank geholt! Sie wird dir sowas nicht nochmal durchgehen lassen.');
                                                $session['user']['goldinbank']-=($row['value']-$tradeinvalue);
                                                if ($session['user']['goldinbank']<0)
                                                {
                                                        output('`n`0Du hast dadurch jetzt `$'.abs($session['user']['goldinbank']).' `0Gold Schulden bei der Bank!!');
                                                        //debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['armorname'] . " armor");
                                                }

                                                 $arr_arm['tpl_name'] = $row['armorname'];
                                                $arr_arm['tpl_value1'] = $row['defense'];
                                                $arr_arm['tpl_gold'] = round($row['value'] * 0.75);
                                        }
                                        addnav('Zurück zum Marktplatz','market.php');
                                }
                                else
                                { // Diebstahl gelingt nicht
                                        output('`0Du wartest, bis Phaedra wieder abgelenkt ist. Dann näherst du dich vorsichtig `z'.$row['armorname'].'`0 und lässt die Rüstung leise vom Stapel verschwinden, auf dem sie lag. Deiner Beute sicher drehst du dich um ... nur um festzustellen, dass du dich nicht ganz umdrehen kannst, weil sich zwei Hände fest um deinen Arme schliessen. Du schaust an ihnen herunter und stellst fest, dass diese kurzen, aber muskulösen Arme nur dem Zwerg Thorim gehören können. Als du anfängst, eine Erklärung zu stammeln, hörst du nur das Zerschellen einer Vase auf deinem Kopf.
                                        `n`nLangsam wird es dunkel um dich, du siehst nur noch, wie Phaedra etwas entsetzt auf dich starrt, nachdem Thorim die Vase geschleudert hat.
                                        `n`n`&Du wurdest von `4Thorim`& umgebracht!!!
                                        `n`$Das Gold, das du dabei hattest, hast du verloren!
                                        `n`$Du hast 10% deiner Erfahrung verloren!
                                        `n`&Du kannst morgen wieder kämpfen.`n
                                        `n`0Wegen der Unehrenhaftigkeit deines Todes landest du im Fegefeuer und wirst das Reich der Schatten aus eigener Kraft heute nicht mehr verlassen können!');
                                        killplayer(100,10,0,'news.php','Tägliche News');
                                        $session['user']['gravefights']=round($session['user']['gravefights']*0.75);
                                        addnews('`%'.$session['user']['name'].'`5 wurde von `!Thorim`5 für den Versuch, bei `#Phaedra`5 zu stehlen, umgebracht.');
                                }
                        }
                        else
                        {
                                $session['user']['reputation']-=10;
                                if ($klau==1)
                                { // Fall nur für Diebe
                                        output('`0Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `z'.$row['armorname'].'`0 gegen `z'.$session['user']['armor'].'`0 aus und verlässt fröhlich pfeifend den Laden. `bGlück gehabt!`b Phaedra ist immer noch in ihre Handarbeit vertieft und hat nichts bemerkt. Trotzdem wird sie den Diebstahl früher oder später bemerken und in Zukunft besser aufpassen! Stolz auf deine fette Beute stolzierst du über den Marktplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!');

                                        $session['user']['charm']=max(0,$session['user']['charm']-1);
                                        $arr_arm['tpl_name'] = $row['armorname'];
                                        $arr_arm['tpl_value1'] = $row['defense'];
                                        $arr_arm['tpl_gold'] = round($row['value'] * 0.75);

                                        addnav("Zurück zum Marktplatz","market.php");
                                }
                                else if ($klau==2 || $klau==3)
                                { // Diebstahl gelingt perfekt
                                        output('`0Du grapschst dir `z'.$row['armorname'].'`0 und tauschst `z'.$session['user']['armor'].'`0 unauffällig dagegen aus. `bGlück gehabt!`b `0Phaedra ist immer noch in ihre Handarbeit vertieft und hat nichts bemerkt. Trotzdem wird sie den Diebstahl früher oder später bemerken und in Zukunft besser aufpassen! Stolz auf deine fette Beute stolzierst du über den Marktplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!');

                                        $session['user']['charm']=max(0,$session['user']['charm']-1);
                                        $arr_arm['tpl_name'] = $row['armorname'];
                                        $arr_arm['tpl_value1'] = $row['defense'];
                                        $arr_arm['tpl_gold'] = round($row['value'] * 0.75);

                                        addnav("Zurück zum Marktplatz","market.php");
                                }
                                else if ($klau==4 || $klau==5)
                                { // Diebstahl gelingt, aber nachher erwischt
                                        output('`0Du grapschst dir `z'.$row['armorname'].'`0 und tauschst `z'.$session['user']['armor'].'`0 unauffällig dagegen aus. So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Marktplatz stolzierst, siehst du aus dem Augenwinkel `4Phaedra`0 knapp an dir vorbei Richtung Stadtbank laufen. Im Vorbeigehen reißt sie das Preisschild ab, das noch immer von deiner neuen Rüstung baumelt...`n`n');
                                        if ($session['user']['goldinbank']<0)
                                        {
                                                output('Da du jedoch schon Schulden bei der Bank hast, bekam Phaedra von dort nicht, was sie verlangte.`n
                                                Als du dein(e/n) `z'.$row['armorname'].'`0 stolz auf dem Marktplatz präsentierst, packt dich von hinten `4Thorims`0 starke Hand. Er entreißt dir '.$row['armorname'].' gewaltsam, drückt dir dein(e/n) alte(n/s) '.$session['user']['armor'].' in die Hand und schlägt dich nieder. Er raunzt noch etwas, dass du Glück hast, so arm zu sein, sonst hätte er er dich umgebracht und dass er dich beim nächsten Diebstahl ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n
                                                Phaedra wird dich in Zukunft sehr genau im Auge behalten, wenn du ihren Laden betrittst.');
                                                $session['user']['hitpoints']=ceil($session['user']['hitpoints']/2);
                                        }
                                        else
                                        {
                                                output('`0Phaedra hat sich die `^'.($row['value']-$tradeinvalue).' `0Gold, die du ihr schuldest, von der Bank geholt! Sie wird dich in Zukunft sehr genau im Auge behalten, wenn du ihren Laden betrittst.');
                                                $session['user']['goldinbank']-=($row['value']-$tradeinvalue);
                                                if ($session['user']['goldinbank']<0) output("`nDu hast dadurch jetzt `$".abs($session['user']['goldinbank'])." `0Gold Schulden bei der Bank!!");
                                                //debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['armorname'] . " armor");
                                                 $arr_arm['tpl_name'] = $row['armorname'];
                                                $arr_arm['tpl_value1'] = $row['defense'];
                                                $arr_arm['tpl_gold'] = round($row['value'] * 0.75);
                                        }
                                        addnav('Zurück zum Marktplatz','market.php');
                                }
                                else
                                { // Diebstahl gelingt nicht
                                        output('`0Du grapschst dir `z'.$row['armorname'].'`0 und tauschst `z'.$session['user']['armor'].'`0 unauffällig dagegen aus. So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du dein(e/n) `z'.$row['armorname'].'`0 stolz auf dem Marktplatz präsentierst, packt dich von hinten `4Thorims`0 starke Hand. Er entreißt dir '.$row['armorname'].' gewaltsam, drückt dir dein(e/n) alte(n/s) '.$session['user']['armor'].' in die Hand und schlägt dich nieder. Er raunzt noch etwas, dass er dich beim nächsten Diebstahl ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n');
                                        $session['user']['hitpoints']=1;
                                        if ($session['user']['turns']>0)
                                        {
                                                output('`n`4Du verlierst einen Waldkampf und fast alle Lebenspunkte.');
                                                $session['user']['turns']--;
                                        }
                                        else
                                        {
                                                output('`n`4Thorim hat dich so schlimm erwischt, dass eine Narbe bleiben wird.`n
                                                Du verlierst 3 Charmepunkte und fast alle Lebenspunkte.');
                                                $session['user']['charm']=max(0,$session['user']['charm']-3);
                                        }
                                        addnav('Zurück zum Marktplatz','market.php');
                                }
                        }
					}
                }
                else
                {
                        output('`0Phaedra nimmt dein Gold und sehr zu deiner Überraschung nimmt sie auch dein(e/n) `z'.$session['user']['armor'].'`0, hängt ein Preisschild dran und legt die Rüstung hübsch zu den anderen.
                        `nIm Gegenzug händigt sie dir deine wunderbare neue Rüstung `z'.$row['armorname'].'`0 aus.
                        `nDu fängst an zu protestieren: `f"Werde ich nicht albern aussehen, wenn ich nichts außer '.$row['armorname'].' trage?" `0Du denkst einen Augenblick darüber nach, dann wird dir klar, dass jeder in der Stadt ja dasselbe macht. `f"Na gut. Andere Länder, andere Sitten."`0');
                        //debuglog("spent " . ($row['value']-$tradeinvalue) . " gold on the " . $row['armorname'] . " armor");
                         $session['user']['gold']-=$row['value'];
                        $session['user']['gold']+=$tradeinvalue;

                        $arr_arm['tpl_name'] = $row['armorname'];
                        $arr_arm['tpl_value1'] = $row['defense'];
                        $arr_arm['tpl_gold'] = round($row['value'] * 0.75);

                        if($rename_armor)
                        {
                                addnav('f?Rüstung färben (10DP)','armor.php?op=name');
                        }
                        addnav('Zurück zum Marktplatz','market.php');
                }
        }
}

else if ($_GET['op']=='name')
{
        $armorname_blank=str_replace('`0','',strip_appoencode($session['user']['armor']));

        $usercolor=color_from_name($armorname_blank);
        $weaponcolor=color_from_name($armorname_blank,$session['user']['weapon']);

        output('`0`bEine Rüstung färben`b`n
        `nDu kannst wählen zwischen
        `n- der Farbe deines Namens
        `n- der Farbe deiner Waffe
        `n
        `nWie soll deine Rüstung aussehen?
        `n<form action="armor.php?op=changename" method="post">
        `n<input type="radio" name="newname" value="');
        rawoutput(urlencode($usercolor));
        output('">'.$usercolor.'
        `n<input type="radio" checked name="newname" value="');
        rawoutput(urlencode($weaponcolor));
        output('">'.$weaponcolor.'
        `n`n<input type="submit" value="Das nehm ich!">
        </form>');
        //freie Farbwahl ist nicht erwünscht. Der Umweg über gefärbte Billigwaffe ist jedoch zulässig
        addnav('','armor.php?op=changename');
        addnav('Zurück zum Laden','armor.php?op=browse');
}

else if ($_GET['op']=='changename')
{
        $pointsavailable=$session['user']['donation']-$session['user']['donationspent'];
        if ($pointsavailable>=$renamepoints)
        {
                $session['user']['donationspent']+=$renamepoints;
                item_set_armor(urldecode($_POST['newname']),-1,-1,0,0,1);
                //user_set_aei(array('armorname'=>$session['user']['armor']));
                output('`0Gratulation, deine neue Rüstung heißt jetzt '.$session['user']['armor'].'`0!`n`n');
        }
        else
        {
                output('`0Eine Gravur kostet '.$renamepoints.' Punkte, aber du hast nur '.$pointsavailable.' Punkte.');
        }
        addnav('Zurück zum Marktplatz','market.php');
}

elseif ($_GET['op']=='fight')
{
        $battle=true;
}

if(is_array($arr_arm))
{

        // Zu invent hinzufügen
        $int_aid = item_add($session['user']['acctid'],'rstdummy',$arr_arm);
        // Als Rüstung ausrüsten (dabei alte Rüstung löschen)
        item_set_armor($arr_arm['tpl_name'],$arr_arm['tpl_value1'],$arr_arm['tpl_gold'],$int_aid,0,2);

}

if($battle)
{
        if (is_array($session['bufflist']) && count($session['bufflist'])>0 || $_GET['skill']!=''){
                $_GET['skill']='';
                $session['user']['buffbackup']=utf8_serialize($session['bufflist']);
                $session['bufflist']=array();
                output('`&Die Regeln verbieten es, während des Stick-Wettbewerbes Gebrauch von besonderen Fähigkeiten zu machen!`0');
        }
        include('battle.php');

        //Texte ändern
        $arr_search=array(
                'Kampf'
                ,'Lebenspunkte'
                ,'überrascht dich und hat den ersten Schlag!'
                ,'Dein Können erlaubt dir den ersten Angriff!'
                ,'treffen'
                ,'trifft'
                ,'triffst'
                ,'der </span><span class="c36">ABWEHRSCHLAG'
                ,'dein </span><span class="c94">ABWEHRSCHLAG'
                ,'TRIFFT NICHT'
                ,'TRIFFST NICHT'
        );
        $arr_replace=array(
                'Stick-Wettbewerb'
                ,'Motivation'
                ,'legt ein schönes Muster vor.'
                ,'Als Herausforderer darfst du beginnen.'
                ,'übertrumpfen'
                ,'demotiviert'
                ,'demotivierst'
                ,'</span><span class="c36">Phaedras Muster'
                ,'dein </span><span class="c94">besseres Muster'
                ,'bringt NICHTS zustande'
                ,'bringst NICHTS zustande'
        );
        $output=str_replace($arr_search,$arr_replace,$output);

        if ($victory)
        {
                $badguy=array();
                $session['user']['badguy']='';
                $battle=false;
                output('`0Bevor du zum letzten Schlag ansetzen kannst hebt Phaedra eine Hand.`n
                `U"Du hast Dich wahrhaft würdig erwiesen und mich in einem fairen Wettkampf geschlagen. Komm mit mir und ich zeige dir einen Ort, an dem ich besondere Arbeiten für ganz besondere Leute vollbringe."');
                addnews('`#'.$session['user']['name'].'`5 hat `!Phaedra`5 in einem Stick-Wettbewerb bezwungen.');
                $session['user']['donationspent']+=400;
                $Char->setBit(2,'rename_weapons', 1);
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                addnav('Mitgehen','armor.php?op=name');
                addnav('Zurück zum Marktplatz','market.php');
        }
        else if ($defeat)
        {
                output ('`0Als du völlig demotiviert und unter Tränen zusammenbrichst, reicht Phaedra dir ihre Hand und muntert dich auf. Das war wohl nichts!');
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                $battle=false;
                addnews('`%'.$session['user']['name'].'`5 wurde von `!Phaedra`5 in einem Stick-Wettbewerb völlig demotiviert.');
                addnav('Zurück zum Marktplatz','market.php');
        }
        else
        {
                fightnav(false,false);
        }
} //Duell Ende

page_footer();
?>