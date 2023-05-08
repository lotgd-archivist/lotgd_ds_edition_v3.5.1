<?php

//27122004
//20.06.2006 Tippfehler (Forum 15.4.06) beseitigt

//Pflanzenzucht
//Idee von Fichte, Texte von Kisa, Zusammengeschuster von Hecki )
//Version: 1.1
//Erstmals eschienen auf http://www.cirlce-of-prophets.de/logd
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//Modifiziert von anpera: Nutzt items table
//
/**** EINBAUANLEITUNG (nur für LoGD 0.9.7 ext GER Release Nr. 3) ***

* In gardens.php finde:
addnav("Geschenkeladen","newgiftshop.php");

* Füge danach ein:

addnav("Blumenbeet","flowers.php");

* In newday.php finde:
$sql="SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk' OR class='Zauber') AND owner=".$session['user']['acctid']." ORDER BY id";

* und ersetze es durch:

$sql="SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk' OR class='Zauber' OR class='Beet') AND owner=".$session['user']['acctid']." ORDER BY id";

* Finde weiter:
if (mb_strlen($row[buff])>8){

*Füge DAVOR ein:

if ($row['class']=="Beet" && $row['value1']>0) db_query("UPDATE items SET value1=0 WHERE class='Beet' and owner=".$session['user']['acctid']);

*Datei flowers.php in den Logd Ordern hochladen
*/
// Viel Spass ihr Hobbygärtner!

require_once "common.php";

$beet = item_get(' owner='.$session['user']['acctid'].' AND tpl_id="beet" ',false);

if($beet['id'] && $beet != false)
{
	$beet['bit']=1;
}

page_header("Blumenbeet");

if($_GET['act'] == 'convert' && $beet != false)
{

	$session['user']['gems'] += 11;
	$session['user']['gold'] += 4400;

	output('Dragonslayer sprach: Es werde Licht! und es ward Licht. Und Dragonslayer sah, dass es gut war. Und du hast nun 4400 Gold und 11 Edelsteine wieder (was sogar noch 10% über dem Kaufpreis liegt - wenn das nicht mal ein Geschäft war..)!
			Dein Blumenbeet verschwindet in einer Dimensionslücke und ward fortan nimmermehr gesehen..');

	item_delete('id='.$beet['id'],1);
	addnav('JIPPIE!','village.php');
	page_footer();
}
elseif($_GET['act'] == 'convert' && $beet == false)
{
	output('Moment mal, du hast doch gar kein Beet (mehr)...hier lief was schief!`n');
}

output('`$Das Blumenbeet steckt nun im Haus-Anbau Garten. Du kannst dafür eine Entschädigung erhalten. Demnächst verliert dieses Blumenbeet seine Kraft!`0`n');

addnav('Aktionen');
if(is_array($beet))
{
	addnav('`^Rückerstattung des Kaufpreises!`0','flowers.php?act=convert',false,false,false,false,'Bist du dir sicher?');
}
//addnav('Zurück zum Garten','gardens.php');
//page_footer();


if ($_GET['op'] == ""){
    output("`c`bPflanzenzucht`c`b");
    output("`n`n");
    if ($beet['bit']==0){
        output("`@ Hier kannst du dir ein Blumenbeet anlegen. Wenn du es täglich pflegst wird schon bald die erste Knospe zu einer wunderschönen Blüte werden.");
        output("`@ Sei sorgsam und liebevoll, dann wird dich deine Pflanze sicher belohnen! Denn der Samen enthält magische Zutaten!");
        output("`n`n");
        output("`@ Ein Beet kostet einmalig 4000 Gold und 10 Edelsteine!`n");
        output("`@ Auf dem Beet ist Platz für eine Blume, aber diese entwickelt unendlich viele Knospen und in jeder ihrer Knospen wartet eine kleine Überraschung auf dich!`n");
        //addnav("Ein Beet anlegen","flowers.php?op=anlegen");
        addnav("Zurück zum Garten","gardens.php");
    }else{
        output("`@Voller Vorfreude betrittst du dein Beet. Du bist gespannt ob heute vielleicht etwas aus einer der Knospen spriest.`n`n");
        output("Du solltest etwas Zeit und Gold in die Aufzucht deiner Pflanze investieren, schliesslich braucht eine Pflanze, Liebe, Wasser und Dünger damit sie gedeiht!`n`n");
        if ($beet['value1']>0){
            output("`n`nDu hast dich heute schon um deine Pflanze gekümmert und siehst, dass es ihr gut geht.");
        }
        addnav("Um deine Pflanze kümmern (`^100`0 Gold)","flowers.php?op=kuemmern");
        addnav("Zurück zum Garten","gardens.php");
    }
}

if ($_GET['op'] == "anlegen"){
    if ($session['user']['gold']>3999 && $session['user']['gems']>9){
        $session['user']['gold'] -= 4000;
        $session['user']['gems'] -= 10;

		item_add($session['user']['acctid'],'beet');

        output("`n`n`2Du hast jetzt ein schönes Blumenbeet, und kannst mit deiner Aufzucht beginnen!`n");
        addnav("Zurück zum Garten","gardens.php");
        addnav("Zu deinem Beet","flowers.php");
    }else{
        output("`n`n`2Leider hast du nicht genug Gold und/oder Gems dabei, komm doch später wieder vorbei!`n");
        output("`n`n");
        addnav("Zurück zum Garten","gardens.php");
    }
}

if ($_GET['op'] == "kuemmern"){
    if($session['user']['gold']>99 && $beet['value1']==0 && $session['user']['turns']>0){
        $session['user']['turns'] --;
        $session['user']['gold'] -=100;
        $beet['value2'] ++;
        $beet['value1'] = 1;
        output("`@Du steckst viel Liebe und Energie in deine Arbeit, und hoffst das dich deine Pflanze in naher Zukunft für deine aufopferungsvollen Bemühungen belohnen wird!`n`n");
        addnav("Zurück zum Garten","gardens.php");
        if ($beet['value2']==10){
            $up = e_rand(1,13);
            $beet['value2']=0;
            switch ($up){
                case 1:
                case 2:
                output("`qVor deinen Augen öffnet sich plötzlich eine der Knospen und eine wunderschöne, ");
                output("`qlecker riechende Frucht erblickt das Licht der Welt und danach die Dunkelheit deines Rachens.`n");
                output("`@ Diese Frucht bringt dir 1 permanenten Lebenspunkt!");
                $session['user']['maxhitpoints']++;
                break;
                case 3:
                case 4:
                output("`qAls du deine Blume hoffnungsvoll anschaust scheint sie sich doch tatsächlich zu bewegen.");
                output("`qJa, es ist wahr, die Blüte öffnet sich ganz langsam und als sie vollkommen aufgeblüht ist bist du dir ganz sicher, dass es die allerschönste Blume ist, die du je in deinem Leben gesehen hast.");
                output("`qVor lauter Begeisterung kannst du garnicht reagieren als dein Nachbar auf dich zugerannt kommt,");
                output("`qdir 500 Gold in die Hand drückt und mit deiner wunderschönen Blume hinter der nächsten Ecke verschwindet. Du stehst da mit offenem Mund und fragst dich ob du je wieder eine solch wundervolle Blume züchten kannst!!!");
                $session['user']['gold']+=500;
                break;
                case 5:
                case 6:
                output("`qVor deinen Augen öffnet sich plötzlich eine der Knospen und eine wunderschöne, ");
                output("`qlecker riechende Frucht erblickt das Licht der Welt und danach die Dunkelheit deines Rachens.`n");
                output("`@ Diese Frucht bringt dir 5 weitere Waldkämpfe!");
                $session['user']['turns'] += 5;
                break;
                case 7:
                case 8:
                output("`5 Verträumt schaust du dein Blümchen an und hoffst das du dich bald an ihrer wunderschönen Blüte erfreuen kannst.`n`n");
                output("`5 Plötzlich reckt sich das kleine Blümchen und innerhalb von Sekunden erblüht eine ihrer Knospen in den schönsten Regenbogenfarben.`n");
                output("`5 Sie scheint richtig zu glänzen, nur für dich. Du hältst sie an deine Nase um ihren lieblichen Duft in dir aufzunehmen und je näher du sie richtung Nase hältst desto heller leuchtet sie!`n`n");
                output("`5 Heller, heller und immer heller strahlt sie dich an, du bist von Ihrer Schönheit wahrlich geblendet");
                output("`5 und entdeckst erst als du die Blume ganz an deiner Nase hast, dass ihre Blüte mit Edelsteinen verziert ist.`n`n");
                output("`5 Du steckst die 2 Edelsteine sorgsam ein und beschließt dich noch intensiver um dein kleines Pflänzchen zu kümmern - wer weiß was die nächste Blüte für wundersame Kräfte in sich verbirgt - ");
                $session['user']['gems']+=2;
                break;
                case 9:
                case 10:
                output("`qGespannt wartest du, wann deine Mühen endlich belohnt werden und tatsächlich, eine der größten Knospen an deiner Blume reckt und streckt sich und erblüht zu einer wahren Pracht.");
                output("`qDu bist so stolz wie noch nie zuvor auf dich selbst. Jetzt weißt du was einen richtigen Gärtner ausmacht.`n`n");
                output("`@Dieses Wissen lässt deine Erfahrung um 2% ansteigen!");
                $session['user']['experience']*=1.02;
                break;
                case 11:
                case 12:
                output("`tMit Erschrecken musst du feststellen, dass deine hübsche Blume eingegangen ist.`nDoch erblickst du, dass etwas Anderes in deinem Beet an ihrer Stelle gewachsen ist: Ein kleiner Macadamia-Strauch!`nDu denkst dir, dass es sicherlich nicht falsch wäre die Nüsse mitzunehmen, da der Strauch wohl nicht mehr lange überleben wird und du schon einen kleinen Ableger deiner geliebten Blume erblickst.`n");
                $res = item_tpl_list_get( 'tpl_name="Macadamia-Nüsse" LIMIT 1' );
                if( db_num_rows($res) )
                        {
                         $itemnew = db_fetch_assoc($res);
                         item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;
                case 13:
                output("`qGespannt wartest du, wie deine Mühen belohnt werden und tatsächlich, eine der größten Knospen an deiner Blume öffnet sich. Du findest darin... Erdnüsse ???");
                $res = item_tpl_list_get( 'tpl_name="Eine Hand voll Erdnüsse" LIMIT 1' );
                if( db_num_rows($res) )
                        {
                         $itemnew = db_fetch_assoc($res);
                         item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;
            }
        }
    }else if ($session['user']['gold']<100){
        output("Du hast zuwenig Gold dabei.");
        addnav("Zurück zum Garten","gardens.php");
    }else{
        output("Du kannst dir heute keine Zeit mehr für deine Pflanze nehmen.");
        addnav("Zurück zum Garten","gardens.php");
    }

	item_set(' id='.$beet['id'], $beet);

}

page_footer();
?>
