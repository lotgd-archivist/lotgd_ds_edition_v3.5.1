<?php
require_once "common.php";
checkday();
addcommentary();

page_header("Der Kerker");
output("`c`b`ND`(e`)r `TKer`)k`(e`Nr`0`b`c`n");

music_set('kerker');

$sql3 = 'SELECT sentence FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
$res3 = db_query($sql3);
$row3 = db_fetch_assoc($res3);

if ($session['user']['imprisoned']!=0)
{ //Inhaftiert
        if ($session['user']['drunkenness']>90)
        {
                $session['user']['drunkenness']=90;
        }
        if ($session['user']['imprisoned']>0)
        {
                if ($row3['sentence']>0)
                {
                        $maxsentence=getsetting('maxsentence',5);
                        $lockup=getsetting('locksentence',4);
                        $session['user']['imprisoned']+=$row3['sentence'];
                        if ($session['user']['imprisoned']>$maxsentence)
                        {
                                $session['user']['imprisoned']=$maxsentence;
                        }
                        $sql = 'UPDATE account_extra_info SET sentence=0 WHERE acctid='.$session['user']['acctid'];
                        db_query($sql);
                }
                if($session['user']['alive']==0)
                { //falls man mit Vollstreckungsbescheid stirbt
                        output('`qIn einer Vision siehst du `$Ramius`q, welcher zu dir spricht: "`$Gehe in das Gefängnis! Begib dich direkt dorthin! Gehe nicht über das Totenreich und quäle keine '.$session['user']['gravefights'].' Seelen!`q"`n`n');
                        $session['user']['alive']=1;
                        $session['user']['hitpoints']=1;
                }
        }


        if ($_GET['op']=='')
        { //Eingang für Inhaftierte
                output('`TDicke, eiserne Gitterstäbe trennen dich von der Freiheit, doch aus welchen Gründen du auch hier gelandet bist, von nun an wirst du dich mit einer hölzernen Pritsche und dreckigen Wänden zufrieden geben müssen. ');
                if ($session['user']['imprisoned']>0)
                {
                        output('Zumindest für '.($session['user']['imprisoned']).' Tage wirst du hier deine Strafe absitzen müssen.`n');
                }
                else
                {
                        output('Doch du bist `bunbestimmte Zeit`b im Kerker.`n Wahrscheinlich hast du `bgegen die Regeln verstoßen`b oder eine `bMail zur Namensänderung ignoriert`b! Öffne zunächst durch einen Klick auf "Brieftauben" dein `bPostfach`b und sieh nach, ob du dort einen Grund findest. Falls nicht, schreib eine `bAnfrage`b.`n`n');
                }
                $indate = getsetting('gamedate','0005-01-01');
                $date = explode('-',$indate);
                $monat = $date[1];
                if($monat<4 || $monat>9){
                        output('`TDes Winters Kälte schwingt ihr Zepter. Im Kerker schmiegen sich die von der Feuchtigkeit moderden Kadaver dicht aneinander. Hungernde Kreaturen, die in der Dunkelheit verwesen. Ratten nagen das Aas von den Knochen. Für ein Geständnis ist es längst zu spät. Hier ist das Menschsein relativ bedeutungslos...`n');
                }
                output('`TEinzelne Wasserrinnsale bahnen sich ihren Weg durch das Mauerwerk, viele Kratzer kannst du in den Ziegeln sehen - verzweifelte Versuche anderer Häftlinge, die vor dir in dieser Zelle ihr Dasein schindeten. Auch die Pritsche besitzt unzählige Zeugnisse früherer Benutzung, die du aber nicht alle zuordnen vermagst ( oder magst... )! Das einzige Licht dringt vom Gang durch die dicken Gitterstäbe, denn auch an Fackeln hat man hier gespart und nur der Kerkermeister beansprucht ein wenig Wärme für sich. `n`n`)Du spürst, dass über diesem Kerker eine starke Aura liegt, die jegliche Magie unterbindet. Auch hat man dir gesagt, dass nur der Versuch zu zaubern, mit nicht weniger als `A10 Peitschenhieben`) belohnt wird!`n`n`T
                `TDir bleibt nichts übrig, als die Zeit, die dir auferlegt wurde, abzusitzen. Oder kommt dir etwas Anderes in den Sinn?`n`n');

                if ($session['user']['imprisoned']!=0)
                {
                        $session['user']['location'] = USER_LOC_PRISON;
                }

                addnav('Umsehen','prison.php?op=look');
                
                if ($session['user']['imprisoned']>0)
                {
                	addnav('Strafe absitzen','prison.php?op=wait');
                }
                
                if ($session['user']['imprisoned']>0)
                {
                        addnav('Freikaufen','prison.php?op=bribe');
                }
                if ($session['user']['imprisoned']>0)
                {
                        addnav('Ausbrechen','prison.php?op=flee');
                }
                if (($session['user']['imprisoned']>0) && ($session['user']['marks']>=31))
                {
                        addnav('Die Male zeigen','prison.php?op=chosen');
                }
                if ($session['user']['imprisoned']>0 && $session['user']['imprisoned']<getsetting('maxsentence',5))
                {
                        addnav('Inventar zeigen','prison.php?op=letter');
                }
                if ($session['user']['imprisoned']>0 && $session['user']['specialmisc']!='prisonmagic')
                {
                        addnav('Magie versuchen','prison.php?op=magic');
                }

                addnav('Zur Drachenbücherei','library.php');

                addnav('Schlafen (Logout)','login.php?op=logout&loc='.USER_LOC_PRISON,true);
                viewcommentary('prison','Flehen:',30,'fleht');
        }

}

else
{ //nicht Inhaftiert
        if ($_GET['op']=='')
        {
                output('`NW`(e`)ni`Tge, steinerne Treppen führen hinab zu einer Holztür, die mit massiven Eisen an mehreren Stellen beschlagen ist.
                Zu beiden Seiten der Tür sind zwei vereinzelte Fackeln an der Wand angebracht, die den Zugang zum Kerker erleuchten sollen.
                Jenseits dieser Tür erstreckt sich ein langer, gerader Gang, an dessen Seiten je rechts und links Zellen angeordnet sind.
                Durch die Gitterstäbe der Zellen mag der ein oder andere ein ihm bekanntes Gesicht erblicken; sofern er an dem Kerkermeister vorbei kommt, der mit strengen Blicken jeder Bewegung und jedem Geräusch nachgeht.
                Dieser Ort scheint wie geschaffen für Kriminelle, die dicken, eisernen Gitterstäbe sind unnachgiebig und der vorherrschende Gestank kommt sicher nicht nur von Lebenden.
                Auch der harte Steinboden macht einen wenig einladenden Eindruck und die dünnen Pritschen in den Zellen werden wohl nicht einmal für eine Drehung im Schlaf rei`)ch`(e`Nn.
                `n`n`)Du spürst, dass über diesem Kerker eine starke Aura liegt, die jegliche Magie unterbindet. Auch weißt du, dass dich allein der Versuch zu zaubern, schnell selbst hinter Gitter bringen könnte!`n`n`&');
                viewcommentary('prison','Verspotten:',30,'spottet');
                addnav('Umsehen','prison.php?op=look');
                addnav('Kaution zahlen','prison.php?op=free');
                addnav('Schutzhaft','prison.php?op=prot');
                if($access_control->su_check(access_control::SU_RIGHT_DEBUG))
                {
                        addnav('Münzspiel (SU)','prison.php?op=coin_game');
                }
                if ($row3['sentence']>0)
                {
                        addnav('Den Behörden stellen','prison.php?op=imprison');
                }
                addnav('Den Kerker verlassen','village.php');
        }
}

if ($_GET['op']=='prot')
{ //Schutzhaft Intro
        output('`TDer Kerkermeister mustert dich von oben bis unten, mit leicht spöttischem Blick. `)"Soso! Dir will man also an den Kragen... und du suchst hier Zuflucht?! Ha, du Narr! Kannst du gern haben, aber obwohl du nichts getan hast, wirst du behandelt werden, wie der ganze Abschaum hier unten! Denn meine Schicht ist gleich rum und meine Ablösung wird nicht wissen, wer was verbrochen hat und wer hier nur zu Gast ist...
        `nAußerdem wird dich der Spaß `#3 Edelsteine`) kosten... für die üppige Verpflegung, die du für eine Nacht genießen darf!"
        `n`n`TDas klingt ja toll. Aber bist du dir wirklich sicher?');
        addnav('Ok, Schutzhaft!','prison.php?op=prot2');
        addnav('Nee, dann nicht...','prison.php');
}

else if ($_GET['op']=='prot2')
{ //Schutzhaft
        if ($session['user']['gems']<3)
        {
                output('`T`nOh je! Du armer Schlucker bist sogar zu arm für den Kerker! Der Kerkermeister klopft sich lachend auf die Schenkel und scheucht dich kurz darauf aus dem dunklen Loch..`n`n');
                addnav('Zurück','prison.php');
        }
        else
        {
                output('`T`nDu zahlst drei Edelsteine und der Kerkermeister sperrt dich in eine Zelle. Hier wird dich niemand angreifen können!');
                $session['user']['gems']-=3;
                $session['user']['imprisoned']=1;
                addnav('In die Zelle','prison.php');
        }
}

else if ($_GET['op']=='imprison')
{ //denBehörden stellen Intro
        output('`TGegen dich liegt ein Haftbefehl vom hohen Gericht von '.getsetting('townname','Atrahor').' vor.`nWenn du dich jetzt stellst, um deine Schuld zu sühnen, wirst du für '.$row3['sentence'].' Tage den Kerker von der anderen Seite kennen lernen und in einer dreckigen Zelle leben.
        `nWillst du das wirklich tun?');
        addnav('Ja','prison.php?op=imprison2');
        addnav('Nein','prison.php');
}

else if ($_GET['op']=='imprison2')
{ //den Behörden stellen
        $maxsentence=getsetting('maxsentence',5);
        $lockup=getsetting('locksentence',4);
        $session['user']['imprisoned']+=$row3['sentence'];
        if ($session['user']['imprisoned']>$maxsentence)
        {
                $session['user']['imprisoned']=$maxsentence;
        }

        $sql = 'UPDATE account_extra_info SET sentence=0 WHERE acctid='.$session['user']['acctid'].'';
        db_query($sql);
        addnews('`#'.$session['user']['name'].'`^ hat sich dem Kerkermeister gestellt und eine '.$session['user']['imprisoned'].'tägige Haftstrafe angetreten.');
        redirect('prison.php');
}

else if ($_GET['op']=='wait')
{ //Strafe absitzen Intro
	
	if ($session['user']['imprisoned']==1)
    {
		output('`TDer Kerkermeister lacht dich aus: Du bist sowieso nur noch einen Tag hier - was gibt es da noch abzusitzen?!');
		addnav('Schade!','prison.php');
	}
	elseif($session['user']['imprisoned']>=getsetting('maxsentence',5)) {
		output('`TDer Kerkermeister lacht dich aus: Du hast offensichtlich zuviel ausgesfressen, um sofort wieder rauszukommen. Einen Tag wirst du dein Schicksal wohl auf jeden Fall ertragen müssen..');
		addnav('Schade!','prison.php');
	}
	else {
        output('`TDu hast hier die Möglichkeit dich deinem Schicksal zu ergeben und die dir auferlegte Strafe abzusitzen.`nDie abgesessenen Tage werden dabei deinen Straftagen hinzugefügt, du darfst den Kerker allerdings nach Ablauf dieses Tages verlassen. Möchtest du das?
        `n(Dies beschleunigt lediglich die Wartezeit für den Spieler, die eigentliche Haftzeit des Charakters verkürzt sich dadurch nicht!)');
        addnav('Ja, warten','prison.php?op=wait2');
        addnav('Nein','prison.php');
	}
}

else if ($_GET['op']=='wait2')
{ //Strafe absitzen
        $days=$session['user']['imprisoned'];
        $injail=$days-1;
        output('`TDu setzt dich auf deine Pritsche und lässt die Tage bis zu deiner Entlassung verstreichen...`nDeinen Straftagen wurden `#'.$injail.'`T Tage hinzugefügt.
        `nMorgen kommst du hier raus!`n');
        $session['user']['daysinjail']+=$injail;
        $session['user']['imprisoned']=1;
        $session['user']['age']+=$injail;
        addnav('Weiter','prison.php');
}

else if ($_GET['op']=='free')
{ //Freikaufen Übersichtsliste
        $sql = 'SELECT acctid,name,race,imprisoned,login,sex,level,laston,loggedin,activated FROM accounts WHERE imprisoned!=0 ORDER BY level DESC, dragonkills DESC, login ASC';
        $result = db_query($sql);
        $max = db_num_rows($result);

        // Rassen abrufen
        $arr_races = db_create_list(db_query('SELECT colname,id FROM races'),'id');

        $str_output='`TZu beiden Seiten des Ganges erstrecken sich die Zellen der Häftlinge, die du dir einzeln besiehst. Nun kannst du Großzügigkeit beweisen und dir einen der Häftlinge aussuchen, für den du gegen einen kleinen Aufpreis die Haft beenden kannst. Doch wen wirst du dir wählen?`n`n
        <table border="0" cellpadding="2" cellspacing="1" bgcolor="#999999">
        <tr class="trhead">
        <th>Level</th>
        <th>Name</th>
        <th>Rasse</th>
        <th><img src="./images/female.gif">/<img src="./images/male.gif"></th>
        <th>Status</th>
        <th>Strafe in Tagen</th>
        </tr>';

        for ($i=0; $i<$max; $i++)
        {
                $row = db_fetch_assoc($result);
                $loggedin=user_get_online(0,$row);
                $str_output.='<tr class="'.($i%2?'trdark':'trlight').'">
                <td>`^'.$row['level'].'`0</td>
                <td><a href="mail.php?op=write&to='.$row['acctid'].'" target="_blank" onClick="'.popup('mail.php?op=write&to='.$row['acctid']).';return false;"><img src="./images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>
                <a href="prison.php?op=free2&char='.$row['acctid'].'">`&'.$row['name'].'`0</a></td>
                <td>'.($arr_races[$row['race']]['colname']).'</td>
                <td align="center">'.($row['sex']?'<img src="./images/female.gif">':'<img src="./images/male.gif">').'</td>
                <td>'.($loggedin?'`#Wach`0':'`3Schläft`0').'</td>
                <td>'.($row['imprisoned']>0?$row['imprisoned']:'unbestimmt').'</td>
                </tr>';
                addnav('','prison.php?op=free2&char='.$row['acctid'].'');
        }
        output($str_output.'</table>');
        addnav('Zurück','prison.php');
}

else if ($_GET['op']=='free2')
{ //Freikaufen bestätigen
        $result = db_query('SELECT name,acctid,level,imprisoned FROM accounts WHERE acctid='.$_GET['char']);
        $row = db_fetch_assoc($result);
        $cost= abs($row['imprisoned'])*$row['level'];
        $lockup=getsetting("locksentence",4);

        if ($row['imprisoned']>0)
        {
                if ($row['imprisoned']<$lockup)
                {
                        output('`TDer Kerkermeister sieht dich mit prüfenden, scharfen Blicken an, als du ihm deinen Entschluss mitteilst. `)"So? Du willst also '.($row['name']).'`) aus diesem Loch rausholen? Kannst du gern haben, aber das wird nicht billig! Bezahl mir `#'.$cost.'`) Edelsteine und ich schließe die Tür auf!"
                        `n`TWillst du die Kaution bezahlen?');
                        addnav('Ja','prison.php?op=free3&gem='.$cost.'&char='.$row['acctid']);
                        addnav('NEIN!','prison.php');
                }
                else
                {
                        output('`TDer Kerkermeister sieht dich scharf an und schüttelt sofort missbilligend den Kopf. `)"Nein! '.($row['name']).'`) hat eine zu hohe Haftstrafe, um freigekauft zu werden!"`n`n');
                }
        }
        else if ($row['imprisoned']<0)
        {
                output('`TDer Kerkermeister sieht dich scharf an und schüttelt sofort missbilligend den Kopf. `)"Nein! '.($row['name']).'`) hat zu viel auf dem Gewissen, um freigekauft zu werden!"`n`n');
        }
        else output('Irgendetwas stimmt hier nicht');
        addnav('Weiter','prison.php');
}

else if ($_GET['op']=='free3')
{ //Freikaufen abschließen
        $result = db_query('SELECT name,acctid,imprisoned FROM accounts WHERE acctid='.$_GET['char']);
        $row = db_fetch_assoc($result);
        $cost=$_GET['gem'];
        if ($session['user']['gems']<$cost)
        {
                output('`TNa, das kannst du dir beim besten Willen nicht leisten...');
                addnav('Stimmt...','prison.php');
        }
        else
        {
                output ('`TDu drückst dem Kerkermeister '.$cost.' Edelsteine in die Hand und dieser macht sich sogleich daran, '.($row['name']).'s`T Zelle aufzuschließen, sodass dieser den Kerker endlich verlassen kann. Wieder frei! Du fühlst dich einfach... toll.');
                $session['user']['gems']-=$cost;
                user_update(
                        array
                        (
                                'imprisoned'=>0,
                                'location'=>0,
                                'prangerdays'=>0
                        ),
                        $row['acctid']
                );
                systemmail($row['acctid'],'`$Kaution bezahlt!`0','`@'.$session['user']['name'].'`& hat die Kaution für dich bezahlt und dich damit aus dem Kerker befreit. Du solltest dich dankbar erweisen!');
                addnews('`#'.$session['user']['name'].'`& hat `@'.$row['name'].'`& aus dem Kerker freigekauft!');
                $sql='INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"prison_ooc",'.$session['user']['acctid'].',": `@bezahlt die Kaution für '.db_real_escape_string($row['name']).'`@.")';
                db_query($sql);
                debuglog('bezahlte '.$cost.' ES Kaution für',$_GET['char']);
        }
        addnav('Weiter','prison.php');
}

else if ($_GET['op']=="look")
{ //Umsehen
        // Rassen abrufen
        $arr_races = db_create_list(db_query('SELECT colname,id FROM races'),'id');

        $sql = "SELECT        accounts.name,
                                        accounts.login,
                                        accounts.acctid,
                                        accounts.loggedin,
                                        accounts.laston,
                                        accounts.imprisoned,
                                        accounts.activated,
                                        accounts.expedition,
                                        accounts.level,
                                        accounts.sex,
                                        accounts.race,
                                        aei.html_locked
                                        FROM accounts
                                        INNER JOIN account_extra_info aei ON accounts.acctid=aei.acctid
                                        WHERE imprisoned!=0 ORDER BY level DESC, dragonkills DESC, login ASC";
        $result = db_query($sql);

        $str_output.='`TZur Zeit kannst du folgende Bürger Atrahors in den Zellen erblicken:`n`n
        <table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999">
        <tr class="trhead">
        <th>Level</th>
        <th>Name</th>
        <th>Rasse</th>
        <th><img src="./images/female.gif">/<img src="./images/male.gif"></th>
        <th>Status</th>
        <th>Strafe in Tagen</th>
        </tr>';
        $max = db_num_rows($result);
        for($i=0;$i<$max;$i++)
        {
                $row = db_fetch_assoc($result);
                $loggedin=user_get_online(0,$row);
                $str_output.='<tr class="'.($i%2?'trdark':'trlight').'">
                <td>`^'.$row['level'].'`0</td>
                <td>'. CRPChat::menulink( $row ).'</td>
                <td>'.$arr_races[$row['race']]['colname'].'</td>
                <td align="center">'.($row['sex']?'<img src="./images/female.gif">':'<img src="./images/male.gif">').'</td>
                <td>'.($loggedin?'`#Wach`0':'`3Schläft`0').'</td>
                <td>'.($row['imprisoned']>0?$row['imprisoned']:'unbestimmt').'</td>
                </tr>';
        }
        output($str_output.'</table>');
        viewcommentary('prison_ooc','Flehen:',30,'fleht',false,false);
        addnav('Zurück','prison.php');
}

else if ($_GET['op']=='chosen')
{ //die Male zeigen Intro
        output('`TDu weißt, dass die Götter den Missbrauch ihrer Geschenke nicht gern sehen und glaubst gehört zu haben, dass sie einen solchen Frevel ab und an mit dem Entzug eines der Male bestrafen.
        `nWillst du es wirklich riskieren, ein Mal zu verlieren, nur um jetzt hier raus zu kommen?');
        addnav('Ja','prison.php?op=chosen2');
        addnav('NEIN!','prison.php');
}

else if ($_GET['op']=='chosen2')
{ //die Male zeigen
        output('`TDu wartest, bis der Wächter wieder seine Runde dreht, räusperst dich laut und hältst ihm dabei demonstrativ deinen Arm mit den `)5 Malen`T unter die Nase. Kreidebleich und unter wortreicher Entschuldigung schließt er deine Zelle auf und lässt dich frei.`n');
        if (e_rand(1,3)==2)
        {
                output('`n`ADie Götter reagieren zornig auf das Ausnutzen der Male. Sie sehen es nicht ein, deine kriminellen Machenschaften zu decken und entziehen dir das `#Erdmal`A!
                `nSag nicht, man hätte dich nicht gewarnt...');
                if ($session['user']['marks'] & CHOSEN_BLOODGOD)
                {
                        systemmail($session['user']['acctid'],'`$Von : Blutgott!`0','`&Sterblicher!`nWisse dass sich der Blutgott nur mit jenen abgibt, die sich die Auserwählten nennen. Da du nun nicht mehr dazu gehörst, betrachte unseren Pakt als nichtig!');
                }
                $Char->setBit(CHOSEN_EARTH+CHOSEN_BLOODGOD,'marks',0);
        }
        else
        {
                output('`n`TDie Götter registrieren missbilligend deine Tat, lassen dich aber diesmal noch davon kommen. Denke aber daran, dass es schon beim nächsten Mal ganz anders ausgehen kann.');
        }
        $session['user']['imprisoned']=0;
        $session['user']['location']=0;
        debuglog('hat die Male gezeigt um aus dem Gefängnis zu kommen');
        $sql='INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"prison_ooc",'.$session['user']['acctid'].',": `@wird von der Wache frei gelassen.")';
        db_query($sql);
        addnav('Na also','prison.php');
}

else if ($_GET['op']=='bribe')
{ //Bestechen Intro
        $caution=($session['user']['imprisoned']*($session['user']['level']));
        output('`TDu wartest, bis der Wächter wieder seine Runde dreht und raschelst einmal ganz unauffällig mit deinem Edelsteinbeutel. Diese Geste wohl verstehend schaut dich der Wächter an und deutet dir ebenso unauffällig an, dass deine Freilassung wohl `#'.$caution.' `TEdelsteine kosten würde.
        `nNach reichlicher Überlegung und Prüfung deines Edelsteinvorrates triffst du eine Entscheidung...');
        if ($session['user']['gems']>= $caution)
        {
                addnav('Bestechen','prison.php?op=bribe2');
        }
        addnav('Die Sache vergessen','prison.php');
}

else if ($_GET['op']=='bribe2')
{ //Bestechen
        $caution=($session['user']['imprisoned']*($session['user']['level']));
        $lockup=getsetting('locksentence',4);
        $chance=e_rand(1,2);
        if ($session['user']['imprisoned']>=$lockup)
        {
                $chance=2;
        }
        if ($chance==1)
        {
                output('`TDer Wächter schaut in deine Zelle und beginnt plötzlich laut zu rufen: `)"Nein! Aber dich kenne ich doch! DU kannst es gewiss nicht gewesen sein... Es handelt sich um einen Irrtum! So warte, ich lasse dich frei!"
                `nEr öffnet die Türe und nimmt sich unauffällig die versprochenen Edelsteine. Du bist frei! ');
                $session['user']['gems']-=$caution;
                $session['user']['imprisoned']=0;
                $session['user']['location']=0;
                $sql='INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"prison_ooc",'.$session['user']['acctid'].',": `@wird von der Wache frei gelassen.")';
                db_query($sql);
                addnav('Raus hier!','village.php');
        }
        else
        {
                output('`TDer Wächter nimmt deine Edelsteine entgegen und geht grinsend seines Weges, natürlich ohne dich freizulassen... Der Schuft hat dich reingelegt!');
                $session['user']['gems']-=$caution;
                addnav('Mist!','prison.php');
        }
}

else if ($_GET['op']=='letter')
{ //Freibrief
        if (!$_GET['id'])
        {

                output('`TDu wartest, bist der Wärter wieder seine Runden dreht und räusperst dich laut. Nun, da du seine Aufmerksamkeit hast, kramst du in deinen Taschen und hältst dem Wärter folgendes unter die Nase:`n`n');

                $options = array('Zeigen'=>'letter');
                item_invent_set_env(ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_SEARCH);

                item_invent_show_data(item_invent_head(' owner='.$session['user']['acctid'].' AND showinvent>0 AND deposit1=0 AND deposit2=0 ',10),'`TIrgendwie scheinen deine Taschen ein Loch zu haben. Du findest nichts, was den Wärter interessieren könnte.',$options);

                addnav('Zurück','prison.php');
        }
        else
        {

                $row = item_get(' id='.(int)$_GET['id'],false);

                if ($row['tpl_id']!='frbrf')
                {
                        output('`TDer Wärter betrachtet sich dein '.$row['name'].' ganz genau und schaut dich mit ernster Miene an. Langsam streckt er den Zeigefinger seiner rechten Hand aus und führt ihn ebenso langsam an seine Schläfe, wo er mehrmals mit dem Finger dagegen tippt, bevor er seinen Rundgang fortsetzt.`n`n');
                        addnav('Das war wohl nix...','prison.php');
                }
                else
                {
                        output('`TDer Wärter erkennt sehr wohl, was du ihm mit diesem '.$row['name'].' deutlich machen willst und schließt die Türe zu deiner Zelle auf. Du übergibst ihm den '.$row['name'].' und er zerreist das Pergament in kleine Streifen. Dann verlässt du deine Zelle, da du ja nun wieder frei bist!');

                        item_delete(' id='.(int)$_GET['id']);

                        $session['user']['imprisoned']=0;
                        $session['user']['location']=0;
                        $sql='INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"prison_ooc",'.$session['user']['acctid'].',": `@wird vom Wächter frei gelassen.")';
                        db_query($sql);
                        addnav("Freiheit!","prison.php");
                }
        }
}

else if ($_GET['op']=='flee' || $_GET['op']=='flee2')
{ //Ausbrechen
        $lockup=getsetting('locksentence',4);
        if ($session['user']['turns']>0)
        {
                if ($session['user']['imprisoned']<$lockup)
                {
                        output('`TDu machst dich auf die Suche nach einem Weg in die Freiheit. Du rüttelst an den Gitterstäben, suchst nach Geheimtüren in der Wand, für einen winzigen Augenblick denkst du sogar an das stinkende Loch... aber dann kommt dir nur noch der älteste Trick der Welt in den Sinn und du wirfst dich schreiend vor Schmerz auf den Boden.`n');
                        $session['user']['turns']=max(0,$session['user']['turns']-2);
                        $chance=($_GET['chance']?$_GET['chance']:e_rand(1,6));
                        switch ($chance)
                        {
                        case 1 :
                        case 2 :
                                output('`TDoch außer einem spöttichen Lächeln deiner Mitgefangenen bringt dir das nichts.');
                                addnav('Na super...','prison.php');
                                break;
                        case 3 :
                        case 4 :
                        case 6 :
                                output('`TAls der Wärter kommt, um nach dir zu sehen, hältst du deinen hölzernen Napf bereit und versuchst ihn damit niederzuschlagen. Doch leider bist du nicht der Erste, der diese geniale Idee hatte und der Wächter schafft es leicht, dich zu überwältigen.`n');
                                if ($session['user']['imprisoned']<=$lockup)
                                {
                                        output('`TTja... deine Haftstrafe wurde soeben um `)1 Tag`T verlängert');
                                        $session['user']['imprisoned']+=1;
                                }
                                addnav('Sch...ade','prison.php');
                                break;
                        case 5 :
                                $bounty=$session['user']['imprisoned']*$session['user']['level']*50;

                                // Items verlieren
                                $lost_str = '';

                                $min_chance = item_get_chance();

                                $res = item_list_get(' prisonescloose = '.$min_chance.' AND owner='.$session['user']['acctid'].' AND deposit1=0 AND deposit2=0 ',
                                ' ORDER BY RAND() LIMIT 1',true,' name,vendor,id ' );

                                if (db_num_rows($res) > 0)
                                {
                                        $item = db_fetch_assoc($res);

                                        if ($item['vendor'] == 1 || $item['vendor'] == 3)
                                        {
                                                // Wenn bei Wanderhändler zu erwerben
                                                item_set(' id='.$item['id'], array('owner'=>ITEM_OWNER_VENDOR));
                                        }
                                        else
                                        {
                                                item_delete(' id='.$item['id']);
                                        }

                                        $lost_str = '`n`n`TWährend der Flucht fällt dir '.$item['name'].'`T aus der Tasche!';
                                        $lost_comment_str = '. `TDabei fiel '.$item['name'].'`T aus der Tasche';

                                }
                                // END items verlieren

                                output('`TAls sich dir der Wächter sorgenvoll nähert, nutzt du deine Chance und schlägst ihn mit deinem Holznapf nieder. Durch die offene Türe rennst du so schnell du kannst hinaus und versteckst dich erst einmal im Wald.
                                `n`n`TSpäter erfährst du, dass aufgrund deiner Flucht nun ein Kopfgeld in Höhe von '.$bounty.' Gold auf dich ausgesetzt ist...'.$lost_str);
                                $session['user']['imprisoned']=0;
                                $session['user']['location']=0;
                                $session['user']['bounty']+=$bounty;
                                $session['user']['reputation']-=10;
                                $bounty=$session['user']['bounty'];
                                addnews('`^'.$session['user']['name'].'`@ ist aus dem Kerker geflohen! Es steht nun ein Kopfgeld von `^'.$bounty.' Gold`@ aus!');
                                addcrimes('`^'.$session['user']['name'].'`@ ist aus dem Kerker geflohen! Es steht nun ein Kopfgeld von `^'.$bounty.' Gold`@ aus!');
                                $sql='INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"prison_ooc",'.$session['user']['acctid'].',": `@ist soeben geflohen'.$lost_comment_str.'!")';
                                db_query($sql);

                                addnav('Schnell weg...','forest.php');
                                break;
                        default:
                                output('`TDas ist ja mal ganz was Neues, hilft dir aber auch nicht weiter. Du bleibst hinter weiterhin Gittern.');
                        }
                }
                else
                {
                        output('`TAufgrund der Höhe deiner Haftstrafe hat man dich in den Hochsicherheitsbereich gebracht. Hier ist eine Flucht unmöglich, denn die Wache sind hier besonders vorsichtig und die Gitterstäbe besonders dick.');
                        addnav('Zurück','prison.php');
                }
                // höchst
        }
        else
        {
                output('`TSo gern du hier auch raus willst, du bist einfach zu müde für eine Flucht. Du kannst es morgen noch einmal versuchen.');
                addnav('Zurück','prison.php');
        }
}

else if ($_GET['op']=="magic")
{ //Zauber versuchen
        $sql='SELECT specname,usename FROM specialty WHERE specid='.$session['user']['specialty'];
        $row=db_fetch_assoc(db_query($sql));
        $row['usename']=$row['usename'].'uses';
        if($session['user']['specialtyuses'][$row['usename']]>0)
        {
                $session['user']['specialmisc']='prisonmagic'; //Zaubern ist nur einmal pro Haftstrafe nutzbar
                output('`TDu besinnst dich auf deine Fähigkeiten in '.$row['specname'].'`T und obwohl du weißt, dass Zaubern im Kerker verboten ist, riskierst du einen Versuch.`n`n');
                switch (e_rand(0,10)) {
                case 0: //Pfui!
                        output('`TNa das ging ja mächtig in die Hose, im wahrsten Sinne des Wortes! Statt deiner Zellentür öffnet sich dein Schließmuskel und du hast einen unübersehbaren Fleck in der Hose.`nNaja, wenigstens hast du hier genug Zeit, den zu entfernen...');
                        addnav('Urghs!','prison.php');
                        break;
                case 1: //Flucht klappt
                        redirect('prison.php?op=flee&chance=5');
                        break;
                case 3:
                case 5:
                case 7:
                case 9: //Auspeitschung
                        $anz=e_rand(10,25);
                        output('`TDer Wächter hat dich jedoch genau im Blick. Noch bevor du richtig anfangen kannst, kommt der Wächter in deine Zelle und bestraft dich mit `A'.$anz.' Peitschenhieben`T.`nDas hinterlässt natürlich hässliche Striemen, wodurch du 1 Charmepunkt verlierst.');
                        $session['user']['charm']--;
                        insertcommentary($session['user']['acctid'],': `5wird für den Versuch zu zaubern mit '.$anz.' Peitschenhieben bestraft.','prison_ooc');
                        addnav('Autsch!','prison.php');
                        break;
                default: //an den Pranger
                        output('`TDer Wächter hat dich jedoch genau im Blick. Umgehend wirst du abgeführt und als Abschreckung für die anderen Gefangenen an den Pranger gestellt.');
                        $session['user']['prangerdays']=1;
                        $session['user']['pqtemp']=$session['user']['name'].' hat versucht im Kerker zu zaubern.';
                        insertcommentary($session['user']['acctid'],': `%hat versucht zu zaubern und wird zur Abschreckung an einen dicken Eichenstamm auf dem Richtplatz gekettet.','prison_ooc');
                        addnav('Na toll...','pranger.php');
                        addnews("`^".$session['user']['name']."`% wurde an einem dicken Eichenstamm für 1 Tag angekettet.");
                        $prangerfrucht=getsetting('prangerfrucht',0);
                        if($prangerfrucht<10)
                        {
                                $prangerfrucht++;
                                savesetting('prangerfrucht',$prangerfrucht);
                        }
                        break;
                }
        }
        else
        {
                output('`TDu hast deine Spezialfähigkeiten für heute bereits aufgebraucht.');
                addnav('Schade...','prison.php');
        }
}
else if ($_GET['op']=="coin_game")
{
        switch($_GET['act'])
        {
                case 'play':
                        {
                                if(isset($_GET['submit']) == false)
                                {
                                        output('`0Prima! Dann erkläre ich Dir kurz wie das Spiel funktioniert.`n`n
                                        Ich habe in meiner Tasche zwölf Dublonen. Ihr sagt wahrscheinlich eher Goldmünzen dazu. Sie zeigen auf der einen Seite einen Drachen und auf der anderen Seite den Namen eurer Stadt, '.getsetting('townname','Atrahor').'. Diese Münzen werde ich gut vermischt vor Euch auf den Boden werfen und in zwei Stapel mit je sechs Münzen aufteilen. Da Ihr Euch zuvor die Augen verbunden habt, werdet Ihr nicht erkennen können welche Seite bei diesen Münzen oben liegt. Ich garantiere euch aber, dass insgesamt sechs Münzen den Drachen und die restlichen sechs '.getsetting('townname','Atrahor').' zeigen. Nun verlange ich folgendes von euch:`n
                                        Wendet die Münzen derart, dass im linken Stapel ebensoviele Münzen den Drachen nach oben zeigen lassen, wie im rechten Stapel. Gelingt Euch dies mit nur einem Versuch so seid ihr frei. Und denkt daran, dies ist kein Glücksspiel!`n
                                        `n
                                        `TOk denkst du dir. Verbundene Augen, zwei Stapel à sechs Münzen, in beiden sollen am Ende die gleiche Anzahl an Drachen oben zu sehen sein und zu Beginn zeigen sechs Münzen den Drachen und sechs Münzen die Stadt...das muss doch zu schaffen sein.`n
                                        Mit einem Stück Stoff verbindest Du dir die Augen und lauschst. Als du die Münzen klingen hörst, tastest du vorsichtig danach. Nun nur noch korrekt wenden und du bist frei!`n`n
                                        (Mit der Maus die Münzen auswählen die gedreht werden sollen. Zu drehende Münzen werden hervorgehoben. Ein Klick auf den Wenden Knopf schließt das Ganze ab!)');

                                        $arr_coins = array();

                                        $str_coins = '';
                                        $int_height_offset = 0;
                                        $int_max_1 = 6;
                                        $int_max_0 = 6;
                                        for($int_i = 0; $int_i<12; $int_i++)
                                        {
                                                $int_rand = mt_rand(0,1);
                                                ${'int_max_'.$int_i}--;
                                                if(${'int_max_'.$int_i} == 0)
                                                {
                                                        $arr_coins[$int_i] = ($int_rand == 1?0:1);
                                                }
                                                $arr_coins[$int_i]=mt_rand(0,1);
                                        }
                                        $str_coins = '
                                                <img src="./images/muenze_unknown.png" id="coin_1" style="position:absolute; width:64px; height:70px; top:17px; left:113px;">
                                                <img src="./images/muenze_unknown.png" id="coin_2" style="position:absolute; width:64px; height:70px; top:96px; left:132px;">
                                                <img src="./images/muenze_unknown.png" id="coin_3" style="position:absolute; width:64px; height:70px; top:147px; left:23px;">
                                                <img src="./images/muenze_unknown.png" id="coin_4" style="position:absolute; width:64px; height:70px; top:162px; left:100px;">
                                                <img src="./images/muenze_unknown.png" id="coin_5" style="position:absolute; width:64px; height:70px; top:16px; left:19px;">
                        <img src="./images/muenze_unknown.png" id="coin_0" style="position:absolute; width:64px; height:70px; top:79px; left:67px;">

                                                <img src="./images/muenze_unknown.png" id="coin_6" style="position:absolute; width:64px; height:70px; top:101px; left:493px;">
                                                <img src="./images/muenze_unknown.png" id="coin_7" style="position:absolute; width:64px; height:70px; top:20px; left:459px;">
                                                <img src="./images/muenze_unknown.png" id="coin_8" style="position:absolute; width:64px; height:70px; top:171px; left:418px;">
                                                <img src="./images/muenze_unknown.png" id="coin_9" style="position:absolute; width:64px; height:70px; top:135px; left:330px;">
                                                <img src="./images/muenze_unknown.png" id="coin_10" style="position:absolute; width:64px; height:70px; top:75px; left:399px;">
                                                <img src="./images/muenze_unknown.png" id="coin_11" style="position:absolute; width:64px; height:70px; top:32px; left:346px;">

                                                '.JS::event('#coin_0','click','switch_coin(0)').'
                                                '.JS::event('#coin_1','click','switch_coin(1)').'
                                                '.JS::event('#coin_2','click','switch_coin(2)').'
                                                '.JS::event('#coin_3','click','switch_coin(3)').'
                                                '.JS::event('#coin_4','click','switch_coin(4)').'
                                                '.JS::event('#coin_5','click','switch_coin(5)').'
                                                '.JS::event('#coin_6','click','switch_coin(6)').'
                                                '.JS::event('#coin_7','click','switch_coin(7)').'
                                                '.JS::event('#coin_8','click','switch_coin(8)').'
                                                '.JS::event('#coin_9','click','switch_coin(9)').'
                                                '.JS::event('#coin_10','click','switch_coin(10)').'
                                                '.JS::event('#coin_11','click','switch_coin(11)').'
                                        ';
                                        $session['user']['pqtemp'] = utf8_serialize($arr_coins);
                                        rawoutput(
                                        '
                                                '.JS::encapsulate('
                                                        arr_coins_to_turn = new Array();
                                                        function switch_coin(id)
                                                        {
                                                                if(arr_coins_to_turn[id] === undefined || arr_coins_to_turn[id] == 0)
                                                                {
                                                                        arr_coins_to_turn[id]=1;
                                                                        //document.getElementById("coin_"+id).style["border"]="1px solid red";
                                                                        document.getElementById("coin_"+id).src="./images/muenze_unknown_highlight.png";
                                                                }
                                                                else
                                                                {
                                                                        arr_coins_to_turn[id]=0;
                                                                        //document.getElementById("coin_"+id).style["border"]="0px";
                                                                        document.getElementById("coin_"+id).src="./images/muenze_unknown.png";
                                                                }
                                                        }

                                                        function save_arr_coins_to_turn()
                                                        {
                                                                document.getElementById("arr_coins_to_turn").value = arr_coins_to_turn;
                                                        }
                                                ').'
                                                <div style="width:600px; height:300px; position:relative;">'.
                                                        form_header('prison.php?op=coin_game&act=play&submit=1','POST',true, '', 'save_arr_coins_to_turn();').
                                                        $str_coins.
                                                        '<center style="position:absolute; bottom:0px; width:100%;"><input type="submit" value="Gewählte Münzen wenden !" /></center>
                                                        <input type="hidden" name="arr_coins_to_turn[]" id="arr_coins_to_turn">
                                                </div>
                                        '
                                        );
                                }
                                //Formular abgeschickt, prüfen auf Lösung
                                else
                                {
                                        $arr_coins_to_turn = $_POST['arr_coins_to_turn'];
                                        $arr_coins = utf8_unserialize($session['user']['pqtemp']);

                                        //Temp wieder löpschen, brauchen wir nicht mehr
                                        $session['user']['pqtemp'] = '';

                                        //Werte des Arrays switchen wenn sie sich auch im POST Array befinden
                                        foreach ($arr_coins_to_turn as $key=>$val)
                                        {
                                                if($val == 1)
                                                {
                                                        $arr_coins[$key] = ($arr_coins[$key] == 1 ? 0 : 1);
                                                }
                                        }

                                        $str_coins = '';
                                        $arr_left = array_slice($arr_coins,0,6);
                                        $arr_right = array_slice($arr_coins,6,6);

                                        $str_img_a = 'muenze_1.png';
                                        $str_img_b = 'muenze_2.png';

                                        $str_coins = '
                                                <img src="./images/'.($arr_coins[0]==1?$str_img_a:$str_img_b).'" id="coin_1" style="position:absolute; width:64px; height:70px; top:17px; left:113px;">
                                                <img src="./images/'.($arr_coins[1]==1?$str_img_a:$str_img_b).'" id="coin_2" style="position:absolute; width:64px; height:70px; top:96px; left:132px;">
                                                <img src="./images/'.($arr_coins[2]==1?$str_img_a:$str_img_b).'" id="coin_3" style="position:absolute; width:64px; height:70px; top:147px; left:23px;">
                                                <img src="./images/'.($arr_coins[3]==1?$str_img_a:$str_img_b).'" id="coin_4" style="position:absolute; width:64px; height:70px; top:162px; left:100px;">
                                                <img src="./images/'.($arr_coins[4]==1?$str_img_a:$str_img_b).'" id="coin_5" style="position:absolute; width:64px; height:70px; top:16px; left:19px;">
                        <img src="./images/'.($arr_coins[5]==1?$str_img_a:$str_img_b).'" id="coin_0" style="position:absolute; width:64px; height:70px; top:79px; left:67px;">

                                                <img src="./images/'.($arr_coins[6]==1?$str_img_a:$str_img_b).'" id="coin_6" style="position:absolute; width:64px; height:70px; top:101px; left:493px;">
                                                <img src="./images/'.($arr_coins[7]==1?$str_img_a:$str_img_b).'" id="coin_7" style="position:absolute; width:64px; height:70px; top:20px; left:459px;">
                                                <img src="./images/'.($arr_coins[8]==1?$str_img_a:$str_img_b).'" id="coin_8" style="position:absolute; width:64px; height:70px; top:171px; left:418px;">
                                                <img src="./images/'.($arr_coins[9]==1?$str_img_a:$str_img_b).'" id="coin_9" style="position:absolute; width:64px; height:70px; top:135px; left:330px;">
                                                <img src="./images/'.($arr_coins[10]==1?$str_img_a:$str_img_b).'" id="coin_10" style="position:absolute; width:64px; height:70px; top:75px; left:399px;">
                                                <img src="./images/'.($arr_coins[11]==1?$str_img_a:$str_img_b).'" id="coin_11" style="position:absolute; width:64px; height:70px; top:32px; left:346px;">
                                        ';

                                        $arr_count_left = array_count_values($arr_left);
                                        $arr_count_right = array_count_values($arr_right);

                                        output('`0Hmm, na dann schaun wir mal ob du das Rätsel gelöst hast...`n`n');

                                        if($arr_count_left[1] == $arr_count_right[1] || $arr_count_left[0] == $arr_count_right[0])
                                        {
                                                output('Ich bin beeindruckt. War das Glück oder Können? Nun, in jedem Falle werde ich deine Kaution hinterlegen, so dass Du schon bald wieder frei sein wirst. Versprochen ist versprochen!`n
                                                `TMit diesen Worten steht dein Wohltäter auf, pfeifft dem Gefängniswärter zu, der auch seltsamerweise ziemlich flott angerannt kommt und flüstert diesem erneut etwas ins Ohr. Ehe du dich überhaupt noch einmal an den Namen erinnern kannst, stehst du auch schon wieder auf dem Stadtplatz und bist frei!');
                                                addnav('Juchuu, frei!','village.php');

                                                //Ziehen wir dem User als Belohnung 5 Kerkertage ab
                                                $session['user']['imprisoned'] = max(0,$session['user']['imprisoned']-5);
                                                debuglog('Münzen Rätsel im Gefängis gelöst und dadurch Freigang erhalten');
                                        }
                                        else
                                        {
                                                output('Tja, da hast du dich wohl leider verschätzt. Tut mir leid, aber ich bleibe meinen Prinzipien stets treu. Rätsel nicht gelöst, keine Kaution.`n
                                                `TMit diesen Worten steht der Herr auf und ehe du dich überhaupt noch einmal an seinen Namen erinnern kannst, ist dieser auch schon wieder verschwunden.');
                                                addnav('Mist!','prison.php');
                                        }

                                        rawoutput(
                                        '
                                                <div style="width:600px; height:300px; position:relative;">'.
                                                        $str_coins.
                                                '</div>
                                        '
                                        );
                                }
                                break;
                        }
                default:
                        {
                                output('`TDu dämmerst bereits eine Weile vor dich hin, als mit einem Male ein sehr wohl gekleideter Herr durch das Gefängnis stolziert, dem Wärter ein paar Worte ins Ohr flüstert und auf deine Zelle zeigt. Dieser schüttelt zwar zunächst energisch den Kopf, doch nach ein paar überzeugenden kleinen Goldmünzen läuft er zielstrebig auf dich zu und grunzt nur `0Besuch für dich!`T`n".
                                                Der Mann kommt kommt auf Dich zu, setzt sich vor deinen Gitterstäben auf den Boden und stellt sich als Kongrith Amrolion vor, fahrender Händler und Wohltäter. Und was er Dir vorschlägt klingt ganz erstaunlich:`n
                                                `0Ich grüße Dich! Ab und an mache ich mir dir Freude und bezahle für einen armen Tor die Kaution. Natürlich nicht ganz ohne Gegenleistung. Du müsstest zuvor mit mir spielen, ein kleines Rätsel lösen. Was sagst Du?`n
                                                `TDu überlegst kurz und antwortest dann:`n`n'.
                                                create_lnk('Ja, warum eigentlich nicht?`n','prison.php?op=coin_game&act=play',true,true,'',false,'Ja, spielen wir!').
                                                create_lnk('Nein danke, das muss nicht sein?`n','prison.php',true,true,'',false,'Nein, danke!'));
                        }
        }
}

page_footer();
?>