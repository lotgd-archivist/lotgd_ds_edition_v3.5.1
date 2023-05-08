<?php
//Flirt-Aktionen aus gardens.php ausgelagert um Nutzung mit variablen Texten auch anderweitig zu ermöglichen
if(!isset($str_output))$str_output='';
	//Anpassung der Farbgebung an unterschiedliche Orte
/** @noinspection PhpUndefinedVariableInspection */
if($flirt_inc_style=='dinner')
	{
		$flirt_style_begin = "<span style=color:\"#CE4040\">";
		$flirt_style_end = "</span>";
	}
	elseif ($flirt_inc_style=='expe')
	{
		$flirt_style_begin = "`0";
	}
	elseif($flirt_inc_style=='chimneyroom')
	{
		$flirt_style_begin = "`D";
        /** @noinspection PhpUndefinedVariableInspection */
        $str_output .= get_title('`DFlirten');
	}
	else
	{
		$flirt_style_begin = "`g";
        /** @noinspection PhpUndefinedVariableInspection */
        $str_output .= get_title('`GFlirten');
	}

/** @noinspection PhpUndefinedVariableInspection */
$charmdiff=$session['user']['dragonkills']*2+23; //neue Charmedifferenz: 23 Basiswert + 2 pro Drachen
	$flirts_to_affiance=5; //Nötige Flirts bis zur Verlobung
	$buff = array('name'=>'`!Schutz der Liebe','rounds'=>60,'wearoff'=>'`!Du vermisst deine große Liebe!`0','defmod'=>1.2,'roundmsg'=>'Deine große Liebe lässt dich an deine Sicherheit denken!','activate'=>'defense');
	if ($session['user']['goldinbank']>0)
	{
		//Bitshift ist schneller als Division durch zwei
		$getgold=round($session['user']['goldinbank'] >> 1);
	}

	$sql = 'SELECT acctid,name,sex,alive,hitpoints,charm,charisma,lastip,emailaddress,race,marriedto,uniqueid FROM accounts WHERE acctid="'.(int)$_GET['id'].'"';
	$result = db_query($sql);
	if (db_num_rows($result)>0)
	{
		$row = db_fetch_assoc($result);
		if ($session['user']['acctid']==$row['marriedto'] && $session['user']['marriedto']==$row['acctid'])
		{ //gegenseitige Flirts
			$flirtnum=min($session['user']['charisma'],$row['charisma']);
		}
		else
		{
			$flirtnum=0;
		}

		if (ac_check($row))
		{ //Multikontrolle
			$str_output .= '`$`bDas geht doch nicht!!`b'.$flirt_style_begin.' Du kannst doch nicht mit deinen eigenen Charakteren oder deiner eigenen Familie flirten!'.$flirt_style_end.'';
		}
		else /** @noinspection PhpUndefinedVariableInspection */
            if ($row['alive'] == 0 && $row['hitpoints'] == 0 && $flirtlocation==' im Garten ')
		{ //Partner ist tot, Fall nur für Schnellflirt relevant
			$str_output .= ''.$flirt_style_begin.'`AL`4e`oi`rder musst du feststellen, dass sich '.$row['name'].''.$flirt_style_begin.' gerade die Radieschen von unten anguckt. So kannst du natürlich nicht mit deinem Partner flirten, sodass du deprimiert den Garten verlä`os`4s`At.'.$flirt_style_end.''.$flirt_style_end.'';
			if($session['user']['marriedto']==$row['acctid'] && $session['user']['charisma']==4294967295)
			{
				$str_output.='`n'.$flirt_style_begin.'`oVielleicht kann ja Vessa, die Zigeunerin, etwas für dich tun.'.$flirt_style_end.'';
			}
		}
		else /** @noinspection PhpUndefinedVariableInspection */
            if ($session['user']['charisma'] == 999 && $row['acctid'] != $session['user']['marriedto'] && $bool_flirtaffianced != true)
		{ //verlobt Fremdflirten
			$str_output .= ''.$flirt_style_begin.'`AS`4o`o g`rern du auch mit jemand anderem flirten möchtest, dein Partner geht dir einfach nicht aus dem Kopf! Verwirrt drehst du wieder um. Du solltest dir überlegen, was du wirklich wil`ol`4s`At!'.$flirt_style_end.'';
		}
		else if ( $row['charisma'] == 999 && $row['marriedto'] != $session['user']['acctid'] )
		{ //einen verlobten anflirten
			$str_output .= ''.$flirt_style_begin.'`AN`4e`oi`rdisch beobachtest du '.$row['name'].''.$flirt_style_begin.' bei '.($row['sex'] ? 'ihren' : 'seinen').' Hochzeitsvorbereitungen. In diese traute Zweisamkeit solltest du dich wirklich nicht einmisc`oh`4e`An...'.$flirt_style_end.''.$flirt_style_end.'';
		}
		else if ((($session['user']['race']=='elf' && $row['race']=='zwg') || ($session['user']['race']=='zwg' && $row['race']=='elf')) && ($row['marks']<31) && ($session['user']['marks']<31))
		{ //Elfen und Zwerge
			$str_output .= ''.$flirt_style_begin.'`AD`4u`o w`rartest im Garten auf '.$row['name'].''.$flirt_style_begin.' und beobachtest dein Herzblatt eine Weile. Bei näherer Betrachtung stellst du aber fest, dass Elfen und Zwerge vielleicht doch niemals zusammen passen werden. So verlässt du den Gar`ot`4e`An.'.$flirt_style_end.''.$flirt_style_end.'';
		}
		else /** @noinspection PhpUndefinedVariableInspection */
            if ($session['user']['charm']<=1 && $session['user']['charisma']!=4294967295 && $bool_flirtcharmdiff != true)
		{ //0 Charme und nicht verheiratet
			$str_output .= ''.$flirt_style_begin.'`AD`4u `on`räherst dich '.$row['name'].''.$flirt_style_begin.' und mit dem Charme einer Blattlaus sprichst du dein Herzblatt an. Schon fast beleidigt dreht sich '.$row['name'].''.$flirt_style_begin.' um und stapft davon.`nDu solltest etwas an deiner Ausstrahlung arbei`ot`4e`An...'.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.'';
		}
		else if ($row['charm']<=1 && $session['user']['charisma']!=4294967295 && $bool_flirtcharmdiff != true)
		{ //Ziel hat 0 Charme und ist nicht verheiratet
			$str_output .= ''.$flirt_style_begin.'`AD`4u `on`räherst dich '.$row['name'].''.$flirt_style_begin.'. Je näher du deinem Herzblatt kommst, umso hässlicher kommt dein Herzblatt dir vor. Am Ende wirkt dein Herzblatt so abstoßend auf dich, dass du dein Herzblatt einfach stehen lässt und zurück in die Stadt läu`of`4s`At.'.$flirt_style_end.''.$flirt_style_end.'';
		}
		else if ((abs($row['charm']-$session['user']['charm'])>$charmdiff && $session['user']['charisma']!=4294967295) && ($session['user']['marks']<31) && $session['user']['acctid']!=$row['marriedto'] && $bool_flirtcharmdiff != true)
		{ //Charmeunterschied zu groß + nicht verheiratet + nicht auserwählt. Zurückflirten geht aber
			$str_output .= ''.$flirt_style_begin.'`AD`4u `on`räherst dich'.$row['name'].''.$flirt_style_begin.'. Ihr beginnt ein Gespräch, aber irgendwie redet ihr aneinander vorbei. Ein richtiger Flirt entwickelt sich nicht. Du beschließt, es später nochmal zu versuchen und machst dich auf den Weg zurück ins D`oo`4r`Af.'.$flirt_style_end.''.$flirt_style_end.'';
		}
		else if ($session['user']['drunkenness']>66 && $flirtlocation==' im Garten ')
		{ //besoffen im Garten
			$str_output .= ''.$flirt_style_begin.'`AD`4u`o e`rntdeckst '.$row['name'].''.$flirt_style_begin.' im Schatten unter einer Gruppe Bäume und machst dich sofort daran, dein Herzblatt mit deiner Alefahne vollzulallen. Als dein Herzblatt überhaupt nicht reagiert und immer noch irgendwie auf den Boden zu starren scheint, willst du den Kopf von deinem Herzblatt heben - und greifst voll in das Dornengestrüpp vor dir.`n
			Du hast in deinem Rausch diesen Busch für '.$row['name'].''.$flirt_style_begin.' gehalten!! Vielleicht ist es besser, erst etwas auszunüchtern, bevor du es nochmal versuc`oh`4s`At.'.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.'`n`n
			`&Dieser Irrtum hat dich einen Waldkampf und einen Charmepunkt gekostet!';
			$session['user']['turns']-=1;
			$session['user']['charm']-=1;
		}
		else if (($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295) && ($row['marriedto']==4294967295 || $row['charisma']==4294967295))
		{ // Möglichkeiten, wenn beide verheiratet
			if ($session['user']['marriedto']==$row['acctid'] && $session['user']['acctid']==$row['marriedto'])
			{ //miteinander
				if($flirtlocation==' in der Expedition ') {
					$str_output.=''.$flirt_style_begin.'Du schreibst deinem Herzblatt '.$row['name'].''.$flirt_style_begin.' einen wahnsinnig romantischen Liebesbrief und hoffst dass sich der Bote beeilt.'.$flirt_style_end.' ';
				}
				else {
					$str_output .= ''.$flirt_style_begin.'`AD`4u `of`rührst deinem Herzblatt '.$row['name'].''.$flirt_style_begin.' in den Garten aus und ihr nehmt euch etwas Zeit füreinan`od`4e`Ar.'.$flirt_style_end.' ';
				}
				$str_output .= '`n'.$flirt_style_begin.'`rDu bekommst einen Charmepunkt.'.$flirt_style_end.'';
				$session['bufflist']['lover']=$buff;
				$session['user']['charm']++;
				$session['user']['seenlover']=1;
				if(e_rand(1,15) == 2) //nicht ständig Mails schicken
				{
                    /** @noinspection PhpUndefinedVariableInspection */
                    /** @noinspection PhpUndefinedVariableInspection */
                    /** @noinspection PhpUndefinedVariableInspection */
                    systemmail($row['acctid'],$flirtmail_subject,$flirtmail_body.' und dir von Neuem die Liebe versichert.'.$more);
				}
			}
			else if ($session['user']['charm']==$row['charm'])
			{ //beide mit jemand anderem verheiratet und Sonderfall Charmegleichheit
				$str_output .= ''.$flirt_style_begin.'`AD`4u `on`räherst dich '.$row['name'].''.$flirt_style_begin.'. Sofort entsteht ein heftiger Flirt und ein angeregtes Gespräch. Du verstehst dich einfach blendend mit '.$row['name'].''.$flirt_style_begin.'! Ihr zieht euch eine Weile an einen etwas abseits gelegenen Ort zurück und verbringt ein paar sehr schöne Stunden miteinander. Da ihr beide verheiratet seid, versprecht ihr euch gegenseitig, dass niemand jemals davon erfahren w`oi`4r`Ad.'.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.'';
				$str_output .= '`n`rIhr bekommt beide einen Charmepunkt!';
				$session['user']['charm']+=1;
				$session['user']['seenlover']=1;
				user_update(
					array
						(
							'charm'=>array('sql'=>true,'value'=>'charm+1')
						),
						$row['acctid']
				);

                /** @noinspection PhpUndefinedVariableInspection */
                /** @noinspection PhpUndefinedVariableInspection */
                /** @noinspection PhpUndefinedVariableInspection */
                systemmail($row['acctid'],$flirtmail_subject,$flirtmail_body.'. Ihr habt beide einen Charmepunkt bekommen und haltet euer Geheimnis vor eurem Ehepartner verborgen.'.$more);
			}
			else
			{ //beide mit jemand anderem verheiratet
				$str_output .= ''.$flirt_style_begin.'`AD`4u `on`räherst dich '.$row['name'].''.$flirt_style_begin.' und fängst an zu flirten, was das Zeug hält. '.$row['name'].''.$flirt_style_begin.' steigt darauf ein'.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.'';
				switch(e_rand(1,4))
				{
					case 1: //Fremdflirten mit Reue
					case 2:
						$str_output .= ''.$flirt_style_begin.' und da ihr beide verheiratet seid, versprecht ihr euch gegenseitig, dass niemand jemals davon erfahren w`oi`4r`Ad.'.$flirt_style_end.'';
						$str_output .= '`n'.$flirt_style_begin.'Ihr `&VERLIERT'.$flirt_style_begin.' beide einen Charmepunkt, da ihr euer schlechtes Gewissen nicht vor eurem Ehepartner verbergen könnt!'.$flirt_style_end.''.$flirt_style_end.'';
						
						user_update(
						array
							(
								'charm'=>array('sql'=>true,'value'=>'charm-1')
							),
							$row['acctid']
						);
						
						$session['user']['charm']-=1;
                        /** @noinspection PhpUndefinedVariableInspection */
                        /** @noinspection PhpUndefinedVariableInspection */
                        /** @noinspection PhpUndefinedVariableInspection */
                        systemmail($row['acctid'],$flirtmail_subject,$flirtmail_body.'. Ihr habt beide einen Charmepunkt VERLOREN, da euer schlechtes Gewissen eurem Ehepartner nicht verborgen blieb.'.$more);
						$session['user']['seenlover']=1;
						break;
					case 3: //Fremdflirten geht gut
						$str_output .= ''.$flirt_style_begin.' und da ihr beide verheiratet seid, versprecht ihr euch gegenseitig, dass niemand jemals davon erfahren w`oi`4r`Ad.'.$flirt_style_end.'';
						$str_output .= '`n'.$flirt_style_begin.'Ihr bekommt beide einen Charmepunkt!'.$flirt_style_end.'';
						
						user_update(
							array
								(
									'charm'=>array('sql'=>true,'value'=>'charm+1')
								),
								$row['acctid']
						);
						
						$session['user']['charm']+=1;
                        /** @noinspection PhpUndefinedVariableInspection */
                        /** @noinspection PhpUndefinedVariableInspection */
                        /** @noinspection PhpUndefinedVariableInspection */
                        systemmail($row['acctid'],$flirtmail_subject,$flirtmail_body.'. Ihr habt beide einen Charmepunkt bekommen und haltet euer Geheimnis vor euren Ehepartnern verborgen.'.$more);
						$session['user']['seenlover']=1;
						break;
					case 4: //Scheidung
						require_once(LIB_PATH.'board.lib.php');
						$boardmsg='`$wurde beim Fremdflirten erwischt und ward geschieden.';
						if(board_add('tempel_sys',30,1,$boardmsg) ==-1)
						{
							$str_output .= '.`n`n`&Du lebst bereits in Scheidung. Warte bis ein Priester einen Termin frei hat um deine Ehe aufzulösen.';
						}
						else
						{
							$str_output .= $flirt_style_begin.', aber ihr werdet bei eurem Vergnügen von deinem Herzblatt erwischt.`nDie Katastrophe ist komplett.'.$flirt_style_end.'`&`n`nDein Ex-Herzblatt reicht die Scheidung ein und bekommt 50% deines Vermögens von der Bank zugesprochen.`nEin Priester wird in Kürze eure Ehe für nichtig erklären.`nDu verlierst einen Charmepunkt.`n`nSolltest du die Schuld des fremden Vergnügens einem alkoholischen Rausch oder etwas ähnlichem in die Schuhe schieben wollen, so wie es viele andere zu tun pflegen, kannst du einem amtierenden Priester bescheid geben, dass er eure Scheidung verhindern soll. Dies sollte jedoch umgehend in Kraft treten, sonst können dir die Götter für nichts garantie`or`4e`An.';
							if ($getgold>0)
							{
								user_update(
									array
										(
											'goldinbank'=>array('sql'=>true,'value'=>'goldinbank+'.$getgold)
										),
										$session['user']['marriedto']
								);
								$session['user']['goldinbank']-=$getgold;
							}
							systemmail($session['user']['marriedto'],'`$Scheidung!`0','`6Du hast `&'.$session['user']['name'].'`6 mit `&'.$row['name'].$flirtlocation.' erwischt und reichst die Scheidung ein.`nDir werden `^'.$getgold.'`6 Gold von deinem ehemaligen Ehepartner zugesprochen.`nEin Priester wird in Kürze eure Ehe für Nichtig erklären.');
							systemmail($row['acctid'],$flirtmail_subject,'`&'.$session['user']['name'].'`6 hat mit dir geflirtet und wurde dabei von deinem Herzblatt erwischt.');
							$session['user']['seenlover']=1;
							$session['user']['charm']-=1;
							addnews('`$'.$session['user']['name'].'`$ wurde beim Flirten mit '.$row['name'].' `$'.$flirtlocation.' erwischt und ist jetzt wieder solo.');
						}
						break;
				}
			}
		}
		else if ($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295)
		{ // Möglichkeiten, wenn nur selbst verheiratet
			if ($session['user']['marriedto']==4294967295 && $session['user']['charisma']>=5)
			{ //mit Seth/Violet
				if($flirtlocation==' beim Dinner ') {
					$str_output.=''.$flirt_style_begin.'Zu dumm, dass '.($session['user']['sex']?'Seth':'Violet').''.$flirt_style_begin.' in dieser Schenke arbeitet und natürlich etwas von dem mitbekommst, was du im Hinterzimmer treibst. '.($session['user']['sex']?'Er':'Sie').' verlässt dich...`n'.$flirt_style_end.''.$flirt_style_end.'';
				}
				else {
				$str_output .= ''.$flirt_style_begin.''.($session['user']['sex']?'Seth':'Violet').' springt aus einem Gebüsch und beschimpft dich aufs Heftigste, als du dich '.$row['name'].''.$flirt_style_begin.' nähern willst. '.($session['user']['sex']?'Er':'Sie').'  beobachtet deine "Gartenarbeit" schon eine ganze Weile!'.$flirt_style_end.''.$flirt_style_end.'`&`n`n'.($session['user']['sex']?'Seth':'Violet').' verlässt dich.`nDu verlierst einen Charmepunkt.';
				}
				$session['user']['marriedto']=$row['acctid'];
				$session['user']['charisma']=1;
				$session['user']['seenlover']=1;
				$session['user']['charm']-=1;
				addnews('`$'.$session['user']['name'].'`$ wurde beim Flirten mit '.$row['name'].'`$'.$flirtlocation.' von '.($session['user']['sex']?'Seth':'Violet').' erwischt und ist jetzt wieder solo.');
			}
			else
			{
				if ($session['user']['acctid']==$row['marriedto'])
				{ //wurde bereits angeflirtet
					$str_output .= ''.$flirt_style_begin.'Obwohl du verheiratet bist, gehst du auf die Flirtversuche von '.$row['name'].''.$flirt_style_begin.' ein. Ihr versteht euch blendend und für einen Moment vergisst du dein Herzblatt. '.$flirt_style_end.''.$flirt_style_end.'';
				}
				else
				{ //erster Flirtversuch
					$str_output .= ''.$flirt_style_begin.'Obwohl du verheiratet bist, lässt du dich auf einen Flirt ein. Ihr versteht euch blendend und für einen Moment vergisst du dein Herzblatt. '.$flirt_style_end.'';
				}
				switch(e_rand(1,4))
				{
					case 1: //nichts passiert
					case 2:
					case 3:
						$str_output .= ''.$flirt_style_begin.' Aber du weißt, dass eine Beziehung keine Zukunft hat, solange du verheiratet bist.'.$flirt_style_end.'';
                        /** @noinspection PhpUndefinedVariableInspection */
                        /** @noinspection PhpUndefinedVariableInspection */
                        /** @noinspection PhpUndefinedVariableInspection */
                        systemmail($row['acctid'],$flirtmail_subject,$flirtmail_body.'.'.$more);
						$session['user']['seenlover']=1;
						if ($session['user']['marriedto']==4294967295) $session['user']['charisma']+=1;
						break;
					case 4: //Scheidung
						require_once(LIB_PATH.'board.lib.php');
						$boardmsg='`$wurde beim Fremdflirten erwischt und ward geschieden.';
						if(board_add('tempel_sys',30,1,$boardmsg) ==-1)
						{
							$str_output .= '`n`n`&Du lebst bereits in Scheidung. Warte bis ein Priester einen Termin frei hat, um deine Ehe aufzulösen.';
						}
						else
						{
							$str_output .= ' '.$flirt_style_begin.'Aber '.($session['user']['sex']?'er':'sie').' ruft sich selbst aufs Heftigste ins Gedächtnis zurück!`nDie Katastrophe ist komplett.'.$flirt_style_end.'`&`n`nDein Ex-Herzblatt reicht die Scheidung ein und bekommt 50% deines Vermögens von der Bank zugesprochen.`nEin Priester wird in Kürze eure Ehe für Nichtig erklären.`nDu verlierst einen Charmepunkt.`n`nSolltest du die Schuld des fremden Vergnügens einem alkoholischen Rausch oder etwas ähnlichem in die Schuhe schieben wollen, so wie es viele andere zu tun pflegen, kannst du einem amtierenden Priester bescheid geben, dass er eure Scheidung verhindern soll. Dies sollte jedoch umgehend in Kraft treten, sonst können dir die Götter für nichts garantieren.';
							if($getgold>0) {
								
								user_update(
									array
										(
											'goldinbank'=>array('sql'=>true,'value'=>'goldinbank+'.$getgold)
										),
										$session['user']['marriedto']
								);
								
								$session['user']['goldinbank']-=$getgold;
							}
							systemmail($session['user']['marriedto'],'`$Scheidung!`0','`6Du hast `&'.$session['user']['name'].'`6 mit `&'.$row['name'].'`6'.$flirtlocation.' erwischt und reichst die Scheidung ein.`nDir werden `^'.$getgold.'`6 Gold von deinem ehemaligen Ehepartner zugesprochen.`nEin Priester wird in Kürze eure Ehe für Nichtig erklären.');
							systemmail($row['acctid'],$flirtmail_subject,'`&'.$session['user']['name'].'`6 hat mit dir geflirtet, wurde dabei aber erwischt.');
							$session['user']['seenlover']=1;
							$session['user']['charm']-=1;
							addnews('`$'.$session['user']['name'].'`$ wurde beim Flirten '.$flirtlocation.' erwischt und ist jetzt wieder solo.');
						}
						break;
				}
			}
		}
		else if ($row['marriedto']==4294967295 || $row['charisma']==4294967295)
		{ // Möglichkeiten, wenn nur Gegenüber verheiratet
			if ($session['user']['marriedto']==$row['acctid'])
			{ //wiederholtes Anflirten
				if ($session['user']['charisma']<999) $session['user']['charisma']+=1;
				$session['user']['seenlover']=1;
				$str_output .= ''.$flirt_style_begin.'Du flirtest zum `&'.$session['user']['charisma'].'.'.$flirt_style_begin.' Mal mit '.$row['name'].' '.$flirt_style_begin.', weißt aber, dass der Flirt wohl nicht erwiedert wird, da '.$row['name'].''.$flirt_style_begin.' (noch) verheiratet ist.'.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.'';
			}
			else
			{ //erster Flirtversuch
				$str_output .= ''.$flirt_style_begin.'Du flirtest mit '.$row['name'].''.$flirt_style_begin.' und ihr verbringt einige Zeit gemeinsam '.$flirtlocation.'.'.$flirt_style_end.''.$flirt_style_end.'';
				$session['user']['charisma']=1;
				$session['user']['seenlover']=1;
				$session['user']['marriedto']=$row['acctid'];
			}
            /** @noinspection PhpUndefinedVariableInspection */
            /** @noinspection PhpUndefinedVariableInspection */
            systemmail($row['acctid'],$flirtmail_subject,'`&.'.$session['user']['name'].'`6 hat mit dir '.$flirtlocation.' geflirtet.'.$more);
		}
		else
		{ // beide unverheiratet
			if ($session['user']['acctid']==$row['marriedto'])
			{ //Flirtpartner
				if ($flirtnum>=$flirts_to_affiance && $session['user']['marriedto']==$row['acctid'])
				{ //Verlobung
					if($session['user']['charisma']!=999)
					{
						$session['user']['charisma']=999;
						
						user_update(
							array
								(
									'charm'=>array('sql'=>true,'value'=>'charm+1'),
									'charisma'=>999
								),
								$row['acctid']
						);
						
						//alte Verehrer zurücksetzen
						user_update(
							array
								(
									'marriedto'=>0,
									'charisma'=>0,
									'where'=>' 
									(acctid<>'.$row['acctid'].'	AND marriedto='.$session['user']['acctid'].') 
									OR 
									(acctid<>'.$session['user']['acctid'].' AND marriedto='.$row['acctid'].')'
								)
						);						

						$str_output .= ''.$flirt_style_begin.'`AD`4a`os `rheutige Treffen ist etwas Besonderes! Ihr versteht euch intuitiv besonders gut, scheint gar vor Liebe der Welt entrückt zu sein..`n';
						$str_output .= 'Nach einem langen Gespräch, in dem ihr euch immer wieder eure Zuneigung versichert, fasst sich '.$row['name'].''.$flirt_style_begin.' ein Herz und macht dir einen romantischen `bHeiratsantrag`b!`n`n';
						$str_output .= 'Ihr seid jetzt offiziell verlobt. Ihr könnt von nun an in Form einer `$ausführlichen, schriflichen Bewerbung'.$flirt_style_begin.' Kontakt mit den Priestern im Tempel aufneh`om`4e`An!`n`/(Für eine Rollenspiel-Hochzeit ist es vorteilhaft für die Priester und euch selbst, wenn ihr ein wenig Erfahrung im Rollenspiel besitzt, die Regeln für ein solches auf diesem Server gelesen habt und euch schon ein wenig die anderen Rollenspiele auf dem Marktplatz, Stadtzentrum etc. angeschaut habt. Schreibt deshalb bitte eine ausführliche, schriftliche Bewerbung an einen Priester (oder eine Hexe) eurer Wahl, sodass sie sich ein Bild von euren Rollenspiel-Kenntnissen machen und entscheiden können, ob eine Rollenspiel-Hochzeit einen Sinn hätte oder eine systemtechnische Hochzeit vorteilhafter wäre.)'.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.'`n`n';

						$session['user']['seenlover']=1;
						$session['bufflist']['lover']=$buff;
						$session['user']['charm']+=1;
						$session['user']['donation']+=1;

						addhistory('Verlobung mit '.$row['name'],1,$session['user']['acctid']);
						addhistory('Verlobung mit '.$session['user']['name'],1,$row['acctid']);

                        /** @noinspection PhpUndefinedVariableInspection */
                        systemmail($row['acctid'],'`&Verlobung!`0','`& Du und `&'.$session['user']['name'].'`& habt nach zahlreichen gemeinsamen Flirts beschlossen, bald zu heiraten!`nIhr könntet von nun an in Form einer `$ausführlichen, schriflichen Bewerbung`& Kontakt mit den Priestern im Tempel aufnehmen!`n`/(Für eine Rollenspiel-Hochzeit ist es Vorteilhaft für die Priester und euch selbst, wenn ihr ein wenig Erfahrung im Rollenspiel besitzt, die Regeln für ein solches auf diesem Server gelesen habt und euch schon ein wenig die anderen Rollenspiele auf dem Marktplatz, Stadtzentrum etc. angeschaut habt. Schreibt deshalb bitte eine ausführliche, schriftliche Bewerbung an einen Priester (oder eine Hexe) eurer Wahl, so dass sie sich ein Bild von euren Rollenspiel-Kenntnissen machen und entscheiden können, ob eine Rollenspiel-Hochzeit einen Sinn hätte oder eine systemtechnische Hochzeit vorteilhafter wäre.)'.$more);
						$boardmsg='`#hat sich heute mit `&'.$row['name'].'`# verlobt.';
						require_once(LIB_PATH.'board.lib.php');
						board_add('tempel_sys',30,1,$boardmsg);

					}
					elseif ($session['user']['charisma']==999)
					{//ist schon verlobt
						if(e_rand(1,5)==3)
						{
							$str_output .= ''.$flirt_style_begin.'`AD`4u`o f`rührst dein Herzblatt '.$row['name'].''.$flirt_style_begin.' `rin den Garten aus und ihr nehmt euch etwas Zeit füreinan`od`4e`Ar. '.$flirt_style_end.''.$flirt_style_end.'';
							$str_output .= '`n'.$flirt_style_begin.'`rDu bekommst einen Charmepunkt.'.$flirt_style_end.'';
							if(is_array($buff)) $session['bufflist']['lover']=$buff;
							$session['user']['charm']++;
                            /** @noinspection PhpUndefinedVariableInspection */
                            /** @noinspection PhpUndefinedVariableInspection */
                            /** @noinspection PhpUndefinedVariableInspection */
                            systemmail($row['acctid'],$flirtmail_subject,''.$flirtmail_body.' und dir von Neuem die Liebe versichert.'.$more);
						}
						else
						{
							$str_output .= ''.$flirt_style_begin.'`rVoller Vorfreude plant ihr eure Hochzeit. Wenn ihr nicht grade mit dem jeweils anderen beschäftigt seid..'.$flirt_style_end.'';
						}
						$session['user']['seenlover']=1;
					}
					else
					{ //homo
						$str_output.='Als einige Stadtbewohner mit Mistgabeln am Garteneingang erscheinen und auf dich zeigen, ziehst du es vor, schnell zu verschwinden. Nicht dass man dich noch lyncht...';
					}
					addnav('T?Zum Tempel','tempel.php');
				}
				else if ($flirtnum>0)
				{
					if ($session['user']['charisma']<998)
					{
						$session['user']['charisma']++;
					}
					$flirtnum=min($session['user']['charisma'],$row['charisma']);
					$session['user']['seenlover']=1;
					$session['user']['charm']+=1;
					$str_output .= ''.$flirt_style_begin.'`AD`4u `of`rlirtest zum `&'.$session['user']['charisma'].'. '.$flirt_style_begin.'Mal mit deiner Flamme '.$row['name'].''.$flirt_style_begin.'.'.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.'`n';
					$str_output .= ''.$flirt_style_begin.'Ihr habt eure Flirts schon '.$flirtnum.''.$flirt_style_begin.' Mal gegenseitig erwidert. Gelingt euch das insgesamt '.$flirts_to_affiance.' Mal, verspricht '.$row['name'].' '.$flirt_style_begin.'dir, dich zu heira`ot`4e`An!'.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.'';
					$str_output .= '`n`n`rDu erhältst einen Charmepunkt.';
                    /** @noinspection PhpUndefinedVariableInspection */
                    /** @noinspection PhpUndefinedVariableInspection */
                    /** @noinspection PhpUndefinedVariableInspection */
                    systemmail($row['acctid'],$flirtmail_subject,$flirtmail_body.'. Damit habt ihr '.$flirtnum.' gegenseitige Flirts. Nach dem '.$flirts_to_affiance.'. gemeinsamen Flirt könnt ihr heiraten!'.$more);
				}
				else
				{
					if ($session['user']['charisma']<998) $session['user']['charisma']+=1;
					$session['user']['seenlover']=1;
					$session['user']['charm']+=1;
					$str_output .= ''.$flirt_style_begin.'`AD`4u `oe`rrwiderst den Flirt von `r'.$row['name'].'`r '.$flirt_style_begin.'und verbringst einige Zeit mit deinem Herzblatt '.trim($flirtlocation).'.'.$flirt_style_end.''.$flirt_style_end.'`n';
                    /** @noinspection PhpUndefinedVariableInspection */
                    /** @noinspection PhpUndefinedVariableInspection */
                    /** @noinspection PhpUndefinedVariableInspection */
                    systemmail($row['acctid'],$flirtmail_subject,'`&'.$session['user']['name'].'`6 erwidert deinen Flirt.  '.$flirtmail_body.'.'.$more);
					$str_output .= '`n`n`rDu erhältst einen Charmepunkt.';
				}
				$session['user']['marriedto']=$row['acctid']; //warum steht das hier?
			}
			else if ($session['user']['marriedto']==$row['acctid'])
			{ //wiederholter Flirt ohne Erwiederung
				if ($session['user']['charisma']<998) $session['user']['charisma']+=1;
				$session['user']['seenlover']=1;
				$str_output .= ''.$flirt_style_begin.'`AD`4u `of`rlirtest zum `&'.$session['user']['charisma'].'.'.$flirt_style_begin.' Mal mit '.$row['name'].' '.$flirt_style_begin.'und hoffst darauf, dass der Flirt erwidert w`oi`4r`Ad.'.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.'';
                /** @noinspection PhpUndefinedVariableInspection */
                /** @noinspection PhpUndefinedVariableInspection */
                /** @noinspection PhpUndefinedVariableInspection */
                systemmail($row['acctid'],$flirtmail_subject,$flirtmail_body.'.`nWillst du nicht mal reagieren?'.$more);
			}
			else if ($session['user']['charisma']==999)
			{ //verlobt fremdflirten falls nicht oben verboten
				$sql='SELECT name FROM accounts WHERE acctid='.$session['user']['marriedto'];
				$row2=db_fetch_assoc(db_query($sql));
				$str_output .= ''.$flirt_style_begin.'`AD`4u `of`rlirtest mit '.$row['name'].''.$flirt_style_begin.' und dir fällt auf, dass auch andere Mütter schöne '.($session['user']['sex']?'Söhne':'Töchter').' haben. Jetzt liegt es an dir, willst du `'.$row2['name'].''.$flirt_style_begin.' verlassen und dein Glück mit '.$row['name'].''.$flirt_style_begin.' versuc`oh`4e`An?'.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.''.$flirt_style_end.'';
				$session['disband']['newname']=$row['name'];
				$session['disband']['oldname']=$row2['name'];
				addnav('Verlobung auflösen','gardens.php?op=disband&acctid='.$row['acctid']);
			}
			else
			{ //erster Flirt
				$str_output .= ''.$flirt_style_begin.'`AD`4u`o f`rlirtest mit '.$row['name'].''.$flirt_style_begin.' und ihr verbringt einige Zeit gemeinsam '.$flirtlocation.'.'.$flirt_style_end.''.$flirt_style_end.'';
                /** @noinspection PhpUndefinedVariableInspection */
                /** @noinspection PhpUndefinedVariableInspection */
                /** @noinspection PhpUndefinedVariableInspection */
                systemmail($row['acctid'],$flirtmail_subject,$flirtmail_body.'.'.$more);
				$session['user']['charisma']=1;
				$session['user']['seenlover']=1;
				$session['user']['marriedto']=$row['acctid'];
			}
		}
	}
	else
	{
		$str_output .= '`$Fehler:`4 Dieser Krieger wurde nicht gefunden. Darf ich fragen, wie du überhaupt hierher gekommen bist?';
	}
?>