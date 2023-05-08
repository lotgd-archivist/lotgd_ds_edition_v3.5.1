<?php



$DONT_OVERWRITE_NAV 	= true;
$BOOL_JS_HTTP_REQUEST 	= true;
require_once('common.php');




switch( $_GET['op'] ){
	
//SYMPATHIEPUNKT VERGEBEN
	case 'symp': 

		$sres = db_query('SELECT symp_given,symp_votes FROM account_extra_info WHERE acctid='.$session['user']['acctid']);
		$rowsy = db_fetch_assoc($sres);
		$to_id = (int)$_GET['id'];
		
		
		$maxsymp=getsetting('max_symp','10');
		if ( ($rowsy['symp_given']==0) && 
			($rowsy['symp_votes']<$maxsymp) && 
			(	($session['user']['dragonkills']>0) && 
				(getsetting('symp_dk_lock','1')==1)
			)
		   )
		{	
			$failed = false;
			//Prüfen, ob er SP bekommen darf
			$res = db_query('SELECT acctid FROM accounts WHERE acctid='.$to_id.' AND 0=(conf_bits & '.UBIT_DISABLE_SYMPVOTE.')');
			if( db_num_rows($res) == 0 ){
				$str_back = '/mb Dieser Charakter darf keine Sympathiepunkte bekommen.';
				$failed = true;
			}
			else if(getsetting('symp_per_acc',10) < $maxsymp) {
				// Wenn max. Anzahl an Symp.punkten auf diesen Account noch nicht überschritten
				$sql = 'SELECT COUNT(*) AS c FROM sympathy_votes WHERE from_user='.$session['user']['acctid'].' AND to_user='.$to_id;
				$count = db_fetch_assoc(db_query($sql));
				
				if($count['c'] >= getsetting('symp_per_acc',10)) {
					$str_back = '/mb Du hast diesem Charakter bereits genug Sympathiepunkte gegeben. So gerne kannst du ihn ja gar nicht haben.`0';
					$failed = true;
				}
							
			}
			if( !$failed ){
				$sql = 'UPDATE account_extra_info SET sympathy=sympathy+1 WHERE acctid = '.$to_id;
				db_query($sql);
				$sql = 'UPDATE account_extra_info SET symp_given=1, symp_votes=symp_votes+1 WHERE acctid = '.$session['user']['acctid'];
				db_query($sql);
				
				$sql='INSERT INTO sympathy_votes (timestamp,from_user,to_user) VALUES (now(),'.$session['user']['acctid'].','.$to_id.')';
				db_query($sql);
				
				debuglog('Vergibt einen Sympathiepunkt an ',$to_id);
				$str_back = '/mb Sympathiepunkt vergeben!';	
			}
		}
		else{
			$str_back = '/mb Du kannst keinen Sympathiepunkt vergeben!';
		}
		
	break;

//MODAKTIONEN
//EINKERKERN
	case 'prison':
		user_update(
			array
			(
				'location'=>USER_LOC_PRISON,
				'restatlocation'=>0,
				'imprisoned'=>-1
			),
			$_GET['id']
		);
		
		systemmail($_GET['id'],'`\$Eingekerkert!`0','`@'.$session['user']['name'].'`& hat dich in den Kerker sperren lassen. Warscheinlich hast du dich schlecht benommen oder gegen die Regeln verstoßen. Wenn du dir nicht sicher bist, solltest du vielleicht mal in einer Mail nach dem Grund fragen.');
				
		systemlog('`qEinkerkerung von:`0 ',$session['user']['acctid'],$_GET['id']);
		
		$str_back = '/mb PID('.$_GET['id'].') sitzt nun hinter schwedischen Gardienen!';
	break;

//BEFREIEN
	case 'free':
		
		user_update(
			array
			(
				'location'=>0,
				'imprisoned'=>0
			),
			$_GET['id']
		);

		systemmail($_GET['id'],'`@Freilassung!`0','`@'.$session['user']['name'].'`& hat dich wieder aus dem Kerker befreit.');
				
		systemlog('`qFreilassung von:`0 ',$session['user']['acctid'],$_GET['id']);
		
		$str_back = '/mb PID('.$_GET['id'].') wurde von dir aus dem Kerker freigelassen!';
	break;
	
//HTML SPERREN
	case 'lock_html':
		
		$sql = 'UPDATE account_extra_info SET html_locked=1 WHERE acctid = '.$_GET['id'];
		db_query($sql);
		systemmail($_GET['id'],'`\$HTML gesperrt!`0','`@'.$session['user']['name'].'`& hat HTML für deine Bio deaktiviert. Wahrscheinlich hast du es mit der Nutzung von Bildern übertrieben. Wenn du dir nicht sicher bist, solltest du vielleicht mal in einer Mail nach dem Grund fragen.');
		systemlog('`qSperrung des Bio-HTML für:`0 ',$session['user']['acctid'],$_GET['id']);
		
		$str_back = '/mb PID('.$_GET['id'].') kann von nun an kein HTML mehr in der Bio nutzen!';
	break;

//HTML ENTSPERREN
	case 'unlock_html':
		$sql = 'UPDATE account_extra_info SET html_locked=0 WHERE acctid = '.$_GET['id'];
		db_query($sql);		
		systemlog('`qEntsperrung des Bio-HTML für:`0 ',$session['user']['acctid'],$_GET['id']);
		
		$str_back = '/mb PID('.$_GET['id'].') kann wieder HTML in der Bio nutzen!';
	break;
	
//KNEBELN	
	case 'mute':
		user_update(
			array
			(
				'activated'=>USER_ACTIVATED_MUTE,
			),
			$_GET['id']
		);
		systemmail($_GET['id'],'`\$Geknebelt!`0','`@'.$session['user']['name'].'`& hat dich geknebelt, so dass du nun keine Kommentare mehr schreiben kannst. Warscheinlich hast du dich schlecht benommen oder gegen die Regeln verstoßen. Wenn du dir nicht sicher bist, solltest du vielleicht mal in einer Mail nach dem Grund fragen.');
		
		systemlog('`qKnebelung von:`0 ',$session['user']['acctid'],$_GET['id']);
		$str_back = '/mb PID('.$_GET['id'].') wurde von dir geknebelt!';
	break;

//ENTKNEBELN
	case 'demute':
		user_update(
			array
			(
				'activated'=>0,
			),
			$_GET['id']
		);		

		systemmail($_GET['id'],'`@Knebel entfernt!`0','`@'.$session['user']['name'].'`& hat dich wieder von deinem Knebel befreit.');
		
		systemlog('`qEntKnebelung von:`0 ',$session['user']['acctid'],$_GET['id']);
		$str_back = '/mb PID('.$_GET['id'].') wurde von dir entknebelt!';
	break;


//EXPEDITION RAUSWERFEN
	case 'exp_fire':
		
		user_update(
			array
			(
				'expedition'=>0,
			),
			$_GET['id']
		);
		
		systemmail($_GET['id'],'`\$Die Expedition ist für dich beendet!`0','`@Deine Einladung zur Expedition wurde dir entzogen. Du kannst in einer Anfrage nach dem Grund fragen.');
		
		debuglog('hat aus Expedition entlassen: ',$_GET['id']);
		
		$str_back = '/mb PID('.$_GET['id'].') nimmt nun nicht mehr an der Expedition teil!';
	break;

//EXPEDITION EINLADEN
	case 'exp_hire':
		user_update(
			array
			(
				'expedition'=>1,
			),
			$_GET['id']
		);

		systemmail($_GET['id'],'`\$Einladung zur Expedition!`0','`@Du wurdest zu einer Expedition in die dunklen Lande eingeladen. Du kannst das Lager über das Stadtzentrum erreichen.');
		
		debuglog('hat zu Expedition eingeladen: ',$_GET['id']);
		
		$str_back = '/mb PID('.$_GET['id'].') kann nun an der Expedition teilnehmen!';
	break;
	
	
//EXPEDITION
//BEFÖRDERN
	case 'exp_lvl_up':
		$row = db_fetch_assoc(db_query('SELECT name,login,ddl_rank FROM accounts WHERE acctid='.$_GET['id']));
		$ddl_rank=$row['ddl_rank'];
		
		if($ddl_rank==0){
			$ddl_rank=PROF_DDL_RECRUIT; 
		}
		elseif ($ddl_rank>=PROF_DDL_RECRUIT && $ddl_rank<PROF_DDL_COLONEL){ 
			if( $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $session['user']['ddl_rank']>$ddl_rank ){
				$ddl_rank++; 
			}
			else{
				$str_back = 'Diese Beförderung ist dir untersagt. Bitte wende dich an deinen Vorgesetzten.';
				$no_act = true;
			}
		}
		elseif($ddl_rank==PROF_DDL_COLONEL){
			$str_back = get_ddl_rank($ddl_rank).' '.$row['login'].' kann nicht weiter befördert werden!';
			$no_act = true;
		}
		else{
			$str_back = $row['login'].' hat schon ein anderes Amt!';
			$no_act = true;
		}
		
		if( !$no_act ){
			$rank= get_ddl_rank($ddl_rank);
			
			user_update(
				array
				(
					'ddl_rank'=>$ddl_rank,
				),
				$_GET['id']
			);			
			
			systemmail($_GET['id'],'`$DDL: Beförderung!`0','`@'.$session['user']['name'].'`& hat dich zum '.$rank.' der Bürgerwehr befördert!');
			addnews_ddl($session['user']['name'].'`& hat '.$row['name'].' zum `^'.$rank.'`& befördert!');
			$str_back = 'Du hast '.$row['login'].' zum '.$rank.' der B&uuml;rgerwehr bef&ouml;rdert!';
		}
		
		$str_back = '/mb '.$str_back;
	break;
//DEGRADIEREN
	case 'exp_lvl_down':
		$row = db_fetch_assoc(db_query('SELECT name,login,ddl_rank FROM accounts WHERE acctid='.$_GET['id']));
		$ddl_rank=$row['ddl_rank'];
		if($ddl_rank==PROF_DDL_RECRUIT){ 
			$ddl_rank=0; 
		}
		elseif($ddl_rank>PROF_DDL_RECRUIT && $ddl_rank<=PROF_DDL_COLONEL){
			if( $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $session['user']['ddl_rank']>$ddl_rank ){
				$ddl_rank--; 
			}
			else{
				$str_back = 'Diese Degradierung ist dir untersagt. Bitte wende dich an deinen Vorgesetzten.';
				$no_act = true;
			}
		}
		else{
			$str_back = $row['login'].' ist garnicht in der Bürgerwehr!';
			$no_act = true;
		}
		
		if( !$no_act ){
			$rank=get_ddl_rank($ddl_rank);
			
			user_update(
				array
				(
					'ddl_rank'=>$ddl_rank,
				),
				$_GET['id']
			);			
			systemmail($row['acctid'],'`$DDL: Degradierung!`0','`@'.$session['user']['name'].'`& hat dich zum '.$rank.' '.($ddl_rank?'der Bürgerwehr':'').' degradiert!');
			addnews_ddl($session['user']['name'].'`& hat '.$row['name'].' zum `^'.$rank.'`& degradiert!');
			$str_back = 'Du hast '.$row['login'].' zum '.$rank.' '.($ddl_rank?'der Bürgerwehr':'').' degradiert!';
		}
		
		$str_back = '/mb '.$str_back;
	break;

//STADTWACHE
	case 'guard_prison':
		$ok = 0;		
		$enemy = db_fetch_assoc(db_query('SELECT login, name, sex, imprisoned,loggedin, ((maxhitpoints/30)+(attack*1.5)+(defence)) AS strength, chat_section FROM accounts WHERE acctid='.$_GET['id']));
		$strength = (($session['user']['maxhitpoints']/30)+($session['user']['attack']*1.5)+($session['user']['defence']));	
		
		if( $strength < $enemy['strength'] ){
			$str_back = '/mb `^'.$enemy['name'].'`^ ist zu stark für dich!`0';	
		}
		else if( $enemy['imprisoned'] ){
			$str_back = '/mb `^'.$enemy['name'].'`^ sitzt schon im Kerker!`0';
		}
		else if( !$enemy['loggedin'] ){
			$str_back = '/mb `^'.$enemy['name'].'`^ ist verschwunden!`0';
		}
		//$rowextra2['profession_tmp'] == 0 ????
		
		else{
			$time = date('Y-m-d H:i:s',time()-600);
		
			// Auf Aktivität in derselben Chatarea prüfen
			/*$sql = 'SELECT c1.section FROM commentary c1
					INNER JOIN commentary c2 ON c1.section=c2.section AND c2.author='.$_GET['id'].' AND c2.postdate>"'.$time.'"
					WHERE c1.author='.$session['user']['acctid'].' AND c1.postdate>"'.$time.'" AND 
					(c1.section = "village" OR c1.section = "marketplace" OR c1.section = "garden") 
					ORDER BY c1.commentid DESC LIMIT 1';					
			$ok = db_fetch_assoc(db_query($sql));*/
			if( $enemy['chat_section'] == $session['user']['chat_section'] ){
				
				user_update(
					array
					(
						'imprisoned'=>-5,
						'location'=>USER_LOC_PRISON,
						'restatlocation'=>0
					),
					$_GET['id']
				);
			
				$msg = ': `5überwindet '.$enemy['login'].', packt '.($enemy['sex']?'sie':'ihn').' mit eisernem Griff und führt '.($enemy['sex']?'sie':'ihn').' Richtung Kerker!';
			
				$sql = 'INSERT INTO commentary SET comment="'.db_real_escape_string($msg).'",postdate=NOW(),author='.$session['user']['acctid'].',section="'.db_real_escape_string($ok['section']).'"';
				db_query($sql);
					
				$sql = 'UPDATE account_extra_info SET profession_tmp=1 WHERE acctid='.$session['user']['acctid'];
				db_query($sql);
			
				debuglog('nutzte seine Stadtwachenfähigkeiten und verhaftete ',$_GET['id']);
				addnews($session['user']['name'].'`# hat '.$enemy['name'].'`# in '.($session['user']['sex']?'ihrer':'seiner').' Eigenschaft als Stadtwache festgenommen und in den Kerker gesteckt!');
					
				systemmail($_GET['id'],'`$Verhaftet!',$session['user']['name'].'`$ hat dich soeben in '.($session['user']['sex']?'ihrer':'seiner').' Eigenschaft als Stadtwache festgenommen. Du darfst nun einen Tag im Kerker verbringen!');
				$str_back = '/mb `^Du hast '.$enemy['name'].'`^ eingekerkert!`0';
			}
			else{
				$str_back = '/mb `^'.$enemy['name'].'`^ ist verschwunden!`0';
			}			
		}
	break;


	//FULLSCREEN
	case 'fullscreen':
	   
	   $session['disablevital'] = $session['disablevital'] ? false : true;
	   $str_back = '';
	   session_write_close();
	   
	break;	
	
	// Stealthmode
	case 'stealth':
						
		$access_control->su_check(access_control::SU_RIGHT_STEALTH,true);
		if($session['user']['activated'] == USER_ACTIVATED_STEALTH) {
			$session['user']['activated'] =  0;	
			$str_back = '/mb ACHTUNG! Der Pöbel kann Eure Heiligkeit nun wieder mit seinen lüsternen Blicken beschmutzen.';
		}
		else {
			$session['user']['activated'] =  USER_ACTIVATED_STEALTH;	
			$str_back = '/mb Ihr seid nun in Eurer unantastbaren Weisheit vor den Augen des gemeinen Volkes unsichtbar!';
		}
		
		saveuser();
	   				   
	break;
	
	// Löschen von Anfragenantworten
	case 'petition_mail_del':
		
		$access_control->su_check(access_control::SU_RIGHT_PETITION,true);
		
		$pid = (int)$_GET['pid'];
		$mid = (int)$_GET['mid'];
		
		// Aus petitionmail löschen
		$sql = 'DELETE FROM petitionmail WHERE petitionid='.$pid.' AND messageid='.$mid;
		db_query($sql);
		
		// Aus Mailbox löschen
		$sql = 'DELETE FROM mail WHERE messageid='.$mid;
		db_query($sql);
		
		$str_back = '/mb Antwort wurde entfernt!';
		
	break;
	
	// Prüfen auf bereits vorhandene Anfragenantworten
	case 'petition_mail_check':
		
		$access_control->su_check(access_control::SU_RIGHT_PETITION,true);
		
		$pid = (int)$_GET['pid'];
		$time_from = (int)$_GET['time_from'];
		$time_from = date('Y-m-d H:i:s',$time_from);

		if(isset($_POST['body'])) {
			$session['pet_refresh_'.$pid] = stripslashes(utf8_encode($_POST['body']));
		}
			
		// Aus petitionmail löschen
		$sql = 'SELECT body FROM petitionmail WHERE petitionid='.$pid.' AND messageid>0 AND sent>"'.$time_from.'"';
		$res = db_query($sql);
		if(db_num_rows($res)) {
			$row = db_fetch_assoc($res);
			$str_back = $row['body'];	
		}
		
						
	break;
	
}

jslib_http_command($str_back);
exit;
?>