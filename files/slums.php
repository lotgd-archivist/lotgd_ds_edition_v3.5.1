<?php
//Slums des Dorfes
//von Anubis92

require_once('common.php');
checkday();
addcommentary();
switch($_GET['op'])
{
        case 'stat':
        {
        	page_header('Die Statuen');
                output('`b`c`mD`Ui`ue `YStat`uu`Ue`mn`0`c`b`n
                `mZu `Ube`uid`Yen Seiten einer abgelegenen Gasse stehen im Zwielicht steinerne Abbilder einflussreicher Gesetzesloser, die in '.getsetting('townname','Atrahor').' ihr Unwesen getrieben haben.
                Bedrohlich schaut Sarazene auf den Weg herab, ebenso wie Harziel und Fiko.
                Doch nun kümmert sich niemand mehr um die aus Sandstein gefertigten Statuen, sodass sie verdreckt und schmutzig sind, wie die ganze Gegend, in der sie aufgestellt worden sind.
                Dass die Obrigkeit '.getsetting('townname','Atrahor').'s diese Verehrung großer Verbrecher nicht gutheißen würde, liegt wohl auf der Hand, doch da keiner der Stadtwachen diese Gassen aufsucht, wird der Fürst nicht darüber in Kenntnis gesetzt.
                `n`nObwohl es wenig helfen wird, könntest du die verdreckten Statuen putzen, um selbst ein bisschen Seelenheil zu er`ufa`Uhr`men ...');
                addnav('Statuen');
                if($session['user']['turns']>0) addnav('p?Die Statuen putzen','slums.php?op=putzen');
                addnav('u?Weiter umsehen','slums.php?op=suchen');
                break;
        }
        case 'putzen':
        {
        		page_header('Statuen putzen');
                $session['user']['turns']--;
                output('`YDu putzt stundenlang die Statuen bis sie glänzen. ');
                $event = e_rand(1,12);
                if ($event==1)
                {
                        addnews($session['user']['name'].' `0wurde von `7R`&a`7m`&i`7u`&s`0 gesegnet!');
                        $session['user']['deathpower']++;
                        $session['user']['reputation']++;
                        output('`YDa erscheint Ramius vor dir: `n`m"Als Dank für deine Mühe, diese Statuen zu putzen, schenke ich dir einen Gefallen! Durch meinen Segen wird auch dein Ansehen steigen!"');
                }
                elseif ($event==2)
                {
                        $session['user']['attack']++;
                        output('`YNach einer Weile erscheint der `4Blutgott `Yund spricht: `n`m"Du hast diese Statuen mühevoll geputzt und dafür schenke ich dir einen Angriffspunkt!"');
                }
                elseif ($event==3)
                {
                        addnews($session['user']['name'].' `0wurde von `7Straßenräuber Sarazene`0 gemeuchelt!');
                        $session['user']['maxhitpoints']--;
                        if($session['user']['gems']>20) $session['user']['gems']-=2;
                        killplayer(100,0,0,'shades.php','Zu den Schatten');
                        output('`n`YAuf einmal wird es dunkel...`n`4Der Räuber `$Sarazene`4 hat dich hinterrücks erschlagen und ausgeraubt!');
                }
                elseif ($event==4)
                {
                        $session['user']['maxhitpoints']++;
                        output('`YNach einer Weile erscheint der `4Blutgott`Y und spricht zu dir: `n`m"Du hast diese Statuen mühevoll geputzt und dafür werde ich dich mit Lebenskraft segnen!"');
                }
                elseif ($event==5)
                {
                        $session['user']['attack']--;
                        if($session['user']['attack']<1) $session['user']['attack']=1;
                        output('`YPlötzlich passiert es: Du machst einen riesigen Kratzer in die Statue. Genau in diesem Moment erscheint der `4Blutgott`Y: `n`m"Du hast diese Statuen beschädigt, Frevler! Dafür strafe ich dich und nehme Dir einen Angriffspunkt!"');
                }
                else output('`YGerade als du fertig bist und dich zum Gehen wendest, landet eine Taube auf einer der Statuen und verziert sie mit einem Klecks.');
                break;
        }
        case 'suchen':
        {
                page_header('Der Bettler');
                output('`ZAls du dich weiter umschaust siehst du einen alten Bettler...
                Du kennst ihn aus deiner Jugend, er hat dir damals immer Heldengeschichten erzählt und du hast geträumt ein ebensolcher Held zu werden...
                Ob er wohl heute Geschichten von dir erzählt?
                Aber du denkst nicht weiter darüber nach, als du bemerkst, dass er erblindet und stumm ist.
                Arme Seele...
                `nDu wirfst ihm ein paar Goldstücke hin und gehst deines Weges.');
                $session['user']['gold']*=0.95;
                break;
        }
        case 'fields':
        {
                page_header('Obdachlosen-Asyl');
                if($_GET['act']=='pray')
                {
                        output('`eDu sprichst ein kleines Nachtgebet. Vielleicht sind die Götter dir ja wohlgesonnen.
                        `nZufrieden schläfst du ein...`n
                        '.JS::encapsulate('
                        function redirect(){
                                window.location.href = "login.php?op=logout";
                        }
                        window.setTimeout(\'redirect()\', 5000);
                        '));
                        addnav('','login.php?op=logout');
                        if ($session['user']['pvpflag']!=PVP_IMMU)
                        {
                                $pvpflag = date('Y-m-d H:i:s',strtotime('+30 minutes'));
                                user_update(
                                        array
                                        (
                                                'pvpflag'=>$pvpflag
                                        ),
                                        $session['user']['acctid']
                                );
                        }
                }
                else
                {
                        output('`c`b`ND`(a`)s As`(y`Nl`0`b`c`n
                        `NAn `(ei`)ne`7m der Häuser hängt ein verwittertes Schild, auf dem mit verblichener Farbe `mAsyl`7 prangt.
                        Pater Elder unterhält dieses heruntergekommene Haus, in dem die armen Schlucker die Möglichkeit einer trockenen Übernachtung bekommen.
                        Doch der Gestank und der Schmutz der Gasse finden sich auch hier wieder und der übermüdete Pater scheint nicht die Kraft zu haben, hier immer für Ordnung zu sorgen.
                        Jedem, der eintritt, weißt er einen Platz auf dem Boden zu und verteilt mottenzerfressene Decken.
                        Doch für die Anwesenden scheint dies immer noch Luxus zu sein.
                        Fraglich scheint aber zu sein, ob man in dieser Gegend wirklich beruhigt schlafen kann oder ob Nachtwachen nicht besser wären...
                        Doch keiner von den Anwesenden scheint dazu noch in der Lage `)zu `(se`Nin.');
                        addnav('Nachtgebet','slums.php?op=fields&act=pray');
                }
                addnav('L?Einschlafen (Logout)','login.php?op=logout');
                break;
        }
        case 'oldhouse':
        {
                page_header('Das verlassene Haus');
                output('`c`b`TD`Ya`}s `Iv`terlassene `IH`}a`Yu`Ts`0`b`c`n
                `tG`Ia`}n`Yz a`Tm Ende einer Gasse steht abseits der anderen Häuser das wohl trostlosestes Gebäude, dass es in '.getsetting('townname','Atrahor').' gibt.
                Die Türen sind notdürftig mit Brettern vernagelt, doch nicht einmal dabei hat man sich wirklich Mühe gegeben, da jeder Handgriff an diesem Haus Verschwendung wäre.
                Die Fensterläden hängen, sofern sie überhaupt noch vorhanden sind, lose in den Angeln und quietschen nur ab und an, wenn ein Sturm aufkommt.
                Die halbherzige Arbeit der Männer erleichtert das Betreten des heruntergekommenen Hauses ungemein.');
                $sql='SELECT * FROM commentary WHERE section=\'oldhouse\' ORDER BY commentid DESC LIMIT 1';
                $row = db_fetch_assoc(db_query($sql));
                if ($row['postdate']>=$session['user']['recentcomments']) output('Ein paar Gauner und Ganoven haben dies ebenfalls genutzt und nutzen nun ein paar alte Möbel für ein kleines, Wärme spendendes Lager`Yfe`}u`Ie`tr.`n`n');
                else output('Ein paar verstaubte Möbel lassen das Innere nicht ganz so verlassen wie die Fassade wirken, doch man merkt schnell, dass lange niemand hi`Yer `}w`Ia`tr.`n`n');
                viewcommentary('oldhouse','Sprechen:');
		addnav('K?In den Keller','lowercity.php?op=keller');
                break;
        }
        case 'pest':
        {
                page_header('Ein Bettler');
                output('`7Ein Bettler zupft dir am Ärmel: `u"'.($session['user']['sex']?'Edle Dame':'Edler Herr').', ich habe Hunger! Bitte gebt mir ein Goldstück! Nur ein einziges Goldstück!"`n');
                if($session['user']['gold']>0)
                {
                        if($session['user']['reputation']>15)
                        {
                                output('`7Du hast heute einen großzügigen Tag und wirfst dem Bettler ein Goldstück hin.');
                                $session['user']['gold']--;
                        }
                        else output('`Z"Lass mich in Ruhe, elender Wurm!" `7fährst du ihn an.');
                }
                else output('`7Aber da du selbst kein Gold hast, gehst du einfach weiter.');

                if($_GET['pest']==1)
                {
                        $count = item_count( ' tpl_id="fldtoten" AND owner='.$session['user']['acctid'] );
                        if ( $count==0 )
                        {
                                output('`n`n`$Wenig später merkst du, dass du mit der Beulenpest angesteckt wurdest!');
                                $row = item_get_tpl( ' tpl_id="fldtoten"' ); //Fluch der Toten
                                $row['tpl_gems']=10;
                                $row['tpl_hvalue']=6;
                                $row['tpl_name']='Der schwarze Tod';
                                $buffs .= ($row['buff1'] > 0 ? ','.$row['buff1'] : '');
                                $buffs .= ($row['buff2'] > 0 ? ','.$row['buff2'] : '');
                                item_set_buffs('newday',$buffs);
                                item_add( $session['user']['acctid'], 0, $row );
                                addnews('`%'.$session['user']['name'].'`t wachsen Brüste am Hals!`0');
                        }
                }
                break;
        }
        default:
        {
                if(!$bool_comment_written && e_rand(1,15)==5)
                {
                        redirect('slums.php?op=pest');
                }
                page_header('Die dunkle Gasse');
                   output('`c`b`ND`(i`)e `7dunkle Ga`)s`(s`Ne`0`b`c`n
                `NIm `(et`)wa`7s abgelegen Teil des Wohnviertels werden die Straßen immer dreckiger; die Häuser sind heruntergekommen und werfen schroffe Schatten auf die kaum noch beleuchteten Straßen.
                Niemand kümmert sich hier um ein gepflegtes Aussehen der Gassen.
                Auf dem Kopfsteinpflaster siehst du allerlei Essenreste und Ratten, die sich hier tummeln und einen unangenehmen Geruch verbreiten.
                Scheinbar haben sich hier die Verarmten und Gesetzeslosen Atrahors zusammengerottet, denn nur zwielichtige Gestalten, zerlumpte Stromer und verdächtig Vermummte treiben sich in diesen Gassen herum.
                Hier, wo die Stadtwachen kaum patrouillieren, liegt Tod und Krankheit in d`)er `(Lu`Nft.

                `n`nAn diesem Ort gibt es offenbar selbst für die Ausgestoßenen ein paar Anziehungspunkte...`n`n');
                
                
                viewcommentary('slums','Sprechen:',15);
                addnav('Die dunkle Gasse');
                addnav('T?Josés Taverne','tittytwister.php');
                addnav('Verlassenes Haus','slums.php?op=oldhouse');
                addnav('Die Gerber','tanner.php');
                addnav('');
                addnav('Asyl "Dief Elder"','slums.php?op=fields');
                addnav('S?Zu den Statuen','slums.php?op=stat');

                if($session['user']['dragonkills']>1)
                {
                        addnav('Ein Häufchen Elend','slums.php?op=pest&pest=1',false,false,false,false);
                }
                break;
        }
}
if($session['user']['alive'])
{
        addnav('Zurück');
        if ($_GET['op']) addnav('G?Zur dunklen Gasse','slums.php');
        addnav('W?Zum Wohnviertel','houses.php');
        addnav('d?Zum Stadtzentrum','village.php');
        addnav('M?Zum Marktplatz','market.php');
}
page_footer();
?>