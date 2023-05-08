<?php

function hintdoc_hook_process($item_hook , &$item )
{

    global $session,$item_hook_info;

    switch ($item_hook )
    {

        case 'use':

            $chance=e_rand(1,100);
            output("`^Es gelingt dir folgenden Text zu lesen, bevor das Pergament unwiderbringlich in deiner Hand zu Staub zerbröselt:`n`n");
            switch($chance)
            {
                case 1: case 2: case 3: case 4: case 5:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`n`n`cGelber Schnee ist pfui!`c'));
                break;
                
                case 6: case 7: case 8: case 9:
                output(show_scroll('`c`n`n`n`n`n`nUm den hässlichen Stock des alten Mannes zu erlangen muss selbiger drei seiner Besitztümer hergeben - sei es friedfertig oder unter Gewaltanwendung.`nAlsdenn diese drei Dinge, wobei es auch die gleichen sein mögen, unter beständigem monotonem Summen im alchimistischen Labor zusammengefügt werden setzt sich der Zauber frei, welcher es unter Umständen vermag den Stock herbeizurufen.`nDoch sei Vorsicht geb...`c'));
                break;
                
                case 10: case 11: case 12: case 13: case 14: case 15:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`cKommt ein Ritter in die Taverne.`n"Wirt!", brüllt er,"Einen Eimer Met für mein Pferd!"`nFragt der Wirt: "Und für Euch nichts?"`nEntgegnet der Ritter: "Nein, ich muss noch reiten."`c'));
                break;
                
                case 16: case 17: case 18:
                output(show_scroll('`n`n`n`n`n`n`n`n`c...ist es eine weitaus leichtere Angelegenheit einen verschleppten Knappen nicht aufwändig und teuer suchen zu müssen...`n...mit Hilfe der Magie erfolgreich und unbeschadet...`nSo mische man ....ertafel, die Trophähe eines...`n...t zuletzt ... ...lfenkunst...`nUnd siehe da: durch Magie... Knappe... gerettet...`c'));
                break;

                case 19: case 20: case 21: case 22: case 23: case 24: case 25:
                output(show_scroll('`n`n`n`n`cGesucht wird wegen abscheulicher Verbrechen gegen die Menschlichkeit`nein Vampyr!`n`n`cBeschreibung:`n -Nicht zu groß und nicht zu klein`n-Übermenschliche Kräfte`n-Angeblich der Sohn eines gewissen "Kain"`n-Hat langes, dunkles Haar`n-Trägt mit Vorliebe Rüschenhemden und wallende Umhänge`n-Fühlt sich oft missverstanden`n-Ist immun gegen Sonnenlicht`n-Wird von einem Einhorn begleitet`n-Mag keine Priester`n`n`nHinweise, die zur Ergreifung dieses Unholdes führen werden stattlich belohnt!'));
                break;
                
                case 26: case 27: case 28: case 29: case 30:
                output(show_scroll('`n`n`n`n`n`n`n`n`cUnwürdiger, ich gebe dir eine letzte Chance deine jämmerliche Haut zu retten!`nBring mir den Kopf von '.strip_appoencode($session['user']['name']).'`0!`n'.($session['user']['sex']?'Sie ':'Er ').'wurde zuletzt in '.getsetting("townname","Atrahor").'`0 gesichtet.`nEnttäusche mich nicht wieder, sonst wirst du dir wünschen nie geboren worden zu sein!`n`n(die Unterschrift ist nicht lesbar)`c'));
                break;
                
                case 31: case 32: case 33: case 34:
                $sql="SELECT acctid,accounts.name,location,loggedin,laston,alive,housekey,activated,restatlocation FROM accounts LEFT JOIN items it ON acctid=it.owner WHERE it.tpl_id='idolrnds'";
                $result = db_query($sql);
                if (db_num_rows($result)>0)
                {
                    $row = db_fetch_assoc($result);
                    $loggedin=user_get_online(0,$row);
                    if ($row['location']==USER_LOC_FIELDS) $loc=($loggedin?"online":"in den Feldern");
                    if ($row['location']==USER_LOC_INN) $loc="in einem Zimmer in der Kneipe";
                    if ($row['location']==USER_LOC_HOUSE){
                    $loc="im Haus Nummer ".($row['restatlocation'])."";
                }
                $output_str.=(strip_appoencode($row['name']).'`W hat das Idol des Waldläufers, befindet sich '.$loc.'`W und '.($row['alive']?" lebt.":" ist tot!").'`n`n`0');
                }
                else
                {
                    $output_str.=('`WDas Idol des Waldläufers ist im Wald!`n`n`0');
                }
                $sql="SELECT acctid,accounts.name,location,loggedin,laston,alive,housekey,activated,restatlocation FROM accounts LEFT JOIN items it ON acctid=it.owner WHERE it.tpl_id='idolgnie'";
                $result = db_query($sql);
                if (db_num_rows($result)>0)
                {
                    $row = db_fetch_assoc($result);
                    $loggedin=user_get_online(0,$row);
                    if ($row['location']==USER_LOC_FIELDS) $loc=($loggedin?"online":"in den Feldern");
                    if ($row['location']==USER_LOC_INN) $loc="in einem Zimmer in der Kneipe";
                    if ($row['location']==USER_LOC_HOUSE){
                    $loc="im Haus Nummer ".($row['restatlocation'])."";
                }
                $output_str.=(strip_appoencode($row['name']).'`W hat das Idol des Genies, befindet sich '.$loc.'`W und '.($row['alive']?" lebt.":" ist tot!").'`n`n`0');
                }
                else
                {
                    $output_str.=('`WDas Idol des Genies ist im Wald!`n`n`0');
                }
                $sql="SELECT acctid,accounts.name,location,loggedin,laston,alive,housekey,activated,restatlocation FROM accounts LEFT JOIN items it ON acctid=it.owner WHERE it.tpl_id='idolkmpf'";
                $result = db_query($sql);
                if (db_num_rows($result)>0)
                {
                    $row = db_fetch_assoc($result);
                    $loggedin=user_get_online(0,$row);
                    if ($row['location']==USER_LOC_FIELDS) $loc=($loggedin?"online":"in den Feldern");
                    if ($row['location']==USER_LOC_INN) $loc="in einem Zimmer in der Kneipe";
                    if ($row['location']==USER_LOC_HOUSE){
                    $loc="im Haus Nummer ".($row['restatlocation'])."";
                }
                $output_str.=(strip_appoencode($row['name']).'`W hat das Idol des Kriegers, befindet sich '.$loc.'`W und '.($row['alive']?" lebt.":" ist tot!").'`n`n`0');
                }
                else
                {
                    $output_str.=('`WDas Idol des Kriegers ist im Wald!`n`n`0');
                }
                $sql="SELECT acctid,accounts.name,location,loggedin,laston,alive,housekey,activated,restatlocation FROM accounts LEFT JOIN items it ON acctid=it.owner WHERE it.tpl_id='idolfish'";
                $result = db_query($sql);
                if (db_num_rows($result)>0)
                {
                    $row = db_fetch_assoc($result);
                    $loggedin=user_get_online(0,$row);
                    if ($row['location']==USER_LOC_FIELDS) $loc=($loggedin?"online":"in den Feldern");
                    if ($row['location']==USER_LOC_INN) $loc="in einem Zimmer in der Kneipe";
                    if ($row['location']==USER_LOC_HOUSE){
                    $loc="im Haus Nummer ".($row['restatlocation'])."";
                }
                $output_str.=(strip_appoencode($row['name']).'`W hat das Idol des Anglers, befindet sich '.$loc.'`W und '.($row['alive']?" lebt.":" ist tot!").'`n`n`0');
                }
                else
                {
                    $output_str.=('`WDas Idol des Anglers ist im Wald!`n`n`0');
                }
                $sql="SELECT acctid,accounts.name,location,loggedin,laston,alive,housekey,activated,restatlocation FROM accounts LEFT JOIN items it ON acctid=it.owner WHERE it.tpl_id='idoldead'";
                $result = db_query($sql);
                if (db_num_rows($result)>0)
                {
                    $row = db_fetch_assoc($result);
                    $loggedin=user_get_online(0,$row);
                    if ($row['location']==USER_LOC_FIELDS) $loc=($loggedin?"online":"in den Feldern");
                    if ($row['location']==USER_LOC_INN) $loc="in einem Zimmer in der Kneipe";
                    if ($row['location']==USER_LOC_HOUSE){
                    $loc="im Haus Nummer ".($row['restatlocation'])."";
                }
                $output_str.=(strip_appoencode($row['name']).'`W hat das Idol des Totenbeschwörers, befindet sich '.$loc.'`W und '.($row['alive']?" lebt.":" ist tot!").'`n`n`0');
                }
                else
                {
                    $output_str.=('`WDas Idol des Totenbeschwörers ist im Wald!`n`n`0');
                }
                output(show_scroll('`n`n`n`n`n'.$output_str));
                break;
                
                case 35: case 36: case 37: case 38: case 39: case 40:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`n`cGras wächst nicht schneller, wenn man daran zieht.`c'));
                break;
                
                case 41: case 42: case 43: case 44:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`n`cDer erste Schlag muss kräftig sein, dann ersparst du dir viele weitere.`c'));
                break;
                
                case 45: case 46: case 47: case 48:
                output(show_scroll('`n`n`n`n`n`n`n`nKillereichhörnchen + Ogerzahn = Todeshörnchen`n`n Killereichhörnchen + Starkbier + Voller Aschenbecher = Partyhörnchen`n`nKillereichhörnchen + Wandsäbel = Terrorhörnchen`n'));
                break;
                
                case 49: case 50: case 51: case 52: case 53: case 54: case 55:
                $sql = "SELECT * FROM news WHERE accountid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_BIO).") ORDER BY rand() LIMIT 1";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`n`c'.strip_appoencode($row['newstext']).'`c`n'));
                break;
                
                case 56: case 57: case 58: case 59: case 60:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`n`cSiehst du nen Elf im Treibsand winken`nwink zurück und lass ihn sinken.`c`n'));
                break;
                
                case 61: case 62: case 63:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`nFür ein Vernichtungselixier gegen Minotauren braucht es einen alten Knochen, einen abgenagten Knochen und einen Ogerzahn.`n'));
                break;
                
                case 64: case 65: case 66: case 67: case 68: case 69: case 70:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`nDem Alltagsstress kann man entgehen, vermeidet man es aufzustehen.`n'));
                break;
                
                case 71: case 72: case 73: case 74: case 75:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`nMische ein Fässchen von Cedricks Ale mit dem Hausrecht.`nEin Kräfteschub sei dir gewiss!`n'));
                break;
                
                case 76: case 77: case 78: case 79: case 80:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`nDer Baum hat Äste, das ist das Beste. Denn wäre er kahl, dann wär\'s ein Pfahl.`n'));
                break;
                
                case 81: case 82:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`nSchwefel, Mohn und Nachtschatte ergeben ein sehr starkes Waffengift.`n'));
                break;
                
                case 83: case 84: case 85: case 86: case 87: case 88:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`nDein Gesicht sieht aus, als hättest du darin geschlafen.`n'));
                break;
                
                case 89: case 90: case 91: case 92: case 93:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`n3x ein kleines Alefass von Cedrick = 1x Starkbier`n`n3x Starkbier = 1x Starkbierkonzentrat.`n'));
                break;
                
                case 94: case 95: case 96: case 97: case 98: case 99: case 100:
                output(show_scroll('`n`n`n`n`n`n`n`n`n`n`nDer Klügere gibt nach, solange, bis er der Dumme ist.`n'));
                break;
            }
            
        item_delete(' id='.$item['id']);
        addnav('Zurück',$item_hook_info['ret']);
        break;

    }


}
?>
