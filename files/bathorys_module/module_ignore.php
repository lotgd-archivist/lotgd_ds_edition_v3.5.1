<?php

class MIgnore
{
	function __construct($do, $ispopup = false)
	{
		global $session,$access_control,$Char;
		$filename = 'bathorys_popups.php?mod=ignore&mdo='.$do.'&';

        popup_header('Ignoreliste',true);

        if($_GET['act']=='ignorenow')
        {
            output('`n`n`c`b');
            $igno_user = db_get("SELECT acctid,superuser FROM accounts WHERE login='".db_real_escape_string($_POST['login'])."' LIMIT 1");

            $int_target = intval($igno_user['acctid']);

            if($Char->acctid !=  $int_target)
            {
                $s = $igno_user['superuser'];

                if($int_target == 0 && 'chuck norris' == mb_strtolower($_POST['login'])){

                    $cn[]='1. Chuck Norris ist der Einzige, der die Zeit wirklich totschlagen kann.';
                    $cn[]='2. Die Schweiz ist nur deshalb neutral, weil sie noch nicht weiß, auf welcher Seite Chuck Norris steht.';
                    $cn[]='3. Chuck Norris kann ein Happy Meal zum Weinen bringen.';
                    $cn[]='4. Chuck Norris hat den Roadrunner gefangen.';
                    $cn[]='5. Chuck Norris hat als Kind auch Sandburgen gebaut - wir kennen sie heute als Pyramiden.';
                    $cn[]='6. Am Anfang war nichts... Dann hat Chuck Norris diesem Nichts einen Roundhouse-Kick verpasst und gesagt: "Besorg dir \'nen Job." Das ist die Geschichte des Universums.';
                    $cn[]='7. Chuck Norris kennt die letzte Ziffer von Pi.';
                    $cn[]='8. Chuck Norris kann chinesisches Essen mit einem Stäbchen essen.';
                    $cn[]='9. Voldemort nennt Chuck Norris "Du-weißt-schon-wer".';
                    $cn[]='10. Der Bart von Chuck Norris verdeckt kein Kinn, sondern eine weitere Faust.';
                    $cn[]='11. Wenn Chuck Norris ins Wasser fällt, wird er nicht nass. Das Wasser wird Chuck Norris.';
                    $cn[]='12. Chuck Norris hat bis Unendlich gezählt - zwei mal.';
                    $cn[]='13. Chuck Norris ist bereits vor zehn Jahren gestorben. Der Tod hatte aber nicht den Mut, es ihm zu sagen.';
                    $cn[]='14. Chuck Norris bekommt bei Praktiker 20 % auf alles. Auch auf Tiernahrung.';
                    $cn[]='15. Es gibt keine Evolutionslehre. Nur eine Liste von Tieren, die von Chuck Norris die Erlaubnis bekommen haben, weiterzuleben.';
                    $cn[]='16. Chuck Norris schläft nicht. Er wartet.';
                    $cn[]='17. Chuck Norris’ Tränen heilen Krebs. Zu schade, dass er nie weint. Niemals.';
                    $cn[]='18. Chuck Norris schläft bei Licht. Nicht weil er Angst vor der Dunkelheit hat - die Dunkelheit hat Angst vor ihm.';
                    $cn[]='19. Wenn Chuck Norris Liegestützen macht, drückt er die Welt nach unten.';
                    $cn[]='20. Wenn Chuck Norris in den Himmel schaut, fangen die Wolken an zu schwitzen. Manche nennen es Regen.';
                    $cn[]='21. Chuck Norris schläft mit einem Kopfkissen unter seiner Waffe.';
                    $cn[]='22. Chuck Norris wird nie einen Oscar als Schauspieler bekommen - weil er nicht schauspielert.';
                    $cn[]='23. Chuck Norris entführt Aliens.';
                    $cn[]='24. Chuck Norris hat alle Farben erfunden. Außer Rosa! Tom Cruise hat Rosa erfunden.';
                    $cn[]='25. Es gibt keine Massenvernichtungswaffen im Irak. Chuck Norris lebt in Oklahoma.';
                    $cn[]='26. Was geht den Opfern von Chuck Norris als letztes durch den Kopf? Sein Fuß!';
                    $cn[]='27. Chuck Norris trägt keine Uhr - ER entscheidet, wie spät es ist.';
                    $cn[]='28. Chuck Norris hat den Niagara Fall gelöst und die Formel 1 ausgerechnet!';
                    $cn[]='29. Chuck Norris wird nicht krank. Er bietet Viren einen Unterschlupf.';
                    $cn[]='30. Chuck Norris hat das tote Meer erschossen.';
                    $cn[]='31. Chuck Norris spielt nicht Gott. Spielen ist für Kinder.';
                    $cn[]='32. Der Film "300" sollte eigentlich "1 - Chuck Norris gegen die Perser" heißen. Aber wer schaut schon einen 3-Sekunden-Film?';
                    $cn[]='33. Chuck Norris kann Bälle umkippen!';
                    $cn[]='34. Chuck Norris hat bei Burger King einen BigMac bestellt - und hat ihn bekommen!';
                    $cn[]='35. Chuck Norris macht Liegestütze und Sit-ups zur gleichen Zeit.';
                    $cn[]='36. Chuck Norris rasiert sich nicht. Er schärft die Klinge an seinem Bart.';
                    $cn[]='37. Chuck Norris hat das Krankenhaus gebaut, in dem er geboren wurde.';
                    $cn[]='38. Chuck Norris kann M&Ms nach Alphabet sortieren.';
                    $cn[]='39. Chuck Norris besteht die Führerscheinprüfung zu Fuß.';
                    $cn[]='40. Chuck Norris kann mit zwei Händen 29 zeigen.';
                    $cn[]='41. Chuck Norris hat mal einen Anstarr-Wettbewerb gegen sein Spiegelbild gewonnen.';
                    $cn[]='42. Das einzige Mal, dass Chuck Norris sich irrte, war, als er dachte, er hätte sich geirrt.';
                    $cn[]='43. Chuck Norris kann sich für ein Gruppenfoto allein im Halbkreis aufstellen.';
                    $cn[]='44. Chuck Norris hat keine Freunde. Er hat Fans!';
                    $cn[]='45. Chuck Norris tötet ohne Waffen. Er ist Pazifist.';
                    $cn[]='46. Chuck Norris hat die Azteken ausgerottet. Es war ihnen eine Ehre.';
                    $cn[]='47. Chuck Norris benutzt Tabasco als Augentropfen!';
                    $cn[]='48. Chuck Norris kann Spanisch auf Englisch.';
                    $cn[]='49. Chuck Norris ist Darth Vaders Vater!';
                    $cn[]='50. Chuck Norris kennt alle Chuck Norris Witze - er hat sie erfunden!';
                    $cn[]='51. Chuck Norris hat sich aus dem Bauch seiner Mutter durch einen Roundhouse-Kick befreit. Kurz darauf wuchs ihm ein Bart.';
                    $cn[]='52. Wenn Bruce Banner wütend wird, verwandelt er sich in Hulk. Wenn Hulk wütend wird, verwandelt er sich in Chuck Norris.';
                    $cn[]='53. Chuck Norris kann mit einer Lupe Feuer machen - bei Nacht.';
                    $cn[]='54. Chuck Norris kann Drehtüren zuknallen.';
                    $cn[]='55. Chuck Norris isst keinen Honig. Er kaut Bienen.';
                    $cn[]='56. Wenn Chuck Norris teilt, bleibt kein Rest.';
                    $cn[]='57. Chuck Norris war Kamikaze-Pilot. Zwölf mal.';
                    $cn[]='58. Popeye isst Spinat! Chuck Norris isst Popeye.';
                    $cn[]='59. Die Kinder schlafen nachts mit Superman-Schlafanzügen. Superman schläft mit einem Chuck-Norris-Schlafanzug.';
                    $cn[]='60. Chuck Norris isst zu jeder Mahlzeit ein Steak - meistens vergisst er, vorher die Kuh zu schlachten.';

                    shuffle($cn);
                    shuffle($cn);
                    shuffle($cn);
                    shuffle($cn);
                    shuffle($cn);

                    output('`$So leid es mir tut, aber Chuck Norris kannst du nicht ignorieren.`0`n');
                    output('`qWeil: Fakt Nr. '.$cn[0].'`0');
                }
                else if($int_target == 0)
                {
                    output('`$Spieler nicht gefunden!`0');
                }
                else if(1 == $s ||2 == $s ||3 == $s ||4 == $s)
                {
                    output('`$So leid es mir tut, aber Teammitglieder kannst du nicht ignorieren.`0');
                }
                else if(CIgnore::ignores($Char->acctid,$int_target))
                {
                    output('`$Den Spieler ignorierst du doch schon!.`0');
                }
                else
                {
                    $igno_types = array();

                    if(isset($_POST['type1']) && intval($_POST['type1']) === 1){
                        $igno_types[] = CIgnore::IGNO_YOM;
                    }
                    if(isset($_POST['type2']) && intval($_POST['type2']) === 1){
                        $igno_types[] = CIgnore::IGNO_CHAT;
                    }
                    if(isset($_POST['type3']) && intval($_POST['type3']) === 1){
                        $igno_types[] = CIgnore::IGNO_LIST;
                    }
                    if(isset($_POST['type4']) && intval($_POST['type4']) === 1){
                        $igno_types[] = CIgnore::IGNO_OOL;
                    }
                    if(isset($_POST['type5']) && intval($_POST['type5']) === 1){
                        $igno_types[] = CIgnore::IGNO_BIO;
                    }
                    if(isset($_POST['type6']) && intval($_POST['type6']) === 1){
                        $igno_types[] = CIgnore::IGNO_BOARDS;
                    }
                    if(isset($_POST['type7']) && intval($_POST['type7']) === 1){
                        $igno_types[] = CIgnore::IGNO_CAL;
                    }

                    if(isset($_POST['type100']) && intval($_POST['type100']) === 1){
                        $igno_types[] = CIgnore::IGNO_TWOWAY;
                    }

                    CIgnore::ignore($int_target, $_POST['reason'],$igno_types);
                    $_POST = array();
                }

            }
            else
            {
                output('`$Du kannst dich wohl kaum selber ignorieren wollen ;)!`0');
            }
            output('`b`c`n`n');
        }
        else if($_GET['act']=='ignorenot')
        {
            $int_target = intval($_GET['acctid']);
            CIgnore::unignore($int_target);
        }
        else if($_GET['act']=='ignoreedit')
        {
            if(in_array('100',$_POST['value'])){
                CIgnore::set_type($_POST['pk'],100,1);
            }else{
                CIgnore::set_type($_POST['pk'],100,0);
            }

            for($z=1; $z < 7; $z++) {
                if(in_array(''.$z,$_POST['value'])){
                    CIgnore::set_type($_POST['pk'],$z,1);
                }else{
                    CIgnore::set_type($_POST['pk'],$z,0);
                }
            }
            exit();
        }
        else if($_GET['act']=='komedit')
        {
            CIgnore::set_reason($_POST['pk'],$_POST['value']);
            exit();
        }

        $form = array();

        $form[]         = 'Spieler ignorieren,title';
        $form[] 		= '<b>Spieler</b>,viewonly';
        $form['login']  =  "Login,usersearch,login";
        $form['reason'] =  "Kommentar,text,255";
        $form[] 		= '<b>Einstellungen</b>,viewonly';

        $form['type1'] =  "Tauben (YOMs),bool";

        $form['type2'] =  "RP-Chat,bool";
        $form['type3'] =  "Einwohnerliste,bool";
        $form['type4'] =  "'Wer ist hier?'-Liste,bool";
        $form['type5'] =  "Seine Bio/Steckbrief und Leistungen/News,bool";
        $form['type6'] =  "Nachrichtenbretter,bool";
        $form['type7'] =  "Kalender,bool";

        $form['type100'] =  "Die Einstellungen gelten auch für den anderen Spieler? Dies bedeutet z.B.: Er kann dein RP nicht lesen solltest du seins ignorieren usw.,bool";

        $str_ignorenow_lnk = $filename.'act=ignorenow';
        output('`n<form action="'.$str_ignorenow_lnk.'" method="POST" enctype="multipart/form-data">');
        showform($form,$_POST,false,'Ignorieren',9);
        output('</form>');

        $g=1;
        $footer = '`n`t`bDie Ignoreliste:`b`n
        <table cellpadding="5" cellspacing="5" border="0">
        <tr>
        <th></th>
        <th>Spieler</th>
        <th>Kommentar</th>
        <th>Einstellungen</th>
        <th>Aufheben?</th>
        </tr>
        ';

        $ign_arr = CIgnore::ignore_list();

        foreach($ign_arr as $key => $val)
        {
            $footer.='<tr>';
            $footer.='<td>`t'.$g.'`0 </td>';
            $footer.='<td>`t'.$val['name'].'`0 </td>';

            $footer.='<td><span class="inline_editable" data-type="text" data-pk="'.$val['ignoreid'].'" data-url="bathorys_popups.php?mod=ignore&mdo=&act=komedit" data-title="Kommentar eingeben">'.utf8_htmlspecialchars($val['reason']).'</span></td>';

            $footer.='<td><a href="#" id="inline_type_'.$g.'" data-type="checklist" data-pk="'.$val['ignoreid'].'" data-url="bathorys_popups.php?mod=ignore&mdo=&act=ignoreedit" data-title="Einstellungen"></a></td>';

            $vals = array();

            for($z=1; $z < 7; $z++) {
                if($val['type'.$z] == 1){
                    $vals[] = $z;
                }
            }

            if($val['type100'] == 1){
                $vals[] = 100;
            }

            rawoutput(JS::encapsulate("
                    atrajQ(function(){
                        atrajQ('#inline_type_".$g."').editable({
                        value: [".implode(',',$vals)."],
                        source: [
                        {value: 1, text: 'Tauben'},
                        {value: 2, text: 'RP- und OOC-Chat'},
                        {value: 3, text: 'Einwohnerliste'},
                        {value: 4, text: 'Wer ist hier?-Liste'},
                        {value: 5, text: 'Bio/Steckbrief und News'},
                        {value: 6, text: 'Nachrichtenbretter'},
                        {value: 100, text: 'Beidseitig'}
                        ]
                        });
                    });"));

            $footer.='<td>`0[ '.create_lnk('Ignore aufheben',$filename.'act=ignorenot&acctid='.$val['ignoreid'].'',true,false,'Bist Du sicher, dass du den Ignore aufheben willst?').' ] </td>';
            $footer.='</tr>';
            $g++;
        }

        output($footer.'</table>`n`n');

        rawoutput(JS::encapsulate("atrajQ.fn.editable.defaults.mode = 'inline';
                    atrajQ(function(){
                        atrajQ('.inline_editable').editable();
                    });"));

        popup_footer();
	}
}
?>