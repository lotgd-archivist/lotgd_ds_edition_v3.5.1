<?php
/**
 * Bossgegner Hel, Texte von Lucia
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3
 */


/**
 * Die Nav darf nur angezeigt werden wenn der User x4 Heldentaten hat
 */
function boss_check_additional_nav_preconditions()
{
    global $Char;
    $bool_user = ($Char->dragonkills-4)%10==0;
    if($bool_user)
    {
        return true;
    }
    else
    {
        return false;
    }
}


function boss_do_intro()
{
    global $g_arr_current_boss,$session,$battle,$badguy,$g_str_base_file,$battle;
    if(!isset($str_output))$str_output='';
    switch($_GET['act'])
    {
        case '':
            {
                $str_output .= get_title('`BModgúdr').'`B
                Langsam wanderst du die Wege des Friedhofes entlang, begleitet vom Krähen einiger Raben, als du ein dir völlig neues Gebiet, abgegrenzt mit einem Zaun, entdeckst. Nach wenigen Momenten findest du auch das Gatter, öffnest es und betrittst eine Welt der Düsternis, in  welcher sich in einiger Entfernung die Gestalt einer jungen Frau abzeichnet. Neugierig näherst du dich ihr und bemerkst, dass du diesen Teil des Friedhofes noch nie gesehen hast. Die junge Frau scheint eine breite, goldene Brücke zu bewachen, deren anderes Ende in völliger Finsternis liegt, sodass du nicht erkennen kannst, was sich dort befindet. `§"Zum Gruße, '.($session['user']['sex']?'junge Kriegerin':'junger Krieger').'! Modgúdr ist mein Name und du befindest dich hier bei der Brücke Gjallabrú zu Niflheim"`B, erklärt sie und weist in die entsprechende Richtung. `§"Nenn mir deinen Namen und deine Herkunft und ich lasse dich passieren." `BWahrheitsgemäß antwortest du der Wächterin Modgúdr und mit einem wohlwollenden Nicken tritt sie zur Seite. `§"Wohlan, so überquere die Brücke und sei Willkommen in Niflheim."`n`n';
                addnav('B?Brücke überqueren',$g_str_base_file.'&act=intro1');
                addnav('F?Flüchten','friedhof.php');
                output($str_output);
                break;
            }
        case 'intro1':
            {
                $str_output .= get_title('`BNiflheim').'`B
                Du setzt den Weg vor, welcher immer tiefer in das Totenreich der Göttin Hel führt, unter den wachsamen vier Augen Grams, des Höllenhundes, welcher dich zwar weiterwandern lässt, jedoch gibt es keinen Zweifel an der Tatsache, dass er dich sicherlich sofort angreifen wird, solltest du die Unterwelt einfach so verlassen. Da du das aber nicht vorhast, beschreitest du weiter den Weg und es kommt dir vor, als würde sich die Zeit hier in dieser düsteren Trostlosigkeit, wo Kälte, Schmerz und Hunger herrscht, endlos lang hinziehen, bis du die Gefilde Hels endlich erreichst. Elend heißt ihr Wohnsitz, Fallende Gefahr ihre Türschwelle, Hunger der Tisch und Verschmachtung ihr Messer. Der Sarg ist ihr Bett und Trägtritt und Langsamtritt sind ihre Magd und ihr Knecht. Halb tot und verrottet, halb lebendig und schön, so erscheint dir nun die Herrin Niflheims höchstselbst. `§"Du gehörst hier noch nicht hin, Sterblicher."`B, verkündet sie und blickt streng auf dich herab. Du weißt, sie ist die Schwester Jörmungandrs und des Fenriswolfs, so muss auch Hel eine Bedrohung sein, oder? Willst du dich ihr also im Kampfe stellen?`n`n';
                addnav('H?Herausforderung annehmen',$g_str_base_file.'&act=intro2');
                addnav('F?Feige flüchten','friedhof.php');
                output($str_output);
                break;
            }    
        case 'intro2':
            {    
                $str_output .= get_title('`BHel').'`BSelbstverständlich stellst du dich der Göttin der Unterwelt, so wie es deine Ehre verlangt! Mit gezogener Waffe forderst du Hel heraus, welche für dein Tun zuerst nur ein Kopfschütteln übrig hat. Doch dann erhebt sie sich zu ihrer vollen Größe in aller Herrlichkeit einer Göttin gerecht und ihre Augen blicken auf dich hernieder. Ernstes Schweigen macht klar, dass mit ihr nicht zu spaßen ist und sie nimmt deine Herausforderung an. Was mit dir geschehen wird, solltest du sie nicht bezwingen können, magst du dir lieber nicht vorstellen.`n
                `bWappne dich für den Kampf`b';
                output($str_output);
                $badguy = boss_get_badguy_array($g_arr_current_boss);
                $session['user']['badguy']=utf8_serialize($badguy);
                $battle=true;
                $session['user']['seendragon']=1;
                break;
            }
    }
}

function boss_do_autochallenge()
{
    
    return true;
}

function boss_do_epilogue()
{
    global $g_str_base_file, $g_arr_current_boss, $session;

    music_set('drachenkill',0);

    switch ($_GET['act'])
    {
        case '':
            {
                $str_output = get_title('Sieg über Hel!');
                $str_output .= '`BDu lässt deine Waffe sinken, im Wissen, dass du eine Göttin nicht besiegen kannst.`n
`§"Alles, was lebt, wird einmal sterben. Doch auch der Tod ist nur ein Zwischenstadium und Sterben wie schlafen gehen. Man erwacht auch wieder, wenn es an der Zeit ist. Doch deine Zeit zu Sterben und schließlich einmal wieder zu erwachen, ist noch nicht gekommen,'.($session['user']['sex']?'junge Kriegerin':'junger Krieger').' sei jedoch ob deiner Tapferkeit meiner Gunst gewiss."`B`n
                Du fühlst dich, als ob neues Leben dich beseelt und seltsam leicht. Vor deinen Augen verschwimmt die Welt und Finsternis umhüllt dich.`n
                Als du nach einigen Stunden wieder zu dir kommst, kannst du dich an nichts erinnern, was geschehen ist.`n`n';
                addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
                break;
            }
        case 'wakeup':
            {
                $str_output = get_title('Erwache!');
                $str_output .= 'Du erwachst umgeben von Bäumen. In der Nähe hörst du die Geräusche eines Dorfs.
                Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von gefährlichen Kreaturen, die die Gegend heimsuchen.
                Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages diesen abscheulichen Wesen stellst.
                `n`n`^Du bist von nun an bekannt als `&'.$session['user']['name'].'`^!!
                `n`n`&Weil du '.$session['user']['dragonkills'].' Heldentaten vollbracht hast, startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du dir verdient oder erkauft hast.
                `n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über Hel!
                `n`nDein Sieg bringt dir einige Gefallen im Reich der Unterwelt ein.`n';
                
                $session['user']['deathpower'] += 100;

                addnav('Es ist ein neuer Tag','news.php');

                // Knappe laden und steigern
                $rowk = get_disciple();
                if ($rowk['state']>0)
                {
                    $str_output .= disciple_levelup($rowk);
                    $session['bufflist'] = array();
                }
                break;
            }
    }
    output($str_output);
}

function boss_do_run()
{
    global $battle;
    $battle = true;
    output('So sehr du auch gehetzt um dich blickst, du vermagst den Ausgang nicht mehr auszumachen. Dir bleibt nichts anderes übrig, als mit aller Macht zu versuchen, den riesigen Todbringer zu bekämpfen.`n');
}

function boss_do_fight()
{
    global $battle;
    $battle = true;
}

function boss_do_victory()
{
    global $g_str_base_file,$badguy,$flawless,$session;

    boss_calc_victory_bonus();
    

    music_set('drachenkill',0);

    $flawless = 0;
    if ($badguy['diddamage'] != 1)
    {
        $flawless = 1;
    }
    addnews('`#'.$session['user']['login'].'`# hat sich den Titel `&'.$session['user']['title'].'`# für die `^'.$session['user']['dragonkills'].'`#te erfolgreiche Heldentat verdient!');

    headoutput('`c`b`@Sieg!`0`b`c`n`BDu willst zum letzten Schlag ausholen, da wirst du plötzlich in gleißendes Licht gebadet. Vor dir steht Hel, gerade noch vom Kampf gezeichnet und in nur wenigen Augenblicken wieder unantastbar, und sie schenkt dir ein seltsames Lächeln. `§"Du kannst den Tod nicht besiegen."`B, raunt die Göttin und dir wird klar, dein Sieg war nur eine Illusion.
    `n`n<hr>`n');
    addnav('Weiter',$g_str_base_file.'&op=epilogue&flawless='.$flawless);
}

function boss_do_flawless_victory()
{
    boss_calc_flawless_victory_bonus();
}

function boss_do_defeat()
{
    global $g_arr_current_boss;
    headoutput(get_title('Niederlage!').'`BDas hast du nun davon, töricht eine Göttin herauszufordern! Du hörst noch ein leises Lachen und dann ist es vorbei. Deine Seele gehört nun Hel und du wirst ihr nie wieder entkommen können, oder?`n
            `4Du hast dein ganzes Gold verloren!`n
            Du kannst morgen wieder kämpfen.`0`n`n<hr>`n');

    boss_calc_defeat();

    addnav('Tägliche News','news.php');

}

function boss_get_victory_news_text()
{
    global $session;

    $str_news = '`&'.$session['user']['name'].'`& hat von Hel, Herrin von Niflheim, etwas über Leben und Tod gelernt!';

    return $str_news;
}
function boss_get_defeat_news_text()
{
    global $session;

    $str_news = '`%'.$session['user']['name'].'`5 verlor '.($session['user']['sex']?'ihre':'seine').' Seele an Hel.';

    return $str_news;
}

?>
