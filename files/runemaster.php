<?php
/**
* runemaster.php: Runenmeister
* @author Alucard <diablo3-clan[AT]web.de>
* @version DS-E V/2
* @TODO:
*/
require_once('common.php');
require_once(LIB_PATH.'runes.lib.php');
require_once(LIB_PATH.'disciples.lib.php');

checkday();
page_header('Runenmeister');

$out = '`^';
$session['runemaster_visit'] = ($session['runemaster_visit'] ? ($_GET['op']!='leave1' ? 2 : $session['runemaster_visit']) : 1);

switch( $_GET['op'] ){

	case 'master':
		$out .= '`b`c`SD`Te`;r R`Yunenmei`;st`Te`Sr`c`b`n`n';
		$out .= '`SI`Tm `;Sc`Yhatten eines knorrigen Astes, hat sich ein düster dreinblickender Mann ';
		$out .= 'nieder gelassen. Auf seinem Arm hockt ein schwarzer Rabe und zu seinen Füßen liegen etliche mit Runen versehene ';
		$out .= 'Steine. Ohne aufzublicken spricht die Gestalt mit finsterer Stimme:`n';
		$out .= '`STritt näher! Doch gib Acht! '.runes_rand_god().' wacht über uns!`n`n';
		$out .= '`YDu gehst auf die Gestalt zu und blickst ihn erfurchtsvoll an.';
		$out .= '`nEr mustert dich und spricht zu dir: ';
		$out .= '`SIch bin der Runenmeister! Was treibt Dich zu mir?`n`n';
		$out .= '`YWas tust du?`n';
		addnav('Was tust du?');
		addnav('Runen?','runemaster.php?op=runes');
		addnav('Preisliste','runemaster.php?op=pricelist');
		addnav('Mein Runenbuch','runemaster.php?op=buch');

		addnav('','runemaster.php?op=runes');
		$out .= '<a href="runemaster.php?op=runes">Ihn auf Runen ansprechen</a>`n';

		if(!(e_rand(1,23)%5) && $session['user']['turns']){
			$out .= create_lnk(	'Seinen Raben anfassen',
								'runemaster.php?op=raven', true, true, false, false,
								'Den Raben anfassen').'`n';
		}

		if( $session['user']['turns'] > 0 && item_count('tpl_id="r_raidho" AND owner='.$session['user']['acctid']) > 0  ){
			$out .= create_lnk(	'Erkläre mir den Weg zur Orkburg für eine Raidho - Rune!',
								'runemaster.php?op=castle', true, true, false, false,
								'Zur Burg (Raidho)').'`n';
		}

		if( item_count('tpl_id="r_dagaz" AND owner='.$session['user']['acctid']) > 0 ){
			$out .= create_lnk(	'Entzaubere mich für eine Dagaz - Rune!',
								'runemaster.php?op=unban', true, true, false, false,
								'Entzaubern (Dagaz)').'`n';
		}

		if( item_count('tpl_id="r_jera" AND owner='.$session['user']['acctid']) > 0 ){
			$out .= create_lnk(	'Fülle Runden auf für eine Jera - Rune!',
								'runemaster.php?op=fillup', true, true, false, false,
								'Runden auffüllen (Jera)').'`n';
		}

		if( item_count('tpl_id="r_ansuz" AND owner='.$session['user']['acctid']) > 0 ){
			$out .= create_lnk(	'Graviere meine Rüstung für eine Ansuz - Rune!',
								'runemaster.php?op=armorrename', true, true, false, false,
								'Rüstung gravieren (Ansuz)').'`n';
		}

		if( item_count('tpl_id="r_sowilo" AND owner='.$session['user']['acctid']) > 0 ){
			$out .= create_lnk(	'Taufe meinen Knappen für eine Sowilo - Rune!',
								'runemaster.php?op=disciplerename', true, true, false, false,
								'Knappen taufen (Sowilo)').'`n';
		}
		
		if( item_count('tpl_id="r_wunjo" AND owner='.$session['user']['acctid']) > 0 ){
			$out .= create_lnk(	'Verschönere den Namen meines Knappen für eine Wunjo - Rune!',
								'runemaster.php?op=disciple_color', true, true, false, false,
								'Knappen umfärben (Wunjo)').'`n';
		}

		if( item_count('tpl_id="r_teiwaz" AND owner='.$session['user']['acctid']) > 0 ){
			$out .= create_lnk(	'Taufe meine Puppe für eine Teiwaz - Rune!',
								'runemaster.php?op=barbierename', true, true, false, false,
								'Puppe taufen (Teiwaz)').'`n';
		}

		if( item_count('tpl_id="r_laguz" AND owner='.$session['user']['acctid']) > 0 ){
			$out .= create_lnk(	'Ändere den Charakter meines Knappens für eine Laguz - Rune!',
								'runemaster.php?op=disciple_char', true, true, false, false,
								'Knappencharakter (Laguz)').'`n';
		}

		if( item_count('tpl_id="r_gebo" AND owner='.$session['user']['acctid']) > 0 ){
			$out .= create_lnk(	'Heilerin Golinda für 4 weitere Tage Gebo - Rune!',
								'runemaster.php?op=golinda', true, true, false, false,
								'Golinda (Gebo)').'`n';
		}

		if( item_count('tpl_id="r_ehwaz" AND owner='.$session['user']['acctid']) > 0 ){
			$out .= create_lnk(	'Zeige mir ein Runenrezept für eine Ehwaz - Rune!',
								'runemaster.php?op=rezept', true, true, false, false,
								'Runenrezept (Ehwaz)').'`n';
		}

		if( item_count('tpl_id="r_algiz" AND owner='.$session['user']['acctid']) > 0 ){
			$out .= create_lnk(	'Sortiere negative, unbekannte Tränke für eine Algiz - Rune aus!',
								'runemaster.php?op=unkn_trank', true, true, false, false,
								'Tränke aussortieren (Algiz)').'`n';
		}

		if( $session['user']['exchangequest']==17 ){
			$out .= create_lnk(	'Ihn auf bunte, leuchtende Steine ansprechen',
								'exchangequest.php', true, true, false, false,
								'`%Bunte Steine?`0').'`n';
		}

		$out .= create_lnk(	'Diesen Ort verlassen',
							'runemaster.php?op=leave1', true, true, false, false,
							'Gehen').'`n';

		addnav('Orte');
		addnav('d?Zurück zum Stadtzentrum','runemaster.php?op=leave1&target=village');
		addnav('E?Zur Eiche','runemaster.php?op=leave1&target=oak');
	break;


	case 'leave1':
		if(isset($_GET['target']))
		{
			unset($session['runemaster_visit']);
			if($_GET['target'] == 'village') redirect('village.php');
			if($_GET['target'] == 'oak') redirect('greatoaktree.php');
			exit();
		}
		if( $session['runemaster_visit'] == 2 ){
			$out .= '`YDu verabschiedest dich freundlich vom Runenmeister und wendest dich ab.';
		}
		else{
			$out .= '`YDu sagst, dass du dich verlaufen hättest und kehrst ihm den Rücken zu.';
		}
		unset($session['runemaster_visit']);
		addnav('Orte');
		addnav('d?Zurück zum Stadtzentrum','village.php');
		addnav('E?Zur Eiche','greatoaktree.php');
	break;

	case 'pricelist':
		$arr_tmp   = user_get_aei('runes_ident');
		$arr_ident = utf8_unserialize($arr_tmp['runes_ident']);
		$out .= '`c<table>
					<tr class="trhead"><td colspan="2" align="center">`bPreisliste des Runenmeisters`b</td></tr>
					<tr><td>`&Knappencharakter ändern:`0</td><td>`q'.( $arr_ident['21'] ? 'Laguz' : '#%4k*!§Ö@ `@(irgendwie kannst du den Preis nicht lesen)' ).'`0</td></tr>
					<tr><td>`&Knappen taufen:`0</td><td>`q'.( $arr_ident['16'] ? 'Sowilo' : '[/&"#+~ `@(irgendwie kannst du den Preis nicht lesen)' ).'`0</td></tr>
					<tr><td>`&Knappenname färben:`0</td><td>`q'.( $arr_ident['8'] ? 'Wunjo' : '@Ü:SX<~ `@(irgendwie kannst du den Preis nicht lesen)' ).'`0</td></tr>
					<tr><td>`&Puppe taufen:`0</td><td>`q'.( $arr_ident['17'] ? 'Teiwaz' : '!&copy;=}\\9 `@(irgendwie kannst du den Preis nicht lesen)' ).'`0</td></tr>
					<tr><td>`&Zur Burg Reiten:`0</td><td>`q'.( $arr_ident['5'] ? 'Raidho' : '(G93Äö*.; `@(irgendwie kannst du den Preis nicht lesen)' ).'`0</td></tr>
					<tr><td>`&Rüstung gravieren:`0</td><td>`q'.( $arr_ident['4'] ? 'Ansuz' : '@Ü:SX<| `@(irgendwie kannst du den Preis nicht lesen)' ).'`0</td></tr>
					<tr><td>`&Runden auffüllen:`0</td><td>`q'.( $arr_ident['12'] ? 'Jera' : '§6$&@-3k `@(irgendwie kannst du den Preis nicht lesen)' ).'`0</td></tr>
					<tr><td>`&Entzaubern:`0</td><td>`q'.( $arr_ident['23'] ? 'Dagaz' : '?ß+´^°. `@(irgendwie kannst du den Preis nicht lesen)' ).'`0</td></tr>
					<tr><td>`&Golinda für 4 Tage:`0</td><td>`q'.( $arr_ident['7'] ? 'Gebo' : '&%9)[+*€ `@(irgendwie kannst du den Preis nicht lesen)' ).'`0</td></tr>
					<tr><td>`&Identifizieren:`0</td><td>`q'.( $arr_ident['6'] ? 'Kenaz' : '\'+~|>Dx_ `@(irgendwie kannst du den Preis nicht lesen)' ).' o. <img src="./images/icons/gem.gif" valign="middle" alt="Edelsteine" title="Edelsteine">`#'.RUNE_IDENTPAY_GEMS_VALUE.'`q o. <img src="./images/icons/gold.gif" valign="middle" alt="Goldstücke" title="Goldstücke">`t'.RUNE_IDENTPAY_GOLD_VALUE.'`0</td></tr>
					<tr><td>`&Runenrezept:`0</td><td>zufällig: `q'.( $arr_ident['19'] ? 'Ehwaz' : '.F%{=8c# `@(irgendwie kannst du den Preis nicht lesen)' ).'`0 bestimmtes Ergebnis: +<img src="./images/icons/gem.gif" valign="middle" alt="Edelsteine" title="Edelsteine">`#10`0</td></tr>
					<tr><td>`&Unbekannte Tränke aussortieren:`0</td><td>`q'.( $arr_ident['15'] ? 'Algiz' : 'K.²@³_R `@(irgendwie kannst du den Preis nicht lesen)' ).'`0</td></tr>
					<!--<tr><td>`0</td><td>`0</td></tr>-->
				</table>`c';
		addnav('Zurück','runemaster.php?op=master');
	break;


/*SPECIAL ZEUG DER RUNEN*/

//Burg
	case 'castle':
		$out .= '`YDu fragst den Runenmeister, ob er den Weg zur Orkburg kennt. Er nickt und spricht:`&`n';
		$out .= '`SIch kann Euch den Weg erklären, jedoch will ich eine Raidho - Rune dafür!`n';
		addnav('Was tust du?');
		addnav('Rune geben','runemaster.php?op=castle2');
		addnav('Nein, zurück!','runemaster.php?op=master');
	break;

	case 'castle2':
		$out .= '`YNach kurzem Überlegen stimmst du zu und gibst ihm die Rune.`n';
		$out .= 'Er verstaut sie in seinem Beutel und fängt dann an dir folgendes zu erkären:`&`n';
		$out .= '`SGeht zunächst zum Wald! Dort findet Ihr ein Klohäuschen, an dessen Seite sich ein Schild befindet, was den weiteren Weg zur Orkburg weist.`n';
		$out .= '`YDu nickst und hörst aufmerksam zu.`n';
		$out .= 'Er spricht weiter: `&`n';
		$out .= '`SFolgt einfach dem Pfad! Doch gebt Acht, dass Ihr Euch nicht verlauft!`n';
		$out .= '`YDu winkst voller Selbstbewusstsein ab, grinst und machst dich auf den Weg.';
		item_delete('tpl_id="r_raidho" AND owner='.$session['user']['acctid'],1);
		$session['user']['specialinc']="castle.php";
		addnav('Auf zur Burg!', 'forest.php');
	break;

//Entzaubern (Flauschihasi, Kröte, Raminus Sklave)
	case 'unban':
		$out .= '`YDu fragst den Runenmeister, ob er dich nicht von deinem Leiden deines Aussehens erlösen könne. Er nickt und spricht:`&`n';
		$out .= '`SIch kann Euch entzaubern, jedoch will ich eine Dagaz - Rune dafür!`n';
		addnav('Was tust du?');
		addnav('Rune geben','runemaster.php?op=unban2');
		addnav('Nein, zurück!','runemaster.php?op=master');
	break;

	case 'unban2':
		$out .= '`YNach kurzem Überlegen stimmst du zu und gibst ihm die Rune.`n';
		$out .= 'Er verstaut sie in seinem Beutel, holt einige Kräuter aus seinem Mantel, zerreibt sie in seinen Händen und schmiert sie dir auf die Stirn.`n';
		$out .= 'Als du im ersten Augenblick keine Veränderung -mit der Ausnahme, dass du nun auch noch Pampe im Gesicht hast- feststellst, willst du dein '.$session['user']['weapon'];
		$out .= ' ziehen und es dem Runenmeister heimzahlen. Jedoch merkst du, dass du dich nicht bewegen kannst. Plötzlich wird dir sehr warm und `#*PUFF* `^ein Lauter Knall mit viel Schwefelgestank ';
		$out .= 'und du merkst, dass du wieder der Alte bist.`n';
		$out .= 'Der Runenmeister grinst und du bedankst dich.';
		
		$oldname=$session['user']['name'];
		$titles = utf8_unserialize((getsetting('title_array',null)) );
		$session['user']['title'] = $titles[ min($session['user']['dragonkills'], sizeof($titles)-1) ][ $session['user']['sex'] ];
		$row_extra=user_get_aei('ctitle,cname,csign');
		if ($row_extra['ctitle']=='`$Ramius '.($session['user']['sex']?'Sklavin':'Sklave'))
		{
			$row_extra['ctitle']='';
			user_set_aei($row_extra);
		}
		$row_extra['login']=$session['user']['login']; //DB-Abfrage sparen
		$row_extra['title']=$session['user']['title'];
        user_retitle($session['user']['acctid'],false,$row_extra['title'],true,-1);
        user_set_name($session['user']['acctid']);
		
		item_delete('tpl_id="r_dagaz" AND owner='.$session['user']['acctid'],1);
		addnews('`@'.$oldname.'`@ ist dank der `qRunenmagie`@ wieder bekannt als '.$session['user']['name'].'.');
		addnav('Juhu!', 'runemaster.php?op=master');
	break;

//Runden auffüllen
	case 'fillup':
		$resdisc = db_query("SELECT state FROM disciples WHERE master = ".$session['user']['acctid']);
		$disciple = db_fetch_assoc($resdisc);
		$out .= '`YDu fragst den Runenmeister, ob er auch Erfrischen könne. Er nickt und spricht:`&`n';
		$out .= '`SIch kann Euch um 10 Runden'.($session['user']['hashorse']?', Euer Tier komplett':'').($disciple['state']>0?' oder Euern Knappen komplett':'').' erfrischen, jedoch will ich eine Jera - Rune dafür!`n';
		addnav('Erfrischen');
		addnav('Mich selbst','runemaster.php?op=fillup2&w=self');
		if ($session['user']['hashorse']){
			addnav('Tier','runemaster.php?op=fillup2&w=pet');
		}
		if($disciple['state']>0){
			addnav('Knappen','runemaster.php?op=fillup2&w=disc');
		}
		addnav('Zurück');
		addnav('Nein, zurück!','runemaster.php?op=master');
	break;

	case 'fillup2':
		switch( $_GET['w'] ){

		case 'disc'://knappe
				$disciple = get_disciple();

				if($disciple['state'] > 0)
				{
					$out .= '`YDu gibst ihm die Rune und sagst, dass du deinen Knappen eine Erfrischung gönnen willst.`n';
					$out .= 'Er verstaut sie in seinem Beutel und gibt deinem Knappen einen Zaubertrank, welchen `@'.$disciple['name'].'`^ sofort trinkt und sich wie neugeboren fühlt.';
					$session['bufflist']['decbuff'] = $disciple['buff'];
				}
			break;

			case 'self': //mich selbst
				$session['user']['turns'] += 10;
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
				$out .= '`YDu gibst ihm die Rune und sagst, dass du dir selbst eine Erfrischung gönnen willst.`n';
				$out .= 'Er verstaut sie in seinem Beutel und gibt dir einen Zaubertrank, welchen du sofort trinkst und dich wie neugeboren fühlst.`n`n';
				$out .= '`@Du bekommst 10 Waldkämpfe und deine Lebenspunkte wurden vollständig aufgefüllt.';
			break;

			case 'pet': //tier

				getmount($session['user']['hashorse'],true);
				$sql = 'SELECT hasxmount,mountextrarounds,xmountname FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
				$res = db_query($sql);
				$row_extra = db_fetch_assoc($res);
				$session['bufflist']['mount']=utf8_unserialize($playermount['mountbuff']);

				if ($row_extra['hasxmount']==1) {
					$session['bufflist']['mount']['name']=$row_extra['xmountname']." `&({$session['bufflist']['mount']['name']}`&)";
					$tier = $row_extra['xmountname'];
				}
				else{
					$tier = 'Tier';
				}

				$session['bufflist']['mount']['rounds']+=$row_extra['mountextrarounds'];

				$out .= '`YDu gibst ihm die Rune und sagst, dass du deinem Tier eine Erfrischung gönnen willst.`n';
				$out .= 'Er verstaut sie in seinem Beutel und gibt deinem '.$tier.'`^ einen Zaubertrank, welchen dein `@'.$tier.'`^ sofort trinkt und sich wie neugeboren fühlt.';
			break;
		}

		item_delete('tpl_id="r_jera" AND owner='.$session['user']['acctid'],1);
		addnav('Juhu!', 'runemaster.php?op=master');
	break;

//Rüstung Gravieren
	case 'armorrename':
		$out .= '`YDu fragst den Runenmeister, ob er dir deine Rüstung verschönern könne. Er schaut deine Rüstung an und sagt dann:`&`n';
		$out .= '`SFür eine Ansuz - Rune kann ich Euch Eure Rüstung mit einer Gravur nach Wunsch prachtvoll verzieren! Beachtet jedoch, dass sie dabei unter Umständen an Kraft einbüßen wird. Wer schön sein will..`n`n`n';
		$out .= '`bEine Rüstung benennen`b`n';
		$out .= '`^Der Name deiner Rüstung darf 30 Zeichen lang sein und Farbcodes enthalten.`nVermeide es schwarz zu verwenden, da diese Farbe auf dunklem Hintergrund gar nicht oder nur schlecht angezeigt wird.`n`n';
		$out .= '`YDeine Rüstung heißt bisher: `&'.$session['user']['armor'];
		$out .= '`n`n`YWie soll deine Rüstung heißen ?`n';
		$out .= js_preview('newname').'`n';
		$out .= '<form action="runemaster.php?op=armorrename2" method="POST"><input name="newname" id="newname" value="" size="30" maxlength="30" onkeyup="this.focus()"> <input type="submit" value="Gravieren"></form>';
		addnav('','runemaster.php?op=armorrename2');
		addnav('Zurück?');
		addnav('Zurück!','runemaster.php?op=master');

	break;


	case 'armorrename2':

		// überarbeitete Lösung um nachträgliches High-Grade usw. Gepushe abzumildern, immernoch ugly
		$int_max_def = min($session['user']['armordef'],25);
		if (mb_substr_count($session['user']['armor'],'High-Grade ')) $str_pre.='High-Grade ';
		if (mb_substr_count($session['user']['armor'],'verstärkt ')) $str_pre.='verstärkt ';
		if (mb_substr_count($session['user']['armor'],'gehärtet ')) $str_pre.='gehärtet ';
		if (mb_substr_count($session['user']['armor'],' +1')) $str_suf.=' +1';
		if (mb_substr_count($session['user']['armor'],' +2')) $str_suf.=' +2';
		if (mb_substr_count($session['user']['armor'],' -1')) $str_suf.=' -1';
		if (mb_substr_count($session['user']['armor'],' -2')) $str_suf.=' -2';
		if (mb_substr_count($session['user']['armor'],' G:')) {
			$guildplus=mb_substr($session['user']['armor'],mb_strrpos($session['user']['armor'],'G')+2,1);
			$str_suf.=' G:'.$guildplus;
		}

		item_set_armor($str_pre.trim($_POST['newname']).$str_suf,$int_max_def,-1,0,0,1);
		item_delete('tpl_id="r_ansuz" AND owner='.$session['user']['acctid'],1);
        $out .= '`YGratulation, deine Rüstung heißt jetzt '.$session['user']['armor'].'`0!`n`n';
		addnav('Wie schön!');
		addnav('Gut gemacht!','runemaster.php?op=master');
	break;
	
//Knappe umfärben
	case 'disciple_color':
		$disc = get_disciple();
		$out .= '`YDu fragst den Runenmeister, ob er den Namen deines Knappen nicht verschönern könne. Er nickt und spricht zu dir:`&`n';
		if( $disc['state']>0 ){
			$out .= '`n`n`4`bSo soll mein Knappe aussehen:`b`0`n';
			$out .= js_preview('newname').'`n';
			output($out);
			rawoutput('<form action="runemaster.php?op=disciple_color2" method="POST"><input name="newname" id="newname" value="'.utf8_htmlspecialchars($disc['name']).'" size="30" maxlength="90"> <input type="submit" value="Verschönern"></form>');			
			$out = '';
			addnav('','runemaster.php?op=disciple_color2');
			addnav('Zurück?');
			addnav('Zurück!','runemaster.php?op=master');
		}
		else{
			$out .= '`^Als er sich jedoch umschaut, kann er deinen Knappen weit und breit nicht entdecken.';
			addnav('Huch! Wie peinlich!', 'runemaster.php?op=master');
		}
		break;
		
	case 'disciple_color2':
		$disc = get_disciple();
		$str_name  = stripslashes($_POST['newname']);
		
		if (strip_appoencode($disc['name'],3) != strip_appoencode($str_name,3))
		{
			$out .= '`YAls du dem Runenmeister den Namen nennst, schaut er dich an und sagt:`n`&';
			$out .= 'Deinen Knappen umzubenennen kostet dich aber eine andere Rune. Für eine Wunjo färbe ich diesen nur ein.';
			addnav('Schade!');
			addnav('Nochmal!','runemaster.php?op=disciple_color');
			addnav('Zurück!','runemaster.php?op=master');
		}
		else 
		{
			if(mb_strrpos($str_name,'`0') != mb_strlen($str_name)-2) {
				$str_name .= '`0';
			}
			$out .= '`YDu schilderst dem Runenmeister, wie der Name deines Knappen aussehen könnte und gibst ihm eine Wunjo-Rune. Er winkt deinen Knappen zu sich herran und geht mit ihm hinter einen Hügel, als du ihnen folgen willst, erblickst du niemanden und setzt dich nieder.`n';
			$out .= 'Nach einiger Zeit kommt dein Knappe und der Runenmeister spricht zu dir:`n`&';
			$out .= '`SHerr'.($session['user']['sex']?'in':'').'! Seht doch `#'.$str_name.'`Ss neuer schöner Name.`n';
			addnav('Wie schön!');
			item_delete('tpl_id="r_wunjo" AND owner='.$session['user']['acctid'],1);
			addnav('Sehr fein!', 'runemaster.php?op=master');
			addnews($session['user']['name'].'`@\'s Knappe ist nun bekannt als '.$str_name);
			db_query('UPDATE disciples SET name="'.addstripslashes($str_name).'" WHERE master='.$session['user']['acctid']);
		}
		
		break;
		
//Knappen taufen
	case 'disciplerename':
		$disc = get_disciple();
		$out .= '`YDu fragst den Runenmeister, ob er deinen Knappen nach uraltem Brauch taufen könne. Er nickt und spricht zu dir:`&`n';
		$out .= '`SGewiss! Nur müsse er etwas seiner Kraft opfern und ich brauche eine Sowilo Rune für dieses Ritual!`n`n';
		if( $disc['state']>0 ){
			$out .= '`n`n`4`bTauft meinen Knappen auf:`b`0`n';
			$out .= js_preview('newname').'`n';
			$out .= '<form action="runemaster.php?op=disciplerename2" method="POST"><input name="newname" id="newname" value="" size="30" maxlength="60"> <input type="submit" value="Taufen"></form>';
			addnav('','runemaster.php?op=disciplerename2');
			addnav('Zurück?');
			addnav('Zurück!','runemaster.php?op=master');
		}
		else{
			$out .= '`^Als er sich jedoch umschaut, kann er deinen Knappen weit und breit nicht entdecken.';
			addnav('Huch! Wie peinlich!', 'runemaster.php?op=master');
		}

	break;


	case 'disciplerename2':
		$str_name = stripslashes(trim($_POST['newname']));
		$str_name = strip_appoencode($str_name,2);
		$str_valid = evaluate_user_rename( user_rename(0, stripslashes(strip_appoencode($_POST['newname'],3)), false, false, USER_NAME_BADWORD | USER_NAME_BLACKLIST, true));
		// Prüfe ob User Knappen schon einmal weggeben hat, wenn ja, hole den alten Namen
		if($Char->getConfBit(UBIT_DISABLE_DISCREM) == 8)
		{
			$sql = 'SELECT discname
							FROM disc_rem_list
							WHERE owner_id="'.$Char->acctid.'"';
			$res = db_query($sql);
			$result = db_fetch_assoc($res);
			$str_oldname = stripslashes(trim($result['discname']).'`0');
			$str_oldname = strip_appoencode($str_oldname,3);
			$str_name2 = strip_appoencode($str_name,3);
		}
		if(true !== $str_valid) {
			$out .= '`YAls du dem Runenmeister den Namen nennst, schaut er dich an und sagt:`n`&';
			$out .= '`4Dieser Name ist von den Göttern nicht erwünscht! Und ich glaube kaum, dass dein Knappe gern '.$str_name.'`& heissen will!';
			$out .= $str_valid;
			addnav('Schade!');
			addnav('Nochmal!','runemaster.php?op=disciplerename');
			addnav('Zurück!','runemaster.php?op=master');
		}
		elseif(mb_strlen($str_name) < 3)
		{
			$out .= '`b`4Achtung!`b`n`^Naja, wir wollen mal nicht untertreiben, also 3 Buchstaben sollte ein Name schon haben, oder? Stell dir doch mal den armen Knappen vor. Da wird jemand auf dem Stadtplatz geschlagen, ruft "AUUU" und dein Knappe fühlt sich angesprochen...oder "IIIIEH" oder "Bäääää"...also das wollen wir unseren Knappen nicht zumuten!';
			addnav('Schade!');
			addnav('Nochmal!','runemaster.php?op=disciplerename');
			addnav('Zurück!','runemaster.php?op=master');
		}
		elseif(mb_strlen($str_name) > 40 && (!mb_strpos($str_name,' ') || mb_strpos($str_name,' ') > 38)){
			$out .= '`b`4Achtung!`b`n`^Im Namen deines Knappen muss nach höchstens 38 Zeichen ein Leerzeichen kommen, da es sonst zu Darstellungsfehlern in den Chatsections kommt.';
			addnav('Schade!');
			addnav('Nochmal!','runemaster.php?op=disciplerename');
			addnav('Zurück!','runemaster.php?op=master');
		}
		elseif(isset($str_oldname) && $str_name2 == $str_oldname){
			$out .= '`b`4Dein alter Knappe trug diesen Namen bereits, deshalb kann ich dir diesen Namen nicht gestatten! Versuche einen anderen.';
			addnav('Schade!');
			addnav('Nochmal!','runemaster.php?op=disciplerename');
			addnav('Zurück!','runemaster.php?op=master');
		}
		else{
		item_delete('tpl_id="r_sowilo" AND owner='.$session['user']['acctid'],1);
			$out .= '`YDu nennst dem Runenmeister den Namen und gibst ihm eine Sowilo-Rune. Er winkt deinen Knappen zu sich herran und geht mit ihm hinter einen Hügel, als du ihnen folgen willst, erblickst du niemanden und setzt dich nieder.`n';
			$out .= 'Nach einiger Zeit kommt dein Knappe und spricht zu dir:`n`S';
			$out .= 'Herr'.($session['user']['sex']?'in':'').'! Fortan sollt Ihr mich `#'.$str_name.'`S rufen, sobald ihr meine Dienste braucht.`n';
			$out .= '`YDarauf hin fügt der Runenmeister ergänzend hinzu:`n`&';
			$out .= '`SEr ist nun leider etwas schwächer, aber ich bin überzeugt, dass er dieses Defizit, dank des Trainings mit Euch, schnell wieder wett gemacht haben wird!';
			addnav('Wie schön!');
			addnav('Freude!','runemaster.php?op=master');
			addnews($session['user']['name'].'`@\'s Knappe ist nun bekannt als '.$str_name);
			db_query('UPDATE disciples SET name="'.addstripslashes($str_name).'", level = IF(level>0,level-1,0) WHERE master='.$session['user']['acctid']);

			$arr_disc = get_disciple();
			$session['bufflist']['decbuff'] = $arr_disc['buff'];

		}

	break;
//Puppe umbenennen
	case 'barbierename':
		$res  = item_list_get('tpl_id="kpuppe" AND owner='.$session['user']['acctid'].'', '', false);
		if( !db_num_rows($res) ){
			$out .= '`YPuppe! Puppe? Welche Puppe?`nMit verstörtem Blick verlässt du diesen Ort.';
			addnav('Wo ist der Psychiater?','village.php');
		}
		else{
			$out .= '`YDu hast gehört, dass der Runenmeister die Knappen mancher Leute tauft und fragst ihn deshalb zögernd, ob er nicht auch deine Kadaverpuppe taufen könne! Er lacht laut auf und spricht zu dir:`S`n';
			$out .= '`SIch kann es versuchen. Aber dieses Ritual ist eigentlich für Knappen vorgesehen. So manche Puppentaufe hat die Götter schon erzürnt. Es liegt nun an Euch, ob Ihr dieses Risiko eingehen wollt.`n`n';
			$out .= '`n`n`4`bTauft meine Puppe auf:`b`0`n';
			$out .= '<form action="runemaster.php?op=barbierename2" method="POST"><table>';
			$i = 0;
			while( ($p = db_fetch_assoc($res)) ){
				$out .= '<tr><td valign="top"><input '.(!$i?'checked':'').' type="radio" name="pupid" value="'.$p['id'].'"></td><td>`b'.$p['name'].'`b`n'.$p['description'].'</td></tr>';
				$i=1;
			}
			$out .= '</table>';
			$out .= js_preview('newname').'`n';
			$out .= '<input name="newname" id="newname" value="" size="30" maxlength="30"> <input type="submit" value="Taufen"></form>';
			addnav('','runemaster.php?op=barbierename2');
			addnav('Zurück?');
			addnav('Zurück!','runemaster.php?op=master');
		}

	break;


	case 'barbierename2':
		//$out .= $_POST['newname'].'__'.$_POST['pupid'];
		$str_name  = $_POST['newname'];
		$str_valid = evaluate_user_rename (user_rename(0, stripslashes(strip_appoencode($_POST['newname'],3)), false, false, USER_NAME_BADWORD, true));
		if(true !== $str_valid) {
			$out .= '`YAls du dem Runenmeister den Namen nennst, schaut er dich an und sagt:`n`&';
			$out .= '`4Dieser Name ist von den Göttern nicht erwünscht!`n';
			$out .= $str_valid;
			addnav('Schade!');
			addnav('Nochmal!','runemaster.php?op=barbierename');
			addnav('Zurück!','runemaster.php?op=master');
		}
		else{
			item_delete('tpl_id="r_teiwaz" AND owner='.$session['user']['acctid'],1);
			$out .= '`YDu nennst dem Runenmeister den Namen und gibst ihm eine Teiwaz-Rune und die Puppe. Er geht hinter einen Hügel, als du ihm folgen willst, erblickst du niemanden und setzt dich nieder.`n';
			$out .= 'Nach einiger Zeit kommt der Runenmeister zu dir und verkündet ';
			$out .= 'erleichtert: `n`SDas Ritual verlief erfolgreich!';
			addnav('Wie schön!');
			addnav('Freude!','runemaster.php?op=master');
			$succ = true;
			$str_name .= '`0';
			$out .= '`nDeine Puppe heisst jetzt: '.$str_name;
			item_set ( ' id='.intval($_POST['pupid']), array('name'=>$str_name) );
		}

	break;

//KNAPPENCHARAKTER ÄNDERN
	case 'disciple_char':
		addnav('Was tust du?');
		$disc = get_disciple();
		$out .= '`YDa dir der Charakter deines Knappens nicht gefällt, fragst du den Runenmeistern, ob er ihn beinflussen kann. Der Runenmeister lacht:`S`n';
		$out .= '`SNichts leichter, als das! Ich werde Euern Knappen schon umerziehen. Da, ich dies auf magische Weise tue, benötige ich aber eine Laguz - Rune dafür!`n';
		if( $disc['state'] ){
			$out .= '`n`YWas sagst du?`n'.
					create_lnk(	'Tut mir leid! Das ist mir zu teuer!',
								'runemaster.php?op=master', true, true, false, false,
								'Zu teuer! Zurück!').'`n`n
					<form action="runemaster.php?op=disciple_char2" method="POST">
						`SMein '.get_disciple_stat($disc['state'], 'er').' '.$disc['name'].'`S soll ab sofort ein
						<select name="char">';
						for( $i=1; $i<21; ++$i ){
							if( $disc['state'] == $i ) continue;
							$new_char = get_disciple_stat($i, 'er');
							if( !empty($new_char) ){
								$out .= '<option value='.$i.'>'.$new_char.'</option>';
							}
						}
			addnav('','runemaster.php?op=disciple_char2');
			$out .= '</select> '.$disc['name'].'`S sein!`n
					<input type="submit" value="Ausführung!">
					</form>';
		}
		else{
			$out .= '`^Als er sich jedoch umschaut, kann er deinen Knappen weit und breit nicht entdecken.';
			addnav('Huch! Wie peinlich!', 'runemaster.php?op=master');
		}


	break;


	case 'disciple_char2':
		$can_do_this = true;
		$rnd_char = e_rand(1,21);
		$wish_char = $_POST['char'];
		$tmp = get_disciple_stat($rnd_char);
		$new_char = ((empty($tmp) || e_rand(1,50)!=23) ? $wish_char : $rnd_char);
		$out .= '`S"Oh es soll also ein `b`%'.get_disciple_stat($new_char, 'er').'`b`S Knappe werden!"`Y, sagt der Runenmeister und verschwindet mit deiner Laguz - Rune.`n
				Nach kurzer Zeit kehrt er zurück und ';
		switch( $new_char ){
			case 1:
				$out .= 'gibt deinem Knappen einen Verjüngungstrank. Dein Knappe leert diesen in einem Zug und du merkst, wie er immer jünger wird.`n
						`S"Ich hoffe, dass er Euch nun jung genug ist."`Y, sagt der Runenmeister.';
			break;

			case 2:
				$out .= 'verabreicht deinem Knappen ein ganzes Fass `tSlim Fast`Y. In Windeseile ist dein Knappe spindeldürr!';
			break;

			case 3:
				$out .= 'gibt deinem Knappen eine Wachstumsserum. Dein Knappe schlürft dieses genüsslich und schießt plötzlich wie eine Bohnenranke in die Höhe.`n
						Kommentar vom Runenmeister: `S"Praktischer Schattenspendender!"';
			break;

			case 4:
				$out .= 'gibt deinem Knappen einen `&Eiweißshake `Yund `#10 Muscle-Power Riegel`Y. Nachdem die, nich gerade köstlichen, Dinge von deinem Knappen heruntergewürgt wurden, wachsen seine Muskeln unaufhörlich.`n
						Der Runenmesiter meint: `S"Nun ist jeder Kampf so gut, wie gewonnen!"';
			break;

			case 5:
				$out .= 'trägt deinem Knappen eine Gurkenmaske aus `@Kala\'s Beautyshop`Y auf.`nNach einiger Zeit wäscht er deinem Knappen das Gesicht und sagt: `S"Welch ein Schönling!"`n
						`YDu wirkst neben deinem Knappen plötzlich so hässlich und verlierst `#1`Y Charmepunkt!';
				$session['user']['charm']--;
			break;

			case 6:
				$out .= 'heftet deinem Knappen 3 Orden an die Brust. Dein Knappe wird sofort richtig stolz, auch wenn er nicht weiss, wofür er diese Orden bekommen hat.`n
						Solch ein stolzer Knappe hat auch Auswirkung auf dich. Dein Ansehen in der Stadt steigt etwas.';
				$session['user']['reputation']++;
			break;

			case 7:
				$out .= 'lässt deinen Knappen von der `4Anti-Supernanny`Y erziehen. `S"Ey du blöde Schla*piep*! Halts Maul und verpiss dich!"`Y hört man nur noch aus dem Knappenmund.`n
						Der Runenmeister blickt entsetzt, dann meint er aber gleichgültig: `S"Ihr habt es so gewollt!"`n
						`YSo ein Knappe kann deinem Ansehen in der Stadt nur schaden!';
				$session['user']['reputation']--;
			break;

			case 8:
				$disc = get_disciple();
				$out .= 'liest deinem Knappen eine Geschichte vor, in der es einen Helden namens `b'.$disc['name'].'`b`Y gibt. Dein Knappe steigert sich so in die Erzählung des Runenmeisters hinein, dass er selbst nach Ende der Vorlesung nicht in die Realität zurückfindet.`n
						`S"Der wird nun sein ganzes Leben lang verträumt herumlaufen."`Y, sagt der Runenmeister zynisch.';
			break;

			case 9:
				$out .= 'gibt ihm die `tdeutsche offline-Ausgabe von <a href="http://de.wikipedia.org/wiki/Lotgd" target="_blank">www.wikipedia.org</a>`Y. Dein Knappe fängt sofort an zu lesen und zitiert einen Philosophen nach dem anderen.`n
						`S"Jetzt ist er richtig klug! `%Neumalklug!`S"`Y, spricht der Runenmeister.';
			break;

			case 10:
				$disc = get_disciple();
				$out .= 'gibt deinem Knappen `$20 Grillhaxen`Y. Dieser verschlingt eine Haxe nach der anderen. Als er die letzte verzehrt hat, leckt er sich genüsslich die Finger, weitet seinen Gürtel um 5 Löcher und ist nun als dicklicher '.$disc['name'].' `Ybekannt.`n
						Der Runenmeister meint: `S"Die sind beim letzen Grillabend des Stadtfestes übrig geblieben. Ich wusste doch, dass sie noch zu etwas gut sind."';
			break;

			case 11:
				$out .= 'verabreicht deinem Knappen einen Tank, auf dessen Etikett `S"Trank des Vergessens" `Ysteht. Dein Knappe vergisst Alles und ist dir von nun so richtig schön nichtsnutzig.';
			break;

			case 12:
				$out .= 'tätowiert deinem Knappen `&"Besitzer: '.$session['user']['name'].'`&" auf den Arm.`n`S"Nun hat er keine andere Wahl mehr, als Euch treu zu sein!"`Y, sagt der Runenmeister.';
			break;

			case 13:
			case 14:
				$adj = get_disciple_stat($new_char,'');
				$res = db_query('SELECT specid AS id, usename FROM specialty WHERE filename="specialty_thievery"');
				$spec = db_fetch_assoc($res);
				if( $spec['id'] ){
					$res = db_query('SELECT acctid, name, specialtyuses FROM accounts WHERE specialty='.$spec['id'].' AND level>10 AND superuser=0 AND '.user_get_online().' AND acctid<>'.$session['user']['acctid'].' ORDER BY RAND() LIMIT 1');
					if( !db_num_rows($res) ){
						$res = db_query('SELECT acctid, name, specialtyuses FROM accounts WHERE specialty='.$spec['id'].' AND '.user_get_online().' AND superuser=0 AND acctid<>'.$session['user']['acctid'].' ORDER BY RAND() LIMIT 1');
						if( !db_num_rows($res) ){
							$res = db_query('SELECT acctid, name, specialtyuses FROM accounts WHERE specialty='.$spec['id'].' AND acctid<>'.$session['user']['acctid'].' ORDER BY RAND() LIMIT 1');
							if( !db_num_rows($res) ){
								$can_do_this = false;
							}
						}
					}
				}
				else{
					$can_do_this = false;
				}

				if( $can_do_this ){
					$disc 	= get_disciple();
					$hp_add = max(intval($disc['level']/5), 1);
					$player = db_fetch_assoc( $res );
					$player['specialtyuses'] = utf8_unserialize($player['specialtyuses']);
					//$out .= print_r($player['specialtyuses'],true).'`n';
					$player['specialtyuses'][ $spec['usename'] ] += $hp_add;
					//$out .= print_r($player['specialtyuses'],true).'`n';
					$player['specialtyuses'] = utf8_serialize($player['specialtyuses']);
					$out .= 'sagt: `S"Dazu brauche ich die Hilfe von '.$player['name'].'`S".`n`n'.$player['name'].'`Y gibt deinem Knappen eine Lehrstunde in Diebeskünsten. Da Diebe sehr '.$adj.' sind, ist es dein Knappe nun ebenfalls.';
					user_update(
						array
						(
							'specialtyuses'=>db_real_escape_string($player['specialtyuses'])
						),
						$player['acctid']
					);
					systemmail($player['acctid'], '`7Knappenlehrstunde`0',
							   '`7Da du Diebeskünste beherrschst, hat dich der Runenmeister kurz in seinen Dienst gerufen.`n
							   Du hast dem Knappen von '.$session['user']['name'].' `7eine Lehrstunde gegeben und ihn damit sehr '.$adj.' gemacht - so, wie es '.$session['user']['name'].' `7wollte.`n
							   Du steigst dadurch in den Diebeskünsten um `$'.$hp_add.'`7 Level auf!');
				}
			break;

			case 15:
				$out .= 'gibt ihm eine Dose `$R`fed `!B`full`Y. Schnell ist diese geleert und dein Knappe ist voller Energie - so richtig flott.`n`S"Red Bull verleiht Flüüüüügel!"`Y, sagt der Runenmeister.';
			break;

			case 19:
				$out .= 'bindet ihm ein Bärenfell um.`n`S"Pelzig genug?"`Y, fragt der Runenmeister.';
			break;


			case 20:
				$res = db_query('SELECT acctid, name FROM accounts WHERE race="vmp" AND dragonkills>50 AND superuser=0 AND '.user_get_online().' AND acctid<>'.$session['user']['acctid'].' ORDER BY RAND() LIMIT 1');
				if( !db_num_rows($res) ){
					$res = db_query('SELECT acctid, name FROM accounts WHERE race="vmp" AND dragonkills>50 AND superuser=0 AND acctid<>'.$session['user']['acctid'].' ORDER BY RAND() LIMIT 1');
					if( !db_num_rows($res) ){
						$res = db_query('SELECT acctid, name FROM accounts WHERE race="vmp" AND acctid<>'.$session['user']['acctid'].' ORDER BY RAND() LIMIT 1');
						if( !db_num_rows($res) ){
							$can_do_this = false;
						}
					}
				}

				if( $can_do_this ){
					$disc 	= get_disciple();
					$hp_add = intval(max($disc['level'], 1));
					$player = db_fetch_assoc( $res );
					$out .= 'sagt: `S"Dazu brauche ich die Hilfe von '.$player['name'].'`S".`n`n'.$player['name'].'`Y beugt sich über deinen Knappen und saugt ihm jegliches Leben aus. Dein Knappe wird ganz bleich, aber du hast es so gewollt!';
					
					user_update(
						array
						(
							'maxhitpoints'=>array('sql'=>true,'value'=>'maxhitpoints+'.$hp_add)
						),
						$player['acctid']
					);
					
					systemmail($player['acctid'], '`4Knappenbiss`0',
							   '`4Du hast den Knappen von '.$session['user']['name'].' `4gebissen und somit zu einem Untoten gemacht.`n
							   Der Runenmeister hat dich auf Wunsch von '.$session['user']['name'].'`4 dazu verleitet.`n
							   Du bekamst dadurch `$'.$hp_add.'`4 permanente Lebenspunkte hinzu!');
				}
			break;

		}

		if( $can_do_this ){
			$out .= '`n`n`YAls du dir das Werk des Runenmeisters betrachtest, bist du ';
			if( $wish_char != $new_char ){
				$out .= 'verwirrt. Da muss etwas schief gegangen sein!`nGebervt packst du deinen, nun '.get_disciple_stat($new_char, 'en').' Knappen und verlässt diesen Ort.';
				addnav('Bloß weg hier!', 'village.php');
			}
			else{
				$out .= 'erfreut, dass alles so wunderbar geklappt hat.';
				addnav('Danke!', 'runemaster.php?op=master');
			}
			$out .= '`n`nDein Knappe ist für Heute zu nichts mehr zu gebrauchen!';
			buff_remove('decbuff');
			db_query('UPDATE disciples SET state='.$new_char.', oldstate='.$new_char.' WHERE master='.$session['user']['acctid']);
			item_delete('tpl_id="r_laguz" AND owner='.$session['user']['acctid'],1);
		}
		else{
			$out .= 'sagt: `S"Es tut mir leid, aber ich kann das im Moment nicht!"`Y`nEnttäuscht ziehst du davon.';
			addnav('Naja...','village.php');
		}
	break;

//HEILERIN GOLINDA FÜR 4 TAGE
	case 'golinda':
		addnav('Was tust du?');
		$out .= 'Du fragst den Runenmeister, ob er die Heilerin Golinda kennt. Er antwortet: `S"Gewiss doch! Warum fragt Ihr?".`Y`nDu fragst ihn ob es Ihm möglich sei sie zu überreden, weil du dich so gern von Ihr heilen lässt.`nDer Runenmeister nickt und spricht: `S"Natürlich kann ich das tun. Aber ich verlange eine Gebo - Rune als Wegegeld!"`Y.`nDu überlegst kurz und sagst dann:`n`n';
		$out .= create_lnk(	'Ja. Hier habt Ihr eine Gebo - Rune.',
							'runemaster.php?op=golinda2', true, true, false, false,
							'Rune geben').'`n';
		$out .= create_lnk(	'Tut mir leid, das ist mir zu teuer.',
							'runemaster.php?op=master', true, true, false, false,
							'Nein, zurück!').'`n';
	break;


	case 'golinda2':
		$out .= 'Du gibst dem Runenmeister eine Gebo - Rune und er macht sich auf den Weg zu Golinda.Du wartest geduldig auf seine Rückkehr.`n`nAls
				er wieder kommt, ';
		$config = utf8_unserialize($session['user']['donationconfig']);
		if( (int)$config['healer'] >= 100 || e_rand(1,100-(int)$config['healer'])==1 ){
			$out .= 'schaut er dich traurig an und sagt: `S"Tut mir leid, aber sie will Euch nicht noch länger behandeln.Aber ich bin mir sicher, wenn Ihr später wiederkommt, wird sie es ganz bestimmt tun!"`Y.`nEnttäuscht wendest du dich ab...';
			addnav('So\'n Mist!','runemaster.php?op=master');
		}
		else{
			$out .= 'schaut er dich an und sagt: `S"Golinda wird dich nun 4 weitere Tage behandeln!"`Y.`nWie er das gemacht hat, bleibt dir ein Rätsel...';
			$config['healer'] += 4;
			addnav('Sehr gut','runemaster.php?op=master');
		}
		if( $session['user']['turns'] ){
			$out .= '`n`n`@Während des Wartens verlierst du einen Waldkampf.';
			$session['user']['turns']--;
		}
		item_delete('tpl_id="r_gebo" AND owner='.$session['user']['acctid'],1);
		$session['user']['donationconfig'] = utf8_serialize($config);
	break;

//RUNENREZEPT ERFAHREN
	case 'rezept':
		$out .= '`YDu sagst zum Runenmeister, dass gern mehr über die Kombinationsmöglichkeiten der Runen wissen möchtest.`n';
		$out .= 'Der Runenmeister lächelt und sagt: `S"Dafür verlange ich eine `qEhwaz - Rune`S. Wenn du dir das Rezept selber aussuchen möchtest, `n
				verlange ich zusätzlich `#10 `SEdelsteine, weil ich dir so einen Einblick in mein komplettes, niedergeschriebenes Wissen geben muss.`n
				Nun sage mir, wie du dich entscheidest!"`n`n';
		$out .= create_lnk(	'Ich möchte irgend ein Rezept wissen!',
							'runemaster.php?op=rezept3&id=0', true, true, false, false,
							'zufälliges Rezept').'`n';
		if( $session['user']['gems'] >= 10 ){
			$out .= create_lnk(	'Ich möchte ein bestimmtes Rezept wissen!',
								'runemaster.php?op=rezept2', true, true, false, false,
								'bestimmtes Rezept (+10 ES)').'`n';
		}
		$out .= create_lnk(	'Tut mir leid, das ist mir zu teuer.',
							'runemaster.php?op=master', true, true, false, false,
							'Das ist Wucher!').'`n';
	break;


	case 'rezept2':

		$rez = runes_get_recipelist();
		$out 	.= 'Der Runenmeister sucht sein uraltes Runenbuch heraus und legt dir den Rezeptindex offen.`n
					Kurz darauf sagt er zu dir: `S"Nun, das ist mein niedergeschriebenes Wissen über die Kombinationsmöglichkeiten der Runen.`nWenn du dich entschieden hast, deute einfach mit deinem Zeigefinger auf das Rezept deiner Begierde`nund ich werde es dir offenbaren.`n';
		$kr = count(runes_get_known());
		if( $kr != 24 ){
			$out .= 'Denke aber daran, dass du erst `q'.runes_get_rank($kr, $session['user']['sex']).' `Sbist und daher kann es passieren, dass du nicht alle Rezepte richtig deuten kannst."';
		}
		else{
			$out .= 'Da du schon `q'.runes_get_rank($kr, $session['user']['sex']).' `Sbist, wirst du keine Probleme haben die Rezepte richtig zu deuten."';
		}

		$scroll = '<center><b>`SRezeptsammlung des Runenmeisters`0</b><br><br><br><table>';
		foreach( $rez as $r ){
			$scroll .= '<tr>
							<td>`4'.$r['name'].'&nbsp;&nbsp;</td>
							<td>'.create_lnk('`Sdarauf deuten!`0', 'runemaster.php?op=rezept3&id='.$r['id']).'</td>
						</tr>';
		}
		$scroll .= '</table></center>';
		$out 	.= '`c'.show_scroll($scroll).'`c';
		addnav('Hmm, doch nicht.','runemaster.php?op=master');
	break;



	case 'rezept3':
		$id = intval($_GET['id']);
		$es = false;

		if( $id>0 ){
			if( $session['user']['gems'] >= 10 ){
				$session['user']['gems'] -= 10;
				$es = true;
			}
			else{
				$id = 0;
			}
		}
		else{
			$id = 0;
		}
		$res = runes_get_recipe( $id, true );
		$name = str_replace('r_mix_','',$res['name']);
		$out .= '`YDu gibt dem Runenmeister eine `qEhwaz - Rune`Y '.($es?'und `#10 `YEdelsteine ':'');
		if( $es ){
			$out .= 'und sagst ihm, dass du Wissen über das `%'.$name.' `Yerlangen willst. ';
		}
		else{
			$out .= 'und sagst ihm, dass eine zufällige Kombinationsmöglichkeit wissen möchtest.';
		}

		$out .= '`n`nDer Runenmeister nimmt die `qEhwaz - Rune`Y '.($es?'und die `#10 `YEdelsteine ':'').'und spricht zu dir: `n`S"Warte bitte einen Moment ich werde dir eine Kopie '.($es?'dieses':'eines').' Rezeptes anfertigen!"`n`n';
		$out .= '`n`n`YNach einiger Zeit übergibt er dir ein Pergament und meint: `S"Dieses Pergament kannst du in dein Runenbuch einfügen."`Y';


		if( runes_add_known_recipe($res['combo_id']) ){
			addnav('Nein','runemaster.php?op=master');
			$out .= '`n`nDu nickst und fügst das Pergament geschwind in dein Runenbuch ein.`nDer Runenmeister sagt zu dir: `S"Willst du es dir nicht ansehen?"`n';
			$out .= create_lnk(	'Buchseite aufschlagen.','runemaster.php?op=buch&do=rezept&id='.$res['combo_id'], true, true);
		}
		else{
			addnav('Na toll...','runemaster.php?op=master');
			$out .= '`n`nAls du das Pergament in dein Runenbuch einfügen willst, bemerkst du, dass dir das `%'.$name.' `Yschon bekannt ist.';
		}

		item_delete('tpl_id="r_ehwaz" AND owner='.$session['user']['acctid'],1);
	break;


	case 'unkn_trank':
		$count = item_count('owner='.$session['user']['acctid'].' AND tpl_id IN ("trkalk","trkjung","trkcharm","trkgem","trkgiant","trklp","trkvit","trkxp","trknix","trkalt","trkgift","trkklo","trkrace","trkugly","trkxpm")');
		if( $count ){
			$out .= '`YDu fragst den Runenmeister, ob er dir die negativen unbekannten Tränke aussortieren kann.`n
					Dieser antwortet dir: `S"Gewiss doch! Für eine `qAlgiz - Rune `Sbin ich bereit, dies zu tun. Wisse jedoch, dass die Kraft der Rune höchstens für das Aussortieren von 10 Tränken reicht."';
			addnav('Was tust du?');
			addnav('Ups falsch.', 'runemaster.php?op=master');
			addnav('Aussortieren', 'runemaster.php?op=unkn_trank2&cnt='.$count);
		}
		else{
			addnav('Wie peinlich.', 'runemaster.php?op=master');
			$out .= '`YDu setzt an um den Runenmeister zu fragen, ob er dir die negativen unbekannten Tränke aussortieren kann,`ndoch plötzlich merkst du, dass du gar keine unbekannten Tränke hast.';
		}
	break;


	case 'unkn_trank2':
		addnav('Danke', 'runemaster.php?op=master');
		$cnt = intval($_GET['cnt']);
		$good = array('trkalk','trkjung','trkcharm','trkgem','trkgiant','trklp','trkvit','trkxp');
		$str_in = '"trkalt","trkgift","trkklo","trkrace","trkugly","trkxpm"'.(e_rand(0,100-min(99,$cnt))==0?',"'.array_rand($good).'"':'');
		item_delete('owner='.$session['user']['acctid'].' AND tpl_id IN ('.$str_in.')',e_rand(5,10));
		item_delete('tpl_id="r_algiz" AND owner='.$session['user']['acctid'],1);
		$count = item_count('owner='.$session['user']['acctid'].' AND tpl_id IN ("trkalk","trkjung","trkcharm","trkgem","trkgiant","trklp","trkvit","trkxp","trknix",'.$str_in.')');
		$diff = $cnt-$count;
		$verb = ($cnt < 10 ? 'wenig' : 'viele');
		$out .= '`YDu gibt dem Runenmeister deine'.($cnt==1?'n unbekannten Trank' : ' '.$cnt.' unbekannten Tränke').'.`n
				Der Runenmeister sagt: `S"Das sind aber '.$verb.'. Naja mal sehen, was sich da machen lässt. Warte hier!"`n `Yund wendet sich ab.
				Geduldig wartest du auf seine Rückkehr.`n
				Nach '.($cnt<10?'kurzer':($cnt<20 ? 'einiger' : 'langer')).' Zeit kehrt er zurück und gibt dir `@'.$count.' `Yunbekannte'.($count==1?'n Trank' : ' Tränke').' zurück.';
		if( !$diff ){
			$out .= '`nEr sagt zu dir: `S"Ich fand keine negative Energie."';
		}
		if( $cnt >= 15 && $session['user']['turns'] ){
			$session['user']['turns']--;
			$out .= '`n`n`@Durch das lange Warten verlierst du einen Waldkampf!';
		}

	break;

/*RUNENBUCH*/
	case 'buch':
		addnav('Buch schließen', 'runemaster.php?op=master');

		switch( $_GET['do'] ){
			case 'rezept':
				addnav('Buchindex', 'runemaster.php?op=buch');
				$scroll = '<center><b><u>`qRezeptübersicht`0</u></b><br>';
				if( empty($_GET['id']) ){
					$rezepte = runes_get_known_recipes();
					$rez_cnt = count($rezepte);

					if( $rez_cnt ){
						$sql = 'SELECT combo_id AS id, combo_name AS name FROM items_combos WHERE combo_id IN ('.db_real_escape_string(implode(',',$rezepte)).')';
						$res = db_query($sql);
						$scroll .= '<table>';
						$i = 0;
						while( ($r=db_fetch_assoc($res)) ){
							++$i;
							$scroll .= '<tr><td>`4'.str_replace('r_mix_','',$r['name']).'`0</td><td>&nbsp;&nbsp;&nbsp;';
							$scroll .= create_lnk('`S&raquo; Seite '.$i.'`0', 'runemaster.php?op=buch&do=rezept&id='.$r['id']);
							$scroll .= '</td></tr>';
						}
						$scroll .= '</table>';
					}
				}
				else{
					addnav('Rezeptindex', 'runemaster.php?op=buch&do=rezept');
					$rez = runes_get_recipe( $_GET['id'] );
					$scroll .= '- '.$rez['name'].' -<br><br>';
					$scroll .= runes_get_recipe_image( $rez['result'] ).'<br><br>';
					$scroll .= '<b>Zutaten:<br></b><table>';
					for($i=1;$i<5&&!empty($rez['id'.$i]);++$i){
						$scroll .= 	'<tr><td>'.runes_get_recipe_image( $rez['id'.$i] ).'</td></tr>'
									.($i<4&&!empty($rez['id'.($i+1)]) ? '<tr><td align="center">`~<b>+</b>`0</td></tr>' : '');
					}
					$scroll .= '</table>';

				}
				$scroll .= '</center>';
			break;

			case 'known':
				addnav('Buchindex', 'runemaster.php?op=buch');
				$scroll = '<center><b><u>`qMein Runenwissen`0</u></b><br>';
				if( empty($_GET['id']) ){
					$ident = runes_get_known();
					if( sizeof($ident) ){
						$scroll .= '<table>';
						$i = 0;
						foreach( $ident as $rid => $rid_bool ){
							$i++; //seite
							if( $rid > 0 ){
								$sql = 'SELECT name FROM '.RUNE_EI_TABLE.' WHERE id='.$rid;
								$res = db_query( $sql );
								$rune = db_fetch_assoc( $res );
								if( $rune ){
									$scroll .= '<tr>
													<td>`4'.$rune['name'].' - Rune&nbsp;&nbsp;&nbsp;</td>
													<td>'.create_lnk('`S&raquo; Seite '.$i.'`0', 'runemaster.php?op=buch&do=known&id='.$rid).'</td>
												</tr>';
								}
							}
						}
						$scroll .= '</table>';
					}
					else{
						$scroll .= '`bDir sind noch keine Runen bekannt!`b';
					}

				}
				else{
					addnav('Wissensindex', 'runemaster.php?op=buch&do=known');
					$sql = 'SELECT * FROM '.RUNE_EI_TABLE.' WHERE id='.intval($_GET['id']);
					$res = db_query( $sql );
					$rune = db_fetch_assoc( $res );
					if( $rune ){
						$scroll .= '<img src="./images/runes/'.mb_strtolower($rune['name']).'.png"><br><br>';
						$scroll .= '<table width="300">';
						$scroll .= '<tr><td>`SName</td><td>`4'.$rune['name'].'</td></tr>';
						$scroll .= '<tr><td>`SHäufigkeit</td><td>'.runes_get_rarity($rune['seltenheit']).'</td></tr>';
						$scroll .= '<tr><td>`SBuchstabe</td><td>`4'.$rune['buchstabe'].'</td></tr>';
						$scroll .= '<tr><td>`SAusrichtung</td><td>`4'.$rune['ausrichtung'].'</td></tr>';
						$scroll .= '<tr><td valign="top">`SHinweis</td><td valign="top">`4'.$rune['hinweis'].'</td></tr>';
						$scroll .= '</table>';
					}

				}
				$scroll .= '</center>';
			break;

			default:
				$scroll .= '<center>- Index -<br><br><table>';
				$scroll .= '<tr><td>`4Mein Runenwissen&nbsp;&nbsp;&nbsp;</td><td>'.create_lnk('`S&raquo; Kapitel 1`0', 'runemaster.php?op=buch&do=known').'</td></tr>';
				$scroll .= '<tr><td>`4Meine Runenrezepte&nbsp;&nbsp;&nbsp;</td><td>'.create_lnk('`S&raquo; Kapitel 2`0', 'runemaster.php?op=buch&do=rezept').'</td></tr>';
				$scroll .= '</table></center>';
				$scroll .= '<br><br>`~Mein Runenrang: '.runes_get_rank( count(runes_get_known()), $session['user']['sex'] );
			break;
		}

		$out .= $session['rune_book_out']['top'].'`n`c'.show_scroll($scroll).'`c`n'.$session['rune_book_out']['bottom'];
		unset($session['rune_book_out']);

	break;




/*RUNEN*/
	case 'runes':
		$out .= 'Was möchtest Du mit den Runen machen?`n';
		$out .= '-Ich möchte die Runen benutzen um ihre magischen Kräfte zu vereinen!`n';
		$out .= '-Ich benötige das Wissen des Runenmeisters über eine Rune, die ich nicht kenne!`n';
		$out .= '-Ich will mir alle Runen anschauen, die ich schon kenne!';
		addnav('Runenmagie','runemaster.php?op=magic');
		addnav('i?Runen identifizieren','runemaster.php?op=identify');
		addnav('Hilfe','runemaster.php?op=help&back=runes');
		addnav('Zurück!','runemaster.php?op=master');
		addnav('d?Zurück zum Stadtzentrum','village.php');
	break;


	case 'magic':
		$out .= 'Bevor du beginnst lässt dich der Runenmeister mit einer Handbewegung inne halten:`n';
		$out .= '`SDu weißt, für jeden Versuch verlange ich 100 Goldstücke von Dir!`n';
		$out .= '`YDu nickst verstehend.`n';
		$out .= '`SWohlan, lege deine magischen Gegenstände einfach hier vor mir in diese Schalen.`n';
		$out .= 'Bedenke aber, du brauchst zwar nicht alle Schalen zu füllen,`n jedoch ist die Reihenfolge ihrer Beschwörung von essentieller Magischer Bedeutung!`n';		
		
		if($session['user']['prefs']['runenmagienojs'] == true)
		{
			$dropi = '<option value="0">-------</option>';
			
			$Ares['Runen'] 		= runes_get(false,true);
			$Ares['Waffen'] 	= item_list_get('tpl_id="waffedummy" AND deposit1=0 AND deposit2=0 AND owner='.$session['user']['acctid'].' LIMIT 3', '', false);
			$Ares['Rüstungen']  = item_list_get('tpl_id="rstdummy" AND deposit1=0 AND deposit2=0 AND owner='.$session['user']['acctid'].' LIMIT 3', '', false);
			$cc = 0;
			
			foreach( $Ares as $key => $res ){	
				if( db_num_rows( $res ) ){
					while($item = db_fetch_assoc($res))
					{
						$name = str_replace('`0','', strip_appoencode( utf8_preg_replace('/`./','',$item['name'])));
						$cc++;
						$dropi .= '<option value="'.(int)$item['id'].'">('.$cc.') '.$name.'</option>';
					}
				}
			}
			
			
			$link = 'runemaster.php?op=magic_try';
			addnav('',$link);
			$out .= '
			`n`n`c 
			
			'.JS::encapsulate('
			function IT_CHECK(){
				var ret = true;
				 if(getSelectedValue(document.forms[0].drop1) != 0 &&  getSelectedValue(document.forms[0].drop1) == getSelectedValue(document.forms[0].drop2)) ret = false;
				 if(getSelectedValue(document.forms[0].drop2) != 0 &&  getSelectedValue(document.forms[0].drop2) == getSelectedValue(document.forms[0].drop3)) ret = false;
				 if(getSelectedValue(document.forms[0].drop3) != 0 &&  getSelectedValue(document.forms[0].drop3) == getSelectedValue(document.forms[0].drop4)) ret = false;
				 if(getSelectedValue(document.forms[0].drop3) != 0 &&  getSelectedValue(document.forms[0].drop3) == getSelectedValue(document.forms[0].drop1)) ret = false;
				 if(getSelectedValue(document.forms[0].drop4) != 0 &&  getSelectedValue(document.forms[0].drop4) == getSelectedValue(document.forms[0].drop1)) ret = false;
				 if(getSelectedValue(document.forms[0].drop4) != 0 &&  getSelectedValue(document.forms[0].drop4) == getSelectedValue(document.forms[0].drop2)) ret = false;
				
				if(!ret)alert(\'Man kann nicht zweimal den gleichen Gegenstand verwenden!\');
				return ret;
			}
			
			function getSelectedValue( obj )
			{
				return obj.options[obj.selectedIndex].value;
			}
			
			').'
					<form action="'.$link.'" method="POST" id="form3321678">
					<style><!--@import url("templates/runes.css");--></style>
					<table border="0" cellpadding="0" colspan="0" rowspan="0" cellspacing="5">
						<tr>
							<td colspan="7" align="center">`q<b>Magieanordnung</b></td>
						</tr>
						<tr>
							<td align="center"><img id="drop_rune0" src="./images/runes/schale.png" /><br /><div class="text_drop" id="text_drop_rune0">&nbsp;</div></td>
							<td valign="middle">+</td>
							<td align="center"><img id="drop_rune1" src="./images/runes/schale.png" /><br /><div class="text_drop" id="text_drop_rune1">&nbsp;</div></td>
							<td valign="center">+</td>
							<td align="center"><img id="drop_rune2" src="./images/runes/schale.png" /><br /><div class="text_drop" id="text_drop_rune2">&nbsp;</div></td>
							<td valign="center">+</td>
							<td align="center"><img id="drop_rune3" src="./images/runes/schale.png" /><br /><div class="text_drop" id="text_drop_rune3">&nbsp;</div></td>
						</tr>
						<tr>
							<td align="center"><select name="drop1">'.$dropi.'</select></td>
							<td valign="middle">+</td>
							<td align="center"><select name="drop2"">'.$dropi.'</select></td>
							<td valign="center">+</td>
							<td align="center"><select name="drop3">'.$dropi.'</select></td>
							<td valign="center">+</td>
							<td align="center"><select name="drop4">'.$dropi.'</select></td>
						</tr>
					</table>';
			$out .= '<input type="submit" class="button" value="Magie entfalten">
					</form>
					 '.JS::event('#form3321678','submit','return IT_CHECK();').'
					`n`n`n`n';
		}
		else
		{
			$out .= '`n`n`c
					<style><!--@import url("templates/runes.css");--></style>
					<div id="runes_preload" style="font-size: 22px;"><img src="./jslib/img/wait.gif">`nLADE...</div>
					<div id="runes_content" style="visibility: hidden;">
					<table border="0" cellpadding="0" colspan="0" rowspan="0" cellspacing="5">
						<tr>
							<td colspan="7" align="center">`q<b>Magieanordnung</b></td>
						</tr>
						<tr>
							<td align="center"><img id="drop_rune0" src="./images/runes/schale.png" /><br /><div class="text_drop" id="text_drop_rune0">&nbsp;</div></td>
							<td valign="middle">+</td>
							<td align="center"><img id="drop_rune1" src="./images/runes/schale.png" /><br /><div class="text_drop" id="text_drop_rune1">&nbsp;</div></td>
							<td valign="center">+</td>
							<td align="center"><img id="drop_rune2" src="./images/runes/schale.png" /><br /><div class="text_drop" id="text_drop_rune2">&nbsp;</div></td>
							<td valign="center">+</td>
							<td align="center"><img id="drop_rune3" src="./images/runes/schale.png" /><br /><div class="text_drop" id="text_drop_rune3">&nbsp;</div></td>
						</tr>
					</table>';
			$link = 'runemaster.php?op=magic_try';
			addnav('',$link);
			$out .= '<form action="'.$link.'" method="POST" id="form3881678">
					<input id="drop_0_id" type="hidden" name="drop_0_id"><input id="drop_0_tpl" type="hidden" name="drop_0_tpl">
					<input id="drop_1_id" type="hidden" name="drop_1_id"><input id="drop_1_tpl" type="hidden" name="drop_1_tpl">
					<input id="drop_2_id" type="hidden" name="drop_2_id"><input id="drop_2_tpl" type="hidden" name="drop_2_tpl">
					<input id="drop_3_id" type="hidden" name="drop_3_id"><input id="drop_3_tpl" type="hidden" name="drop_3_tpl">
					<input type="submit" class="button" value="Magie entfalten">
					</form>
					 '.JS::event('#form3881678','submit','return RUNES_CHECK();').'
					`n`n`n`n
					`bFolgendes trägst du bei dir:`b`n<div>
					<table border="0" cellpadding="3" colspan="0" rowspan="0" cellspacing="5">';
			$i   = 0;
			$a_items = array();
			$drags   = '';
			$i_drop  = 1;
			$js_rune_data = '[';
			$Ares['Runen'] 		= runes_get(false,true);
			$Ares['Waffen'] 	= item_list_get('tpl_id="waffedummy" AND deposit1=0 AND deposit2=0 AND owner='.$session['user']['acctid'].' LIMIT 3', '', false);
			$Ares['Rüstungen']  = item_list_get('tpl_id="rstdummy" AND deposit1=0 AND deposit2=0 AND owner='.$session['user']['acctid'].' LIMIT 3', '', false);
	
			foreach( $Ares as $key => $res ){
				$i_rows  = 1;
				$out .= '<tr class="trhead"><td align="center" colspan="6">'.$key.'</td></tr>
						<tr>';
	
				if( db_num_rows( $res ) ){
					for(; ($item = db_fetch_assoc($res)); ++$i){
						$name = utf8_preg_replace('/`./','',$item['name']);
						switch( $key ){
							case 'Runen':
								$image = (empty($item['special_info'])?str_replace('r_','',$item['tpl_id']):$item['special_info']);
								$drags .= '<img id="drag'.$i.'" src="./images/runes/'.$image.'.png"/>';
							break;
	
							case 'Waffen':
								$drags .= '<img id="drag'.$i.'"  width="64" height="64" src="./images/runes/runen-waffen.png"/>';
							break;
	
							case 'Rüstungen':
								$drags .= '<img id="drag'.$i.'" width="64" height="64" src="./images/runes/runen-ruestung.png"/>';
							break;
						}
	
						if( array_search($name, $a_items)===false ){
							array_push($a_items, $name);
							if( !($i_rows % 6) ){
								$i_rows=1;
								$out .= '</tr><tr>';
							}
							$out .= '<td><div id="drop'.$i_drop.'" class="drop"></div><div class="text_drop" id="text_drop'.$i_drop.'">&nbsp;</div></td>';
							$i_drop++;
							$i_rows++;
						}
						$js_rune_data .= ($i?',':'').'{tpl_id: "'.$item['tpl_id'].'", name: "'.$name.'", id: '.$item['id'].'}';
	
					}
					$out .= '</tr>';
				}
				else{
					$out .= '<td align="center" colspan="6">`iDu trägst nichts brauchbares bei dir!`i</td></tr>';
				}
			}
			$js_rune_data .= ']';
			$out .= '</table>'.$drags;
			$out .= '</div>'.JS::encapsulate('
						var g_rune_data = '.$js_rune_data.';
						var g_rune_drop = null;
					').'
					'.JS::encapsulate('./jslib/runemaster.js">',true);
				
		}
		addnav('Hilfe','runemaster.php?op=help&back=magic');
		addnav('Zurück','runemaster.php?op=runes');

	break;


	case 'help':
		$out .= get_extended_text('runen_help');
		addnav('Zurück','runemaster.php?op='.$_GET['back']);
	break;

	case 'magic_try':
		addnav('Zurück','runemaster.php?op=magic');
		$item = array();
		$doppelid = false;
	
		
		if($session['user']['prefs']['runenmagienojs'] == true)
		{
			 if(isset($_POST['drop1']) && $_POST['drop1'] != 0){
				 
				  $it = item_get('id="'.(int)$_POST['drop1'].'" AND owner='.$session['user']['acctid']);
				  $item[] = array('id'=>$it['id'], 'tpl'=>$it['tpl_id']);
			 }
			 if(isset($_POST['drop2']) && $_POST['drop2'] != 0){
				 
				  $it = item_get('id="'.(int)$_POST['drop2'].'" AND owner='.$session['user']['acctid']);
				  $item[] = array('id'=>$it['id'], 'tpl'=>$it['tpl_id']);
			 }
			 if(isset($_POST['drop3']) && $_POST['drop3'] != 0){
				 
				  $it = item_get('id="'.(int)$_POST['drop3'].'" AND owner='.$session['user']['acctid']);
				  $item[] = array('id'=>$it['id'], 'tpl'=>$it['tpl_id']);
			 }
			 if(isset($_POST['drop4']) && $_POST['drop4'] != 0){
				 
				  $it = item_get('id="'.(int)$_POST['drop4'].'" AND owner='.$session['user']['acctid']);
				  $item[] = array('id'=>$it['id'], 'tpl'=>$it['tpl_id']);
			 }
			 
			 if(isset($_POST['drop1']) && $_POST['drop1'] != 0 &&  $_POST['drop1'] == $_POST['drop2']) $doppelid = true;
			 if(isset($_POST['drop2']) && $_POST['drop2'] != 0 &&  $_POST['drop2'] == $_POST['drop3']) $doppelid = true;
			 if(isset($_POST['drop3']) && $_POST['drop3'] != 0 &&  $_POST['drop3'] == $_POST['drop4']) $doppelid = true;
			 if(isset($_POST['drop3']) && $_POST['drop3'] != 0 &&  $_POST['drop3'] == $_POST['drop1']) $doppelid = true;
			 if(isset($_POST['drop4']) && $_POST['drop4'] != 0 &&  $_POST['drop4'] == $_POST['drop1']) $doppelid = true;
			 if(isset($_POST['drop4']) && $_POST['drop4'] != 0 &&  $_POST['drop4'] == $_POST['drop2']) $doppelid = true;
		}
		else
		{
			for($i=0;$i<4;++$i){
				if( !empty($_POST['drop_'.$i.'_tpl']) ){
					$item[] = array('id'=>$_POST['drop_'.$i.'_id'], 'tpl'=>$_POST['drop_'.$i.'_tpl']);
					//array_push($item, array('id'=>$_POST['drop_'.$i.'_id'], 'tpl'=>$_POST['drop_'.$i.'_tpl']));
				}
			}
		}
		
		
		//reset($item);
		//print_r($item);
		$item_count = count($item);
		$cantmix = 0;
		
		if($doppelid){
			$out .= 'Als du ihn bittest die Magie zu entfalten, schaut er dich mistrauisch an: `n'.$session['user']['name'].'`S! Du hast den einen oder anderen Gegenstand doppelt gezählt!';
		}
		else if($item_count < 1){
			$out .= 'Als du ihn bittest die Magie zu entfalten, schaut er dich mistrauisch an: `n'.$session['user']['name'].'`S! Sagt Dir das Sprichwort `7`i"Von Nichts kommt Nichts!"`i`S etwas? Nun, hier ist es genau so!';
		}
		elseif( $item_count < 2 ){
			$out .= 'Der Runenmeister schaut dich an und fragt: `SMit was willst Du das denn verbinden? Ein einzelner Gegenstand kann seine Kraft doch nicht einfach so vervielfachen oder erweitern!';
		}
		elseif( $session['user']['gold'] < 100 ){
			$out .= 'Der Runenmeister schaut dich an und sagt: `SKomm wieder, wenn Du meine Dienste bezahlen kannst! Ein Versuch kostet Dich 100 Gold!';
		}
		else{
			$session['user']['gold'] -= 100;
			$out .= 'Der Runenmeister nimmt dir 100 Gold und die '.$item_count.' Dinge ab und verschwindet mit den Worten: `SMal sehen, ob Dir die Götter wohl gesonnen sind!`Y`n`n';
			$cbo1 = item_get_combo($item[0]['tpl'],$item[1]['tpl'],$item[2]['tpl'],ITEM_COMBO_RUNES);
			if( $cbo1 ){
				if( $item_count > 3 ){
					$cbo2 = item_get_combo($cbo1['result'],$item[3]['tpl'],'',ITEM_COMBO_RUNES);
					if( $cbo2 ){
						$newitem = $cbo2['result'];
					}
					else{
						$cantmix = 1;
					}
				}
				else{
					if( mb_strpos($cbo1['result'], 'r_mix') === false ){
						$newitem = $cbo1['result'];
					}
					else{
						$cantmix = 1;
					}
				}
			}
			else{
				$cantmix = 1;
			}

			if( $cantmix ){
				$out .= 'Nach einiger Zeit kommt er heraus und gibt dir die '.$item_count.' Dinge wieder:`n';
				$out .= '`&Diese Kombination ist wertlos!';
			}
			else{

				$out .= 'Nach einiger Zeit kommt er heraus und sagt:`n';
				$out .= '`SDie Kombination:`n';
				$noresult = 0;

				for( $i=0;$i<$item_count;$i++ ){
					$it = item_get ('id='.$item[$i]['id'], false, $what='name');;
					$in .= $item[$i]['id'];
					if( $i < ($item_count-1) ){
						$in .= ',';
						$runename .= mb_substr(str_replace(' - Rune', '', $it['name']),0,3);
					}
					$out .= '`^-`%'.$it['name'].'`n';
				}

				if( mb_strpos($newitem, 'r_amrup_') !== false ){
					$add = str_replace('r_amrup_','',$newitem);
					$amor = item_get ('id='.$item[3]['id'], false, $what='value1, name');
					if( $amor ){
						if( mb_strpos($amor['name'],$runename)!==false ){
							$noresult = 1;
							$out .= '`^wurde bereits auf den Gegenstand angewandt. Andere Kombinationen können ihre Wirkung aber noch entfalten.';
						}
						else{
							$amor['name'] .= ' `&[`q'.$runename.'`&]';
							$amor['value1'] += $add;
							item_set('id='.$item[3]['id'],$amor);
							//item_set_amor($amor['name'], $amor['value1'], $amor['gold']);
							$out .= '`Yverbesserte Deine Rüstung um `#'.$add.'`Y Verteidigungsspunkte!`n';
							$out .= 'Als du genauer hinschaust, bemerkst du, dass die Schriftzeichen in deine Rüstung eingebrannt sind.';
						}
					}else{
						die('FEHLER! Call alucard 0900/232323:>');
					}
					$in = str_replace(','.$item[3]['id'],'',$in);
				}
				elseif( mb_strpos($newitem, 'r_wpnup_') !== false ){
					$add = str_replace('r_wpnup_','',$newitem);
					$weapon = item_get ('id='.$item[3]['id'], false, $what='value1, name');
					if( $weapon ){
						if( mb_strpos($weapon['name'],$runename)!==false ){
							$noresult = 1;
							$out .= '`^wurde bereits auf den Gegenstand angewandt. Andere Kombinationen können ihre Wirkung aber noch entfalten.';
						}
						else{
							$weapon['name'] .= ' `&[`q'.$runename.'`&]';
							$weapon['value1'] += $add;
							item_set('id='.$item[3]['id'],$weapon);
							//item_set_weapon($weapon['name'], $weapon['value1'], $weapon['gold']);
							$out .= '`Yverbesserte Deine Waffe um `#'.$add.'`Y Schadenspunkte!`n';
							$out .= 'Als du genauer hinschaust, bemerkst du, dass die Schriftzeichen in deine Waffe eingebrannt sind.';
						}
					}else{
						die('FEHLER! Call alucard 0900/232323:>');
					}
					$in = str_replace(','.$item[3]['id'],'',$in);

				}
				elseif( mb_strpos($newitem, 'r_cmup_') !== false ){
					$add = str_replace('r_cmup_','',$newitem);
					$session['user']['charm'] += $add;
					$out .= '`Yerhöhte Deinen Charme um `#'.$add.'`Y Punkte!';
				}
				elseif( mb_strpos($newitem, 'r_lpup_') !== false ){
					$add = str_replace('r_lpup_','',$newitem);
					$session['user']['maxhitpoints'] += $add;
					$session['user']['hitpoints']    += $add;
					$out .= '`Yerhöhte Deine permanenten Lebenspunkte um `#'.$add.'`Y!';
				}
				else{
					$sql = 'SELECT * FROM items_tpl WHERE tpl_id="'.db_real_escape_string($newitem).'"';
					$res = db_query( $sql );
					$newitem = db_fetch_assoc($res);

					$rune = db_fetch_assoc(db_query('SELECT id FROM '.RUNE_EI_TABLE.' WHERE tpl_id="'.$newitem['tpl_id'].'" LIMIT 1'));

                    /*
                    if( $rune ){
						$ident	  	= user_get_aei('runes_ident');
						$ident  	= utf8_unserialize($ident['runes_ident']);
						if( !isset($ident[$rune['id']]) ){
							$noresult = 1;
							$out .= '`Swürde einen Gegenstand hervorbringen, den Du nicht beherrschst!`n`YEr gibt dir die Ausgangsgegenstände wieder!';
						}
					}


					if( !$noresult ){

						item_add( $session['user']['acctid'], 0, $newitem );
					}
                         */


                    if( $rune )
                    {
                        $known 	= runes_get_known(false);
                        if( $known[$rune['id']] ){
                            $tpl	= item_get_tpl('tpl_class = '.RUNE_CLASS_ID.' AND tpl_value2='.$rune['id'], 'tpl_id, tpl_name');
                            item_add($session['user']['acctid'], $tpl['tpl_id']);
                            $out .= '`^brachte ein(e): `4'.$newitem['tpl_name'].' `^hervor!';
                        }
                        else{
                            item_add($session['user']['acctid'], RUNE_DUMMY_TPL, array('tpl_value2'=>$rune['id']));
                            $out .= '`^brachte ein(e): `4Unbekannte Rune `^hervor!';
                        }
                    }
                    else
                    {
                        item_add( $session['user']['acctid'], 0, $newitem );
                        $out .= '`^brachte ein(e): `4'.$newitem['tpl_name'].' `^hervor!';
                    }




				}
				if( !$noresult ){
					item_delete('id IN ('.$in.')');
					$rez_id = (isset($cbo2) ? $cbo2['combo_id'] : $cbo1['combo_id']);
					if( runes_add_known_recipe($rez_id) ){
						$out .= '`n`n`@Schnell notierst du dir dieses Rezept in deinem Runenbuch.';
					}
				}

			}
		}
	break;

	case 'identify':

		switch( $_GET['subop'] ){
			case 'pay':
				$canpay = false;
				$paytype = '';
				switch( $_GET['pay'] ){
					case RUNE_IDENTPAY_GOLD:
						$canpay = ( $session['user']['gold'] >= RUNE_IDENTPAY_GOLD_VALUE );
						$paytype = 'Gold';
					break;

					case RUNE_IDENTPAY_GEMS:
						$canpay = ( $session['user']['gems'] >= RUNE_IDENTPAY_GEMS_VALUE );
						$paytype = 'Edelstein';
					break;

					case RUNE_IDENTPAY_RUNE:
						$canpay = 1;
					break;
				}

				if( $canpay ){
					$out .= 'Du schaust in deinen '.$paytype.'beutel und merkst, dass du genug dabei hast!';
					addnav('Runen zeigen','runemaster.php?op=identify&subop=listunknown&pay='.$_GET['pay']);
				}
				else{
					$out .= 'Der Runenmeister beschaut deinen '.$paytype.'beutel und fängt an zu lachen: `SSoviel hast Du doch garnicht bei Dir!';
					addnav('Schade','runemaster.php?op=runes');
				}
			break;


			case 'dontpay':

				$out .= 'Du sagst ihm, dass du ihm nichts geben möchtest und ';


				if(e_rand(1,100)==23 && empty($session['runemaster_dontpay'])){
					$out .= 'er mustert dich und überlegt kurz.`n';
					$out .= 'Plötzlich spricht er zu Dir: `S"Nun gut zeigt mir Eure Runen!"';
					addnav('Habt Dank dafür!','runemaster.php?op=identify&subop=listunknown&pay=0');
				}
				else{
					$out .= 'Als er anfängt zu lachen, senkst du deinen Kopf und überlegst.';
					addnav('Zurück','runemaster.php?op=runes');
				}
				$session['runemaster_dontpay'] = '1';
			break;


			case 'listunknown':
				$res = runes_get_unidentified();
				$out .= 'Du hast folgende, dir unbekannte Runen, dabei:`n';
				while( ($rune = db_fetch_assoc($res)) ){
					$link = 'runemaster.php?op=identify&subop=doit&pay='.$_GET['pay'].'&itemid='.$rune['id'];
					addnav('',$link);
					$out .= '<a href="'.$link.'">'.$rune['name'].'</a>`n';
				}
				addnav('Ach! Doch nicht', 'runemaster.php?op=runes');
			break;


			case 'doit':
				$rune = item_get('id='.$_GET['itemid'], false, 'value2');
				if( $rune ){
					$old_rank = runes_get_rank( count(runes_get_known()), $session['user']['sex'] );
					$identified = runes_identify( $rune['value2'] );
					if( $identified == -1 ){
						$out .= 'FEHLER BEIM IDENTIFIZIEREN! ITEM BITTE NICHT LÖSCHEN ODER ÄHNLICHES. Alucard wurde benachrichtigt!';
						$ft = 'ID:'.$_GET['itemid'].'    VAL2:'.$rune['value2'];
						systemmail(4261,'RUNENFEHLER', $ft);
					}
					else{
						$s_out = '';
						$new_rank = runes_get_rank( count(runes_get_known(false)), $session['user']['sex'] );
						if( $new_rank != $old_rank ){
							$s_out .= '`c`@Du steigst vom Rang `q'.$old_rank.' `@zum Rang `q'.$new_rank.' `@auf!`n';
						}
						$s_out .= '`YDu gibst Ihm die ';
						switch( $_GET['pay'] ){
							case RUNE_IDENTPAY_GOLD:
								$session['user']['gold'] -= RUNE_IDENTPAY_GOLD_VALUE;
								$s_out .= RUNE_IDENTPAY_GOLD_VALUE.' Goldstücke';
							break;

							case RUNE_IDENTPAY_GEMS:
								$session['user']['gems'] -= RUNE_IDENTPAY_GEMS_VALUE;
								$s_out .= RUNE_IDENTPAY_GEMS_VALUE.' Edelsteine';
							break;

							case RUNE_IDENTPAY_RUNE:
								item_delete('tpl_id="r_kenaz" AND owner='.$session['user']['acctid'],1);
								$s_out .= '`qKenaz - Rune`Y';
							break;
						}
						$s_out .= ', er nimmt die Rune und verschwindet kurz und kommt mit einem Pergament zurück. Dieses heftest du ein dein Runenbuch ein und siehst es dir an:`c`n';
						$session['rune_book_out']['top'] = $s_out;

						$sql = 'SELECT id, name FROM '.RUNE_EI_TABLE.' WHERE id='.$rune['value2'];
						$res = db_query( $sql );
						$rune = db_fetch_assoc( $res );

						$s_out = '';
						if( $identified > 1){
							$s_out .= '`n`%Als du die Rune in deinem Beutel verstauen willst, bemerkst du, dank deines neuerlangten Wissens, dass du noch `q';
							$s_out .= ($identified-1).' '.$rune['name'].'-Rune'.( $identified > 2 ? 'n':'').' `%in deinem Beutel hast!`n';
						}
						$session['rune_book_out']['bottom'] = $s_out;
						redirect('runemaster.php?op=buch&do=known&id='.$rune['id']);
					}
				}

				addnav('Zurück', 'runemaster.php?op=runes');
			break;


			default:
				$out .= 'Der Mann räuspert sich, schaut dich an und grinst: `n`S';
				if( runes_get_unidentified_count() ){
					$out .= 'Nun! Auch ich muss leben. Was bietet Ihr mir für die Identifikation? Entweder Ihr zahlt mir 7500 Goldstücke für meine Arbeit, oder Ihr entlohnt mich mit zwei Edelsteinen!';
					addnav('Bezahlen');
					addnav(RUNE_IDENTPAY_GEMS_VALUE.' Edelsteine','runemaster.php?op=identify&subop=pay&pay='.RUNE_IDENTPAY_GEMS);
					addnav(RUNE_IDENTPAY_GOLD_VALUE.' Gold','runemaster.php?op=identify&subop=pay&pay='.RUNE_IDENTPAY_GOLD);
					if( item_count('tpl_id="r_kenaz" AND owner='.$session['user']['acctid']) > 0 ){
						addnav('Kenaz - Rune','runemaster.php?op=identify&subop=pay&pay='.RUNE_IDENTPAY_RUNE);
						$out .= ' Eine Kenaz - Rune würde ich auch als Bezahlung akzeptieren!';
					}

					addnav('Ich bezahl nix','runemaster.php?op=identify&subop=dontpay');
				}
				else{
					$out .= 'Ich spüre keine Energie an Dir, die Du noch nicht beherrschst. Schau doch selbst!`n`YEr zeigt auf deinen Beutel und du schaust hinein. Tatsächlich, alle Runen die sich darin befinden sind dir wohl bekannt.';
					addnav('Zurück','runemaster.php?op=runes');
				}
			break;
		}

	break;




/*RABE*/
	case 'raven':
		$out .= 'Du streckst dem Raben deinen Zeigefinger entgegen';
		switch( e_rand(1,25) ){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				$out .= ' und kraulst ihn am Gropf. Der Rabe krächzt und spuckt Dir `#einen Edelstein`Y auf die Hand.`n';
				$out .= 'Aus Freude über den Edelstein kraulst du ihn noch etwas und vergisst dabei die Zeit. Du verlierst einen Waldkampf!';
				$session['user']['gems']++;
				$session['user']['turns']--;
				addnav('Dem Meister zuwenden','runemaster.php?op=master');
			break;

			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
				$out .= ' und piekst ihm grob in die Seite. Der Rabe flattert auf und hackt Dir mit seinem Schnabel verärgert ein Auge aus.`n';
				$out .= 'Voller Schmerzen hältst du dir deine Augenhöle. ';
				if( $session['user']['maxhitpoints'] > 1){
					$out .= 'Du `4verlierst`Y einen permanenten Lebenspunkt!';
					$session['user']['maxhitpoints']--;
					$session['user']['turns']--;
				}
				addnav('Dem Meister zuwenden','runemaster.php?op=master');
			break;

			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
				$out .= ' und streichelst sein schimmerndes Gefieder. Die Augen des Raben blitzen auf.`n';
				$out .= '`$Du erhältst einen permanenten Lebenspunkt dazu!';
				$session['user']['maxhitpoints']++;
				$session['user']['turns']--;
				addnav('Dem Meister zuwenden','runemaster.php?op=master');
			break;

			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
				$out .= ' und kratzt ihn sanft am Kopf. Der Rabe schließt genüsslich die Augen.`n';
				$out .= '`&Du erhältst einen Charmepunkt!';
				$session['user']['charm']++;
				$session['user']['turns']--;
				addnav('Dem Meister zuwenden','runemaster.php?op=master');
			break;

			case 21:
				$out .= ' und piekst dem Raben ins Auge. Der Rabe krächzt laut vor Schmerzen und flattert los.`n';
				$out .= 'Die Zeichen auf den Steinen fangen an zu glühen, als plötzlich ein gewaltiger Hammer auf dich nieder fährt und dich direkt zu Ramius befördert!`n';
				$out .= 'Du fielst dem Zorn eines mächtigen Gottes zum Opfer.`nEin eisiger Hauch des Vergessens umhüllt dich!';
				$session['user']['turns']--;
				killplayer(0,5,0,'shades.php','Verdammt!');
				addnews('`%'.$session['user']['name'].'`5 wurde von einem Götterhammer niedergeschnettert.');
			break;

			case 22:
			case 23:
			case 24:
			case 25:
				$out .= ' und zwickst den Raben am Fuß. Dem Raben missfällt das sehr und er kratzt deine Hand.`n';
				$out .= '`&Du verlierst einen Charmepunkt!';
				$session['user']['charm']--;
				$session['user']['turns']--;
				addnav('Dem Meister zuwenden','runemaster.php?op=master');
			break;
		}
	break;

	default:
		$out .= '`4`c`bBADNAV`c`b`n`&Wenn u hier landest, schreib eine Anfrage mit deiner Vorgehensweise, mit der du hier her gelangt bist.';
		addnav('Meister','runemaster.php?op=master');
	break;

}


output( $out.'`0' );
page_footer();
?>
