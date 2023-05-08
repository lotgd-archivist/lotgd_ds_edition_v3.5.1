<?php
/**
* Das tolle Flirtevent jetzt auch für die Damen ;)
* @author Maris und Dragonslayer, Modifikation von Sa onserei und Eleya, die Gleichberechtigung wollten *g*
*/

// erstmal hierhin, kann später woanders hin geschoben werden (ggf in die DB, wenn es einen Editor geben soll)

$arr_user=user_get_aei('cname,ctitle');
$cname=$arr_user['cname'];
$ctitle=$arr_user['ctitle'];
if ($cname=='')
{
    /** @noinspection PhpUndefinedVariableInspection */
    $cname=$session['user']['login'].'`0';
}
if ($ctitle=='')
{
	$ctitle=$session['user']['title'].'`0';
}

$part[1][1]='Seid gegrüßt, edler Herr! ';
$part[1][2]='Mein Herr, ich habe die Ehre. ';
$part[1][3]='Verzeiht, schöner Mann. ';
$part[1][4]='Ich... ähm... grüße Euch. ';
$part[1][5]='Guten Tag, junger Herr! ';
$part[1][6]='Es ist mir eine Ehre. ';
$part[1][7]='Hallo! ';
$part[1][8]='Huhu. ';
$part[1][9]='Hallöchen ';
$part[1][10]='*hüstel* ';
$part[1][11]='Na... ';
$part[1][12]='Oh, hallooo! ';
$part[1][13]='He da, Süßer! ';
$part[1][14]='*wimperklimper*' ;
$part[1][15]='Hey, Schönling! ';
$part[1][16]='Ist es heiß hier drin, oder seid Ihr das? ';

$part[2][1]='Erlaubt mir mich vorzustellen: ';
$part[2][2]='Zu Euren Diensten: ';
$part[2][3]='Man nennt mich ';
$part[2][4]='Mein Name ist ';
$part[2][5]='Ich höre auf den Namen ';
$part[2][6]='Vor Euch steht ';
$part[2][7]='Hier spricht ';
$part[2][8]='Ich bin die ';
$part[2][8]='Isch bin ';
$part[2][9]='Ja, also... ähm... ';
$part[2][10]='Sperr die hübschen Öhrchen auf, ich bin ';
$part[2][11]='Ihr werdet es nicht für möglich halten, aber ich bin es wirklich: ';
$part[2][12]='Jetzt mal hergehört. Hier ist ';
$part[2][13]='Eure Traumfrau stellt sich vor: ';
$part[2][14]='Hier ist der Grund Eurer schlaflosen Nächte: ';
$part[2][15]='Entschuldige, aber in Eurer Gegenwart bin ich unheimlich nervös. Ich habe sogar vergessen, wie ich Euch ansprechen wollte: ';
$part[2][16]='Ich bin keine Frau für eine Nacht, aber ein, zwei Stunden hätte ich schon Zeit. Ich bin ';

$part[3][1]= $cname;
$part[3][2]= 'Donna '.$cname;
$part[3][3]= 'Frau '.$cname;
$part[3][4]= 'Lady '.$cname;
$part[3][5]= 'Meisterin '.$cname;
$part[3][6]= 'Ehrenwerte '.$cname;
$part[3][7]= 'Circe '.$cname;
$part[3][8]= 'Edle '.$cname;
$part[3][9]= 'Hungrige '.$cname;
$part[3][10]= 'Großmeisterin '.$cname;
$part[3][11]= 'Taugenichts '.$cname;
$part[3][12]= 'verständnisvolle '.$cname;
$part[3][13]= 'Tante '.$cname;
$part[3][14]= 'Meretrix '.$cname; //'Die Dirne (häufigste Übersetzung für Meretrix) war in erster Linie eine unverheiratete Frau, die Sex mit mehreren Männern hatte.'
$part[3][15]= 'Maid '.$cname;
$part[3][16]= 'Hässliche '.$cname;

$part[4][1]=', und ich bin eine '.$ctitle.'`@.`n';
$part[4][2]=', die '.$ctitle.'`@.`n';
$part[4][3]=', eine stolze '.$ctitle.'`@.`n';
$part[4][4]='...`n';
$part[4][5]=', die Romantikerin.`n';
$part[4][6]=', '.$ctitle.'`@ aus Leidenschaft.`n';
$part[4][7]=', '.$ctitle.'`@ und Heldin, zu Euren Diensten.`n';
$part[4][8]=', die tüchtigste '.$ctitle.'`@ des Landes!`n';
$part[4][9]=', '.$ctitle.'`@ ohne Furcht und Tadel.`n';
$part[4][10]=', eine kleine '.$ctitle.'`@ mit großem Herzen!`n';
$part[4][11]=', der freundliche Geist.`n';
$part[4][12]=', eine betrunkener '.$ctitle.'`@.`n';
$part[4][13]=', das größte Flittchen '.getsetting('townname','Atrahor').'s!`n';
$part[4][14]=', der Engel auf Erden.`n';
$part[4][15]=', die willige '.$ctitle.'`@.`n';
$part[4][16]=', arm und hässlich, aber voller Amourrrrrrr.`n';

$part[5][1]='Man ehrt mich ';
$part[5][2]='Im ganzen Land bin ich bekannt ';
$part[5][3]='Die ganze Stadt kennt meinen Namen ';
$part[5][4]='Ich bin berühmt ';
$part[5][5]='Ich bin berüchtigt ';
$part[5][6]='Männer pfeifen mir nach ';
$part[5][7]='Man fürchtet mich ';
$part[5][8]='Jeder kennt mich ';
$part[5][9]='Ich bin ständig im Gespräch ';
$part[5][10]='Man achtet mich ';
$part[5][11]='Manch einer beneidet mich ';
$part[5][12]='Ich bin einzigartig ';
$part[5][13]='Ich werde Euch stets in Erinnerung bleiben ';
$part[5][14]='Oft werde ich belächelt ';
$part[5][15]='Häufig komme ich in peinliche Situationen ';
$part[5][16]='Man jagt mich ';

$part[6][1]='wegen meiner Herzensgüte.`n';
$part[6][2]='wegen meines Heldenmutes.`n';
$part[6][3]='dafür, dass so ich verlässlich bin.`n';
$part[6][4]='wegen meines freundlichen Lächelns.`n';
$part[6][5]='aufgrund meines Reichtums.`n';
$part[6][6]='weil ich nicht lügen kann.`n';
$part[6][7]='wegen meinem guten Ruf.`n';
$part[6][8]='dafür, dass ich alles mit mir machen lasse.`n';
$part[6][9]='aufgrund meiner Fingerfertigkeiten.`n';
$part[6][10]='weil ich immer die Haare schön hab.`n';
$part[6][11]='wegen meiner Zechprellerei.`n';
$part[6][12]='aufgrund meines Körpergeruchs.`n';
$part[6][13]='wegen meiner alles überstrahlenden Schönheit.`n';
$part[6][14]='aufgrund meiner starken Körperbehaarung.`n';
$part[6][15]='weil ich zauberhaft bin.`n';
$part[6][16]='wegen meiner Jungfräulichkeit.`n';

$part[7][1]='Bitte gestattet mir Euch Gesellschaft leisten zu dürfen...';
$part[7][2]='Es gibt doch nichts schöneres als sich nach getaner Arbeit auf ein Glas Wein bei Cedrick einzufinden...';
$part[7][3]='Findet Ihr nicht auch, dass der Magistrat mehr Macht gegenüber dem Fürsten haben sollte...?';
$part[7][4]='Wie würdet Ihr reagieren, wenn Euch jemand, den Ihr nicht kennst, einfach so in der Kneipe anspricht?';
$part[7][5]='Die Sterne haben mir versprochen, dass ich heute meinen Traummann treffe. Seid Ihr es?';
$part[7][6]='Was macht denn ein so hübscher Jüngling allein an einem Ort wie diesem...?';
$part[7][7]='Kommt Ihr öfter hier her...?';
$part[7][8]='Der Glanz Eurer Iriden verleiht diesem Raum eine ganz andere Atmosphäre...!';
$part[7][9]='Ihr kommt mir sehr bekannt vor. Könnte es sein, dass Ihr der Mann aus meinen Träumen seid?';
$part[7][10]='Sagt mir falls ich mich irre, aber Ihr wollt mich küssen, oder?';
$part[7][11]='Ihr habt da ein `#Biep`@ auf der Nase...';
$part[7][12]='Ich hatte gerade Streit mit meiner Mutter, wollt ihr mich nicht aufmuntern...?';
$part[7][13]='Ich glaube mein Mann betrügt mich...';
$part[7][14]='Glaubt Ihr an Liebe auf den ersten Blick - oder soll ich noch mal reinkommen?';
$part[7][15]='Verzeiht meine Blässe, aber ich habe einen schrecklichen Durchfall...';
$part[7][16]='Ich bin Euer Spielzeug für heute Nacht...';

page_header('Zwischenprüfung in der Flirtschule '.getsetting('townname','Atrahor'));
$session['user']['specialinc'] = basename(__FILE__);
$str_filename = basename($_SERVER['SCRIPT_FILENAME']);
$session['user']['pqtemp']=utf8_unserialize($session['user']['pqtemp']);

$str_output = '';

if ($session['user']['sex']==0)
{
	//Männer dürfen nicht mitpielen, kriegen aber ein paar Nüsse zum Trost ;)
	$str_output .= 'Heute ist erstaunlich wenig los in der Schenke.`nUm genau zu sein stehst du gerade sogar ziemlich allein am Tresen.`n
	Da fällt dir die Schale mit `@Erdnüssen`0, die Cedrick seiner Lieblingskundschaft kredenzt, wenn er bei guter Laune ist, ins Auge.`n
	Und bevor die guten Nüsschen schlecht werden, greifst du dir auch gleich eine kleine Handvoll und lässt sie in deiner Tasche verschwinden - unauffällig natürlich.`n';
	item_add($session['user']['acctid'],'erdnuss');
	$session['user']['specialinc'] = '';
	addnav('Oh schön!','inn.php');
}
else
{
	switch ($_GET['sop'])
	{
		case 'phase':
			{
				$nr = $_GET['nr'];

				if ($_GET['choice'])
				{
					$oldnr = $nr-1;
					$choice = $_GET['choice'];
					$str_output .='`7';
					if ($nr<7)
					{
						if ($choice>=1 AND $choice <=6)
						{
							$session['user']['pqtemp']['pickup']['balance']+=10;
							switch (e_rand(1,10))
							{
								case 1 :
									$str_output .='Er scheint sich für dich zu interessieren!';
									break;
								case 2 :
									$str_output .='Er hat sich dir zugewendet!';
									break;
								case 3 :
									$str_output .='Gut so! Er lächelt...';
									break;
								case 4 :
									$str_output .='Er zwinkert dir frech zu';
									break;
								case 5 :
									$str_output .='Seine Wangen erröten leicht.';
									break;
								case 6 :
									$str_output .='Er nickt dir zu.';
									break;
								case 7 :
									$str_output .='Er blickt dich erwartungsvoll an.';
									break;
								case 8 :
									$str_output .='Du hast seine Aufmerksamkeit gewonnen!';
									break;
								case 9 :
									$str_output .='Er streicht sich lasziv durchs Haar.';
									break;
								case 10 :
									$str_output .='Er schenkt dir ein wunderbares Lächeln!';
									break;
							}
						}
						else if ($choice>=9 && $choice <=16)
						{
							$session['user']['pqtemp']['pickup']['balance']-=15;
							switch (e_rand(1,10))
							{
								case 1 :
									$str_output .='DAS hast du jetzt nicht gesagt?`nDoch, hast du leider...';
									break;
								case 2 :
									$str_output .='Merke dir: Erst denken, dann reden!';
									break;
								case 3 :
									$str_output .='Gut so, Lady, bald hast du ihn soweit, dass er geht - und zwar allein.';
									break;
								case 4 :
									$str_output .='Ist nicht dein Ernst, oder?';
									break;
								case 5 :
									$str_output .='Also wirklich, sowas kannst du doch nicht bringen!';
									break;
								case 6 :
									$str_output .='Geklickt - gesagt. Auch wenns nicht besonders clever war.';
									break;
								case 7 :
									$str_output .='Er legt die Stirn in Falten und schüttelt den Kopf.';
									break;
								case 8 :
									$str_output .='Er wendet sich von dir ab und starrt in sein Aleglas.';
									break;
								case 9 :
									$str_output .='Er tut so, als würde sie dich nicht wahrnehmen.';
									break;
								case 10 :
									$str_output .='Er rückt etwas von dir weg!';
									break;
							}
						}
						else
						{
							$str_output .='Er lässt dich fortfahren.';
						}
					}
					$str_output .='`n`n`0';

					$session['user']['pqtemp']['pickup']['part'][$oldnr]=$choice;
				}

				if ($nr==1)
				{
					$str_output .='Du erhebst dich von deinem Platz und gehst langsam zum Tresen, auf '.$session['user']['pqttemp']['pickup']['victimname'].'`0 zu.`n
					Ohne groß zu überlegen sagst du das erstbeste, was dir in den Sinn kommt.`n`n';
					if ($session['user']['drunkenness']>=40)
					{
						$str_output .='Dein Rausch lockert dabei deine Zunge...`n`n';
					}
				}

				if ($nr<=7)
				{
					addnav('Nene, kneifen is nich mehr');
					$str_output .= '`0Wähle aus, was du sagen möchtest:`n';
					$drunk = round($session['user']['drunkeness']/10);
					for ($i=1; $i<=3; $i++)
					{
						$option = e_rand(1,16)+$drunk;
						if ($option>16)
						{
							$option=16;
						}
                        /** @noinspection PhpUndefinedVariableInspection */
                        if ($offer[$nr][$option]==1)
						{
							$i--;
						}
						else
						{
							$newnr = $nr + 1;
							$newline = $part[$nr][$option];
							strip_appoencode($newline);
							$newline = str_replace("`n", " ", $newline);
							$str_output .= '`@'.create_lnk($newline,$str_filename.'?sop=phase&nr='.$newnr.'&choice='.$option,true,false,'',false,false,CREATE_LINK_LEFT_NAV_HOTKEY).'`n`0';
							$offer[$nr][$option]=1;
						}
					}
					$str_output .='`0`n`n';
					if ($_GET['choice'])
					{
						$str_output .='Bisher hast du heraus gebracht:`n';
						foreach ($session['user']['pqtemp']['pickup']['part'] as $key => $val)
						{
							$str_output .= '`@'.$part[$key][$val];
						}
						$str_output .='`0`n`n';
					}
				}
				else
				{
					// zur glorreichen Auswertung
					if ($choice>=1 && $choice <=6)
					{
						$session['user']['pqtemp']['pickup']['balance']+=10;
					}
					else if ($choice>=9 && $choice <=16)
					{
						$session['user']['pqtemp']['pickup']['balance']-=15;
					}
					$spruch ='';
					foreach ($session['user']['pqtemp']['pickup']['part'] as $key => $val)
					{
						$spruch.= '`@'.$part[$key][$val];
						$str_output .= '`@'.$part[$key][$val];
					}
					$str_output .='`0`n`n';
					$session['user']['specialinc'] = '';
					if ($session['user']['pqtemp']['pickup']['balance']<=0)
					{
						// Niete
						$str_output .='`nNa das ging ja mal ordentlich in die Hose!`nEntsetzt von deinem groben Auftreten erhebt sich der gutaussehende '.$session['user']['pqtemp']['pickup']['victimname'].'`0 und eilt aus der Schenke.`n`n
						Wie gut, dass sich niemand diesen peinlichen Spruch notiert hat, denkst du dir so...`n';
						addnews('`@'.$session['user']['name'].'`5 musste heute etwas über Männer lernen.',$session['user']['acctid']);
						/*
						// Crime ist ein wenig übertrieben, auf Wunsch herausgenommen
						if ($session['user']['pqtemp']['pickup']['balance']<=-50)
						{
							addcrimes("`6".$session['user']['name']."`6 versuchte ".$session['user']['pqtemp']['pickup']['victimname']."`6 vergebens mit folgendem Spruch zu erobern:`n".$spruch."`n");
						}
						*/
						$plumpgold = item_get_tpl(' tpl_id="plumpgold"' );
						$plumpgold['tpl_name'] = $cname.'s`^ '.$plumpgold['tpl_name'];
						$plumpgold['tpl_description'] = $spruch.'`0';
						item_add($session['user']['pqtemp']['pickup']['victimid'] , 0 , $plumpgold );
						systemmail($session['user']['pqtemp']['pickup']['victimid'],"`5Du wurdest in der Schenke belästigt!`0","`%".$session['user']['name']."`% hat auf höchst plumpe Weise versucht dich anzuflirten, ist aber kläglich gescheitert.`nDu erhältst ihren peinlichen Spruch vergoldet zur Erinnerung und Erheiterung als Trophäe in dein Inventar.");
					}
					else
					{
						// Gewinner
						$str_output .='`nGut gemacht!`n
						Du und '.$session['user']['pqtemp']['pickup']['victimname'].'`0 verbringen eine nette Zeit im angenehmen Gespräch.`n
						Eine derart gutaussehende und begehrte Gesellschaft hat natürlich auch Auswirkungen darauf, wie die Leute dich ansehen.`n
						`@Du erhältst 2 Charmepunkte dazu!`0`n';
						$session['user']['charm']+=2;
						addnews('`@'.$session['user']['name'].'`# wurde mit einem gutaussehenden Mann in der Schenke gesehen.',$session['user']['acctid']);
					}
					unset($session['user']['pqtemp']['pickup']);
					addnav("Ich brauch etwas zum Trinken!","inn.php");
				}
			}
			break;
		case 'nothing':
			{
				$str_output .= 'Tja, du siehst es realistisch - bei dem hast du nicht den Ansatz einer Chance.`n
				Also tust du das einzig richtige und ziehst dich in eine dunkle Ecke zurück um dich weiter deinem Getränk zu widmen.';
				$session['user']['specialinc'] = '';
				unset($session['user']['pqtemp']['pickup']);
				addnav('*Schlürf*','inn.php');
			}
			break;

		case 'stare':
			{
				$str_output .= 'Du weißt genau, dass dieser Mann ein paar Nummer zu groß für dich ist und versuchst es daher auch gar nicht.`n
				Aber in deiner Phantasie malst du dir natürlich die tollen Dinge aus, die ihr beide hättet tun können, wenn du nur ein wenig mutiger gewesen wärst.`n`n';
				$session['user']['specialinc'] = '';
				unset($session['user']['pqtemp']['pickup']);
				if (e_rand(1,2)==1)
				{
					$str_output .= 'Zwar bemerkt der heiße Typ nichts von deinen gierigen Blicken, wohl aber Cedrick.`n
					Und der mag es gar nicht wenn seine Kundschaft lüstern angestarrt wird!`n
					Noch bevor du dich versehen kannst findest du dich auch schon vor der Schenke wieder, während Cedrick deinen Kopf zur Abkühlung liebevoll in die Pferdetränke drückt.';
					addnews('`@'.$session['user']['name'].'`@ fiel in der Schenke unangenehm durch lüsterne Blicke und schweres Atmen auf!',$session['user']['acctid']);
					addnav('*blubber*','village.php');
				}
				else
				{
					$str_output .= 'Du starrst diese wunderschöne Geschöpf eine ganze Weile an und nach und nach steigt in dir die Lust dich an jemandem in deiner Liga zu versuchen.`n
					Du darfst heute noch einmal im Garten flirten!';
					$session['user']['seenlover']='';
					addnav('Hrrrr','gardens.php');
				}
			}
			break;

		default:
			{
				// eine der 10 schönsten Männer ermitteln und in pqtemp eintragen
				unset($session['user']['pqtemp']['pickup']);
				$sql = "SELECT name AS vname, acctid as vid FROM (SELECT * FROM accounts WHERE sex='0' ORDER BY charm DESC LIMIT 10) AS b ORDER BY RAND()";
				$resultv = db_query($sql);
				$rowv = db_fetch_assoc($resultv);
				$session['user']['pqtemp']['pickup']['victimname']=$rowv['vname'];
				$session['user']['pqtemp']['pickup']['victimid']=$rowv['vid'];
				$session['user']['pqtemp']['pickup']['balance']=0;

				$str_output .= 'Als du deinen Blick durch den Schankraum schweifen lässt, stockt dir mit einem mal der Atem.`n
				Da erblickst du doch tatsächlich`n`n '.$rowv['vname'].'`0,`n`n
				einen der zehn bestaussehendsten Männer '.getsetting('townname','Atrahor').'s allein am Tresen!`n
				Dir ist klar, dass das die Chance deines Lebens ist und dass dir das Glück sicher nicht noch einmal so schnell derart hold
				sein wird.`n
				Du holst tief Luft und triffst eine Entscheidung...';

				addnav('Das Glück am Schopfe packen');
				addnav('Ihn ansprechen','inn.php?sop=phase&nr=1');
				addnav('Kneifen');
				addnav('Noch ein wenig starren','inn.php?sop=stare');
				addnav('Kleinlaut verkrümeln','inn.php?sop=nothing');
			}
	}
}
output($str_output);
?>