<?php

// 14072004

require_once 'common.php';

page_header('Cathairs Trainingslager');
checkday();
function train_navi () {
    global $session, $access_control;

    if($session['user']['level'] < 15) {
        addnav('Meister befragen','train.php?op=question');
        addnav('Meister herausfordern','train.php?op=challenge');
    }
    else 
    {
        addnav('Meister befragen','train.php?op=bosslist');
    }

    if($session['user']['dragonkills'] >= 1) {
        addnav('Mehr Training');
        if($session['user']['dragonkills'] >= 10) {
            if($session['user']['turns'] >= 6) {
                addnav('1 Schlossrunde (gib '.getsetting('wk_castle_turns',6).' Waldkämpfe)','train.php?op=change&what=turns');
            }
            if($session['user']['castleturns']) {
                addnav(getsetting('castle_turns_wk',4).' Waldkämpfe (gib 1 Schlossrunde)','train.php?op=change&what=castleturns');
            }
        }
        addnav('R?Raufen','tussle.php');
        if($session['user']['dragonkills'] >= 15) {
            addnav('Kampftraining','train.php?op=explore');
        }
        addnav('S?Kampf-Simulator','battlesimulator.php');
        
    }
    
    addnav('Zurück');
    addnav('Zurück zum Stadtzentrum','village.php');
    
    if ($access_control->su_check(access_control::SU_RIGHT_SULVL) && $session['user']['level']<15) {
        addnav('Mod-Aktionen');
        if($_GET['su_op']=='cheat')
        {
            $session['daily']['su_cheatbuttons']=1;
        }
        if($session['daily']['su_cheatbuttons'])
        {
            addnav('Superuser Level erhöhen','train.php?op=challenge&victory=1',false,false,false,false);
        }
        else
        {
            addnav('Funktionen einblenden','train.php?su_op=cheat',false,false,false,false);
        }
    }
}

function check_additional_nav_preconditions($str_name)
{
    global $session;
    switch($str_name)
    {
        case 'jormungandr':
            return (item_count('owner = "'.$session['user']['acctid'].'" AND tpl_id = "trph"') >0 );
            break;
        
        case 'nidhoggr':
            return $session['user']['exchangequest'] > 28;
            break;
        
        case 'lichking':
            return item_count('owner='.$session['user']['acctid'].' AND tpl_id="analloni_s"  AND deposit1=0')>0;
            break;
        
        case 'huntgod':
            $rowe=user_get_aei('hunterlevel');
            return ($rowe['hunterlevel']==6);
            break;
        
        default:
            return(2);
            break;
        
    }
}

output(get_title('`(T`)r`7a`ei`fn`0ings`fl`ea`7g`)e`(r'));
$sql = 'SELECT * FROM masters WHERE creaturelevel = '.$session['user']['level'];
$result = db_query($sql);
$master['creaturename']='Cathair';
if (db_num_rows($result) > 0){
    $master = db_fetch_assoc($result);
    if ($master['creaturename'] == 'Gadriel the Elven Ranger' && $session['user']['race'] == 'elf') {
        $master['creaturewin'] = 'Sowas nennt sich Elf?? Halb-Elf höchstens! Komm wieder, wenn du mehr trainiert hast.';
        $master['creaturelose'] = 'Es ist nur passend, dass ein anderer Elf sich mit mir messen konnte. Du machst gute Fortschritte.';
    }

    $exprequired= get_exp_required($session['user']['level'],$session['user']['dragonkills']);
}
    
if ($_GET['op']==''){
    
    if($session['user']['level']<15){
        output('`(D`)a`7s`e A`fu`0fklirren von Stahl ertönt in den Ohren, wenn man sich dem Trainingslager nähert. Ein jedes Kriegerherz mag hier wohl höher schlagen; doch nicht zum Vergnügen, denn hier geht es um Kämpfe, um hartes Training, das aus jungen Burschen Krieger machen soll. Selten kümmern sich die Schüler um einander, jeder ist sich selbst der Nächste, und diese angeheizte Atmosphäre schlägt sich auch auf das Gemüt nieder und treibt zum Kampfe.`n
Wirst du es wagen, einen der Meister herauszufordern und dich mit ihm messen - um als strahlender Sieger hervor zu gehen, oder doch den Staub zu ihren Füßen zu k`fü`es`7s`)e`(n?
        `n`n`0Dein Meister ist `^'.$master['creaturename'].'`0.');
    }
    else{
        output('`(D`)u `7b`eu`fm`0melst über den Übungsplatz. Jüngere Krieger drängen sich zusammen und deuten auf dich, als du vorüber läufst. Du kennst diesen Platz gut. Cathair grüßt dich und du gibst ihr einen starken Händedruck. Außer Erinnerungen gibt es hier nichts mehr für dich. Du bleibst noch eine Weile und siehst den jungen Kriegern beim Training zu, bevor du zur Stadt zurückk`fe`eh`7r`)s`(t.');
    }
    train_navi();
}
        
else if($_GET['op']=='challenge'){ //Meister herausfordern
    if ($_GET['victory'])
    {
        $bool_superuser_levelup = true;
        $victory=true;
        $defeat=false;
        
        debuglog('Nutzte den Superuser-Button im Trainingslager');
        
        if ($session['user']['experience'] < $exprequired)
        {
            $session['user']['experience'] = $exprequired;
        }
        if ($session['user']['seenmaster']==2)
        {
            $session['user']['seenmaster']=1;
        }
        else
        {
            $session['user']['seenmaster']=0;
        }
        //train_navi();
    }
    if ($session['user']['seenmaster']>=1 && !$_GET['auto']){
        output('Du bist der Meinung, dass du heute vielleicht schon genug von deinem Meister hast. Die Lektion, die du heute gelernt hast, hält dich davon ab, dich nochmal so bereitwillig einer derartigen Demütigung zu unterwerfen.');
        train_navi();
        
    }
    else{

        if (getsetting('multimaster',1)==0 && $session['user']['seenmaster']!=2) 
        {
            $session['user']['seenmaster'] = 1;
        }
        if ($session['user']['experience']>=$exprequired){
                                            
            $changeat = e_rand(2, min($session['user']['dragonkills'] * 0.3, 2));
            $changedef = e_rand(2, min($session['user']['dragonkills'] * 0.3, 2));
            $changehp = e_rand(2,12);
                                            
            $atkflux = (int)$session['user']['attack']*0.9+0.1*$changeat;
            $defflux = (int)$session['user']['defence']*0.9+0.1*$changedef;
            $hpflux = (int)($session['user']['maxhitpoints']*0.9+0.1*$changehp);
            $master['creatureattack']=$atkflux;
            $master['creaturedefense']=$defflux;
            $master['creaturehealth']=$hpflux;
            $session['user']['badguy']=createstring($master);

            $battle=true;
            if ($victory) {
                $badguy = createarray($session['user']['badguy']);
                output('Mit einem Wirbelsturm aus Schlägen schlägst du deinen Meister nieder.`n');
                //train_navi();
            }
        }
        else{
            output('Du machst dich mit '.$session['user']['weapon'].'`0 und '.$session['user']['armor'].'`0 bereit und näherst dich Meister `^'.$master['creaturename'].'`0.
            `n`nEine kleine Menge Zuschauer hat sich versammelt und du bemerkst das Grinsen in ihren Gesichtern. Aber du fühlst dich selbstsicher. Du verneigst dich vor `^'.$master['creaturename'].'`0 und führst einen perfekten Drehangriff aus, nur um zu bemerken, dass du NICHTS in den Händen hast!  `^'.$master['creaturename'].'`0 steht vor dir - mit deiner Waffe in der Hand. Kleinlaut nimmst du '.$session['user']['weapon'].'`0 entgegen und schleichst unter dem schallenden Gelächter der Zuschauer vom Trainingsplatz.');
            $session['user']['seenmaster']=1;
            train_navi();
        }
    }
}
    
else if($_GET['op']=='question'){ //Meister befragen
    output('Furchtsam näherst du dich `^'.$master['creaturename'].'`0, um ihn zu fragen, ob du bereits in der selben Klasse wie er kämpfst.');
    if($session['user']['experience']>=$exprequired){
        output('`n`n`^'.$master['creaturename'].'`0 sagt: "Gee, deine Muskeln werden ja größer als meine..."');
    }
    else{
        output('`n`n`^'.$master['creaturename'].'`0  stellt fest, dass du noch mindestens `%'.($exprequired-$session['user']['experience']).'`0 Erfahrungspunkte mehr brauchst, bevor du bereit bist, ihn zu einem Kampf herauszufordern.');
    }
    if ($session['user']['reputation']>20) 
    {
        output('`nAußerdem ist `^'.$master['creaturename'].'`0 von deinem ausgezeichneten Ruf begeistert.');
    }
    if ($session['user']['reputation']<-20)
    {
        output('`n`^'.$master['creaturename'].'`0 zeigt sich sehr enttäuscht von deinem Verhalten als Kämpfer in der Welt.');
    }
    
    train_navi();
}

else if($_GET['op']=='bosslist')
{
    include_once(LIB_PATH.'boss.lib.php');
    $str_out='Du fragst `^'.$master['creaturename'].'`0, mit wem du dich nun messen könntest.
    `n`^'.$master['creaturename'].'`0 überreicht dir eine Liste und sagt: "Nun, als ein mächtiger Krieger, der du bist könntest du dir Ruhm und Ehre verdienen, wenn du eine dieser Kreaturen besiegst, die sich in den Wäldern um '.getsetting('townname','Atrahor').' aufhalten."
    `nDu schaust auf die Liste und siehst:
    `n';
    foreach($g_arr_boss as $str_name => $val)
    {
        if(
            $g_arr_boss[$str_name]['enabled'] == true &&
            //Nur alle dk_delta Dragonkills anzeigen
            $session['user']['dragonkills'] % $g_arr_boss[$str_name]['dk_delta'] == 0 &&
            //Mindest DK Zahl
            $session['user']['dragonkills'] >= $g_arr_boss[$str_name]['min_dk'] &&
            //Mindest EXP
            $session['user']['experience'] >= $g_arr_boss[$str_name]['min_exp'] &&
            //Mehrere Angriffe gegen den Drachen am Tag erlaubt
            ($session['user']['seendragon'] == 0 || $g_arr_boss[$str_name]['multiple_challenge'] === true)
            )
        {
            //Wenn es zusätzliche Bedingungen für die Bossnav gibt
            //Funktions-Code aus den Modulen ist in der check-Funktion zusammengefasst, wenn benötigt und nicht vorhanden kommt Hinweistext
            $extra=true;
            if($g_arr_boss[$str_name]['additional_nav_preconditions'] == true)
            {
                $extra=check_additional_nav_preconditions($str_name);
            }
            if($extra)
            {
                $str_out.='`n'.$g_arr_boss[$str_name]['name'];
                if($extra===2) //Gegner fehlt in der Check-Funktion
                {
                    $str_out.=' (kann weitere Bedingungen erfordern)';
                }
            }
        }
    }
    $str_out.='`n`nWo genau sich diese Kreaturen aufhalten kann dir `^'.$master['creaturename'].'`0 allerdings auch nicht sagen.';
    output($str_out);
    train_navi();
}

else if($_GET['op']=='autochallenge'){ //Gegen den Meister antreten
    addnav('Gegen den Meister antreten','train.php?op=challenge&auto=1');
    output('`^'.$master['creaturename'].'`0 ist deine Tapferkeit als Krieger zu Ohren gekommen und er hat Gerüchte gehört, dass du glaubst, 
    du bist so viel mächtiger als er, dass du nicht einmal gegen ihn kämpfen müsstest, um irgendetwas zu beweisen. Das hat sein Ego 
    verständlicherweise verletzt. So hat er sich aufgemacht, dich zu finden.  `^'.$master['creaturename'].'`0 fordert einen sofortigen 
    Kampf von dir und dein eigener Stolz hindert dich daran, seine Forderung abzulehnen.');
    if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
        output('`n`nAls fairer Kämpfer gibt dir dein Meister vor dem Kampf einen Heiltrank.');
        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
    }
    $session['user']['reputation']-=2;
    if ($session['user']['seenmaster']==1) $session['user']['seenmaster']=2;
    //addnews("`3{$session['user']['name']}`3 wurde von Meister `^{$master['creaturename']}`3 wegen Überheblichkeit gejagt und gestellt.");
}

else if($_GET['op'] == 'change') { //Schlossrunden tauschen
    
    if($_GET['what'] == 'castleturns') {
        $session['user']['castleturns']--;
        $session['user']['turns']+=getsetting('castle_turns_wk',4);
        output('Du entschließt dich, die Zeit für das Schloss sinnvoller zu nutzen und erhältst '.getsetting('castle_turns_wk',4).' Waldkämpfe!');
    }    
    else {
        $session['user']['castleturns']++;
        $session['user']['turns']-=getsetting('wk_castle_turns',6);
        output('Nachdem du auf einige Minotauruspuppen eingeprügelt und dich im Umgang mit Feen geübt hast, bekommst du schließlich eine Schlossrunde!');
    }
    addnav('T?Zurück zum Trainingslager','train.php');
    addnav('Zurück zum Stadtzentrum','village.php');
}

else if($_GET['op'] == 'explore') //Erfahrung kaufen Start
{
    output('Du fragst deinen Meister `^'.$master['creaturename'].'`0 ob er nicht etwas Zeit hätte um dich privat im Kampf zu unterrichten.`n
    `^'.$master['creaturename'].'`0 überlegt kurz und macht dir dann ein Angebot:`n
    `2"Für 1 Edelstein trainiere ich mit Dir 10 Runden.`n
    Da es keine halben Edelsteine gibt werden Dich weniger als 10 Runden ebenfalls 1 Edelstein kosten."`0`n`n');
    if ($session['user']['turns'] < 1)
    {
        output("`n`n`4Leider bist du schon zu müde zum Trainieren.");
    }
    elseif ($session['user']['gems'] < 1)
    {
        output("`n`n`4Leider hast du nicht einen einzigen Edelstein in der Tasche.");
    }
    else
    {
        output("Wie lange willst du trainieren? `7(0 oder keine Eingabe = alle WK, auf volle 10 abgerundet)`n`0");
        output("<form action='train.php?op=explore2' method='POST'><input name='eround' id='eround'><input type='submit' class='button' value='Runden trainieren'></form>",true);
        JS::Focus('eround');
        addnav("","train.php?op=explore2");
    }
    addnav('T?Zurück zum Trainingslager','train.php');
    addnav('Zurück zum Stadtzentrum','village.php');
}
else if ($_GET['op'] == 'explore2') //Erfahrung kaufen
{
    $eround = abs((int)$_GET['eround'] + (int)$_POST['eround']);
    if ($session['user']['turns'] <= $eround || ($eround==0 && $session['user']['turns'] <= 10))
    {
        $eround = $session['user']['turns'];
    }
    elseif ($eround==0)
    {
        $eround=floor($session['user']['turns'] / 10) * 10;
    }
    $gems=ceil($eround/10);
    if($session['user']['gems']<$gems){
        output('`$Das kannst du dir nicht leisten!');
    }
    else{
        $session['user']['turns']-=$eround;
        $session['user']['gems']-=$gems;
        $rand1=e_rand(12,22);
        $rand2=e_rand(6,11);
        $exp = (($session['user']['level']*0.49)+2)*$rand1+$rand2;
        $totalexp = (int)($exp*$eround);
        $session['user']['experience']+=$totalexp;
        output($master['creaturename'].' nimmt dich hart ran, aber nach '.$eround.' Runden Training fühlst du dich '.($eround>0?'deutlich':'auch nicht').' erfahrener!`n
        `2Du hast `^'.$totalexp.'`2 Erfahrung bekommen!`n
        `0Du gibst '.$master['creaturename'].' den ausgemachten Lohn von `^'.$gems.' Edelstein'.($gems==1?'':'en').'`0.`n');
        debuglog('Hat für '.$gems.' ES das Kampftraining genutzt um Erfahrung zu sammeln');
/*
        admin_output('`nDebug:`nLevel*0.49+2= '.(($session['user']['level']*0.49)+2).'`n
        mal rand1= '.$rand1.'`n
        plus rand2= '.$rand2.'`n
        gesamt '.$exp.'`n
        ----------`n
        Soll: ca. Level*12 ('.($session['user']['level']*12).')`n',false);
*/
    }
    addnav('T?Zurück zum Trainingslager','train.php');
    addnav('Zurück zum Stadtzentrum','village.php');
}
if ($_GET['op']=='fight'){
    $battle=true;
}
if ($_GET['op']=='run'){
    output('`$Dein Stolz verbietet es dir, vor diesem Kampf wegzulaufen!`0');
    $_GET['op']='fight';
    $battle=true;
}
    
if($battle){
    if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!=''){
        $_GET['skill']='';
        
        if(!$bool_superuser_levelup)
        {
            $session['user']['buffbackup']=utf8_serialize($session['bufflist']);
        
            $session['bufflist']=array();
            output('`&Dein Stolz verbietet es dir, während des Kampfes Gebrauch von deinen besonderen Fähigkeiten zu machen!`0');
        }
    }
    if (!$victory)
    {
        include('battle.php');
    }
    if ($victory)
    {
        $search=array(    '%s',
                        '%o',
                        '%p',
                        '%X',
                        '%x',
                        '%w',
                        '%W'
                    );
        $replace=array(    ($session['user']['sex']?'sie':'ihn'),
                        ($session['user']['sex']?'sie':'er'),
                        ($session['user']['sex']?'ihr':'sein'),
                        ($session['user']['weapon']),
                        $badguy['creatureweapon'],
                        $badguy['creaturename'],
                        $session['user']['name']
                    );
        $badguy['creaturelose']=str_replace($search,$replace,$badguy['creaturelose']);

        output('`0`b`&'.$badguy['creaturelose'].'`0`b`n
        `b`$Du hast deinen Meister '.$badguy['creaturename'].' bezwungen!`0`b`n');
        
        increment_level();
        
        train_navi();
                    
        addnews('`%'.$session['user']['name'].'`3 hat '.($session['user']['sex']?'ihren':'seinen').' Meister `%'.$badguy['creaturename'].'`3 an '.($session['user']['sex']?'ihrem':'seinem').' `^'.ordinal($session['user']['age']).'`3 Tagesabschnitt besiegt und steigt auf Level `^'.$session['user']['level'].'`3 auf!!',0,1);
        $badguy=array();
        $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
        $sql='SELECT acctid2,turn FROM pvp WHERE acctid1='.$session['user']['acctid'].' OR acctid2='.$session['user']['acctid'];
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        if($row['acctid2']==$session['user']['acctid'] && $row['turn']==0){
            output('`n`6`bDu kannst die offene Herausforderung in der Arena jetzt nicht mehr annehmen.`b');
            $sql = 'DELETE FROM pvp WHERE acctid2='.$session['user']['acctid'].' AND turn=0';
            db_query($sql);
        }
        //$session['user'][seenmaster]=1;
    }
    else{
        if($defeat){
            addnews('`%'.$session['user']['name'].'`5 hat Meister '.$badguy['creaturename'].' herausgefordert und verloren!`n'.get_taunt(false),0,1);
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
            output('`0`b`&Du wurdest von `%'.$badguy['creaturename'].'`& besiegt!`0`b`n
            `%'.$badguy['creaturename'].'`$ hält vor dem vernichtenden Schlag inne und reicht dir stattdessen seine Hand, um dir auf die Beine zu helfen. Er verabreicht dir einen kostenlosen Heiltrank.`n
            `b`^'.get_taunt($badguy['creaturewin']).'`0`b`n');
            
            train_navi();
            
            //$session['user'][seenmaster]=1;
            if ($session['user']['seenmaster']!=2) 
            {
                $session['user']['seenmaster']=1;
            }
        }
        else
        {
            fightnav(false,false);
        }
    }
}

page_footer();
?>