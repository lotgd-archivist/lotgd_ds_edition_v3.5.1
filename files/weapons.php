<?php

// 21072004

// modifications by anpera:
// stealing enabled with 1:15 success (thieves have 2:12 chance) and 'pay from bank'

// Anpassung fürs Gildenmod durch Talion: rebate-Var

// Thorim's Shop für besondere Kunden
// Waffen umbenennen für 500 DP einmalig, dann für jeweils 10 DP
//
// Erfordert : [rename_weapons] in ['user']
// 2^0 : Waffe umbenennen
// 2^1 : Rüstung umbenennen
// Modifiziert : weapons.php, bio.php, dragon.php
//
// by Maris(Maraxxus@gmx.de)
// (inspiriert durch : lodge.php)

require_once "common.php";
require_once(LIB_PATH.'dg_funcs.lib.php');
checkday();
$rename_weapons=($session['user']['rename_weapons']&1);
$player = user_get_aei('job');
$p_job = $player['job'];

if($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT) {
        $rebate = dg_calc_boni($session['user']['guildid'],'rebates_weapon',0);
}

if ($_GET['op']=='fight')
{
        $battle=true;
}

page_header('Thorims Waffenladen');
output('`c`b`(T`)ho`7r`ei`fm`0s `fW`ea`7f`)fe`(n`0`b`c`n');
$tradeinvalue = round(($session['user']['weaponvalue']*0.75),0);

// 10%iger Händlerbones (Preis modifizieren)
if ($p_job==6)
{
        $tradeinvalue = round($tradeinvalue*1.1);
}

$renamepoints=10; //Kosten Donationpoints zum Waffe umbenennen

if ($_GET['op']=='')
{
        $_GET['op']='peruse';
}

if ($_GET['op']=='duel')
{ // Duell für die Waffenumbenennung
        $pointsavailable=$session['user']['donation']-$session['user']['donationspent'];
        if ($pointsavailable<500) {
                output('Thorim lacht aus vollem Halse, als du ihm entgegentrittst und wendet sich dann auch wieder seiner Arbeit zu, nachdem er etwas Unverständliches gemurmelt hat. Was immer es war, es klang nicht sehr freundlich.');
                addnav('Zurück zum Marktplatz','market.php');
        }
        else {
                output('Thorim legt seine Sachen bei Seite und mustert dich eindringlich und nickt. Ihr steht euch nun gegenüber. Einige Schaulustige haben sich bereits am Schaufenster versammelt. Noch kannst du weglaufen. Der Kampf kostet dich, egal wie er ausgeht, 100 Donation Punkte und weitere 400, wenn du gewinnst.
                `n`nWenn du gewinnst, wird dir Thorim gekaufte Waffen ganz nach deinen Wünschen gravieren.');
                addnav('Angreifen','weapons.php?op=duel2');
                addnav('Zurück zum Marktplatz','market.php');
        }
}
else if ($_GET['op']=='duel2')
{
        $session['user']['donationspent']+=100;

        $battle=true;
        $badguy = array("creaturename"=>"`#Thorim`0","creaturelevel"=>$session['user']['level'],"creatureweapon"=>"Katana","creatureattack"=>$session['user']['attack'],"creaturedefense"=>$session['user']['defence'],"creaturehealth"=>$session['user']['hitpoints'], "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
}

else if ($_GET['op']=='peruse')
{
        $sql = "SELECT max(level) AS level FROM weapons WHERE level<=".(int)$session['user']['dragonkills'];
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $maxlevel=$row['level'];
        $choicelevel=(!empty($_POST['choicelevel'])?$_POST['choicelevel']:$maxlevel);
		//fix bathi
        $sql = "SELECT * FROM weapons WHERE level = ".intval($choicelevel)." ORDER BY damage ASC";
        $result = db_query($sql);
        if($rename_weapons)
        {
                output('`(M`)it `7g`ee`fn`0ügend Entfernung zu den Ställen, um sich vor dem Gestank der Tiere zu schützen, hat sich Thorim, der Zwerg, einen Raum gemietet, der zum Marktplatz hin seinen Eingang und ein kleines Schaufenster hat.
                 Der Raum, in denen er seine Waffen an '.($session['user']['sex']?'die Frau':'den Mann').' bringen will, ist recht überschaulich, dennoch können die vielen ausgestellten Waffen,
                 welche im Schein der Fackeln, die den Raum erhellen, aufblitzen sicherlich für so manch einen begeisterten Blick des ein oder anderen Waffennarren sorgen.`n
                                 Wenngleich Thorim für seine Größe schon einen gewaltigen Bauchumfang hat und auch sein Haupt nicht mehr von viel Haar bedeckt wird, wirkt sein Blick hell und wachsam; verfolgt einen jeden Kunden aufmerksam, um seine Ware zu bewachen.
                 Man kann sich also relativ sicher sein, dass dieser so gemütlich wirkende Mann mit verdorbener Laune kurzen Prozess mit jedem Dieb mach`fe`en `7w`)ir`(d...`0
                 `n`nAls du den Laden betrittst, winkt Thorim dich mit einem angedeuteten Lächeln herein. Der Zwerg hat es gewiss nicht nötig, seine Waffen überschwänglich anzupreisen, wo doch fast jeder Krieger in dieser Stadt für den Kampf gegen den grünen Drachen eine seiner Waffen braucht.
                                 Mit einem Blick, der jahrelange Erfahrung erkennen lässt, mustert er deine alte Waffe. `U"Willkommen in meinem Laden. Such dir ruhig eine neue Waffe aus, die ich nach deinen Wünschen gravieren werde, was aber nicht ganz billig wird!
                 Für `L'.$session['user']['weapon'].' `Ugebe ich dir noch `^'.$tradeinvalue.' `UGold'.($rebate?' und `^'.$rebate.' `U% Rabatt dank deiner Gildenmitgliedschaft.':'.').'"`0`n`n`n');
        }
        else
        {
                $str_out.='`(M`)it `7g`ee`fn`0ügend Entfernung zu den Ställen, um sich vor dem Gestank der Tiere zu schützen, hat sich Thorim, der Zwerg, einen Raum gemietet, der zum Marktplatz hin seinen Eingang und ein kleines Schaufenster hat.
                 Der Raum, in denen er seine Waffen an '.($session['user']['sex']?'die Frau':'den Mann').' bringen will, ist recht überschaulich, dennoch können die vielen ausgestellten Waffen,
                 welche im Schein der Fackeln, die den Raum erhellen, aufblitzen sicherlich für so manch einen begeisterten Blick des ein oder anderen Waffennarren sorgen.`n
                 Wenngleich Thorim für seine Größe schon einen gewaltigen Bauchumfang hat und auch sein Haupt nicht mehr von viel Haar bedeckt wird, wirkt sein Blick hell und wachsam; verfolgt einen jeden Kunden aufmerksam, um seine Ware zu bewachen.
                 Man kann sich also relativ sicher sein, dass dieser so gemütlich wirkende Mann mit verdorbener Laune kurzen Prozess mit jedem Dieb mach`fe`en `7w`)ir`(d...`0
                 `n`nAls du den Laden betrittst, winkt Thorim dich mit einem angedeuteten Lächeln herein. Der Zwerg hat es gewiss nicht nötig, seine Waffen überschwänglich anzupreisen, wo doch fast jeder Krieger in dieser Stadt für den Kampf gegen den grünen Drachen eine seiner Waffen braucht.
                 Mit einem Blick, der jahrelange Erfahrung erkennen lässt, mustert er deine alte Waffe. Thorim merkt allerdings recht schnell, dass du nicht weißt, ob du einfach warten sollst oder die Waffen betrachten kannst. `U"Willkommen in meinem Laden. Schau dich ruhig ein wenig um und such dir eine neue Waffe aus.
                 Für `L'.$session['user']['weapon'].' `Ugebe ich dir noch `^'.$tradeinvalue.' `UGold'.($rebate?' und `^'.$rebate.' `U% Rabatt dank deiner Gildenmitgliedschaft.':'.').' Wenn dir eine gefällt, wähle sie einfach aus!"`0`n`n`n';
                if($session['user']['reputation']<=-10)
                {
                        $str_out.='`n`0Dich mustert er allerdings besonders kritisch, während du die Waffen in Augenschein nimmst. Anscheinend weiß er sehr genau, dass du hier hin und wieder versuchst, ihm seine schönen Waffen zu klauen.`n`n';
                }
        }
        $str_out.="<table border='0' cellpadding='0'>
        <tr class='trhead'>
        <th>Name</th>
        <th>Schaden</th>
        <th>Preis</th>
        </tr>";
        $num_rows=db_num_rows($result);
        for ($i=0;$i<$num_rows;$i++){
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
                if ($row['value']<=($session['user']['gold']+$tradeinvalue)){
                        $str_out.="<tr class='".$bgcolor."'>
                        <td>Kaufe ".create_lnk($row['weaponname'],'weapons.php?op=buy&id='.$row['weaponid'],true,false,($row['damage']<=$session['user']['weapondmg']?'Du wirst nicht stärker wenn du '.$row['weaponname'].' kaufst. Willst du wirklich neu kaufen?':''))."</td>
                        <td align='center'>".$row['damage']."</td>
                        <td align='right'>".$row['value'].$oldvalue."</td>
                        </tr>";
                }
                else
                {
                        $str_out.="<tr class='".$bgcolor."'>
                        <td>- - - - ".create_lnk($row['weaponname'],'weapons.php?op=buy&id='.$row['weaponid'],true,false,'Möchtest du tatsächlich versuchen, diese Waffe zu stehlen?')."</td>
                        <td align='center'>".$row['damage']."</td>
                        <td align='right'>".$row['value'].$oldvalue."</td>
                        </tr>";
                }
        }
        //wenn schonmal Waffe graviert wurde: Diese Waffe wieder kaufen
        if($rename_weapons
        && $session['user']['weapondmg']<15
        && ($session['user']['gold']+$tradeinvalue)>=$row['value']
        && ($session['user']['donation']-$session['user']['donationspent'])>=$renamepoints)
        {
                $rowe=user_get_aei('weaponname');
                if($rowe['weaponname']!='')
                {
                        $session['weaponname']=$rowe['weaponname'];
                        $bgcolor=($i%2==1?"trlight":"trdark");
                        $str_out.="<tr class='$bgcolor'>
                        <td>".create_lnk('Kaufe '.$session['weaponname'].'`0','weapons.php?op=buy&name=1&id='.$row['weaponid'],true,true,'',false,'',CREATE_LINK_LEFT_NAV_HOTKEY)."</td>
                        <td align='center'>$row[damage]</td>
                        <td align='right'>$row[value] `i$oldvalue`i`n+ $renamepoints DP</td>
                        </tr>";
                }
        }
        $str_out.='</table>';

        if($session['user']['dragonkills']>0)
        {
                //$arr_desc=array('Fundstücke','Waffen eines Knappen','Schwerter','Langschwerter','Bastardschwerter','Highlander-Schwerter','Krummsäbel','Kampfäxte','Schlagwaffen','Asiatische Waffen','Pfeil und Bogen','MightyE\'s Hinterlassenschaften','Zaubersprüche','Steinschleudern','Zweihänder','Hieb- und Stichwaffen','undefiniert');
                $arr_desc=utf8_unserialize(getsetting('weaponclasses','a:0:{}'));
                $str_out.='<br>Oder willst du vielleicht einen anderen Bereich wählen?
                <form action="weapons.php?op=peruse" method="post">
                <select name="choicelevel" onchange="this.form.submit();">';
                for($i=0;$i<=$maxlevel;$i++)
                {
                        if(empty($arr_desc[$i]))
                        {
                                $arr_desc[$i]=$i.'DK-Waffen';
                        }
                        $str_out.='
                        <option '.($i==$choicelevel?'selected ':'').'value="0'.$i.'">'.stripslashes($arr_desc[$i]).'</option>';
                }
                $str_out.='</select>
                <input type="submit" value="Wählen">
                </form>';
                addnav('','weapons.php?op=peruse');
        }

        //Das sehen erstmal nur SUs
        if($access_control->su_check(access_control::SU_RIGHT_DEV))
        {
                $str_out.='`n`n--------------TEST irgendwas mit content in den Waffen-Items-----------';
                addnav('Reload','weapons.php');

                //Alle Waffentemplates holen
                $db_res = db_query('SELECT * from items_tpl where tpl_class = 8 AND tpl_content != ""',false);

                $arr_weapons = array();
                $arr_items = array();
                //Alle Waffentemplates decodieren und vorbereiten
                while($arr_item = db_fetch_assoc($db_res))
                {
                        $arr_item['content'] = utf8_unserialize($arr_item['content']);
                        $arr_items[] = $arr_item;
                }
                //Nach Subklasse sortieren
                uasort($arr_items,
                        create_function('$a,$b','return utf8_strcasecmp($a["content"]["subclass"],$b["content"]["subclass"]);')
                );


                //Ausgabe der Waffen in einer Tabelle
                $str_out .= '
                <table border="0" cellpadding="0">
                <tr class="trhead"><th>Name</th><th>Schaden</th><th>Preis</th></tr>
                ';
                $str_old_subclass = '';
                foreach ($arr_items as $arr_item)
                {
                        if($str_old_subclass != $arr_item['content']['subclass'])
                        {}
                        //Rabatt für Gildenmitglieder
                        $row['value'] = ceil( $row['value'] * (100 - $rebate) * 0.01);

                        $bgcolor=($bgcolor == 'trdark'?"trlight":"trdark");

                        if ($row['value']<=($session['user']['gold']+$tradeinvalue)){
                                output("<tr class='$bgcolor'>
                                <td>Kaufe ".create_lnk($row['weaponname'],'weapons.php?op=buy&id='.$row['weaponid'],true,false,($row['damage']<=$session['user']['weapondmg']?'Du wirst nicht stärker wenn du '.$row['weaponname'].' kaufst. Willst du wirklich neu kaufen?':''))."</td>
                                <td align='center'>$row[damage]</td>
                                <td align='right'>$row[value] `i$oldvalue`i</td>
                                </tr>",true);
                        }
                        else
                        {
                                output("<tr class='$bgcolor'>
                                <td>- - - - ".create_lnk($row['weaponname'],'weapons.php?op=buy&id='.$row['weaponid'],true,false,'Möchtest du tatsächlich versuchen, diese Waffe zu stehlen?')."</td>
                                <td align='center'>$row[damage]</td>
                                <td align='right'>$row[value] `i$oldvalue`i</td>
                                </tr>",true);
                        }
                }
                $str_out.='</table>';
        }

        output($str_out);

        $show_invent = true;

        if (!$rename_weapons)
        {
                addnav('Thorim zum Kampf herausfordern (500 DP)','weapons.php?op=duel');
        }
        addnav('Zurück zum Marktplatz','market.php');

}

else if ($_GET['op']=='buy')
{
        $sql = 'SELECT * FROM weapons WHERE weaponid='.$_GET['id'];
        $result = db_query($sql);
        if (db_num_rows($result)==0)
        {
                output('`0Thorim schaut dich eine Sekunde lang verwirrt an und kommt zu dem Schluss, dass du ein paar Schläge zuviel auf den Kopf bekommen hast. Schließlich nickt er und grinst.');
                addnav('Nochmal versuchen?','weapons.php');
                addnav('Zurück zum Marktplatz','market.php');
        }
        else
        {
                $row = db_fetch_assoc($result);
                $row['value'] = ceil( $row['value'] * (100 - $rebate) * 0.01);
                // 10%iger Händlerbones (Preis modifizieren)
                if ($p_job==6)
                {
                        $row['value'] = round ($row['value'] * 0.9);
                }

                if ($row['value']>($session['user']['gold']+$tradeinvalue))
                {
                        if ($rename_weapons && $session['user']['reputation']>0)
                        {
                                output('`0Thorim schüttelt nur den Kopf, als du auf eine Waffe deutest, die du dir beim besten Willen nicht leisten kannst. ');
                                addnav('Nochmal','weapons.php?op=peruse');
                                addnav('Zurück zum Marktplatz','market.php');
                        }
						elseif ($session['user']['spirits']==RP_RESURRECTION)
						{
							output('`0Du bist beim besten Willen nicht in der richtigen Verfassung, um noch etwas klauen zu können!');
                            addnav('Zurück','weapons.php');
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
                                $session['user']['reputation']-=10;
                                if ($session['user']['reputation']<=-20) $klau=10;
                                if ($klau<=3)
                                { // Fall ==1 nur für Diebe
                                        output('`0'.($session['user']['specialtyuses']['thievery']>=2?'Mit den Fertigkeiten eines erfahrenen Diebes':'Da dir das nötige Kleingold fehlt,').' tauschst du `L'.$row['weaponname'].'`0
                                        gegen `L'.$session['user']['weapon'].'`0 aus und verlässt fröhlich pfeifend den Laden.
                                        `bGlück gehabt!`b `0Thorim war gerade durch irgendwas am Fenster abgelenkt.
                                        Aber nochmal passiert ihm das nicht!
                                        Stolz auf deine fette Beute stolzierst du über den Marktplatz - bis dir jemand mitteilt,
                                        dass dir da noch ein Preisschild herumbaumelt...`n
                                        Du verlierst einen Charmepunkt!');
                                        $arr_wpn['tpl_name'] = $row['weaponname'];
                                        $arr_wpn['tpl_value1'] = $row['damage'];
                                        $arr_wpn['tpl_gold'] = round($row['value'] * 0.75);
                                        $session['user']['charm']--;
                                        addnav("Zurück zum Marktplatz","market.php");
                                }
                                else if ($klau==4 || $klau==5)
                                { // Diebstahl gelingt, aber nachher erwischt
                                        output('`0Du grapschst dir `L'.$row['weaponname'].'`0 und tauschst `L'.$session['user']['weapon'].'`0 unauffällig dagegen aus.
                                        So schnell und unauffällig wie du kannst, verlässt du den Laden. Geschafft!
                                        Als du mit deiner Beute über den Marktplatz stolzierst, siehst du aus dem Augenwinkel `4Thorim`0 auf dich zurauschen.
                                        Er packt dich mit einer Hand an '.$session['user']['armor'].' und zerrt dich mit zur Stadtbank...`n`n
                                        Thorim zwingt dich mit seinen Händen eng um deinen Hals geschlungen dazu, die
                                        `^'.($row['value']-$tradeinvalue).'`0 Gold, die du ihm schuldest, von der Bank zu zahlen!');
                                        if ($session['user']['goldinbank']<0)
                                        {
                                                output('`0Da du jedoch schon Schulden bei der Bank hast, bekommt er von dort nicht, was er verlangt.`n
                                                Er entreißt dir '.$row['weaponname'].' gewaltsam, drückt dir deine alte '.$session['user']['weapon'].' in die Hand und schlägt dich nieder.
                                                Er raunzt noch etwas, dass du Glück hast, so arm zu sein, sonst hätte er dich umgebracht
                                                und dass er dich beim nächsten Diebstahl ganz sicher umbringen wird,
                                                bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n');
                                                $session['user']['hitpoints']=round($session['user']['hitpoints']/2);
                                        }
                                        else
                                        {
                                                $session['user']['goldinbank']-=($row['value']-$tradeinvalue);
                                                if ($session['user']['goldinbank']<0)
                                                {
                                                        output('`n`0Du hast dadurch jetzt `$'.abs($session['user']['goldinbank']).' Gold`0 Schulden bei der Bank!!');
                                                }
                                                output('`n`0Das nächste Mal bringt er dich um. Da bist du ganz sicher.');
                                                debuglog('verlor '. ($row['value']-$tradeinvalue) .' Bank-Gold wegen Diebstahl von '. $row['weaponname']);
                                                $arr_wpn['tpl_name'] = $row['weaponname'];
                                                $arr_wpn['tpl_value1'] = $row['damage'];
                                                $arr_wpn['tpl_gold'] = round($row['value'] * 0.75);
                                        }
                                        addnav('Zurück zum Marktplatz','market.php');
                                }
                                else if ($session['user']['reputation']<=-10)
                                { // Diebstahl gelingt nicht und kein Ansehen
                                        output('`0Während du wartest, bis Thorim in eine andere Richtung schaut, näherst du dich vorsichtig dem `L'.$row['weaponname'].'`0 und nimmst es leise vom Regal.
                                        Deiner fetten Beute gewiss drehst du dich leise, vorsichtig, wie ein Ninja, zur Tür, nur um zu entdecken,
                                        dass `4Thorim`0 drohend in der Tür steht und dir den Weg abschneidet.
                                               Als du die Flucht ergreifen willst, hörst du nur noch, wie er hinter dir sein Kurzschwert zieht.
                                         Er grummelt etwas in seinen Zwergenbart, ehe er dich mit gezielten Schlägen zu Ramius schickt.`n`n
                                        `&Du wurdest von `4Thorim`& umgebracht!!!`n
                                        `$Das Gold, das du dabei hattest, hast du verloren!`n
                                        `$Du hast 10% deiner Erfahrung verloren!`n
                                        `&Du kannst morgen wieder kämpfen.`n
                                        `n`0Wegen der Unehrenhaftigkeit deines Todes landest du im Fegefeuer und wirst das Reich der Schatten aus eigener Kraft heute nicht mehr verlassen können!');
                                        killplayer(100,10,0,'news.php','Tägliche News');
                                        $session['user']['gravefights']*=0.75;
                                        addnews('`%'.$session['user']['name'].'`5 wurde beim Versuch, in `!Thorim`5s Waffenladen zu stehlen, niedergemetzelt.');
                                }
                                else
                                { // Diebstahl gelingt nicht
                                        output('`0Du grapschst dir `L'.$row['weaponname'].'`0 und tauschst `L'.$session['user']['weapon'].'`0 unauffällig dagegen aus.
                                        So schnell und unauffällig wie du kannst, verlässt du den Laden. Geschafft!
                                        Als du mit deiner Beute über den Marktplatz stolzierst, siehst du aus dem Augenwinkel `4Thorim`0 auf dich zurauschen.
                                        Er packt dich mit einer Hand an '.$session['user']['armor'].'.`n`n
                                        Er entreißt dir '.$row['weaponname'].' gewaltsam, drückt dir deine alte '.$session['user']['weapon'].' in die Hand und schlägt dich nieder.
                                        Er raunzt noch etwas, dass er dich beim nächsten Diebstahl ganz sicher umbringen wird,
                                        bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n');
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
                                                $session['user']['charm']-=3;
                                        }
                                        addnav('Zurück zum Marktplatz','market.php');
                                }
                        }
                }
                else
                {
                        if($rename_weapons && $_GET['name']==1)
                        {
                                $session['user']['donationspent']+=10;
                                $row['weaponname'] = $session['weaponname'];
                                unset($session['weaponname']);
                                output('`0Thorim` nimmt dein `L'.$session['user']['weapon'].'`0 in Zahlung.
                                `nIm Gegenzug händigt er dir ein glänzendes, neues `L'.$row['weaponname'].'`0 aus.');
                        }
                        elseif($rename_weapons)
                        {
                                output('`0Thorim` nimmt dein `L'.$session['user']['weapon'].'`0 in Zahlung.
                                `nIm Gegenzug händigt er dir ein glänzendes, neues `L'.$row['weaponname'].'`0 aus.`n
                                Thorim fragt dich anschließend: `U"Soll ich dir was darauf eingravieren?"`n
                                `0Eine Gravur kostet dich zusätzlich nochmal 10 Donationpoints. Du könntest damit deiner Waffe einen eigenen Namen geben. Na wie wäre es?`0"');
                                addnav('Gravieren (10 DP)','weapons.php?op=name');
                        }
                        else
                        {
                                output('`0Thorim` nimmt dein `L'.$session['user']['weapon'].'`0 stellt es aus und hängt sofort ein neues Preisschild dran.`n
                                Im Gegenzug händigt er dir ein glänzendes, neues `L'.$row['weaponname'].'`0 aus, das du probeweise im Raum schwingst.
                                Dabei schlägst du Thorim beinahe den Kopf ab.
                                Er duckt sich so schnell, als ob du nicht der erste bist, der seine neue Waffe sofort ausprobieren will...');
                        }
                        $session['user']['gold']-=$row['value'];
                        $session['user']['gold']+=$tradeinvalue;

                        $arr_wpn['tpl_name'] = $row['weaponname'];
                        $arr_wpn['tpl_value1'] = $row['damage'];
                        $arr_wpn['tpl_gold'] = round($row['value'] * 0.75);

                        addnav('Zurück zum Marktplatz','market.php');
                }
        }
}

else if ($_GET['op']=='name')
{
        output('`0`bEine Waffe benennen`b`n`n
        `n`nDer Name deiner Waffe darf 30 Zeichen lang sein und Farbcodes enthalten.`n
        Vermeide es schwarz zu verwenden, da diese Farbe auf dunklem Hintergrund gar nicht oder nur schlecht angezeigt wird.`n`n
        Deine Waffe heißt bisher : `n'.$session['user']['weapon'].'`n`n`0
        Wie soll deine Waffe heißen ?`n
        Vorschau: '.js_preview('newname').'`n`n');
        $output.="<form action='weapons.php?op=namepreview' method='POST'><input name='newname' id='newname' value=\"".utf8_htmlentities($newname)."\" size=\"30\" maxlength=\"30\"> <input type='submit' value='Schaut gut aus!'></form>";
        addnav('','weapons.php?op=namepreview');
        addnav('Zurück zum Laden','weapons.php?op=peruse');
}

else if ($_GET['op']=='namepreview')
{

        $newname=str_replace('`0','',stripslashes($_POST['newname']));
        $newname = utf8_preg_replace('/[`][c]/','',$newname);
        if (mb_strlen($newname)>30)
        {
                $msg.='`0Der neue Name ist zu lang, inklusive Farbcodes darf er nicht länger als 30 Zeichen sein.`n';
        }
        $colorcount = mb_substr_count($newname,'`');
        if (getsetting('weapon_maxcolors',8) != -1 && $colorcount>getsetting('weapon_maxcolors',8))
        {
                $msg.='`0Du hast zu viele Farben im Namen benutzt. Du kannst maximal '.getsetting('weapon_maxcolors',8).' Farbcodes benutzen.`n';
        }
        if ($msg=='')
        {
                output('`0Deine Waffe wird so heißen: '.$newname.'
        `n`n`0Ist es das was du willst?`n`n');
                $output.="<form action=\"weapons.php?op=changename\" method='POST'><input type='hidden' name='name' value=\"".utf8_htmlentities($newname)."\"><input type='submit' value='Ja' class='button'>, meine Waffe heißt nun ".appoencode("{$newname}
        `0")." für $renamepoints Punkte.</form>";
                output('`n`n<a href=\'weapons.php?op=name\'>Nein, lass es mich nochmal versuchen!</a>',true);
                addnav('','weapons.php?op=name');
                addnav('','weapons.php?op=changename');
        }
        else
        {
                output('`0`bFalscher Name`b`n'.$msg.'`n`nDeine Waffe heißt bisher: '.$session['user']['weapon'].'`0,
                und wird so aussehen '.$newname.'`n`nWie soll deine Waffe heißen?`n');
                $output.="<form action='weapons.php?op=namepreview' method='POST'><input name='newname' value=\"".utf8_htmlentities($newname)."\"size=\"30\" maxlength=\"30\"> <input type='submit' value='Vorschau'></form>";
                addnav('','weapons.php?op=namepreview');
        }
        addnav('Namens-Vorschau','');
        addnav('Zurück zum Laden','weapons.php?op=peruse');
}

else if ($_GET['op']=='changename')
{
        addnav('Zurück zum Laden','weapons.php?op=peruse');
        $pointsavailable=$session['user']['donation']-$session['user']['donationspent'];
        if ($pointsavailable>=$renamepoints)
        {
                $session['user']['donationspent']+=$renamepoints;
                item_set_weapon(stripslashes($_POST['name']),-1,-1,0,0,1);
                user_set_aei(array('weaponname'=>db_real_escape_string($session['user']['weapon'])));
                output('`0Gratulation, deine neue Waffe heißt jetzt '.$session['user']['weapon'].'`0!`n`n');
        }
        else
        {
                output('`0Eine Gravur kostet '.$renamepoints.' Punkte, aber du hast nur '.$pointsavailable.' Punkte.');
        }
        addnav('Zurück zum Marktplatz','market.php');
}

if(is_array($arr_wpn))
{
        // Zu invent hinzufügen
        $int_wid = item_add($session['user']['acctid'],'waffedummy',$arr_wpn);
        // Als Waffe ausrüsten (dabei alte Waffe löschen)
        item_set_weapon($arr_wpn['tpl_name'],$arr_wpn['tpl_value1'],$arr_wpn['tpl_gold'],$int_wid,0,2);
        
        debuglog('erhielt eine neue Waffe:'.$arr_wpn['tpl_name']);
}

if ($battle)
{
        if (is_array($session['bufflist']) && count($session['bufflist'])>0 || $_GET['skill']!=''){
                $_GET['skill']='';
                $session['user']['buffbackup']=utf8_serialize($session['bufflist']);
                $session['bufflist']=array();
                output('`&Die Regeln verbieten es, während des Kampfes Gebrauch von besonderen Fähigkeiten zu machen!`0');
        }
        include('battle.php');

        if ($victory)
        {
                $badguy=array();
                $session['user']['badguy']='';
                $battle=false;
                output('`0Bevor du zum letzten Schlag ansetzen kannst hebt Thorim eine Hand.`n
                `U"Du hast Dich wahrhaft würdig erwiesen und mich in einem fairen Kampf geschlagen. Komm mit mir und ich zeige dir einen Ort, an dem ich besondere Arbeiten für ganz besondere Leute vollbringe."');
                addnews('`#'.$session['user']['name'].'`5 hat `!Thorim`5 in einem fairen Zweikampf bezwungen.');
                $session['user']['donationspent']+=400;
                $Char->setBit(1,'rename_weapons',1);
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                addnav('Mitgehen','weapons.php?op=peruse');
                addnav('Zurück zum Marktplatz','market.php');
        }
        else if ($defeat)
        {
                output ('`0Anstatt dich in das Reich des Schlafes zu befördern, reicht Thorim dir eine Hand und hilft dir auf. Das war wohl nichts!');
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                $battle=false;
                addnews('`%'.$session['user']['name'].'`5 wurde von `!Thorim`5 in einem fairen Zweikampf windelweich geschlagen.');
                addnav('Zurück zum Marktplatz','market.php');
        }
        else
        {
                fightnav(false,false);
        }
} //Duell Ende

page_footer();
?>