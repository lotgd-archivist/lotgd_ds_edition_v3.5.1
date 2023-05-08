<?php
/**
* pict.php: Bilderverwaltung für Atrahor.de
* @author 	Jenutan [at] ist-einmalig [dot] de / bathory
* @version 123456789.0001 :P
*/

function manage_single_picture($what, $bool_number = false, $nr = 0)
{
	global	 $session				//Die Session eben
			,$str_output			//Der Output-String
			,$str_filename_get		//Der Ausgangslink
			;

    $warning = '';

	if($what === 'msgavatar' && $bool_number)
	{
		$small_letter = 'mc'.$nr;
		$small_letter_path = $small_letter;
		$upload_name = 'pic_'.$small_letter;
		$sizes = '300 x 300px' ;
		$what = 'MsgChar-Avatar - Nr.' . $nr . ' ';
	}
	elseif ($bool_number)
	{
		$small_letter = $what;
		$small_letter_path = $small_letter;
		$upload_name = 'pic_'.$small_letter;
		$sizes = 'beliebig' ;
		$what = 'Bild Nr.' . $small_letter . ' ';
	}
	else
	{
		switch ($what)
		{
			case 'Charakter-Avatar':
				$small_letter = 'p';
				$upload_name = 'avatar';
				$sizes = '300 x 300px';
			break;

			case 'Knappen-Avatar':
				$small_letter = 'd';
				$upload_name = 'disc_avatar';
				$sizes = '300 x 300px';
			break;

			case 'Tier-Avatar':
				$small_letter = 'm';
				$upload_name = 'mount_avatar';
				$sizes = '300 x 300px';
			break;

			case 'Haus-Avatar':
				$small_letter = 'h';
				$upload_name = 'house_avatar';
				$sizes = '300 x 300px';
			break;
			case 'Marktstand-Avatar':
				$small_letter = 's';
				$upload_name = 'stand_avatar';
				$sizes = '300 x 300px';
			break;
			default:
				die('So ein Bild gibt´s nicht zum Verwalten, bitte melden!');
		}
		$small_letter_path = $small_letter;
	}

	$src_act = CPicture::get_image_path($session['user']['acctid'],$small_letter_path,0);
	$src = CPicture::get_image_path($session['user']['acctid'],$small_letter_path,1);

	$exist_bool = false;

	if( ($src_act) )
	{

		$path = $src_act;
		$exist_bool = true;
		$warning = '`$<big>Bild muss erst von der Administration bestätigt werden!</big>`0<br />';
	}

	if( ($src) )
	{

		$path = $src;
		$exist_bool = true;
		$warning = '';
	}

	$str_output .= '`0`c`^`b' . $what .'`b`0`c`n';
	if( $exist_bool )
	{
		$on_click = "return confirm('Bist du sicher, dass dieses Bild samt Text gelöscht werden soll?');";
		$str_output .= '
			Folgendes Bild ist bereits hochgeladen:`n`n
			`c<img style="max-width:300px;" src="' . $path . '" alt="Bitte Bilder zulassen!" /><br />
			' . $warning . '
			[<a href="' . $str_filename_get . '&amp;loeschen=' . $small_letter . '" onclick="' . $on_click . '" >L&ouml;schen</a>]`c`n
		';
	}
	else
	{
		$str_output .= '`0`c`@Noch kein Bild hochgeladen!`n`n`n`n`n`n`n`n`0`c
		';
	}

	$str_output .= '
		<form method="post" action="' . $str_filename_get . '" enctype="multipart/form-data">
		<table style="width:100%">
	';
		$str_output .= '
			<tr>
				<td colspan="2">
					K&uuml;rzel für die Bio:
				</td>
				<td>
					[PIC=' . $small_letter . ']
				</td>
			</tr>
			<tr>
				<td colspan="2">
					Bild
				</td>
				<td>
					<input type="file" id="image-file" name="' . $upload_name . '" />
				</td>
			</tr>
		';
		$text = CPicture::picture_get_author($small_letter);
		$str_output .= '
			<tr>
				<td>
					Künstler:
				</td>
				<td style="text-align:right;">
					&copy;
				</td>
				<td>
					<input id="author" class="input" name="author" maxlength="255" style="width: 90%;" value="' . utf8_htmlspecialchars($text) . '" />
					<input type="hidden" name="small_letter" value="' . $small_letter . '" />
				</td>
			</tr>
			<tr>
				<td colspan="2">

				</td>
				<td>
				    <input type="button" onclick="this.form.reset();" id="inp3647548" value="Zur&uuml;cksetzen" />
					<input type="submit" value="Speichern" />
				</td>
			</tr>
		</table>
		</form>
	';

	JS::encapsulate(' atrajQ("#image-file").bind("change", function() {
            if((this.files[0].size/1024/1024) > 2.00){
            alert("Das Bild muss kleiner als 2MB sein!");
            atrajQ(this).val("");
            }
        });');

}

require_once('common.php');
$BOOL_JSLIB_PLU_MI = true;
if (!$session['user']['loggedin']) exit;
$str_output = '';
$str_filename = basename(__FILE__);
$str_filename_get = $str_filename . '?';
foreach ($_GET AS $key => $val)
{
	if ($key == 'loeschen') continue;
	$str_filename_get .= $key . '=' . $val . '&amp;';

}
$preflink	= 'prefs.php';
$add = '';
if ($_GET['type'] && isset($_GET['number'])) $add = '
	- <a href="' . $str_filename . '?op=verw&amp;type=' . $_GET['type'] . '">Zur&uuml;ck zu: Bilder</a>
';
$str_output .= '
	`b<a href="' . $preflink . '">Zur&uuml;ck zum Profil</a> -
	<a href="prefs_bio.php">Zur&uuml;ck zur Bioverwaltung</a> -
	<a href="' . $str_filename . '">&Uuml;bersicht</a> ' . $add . '`b
	`n
	`n
';
$str_output .= JS::encapsulate('window.resizeTo(800,700);');
popup_header('Bilderverwaltung',true);
if( isset($_GET['loeschen']) )
{
	$_GET['loeschen'] = db_real_escape_string(stripslashes($_GET['loeschen']));
	switch ($_GET['loeschen']) 
	{
		case 'p': $str_what = 'p'; break;
		case 'm': $str_what = 'm'; break;
		case 'd': $str_what = 'd'; break;
		case 'h': $str_what = 'h'; break;
		default:
			$str_what = $_GET['loeschen'];
		break;
	}
	CPicture::clear_old($session['user']['acctid'],$str_what);
	if('bilder'==$_GET['type'])redirect('pict.php?op=verw&type=bilder',false,false);
	$str_output .= '`4`bBild gelöscht!`b`0`n';
}
if (count($_FILES) || count($_POST))
{
	if (mb_strlen($_POST['author']))
	{
		if (mb_strlen($_POST['author']))
		{
			$_POST['author'] = strip_appoencode($_POST['author'],3);
			if (CPicture::picture_save_author($_POST['author'],$_POST['small_letter']))
			{
				$str_output .= '`@K&uuml;nstler gespeichert!`0`n';
			}
			else
			{
				$str_output .= '`4Fehler beim Künstler-Speichern!`0`n';
			}
		}
		else
		{
			$str_output .= '`4Ein K&uuml;nstler muss mit angegeben werden!`0`n';
			unset($_FILES);
			unset($_POST);
		}
	}
	elseif (mb_strlen($_POST['author']) == 0)
	{
		$picture_uploaded = false;
		foreach ($_FILES AS $key => $val)
		{
			if ($val['name'] == '') continue;
			$picture_uploaded = true;
			break;
		}

		if( $picture_uploaded )
		{
			$str_output .= '
				`$FEHLER: Das Bild muss mit K&uuml;nstler hochgeladen werden!`0`n`n
			';
			unset($_FILES);
			unset($_POST);
		}
		else
		{
			$str_output .= '`4Ein K&uuml;nstler muss gespeichert werden!';
		}
	}
	else
	{
		$str_output .= '
			Fehler 444!
		';
	}
}
if( count($_FILES) )
{
	if(getsetting('avatare',1) == 2) {
		$str_path = CPicture::AVATAR_UPLOAD_DIR;
	}
	else
	{
		$str_path = CPicture::AVATAR_SECURE_DIR;
	}
	if( $_FILES['avatar']['name'] != '' )
	{
		CPicture::clear_old($session['user']['acctid'],'p',false);
		$avatar = new CPicture( $_FILES['avatar'] );
		if(!$avatar->is_valide()) {
			$str_output .= '`$Erlaubt sind als Dateitypen für hochgeladene Bilder nur .jpg, .gif und .png! Bei deinem Avatar handelt es sich um keinen solchen Typen.`0`n';
		}
		else
		{
			$avatar->save($session['user']['acctid'],'p',300,300,$str_path);
			$str_output .= '`@Avatar erfolgreich hochgeladen!`0`n';
		}

	}
	if( $_FILES['disc_avatar']['name'] != '' )
	{
		CPicture::clear_old($session['user']['acctid'],'d',false);
		$avatar = new CPicture( $_FILES['disc_avatar'] );
		if(!$avatar->is_valide()) {
			$str_output .= '`$Erlaubt sind als Dateitypen für hochgeladene Bilder nur .jpg, .gif und .png! Bei deinem Knappenavatar handelt es sich um keinen solchen Typen.`0`n';
		}
		else
		{
			$avatar->save($session['user']['acctid'],'d',300,300,$str_path);
			$str_output .= '`@Knappenavatar erfolgreich hochgeladen!`0`n';
		}

	}
	if( $_FILES['mount_avatar']['name'] != '' )
	{
		CPicture::clear_old($session['user']['acctid'],'m',false);	
		$avatar = new CPicture( $_FILES['mount_avatar'] );
		if(!$avatar->is_valide()) {
			$str_output .= '`$Erlaubt sind als Dateitypen für hochgeladene Bilder nur .jpg, .gif und .png! Bei deinem Tieravatar handelt es sich um keinen solchen Typen.`0`n';
		}
		else
		{
			$avatar->save($session['user']['acctid'],'m',300,300,$str_path);
			$str_output .= '`@Tieravatar erfolgreich hochgeladen!`0`n';
		}
	}
	if( $_FILES['house_avatar']['name'] != '' )
	{
       	CPicture::clear_old($session['user']['acctid'],'h',false);
		$avatar = new CPicture( $_FILES['house_avatar'] );
		if(!$avatar->is_valide()) {
			$str_output .= '`n`$Erlaubt sind als Dateitypen für hochgeladene Bilder nur .jpg, .gif und .png! Bei deinem Hausavatar handelt es sich um keinen solchen Typen.`0`n';
		}
		else
		{
			$avatar->save($session['user']['acctid'],'h',300,300,$str_path);
			$str_output .= '`@Hausavatar erfolgreich hochgeladen!`0`n';
		}
	}
	if( $_FILES['stand_avatar']['name'] != '' )
	{
       	CPicture::clear_old($session['user']['acctid'],'s',false);
		$avatar = new CPicture( $_FILES['stand_avatar'] );
		if(!$avatar->is_valide()) {
			$str_output .= '`n`$Erlaubt sind als Dateitypen für hochgeladene Bilder nur .jpg, .gif und .png! Bei deinem Hausavatar handelt es sich um keinen solchen Typen.`0`n';
		}
		else
		{
			$avatar->save($session['user']['acctid'],'s',300,300,$str_path);
			$str_output .= '`@Marktstand erfolgreich hochgeladen!`0`n';
		}
	}
	$aei = user_get_aei('msg_chars');
	$msgChars = adv_unserialize($aei['msg_chars']);
	$has = count($msgChars);
	$arr_msgChars = array();
	for($i=0; $i<$has;$i++){
		if ($_FILES['pic_mc'.$i]['name'] != '')
		{
			CPicture::clear_old($session['user']['acctid'],'mc'.$i,false);
				$pict 	= new CPicture( $_FILES['pic_mc'.$i] );
				if(!$pict->is_valide()) {
					$str_output .= '`n`$Erlaubt sind als Dateitypen für hochgeladene Bilder nur .jpg, .gif und .png! Bei deinem MsgChar-Avatar Nr. '.$i.' handelt es sich um keinen solchen Typen.`0';
				}
				else {
					$pict->save($session['user']['acctid'],'mc'.$i, 300, 300, $str_path);
					$str_output .= '`@MsgChar-Avatar Nr.' . $i . ' erfolgreich hochgeladen!`0';
				}
		}
	}
	foreach($_FILES as $fk => $fv)
	{
		if ('pic_' == mb_substr($fk,0,4) && $_FILES[$fk]['name'] != '')
		{
			$i = intval(mb_substr($fk,4));
			CPicture::clear_old($session['user']['acctid'],$i,false);
			$pict 	= new CPicture( $_FILES['pic_'.$i] );
			if(!$pict->is_valide()) {
				$str_output .= '`n`$Erlaubt sind als Dateitypen für hochgeladene Bilder nur .jpg, .gif und .png! Bei deinem Bild Nr. '.$i.' handelt es sich um keinen solchen Typen.`0';
			}
			else {
				$pict->save($session['user']['acctid'],$i, -1, -1, $str_path);
				$str_output .= '`@Bild Nr.' . $i . ' erfolgreich hochgeladen!`0';
			}
		}
	}

}
switch($_GET['op'])
{
	case '':
        if('del_all' == $_GET['do']){
            CPicture::clear_all($Char->acctid);
        }
		$quota = CPicture::get_quota();
		$perc = min(100,round((100*$quota)/CPicture::MAX_QOUTA));
		$col = 'green';
		if($perc > 60)$col = 'yellow';
		if($perc > 75)$col = 'orange';
		if($perc > 90)$col = 'red';
		$str_output .= '`cBitte beachte die <a href="./static/nutzungsbestimmungen.html" target="_blank">`bNutzungsbestimmungen`b</a>`c
		`c`n`nFreier Speicherplatz: <span id="quotafree">'.(100-$perc).'% frei ('.bytesToSize($quota).'/'.bytesToSize(CPicture::MAX_QOUTA).')</span>
		<div id="quotabar" class="progress-bar '.$col.'">
				<span id="quotaused" style="width: '.$perc.'%"></span>
			</div>
		`bNeue Bilder hochladen:`b`n`n';

		if($quota < CPicture::MAX_QOUTA) {
			$str_output .= '
		<form action="./picv.php" class="dropzone" id="bilder-dropzone">
		  <div class="dz-message">
			Bilder hier drauf ziehen oder klicken um Bilder hochzuladen!
		  </div>
		  <div class="fallback">
			<input name="file" type="file" multiple />
		  </div>
		</form>`c
		`n`n
		';
			JS::encapsulate('./jquery/dropzone.js', true, false, true);
			JS::encapsulate('
				Dropzone.options.bilderDropzone = {
					acceptedFiles: ".jpg,.jpeg,.gif,.png",
					maxFilesize: 2, // MB
					success: function(file, response){
						if("done" != response.substr(0, 4))
						{
							alert(response);
						}
						else
						{
							var quota = response.split("_");
							document.getElementById("quotaused").style.width = quota[1]+"%";
							document.getElementById("quotafree").innerHTML = quota[3];

							document.getElementById("quotabar").className = "";
							document.getElementById("quotabar").className = "progress-bar "+quota[2];

							document.getElementById("bildcnt").innerHTML = parseInt(document.getElementById("bildcnt").innerHTML, 10)+1;
						}
					}
				};
				');

		}else{
			$str_output .= '`$Du hast die Maximale Quota erreicht, du muss einige Bilder löschen oder durch kleinere Versionen (statt png z.B. jpg verwenden) ersetzen!`0`c
		`n`n';
		}
		$str_output .= '`c`bBilder:`b`n`n
			<table style="text-align: center;">

				<tr class="trhead">
					<th>Bilderrahmen</th>
					<th>max. Aufl&ouml;sung</th>
					<th>Anzahl</th>
					<th>Verwalten</th>
				</tr>
		';
		$name = 'Bilder';
		$class = 'trdark';
		$bildcnt = db_get("SELECT COUNT(*) AS cnt  FROM user_uploads_pictures WHERE small_letter NOT IN ('p','h','d','s') AND small_letter NOT LIKE 'mc%' AND userid='".intval($session['user']['acctid'])."'");
		$str_output .= '
				<tr class="' . $class . '">
					<td>' . $name .'</td>
					<td>beliebig</td>
					<td><span id="bildcnt">'.$bildcnt['cnt'].'</span>/∞</td>
					<td><a href="' . $str_filename . '?op=verw&amp;type=' . 'bilder' . '">Verwalten</a></td>
				</tr>
			';

		$str_output .= '
			</table>
			`n`n`c
		';

		$str_output .= '`c`bAvatare:`b`n`n
			<table style="text-align: center;">

				<tr class="trhead">
					<th>Bilderrahmen</th>
					<th>max. Aufl&ouml;sung</th>
					<th>Anzahl`n(benutzt/verf&uuml;gbar)</th>
					<th>Verwalten</th>
				</tr>
		';
		$i = 0;
		$src_act = CPicture::get_image_path($session['user']['acctid'],'p',0);
		$src = CPicture::get_image_path($session['user']['acctid'],'p',1);
		$exist = 0;
		if(($src_act))
		{
			//$str_add = 'Bild muss erst von der Administration bestätigt werden!';
			$exist ++;
		}
		elseif(($src))
		{
			// Veränderl. Param anhängen, um Caching zu vermeiden
			//$str_add = 'img|'.$src.'?'.filemtime($src);
			$exist ++;
		}
		$class = $i%2?'trlight':'trdark';
		$i++;
		$str_output .= '
				<tr class="' . $class . '">
					<td>Charakter-Avatar</td>
					<td>300 x 300px</td>
					<td>' . $exist . '/1</td>
					<td><a href="' . $str_filename . '?op=verw&amp;type=useravatar">Verwalten</a></td>
				</tr>
		';
		$knappe = (bool) db_num_rows(db_query("
			SELECT
				`id`
			FROM
				`disciples`
			WHERE
				`master`	= '" . $session['user']['acctid'] . "'
			LIMIT
				1
		"));
		if($knappe && getsetting('avatare',0) )
		{
			$src_act = CPicture::get_image_path($session['user']['acctid'],'d',0);
			$src = CPicture::get_image_path($session['user']['acctid'],'d',1);
			$exist = 0;
			if( ($src_act) )
			{
				//$str_add = 'Bild muss erst von der Administration bestätigt werden!';
				$exist ++;
			}
			elseif( ($src) )
			{
				// Veränderl. Param anhängen, um Caching zu vermeiden
				//$str_add = 'img|'.$src.'?'.filemtime($src);
				$exist ++;
			}
			$class = $i%2?'trlight':'trdark';
			$i++;
			$str_output .= '
				<tr class="' . $class . '">
					<td>Knappen-Avatar</td>
					<td>300 x 300px</td>
					<td>' . $exist . '/1</td>
					<td><a href="' . $str_filename . '?op=verw&amp;type=knappenavatar">Verwalten</a></td>
				</tr>
			';
		}
		$aei = user_get_aei('msg_chars');
		$msgChars = adv_unserialize($aei['msg_chars']);
		$has = count($msgChars);
		if( $has > 0 && getsetting('avatare',0) )
		{
			$exist = 0;
			
			for($k=0; $k<$has;$k++){
				$src_act = CPicture::get_image_path($session['user']['acctid'],'mc'.$k,0);
				$src = CPicture::get_image_path($session['user']['acctid'],'mc'.$k,1);

				if( ($src_act) )
				{
					$exist ++;
				}
				elseif( ($src) )
				{
					$exist ++;
				}
			}
			
			$class = $i%2?'trlight':'trdark';
			$i++;

			$str_output .= '
				<tr class="' . $class . '">
					<td>MsgChar-Avatar</td>
					<td>300 x 300px</td>
					<td>' . $exist . '/'.$has.'</td>
					<td><a href="' . $str_filename . '?op=verw&amp;type=msgavatar">Verwalten</a></td>
				</tr>
			';
		}
		if( $session['user']['hashorse'] && getsetting('avatare',0) )
		{

			$src_act = CPicture::get_image_path($session['user']['acctid'],'m',0);
			$src = CPicture::get_image_path($session['user']['acctid'],'m',1);

			$exist = 0;

			if( ($src_act) )
			{
				//$str_add = 'Bild muss erst von der Administration bestätigt werden!';
				$exist ++;
			}
			elseif( ($src) )
			{
				// Veränderl. Param anhängen, um Caching zu vermeiden
				//$str_add = 'img|'.$src.'?'.filemtime($src);
				$exist ++;
			}
			else
			{
				//$str_add = 'Lade ein eigenes Avatarbild für dein Tier hoch!<br />(300 x 300px)';
			}

			$class = $i%2?'trlight':'trdark';
			$i++;

			$str_output .= '
				<tr class="' . $class . '">
					<td>Tier-Avatar</td>
					<td>300 x 300px</td>
					<td>' . $exist . '/1</td>
					<td><a href="' . $str_filename . '?op=verw&amp;type=tieravatar">Verwalten</a></td>
				</tr>
			';
		}
		if ( $session['user']['house'] )
		{
			$src_act = CPicture::get_image_path($session['user']['acctid'],'h',0);
			$src = CPicture::get_image_path($session['user']['acctid'],'h',1);

			$exist = 0;

			if( ($src_act) )
			{
				//$str_add = 'Bild muss erst von der Administration bestätigt werden!';
				$exist = true;
			}
			elseif( ($src) )
			{
				// Veränderl. Param anhängen, um Caching zu vermeiden
				//$str_add = 'img|'.$src.'?'.filemtime($src);
				$exist ++;
			}

			$class = $i%2?'trlight':'trdark';
			$i++;

			$str_output .= '
				<tr class="' . $class . '">
					<td>Haus-Avatar</td>
					<td>300 x 300px</td>
					<td>' . $exist . '/1</td>
					<td><a href="' . $str_filename . '?op=verw&amp;type=hausavatar">Verwalten</a></td>
				</tr>
			';
		}

		$str_output .= '
			</table>
			`n`n'.create_lnk('Alle Bilder und Avatare löschen!','pict.php?do=del_all',false,false,'Bist Du sicher, dass du alle Bilder und Avatare löschen willst?').'`n`n`c
		';
	break;
	case 'verw':
		
			switch($_GET['type']){

			default:
						
				//by bathi
				if( $_GET['type'] == 'msgavatar' && !isset($_GET['number']) )
				{
							$str_table .= '
						`^Übersicht der Kategorie:`0
						`c`b`^MsgChar-Avatare`0`b`c
						`n
						<table style="text-align:center;width:100%;">
							<tr class="trhead">
								<th>
									Bildk&uuml;rzel
								</th>
								<th>
									Bildvorschau
								</th>
								<th>
									Bearbeiten?
								</th>
							</tr>
					';

					$shown = $i = 0;
					
					$aei = user_get_aei('msg_chars');
					$msgChars = adv_unserialize($aei['msg_chars']);
					$has = count($msgChars);
					
					for($h=0; $h<$has;$h++)
					{
						$exist_bool = false;

							$src_act = CPicture::get_image_path($session['user']['acctid'],'mc'.$h,0);
							$src = CPicture::get_image_path($session['user']['acctid'],'mc'.$h,1);

							if (($src_act))
							{

								$path = $src_act;
								$exist_bool = true;
								$warning = '`4Bild muss erst von der Administration bestätigt werden!`0';
							}
							else if( ($src) )
							{

								$path = $src ;
								$exist_bool = true;
								$warning = '';
							}
							$class = $i%2?'trlight':'trdark';
							$i++;

							if ($exist_bool)
							{
								$author = CPicture::picture_get_author('mc'.$h);

								$str_table .= '
									<tr class="' . $class . '">
										<td>
											[PIC=' . 'mc'.$h . ']
										</td>
										<td>
											<img style="max-width:300px;"  src="' . $path . '"  alt= "" />`n
											&copy; ' . ($author ? utf8_htmlentities($author) : '`0`i`$K&uuml;nstler nicht eingetragen!`0`i') . '`n
											' . $warning . '
										</td>
										<td>
											<a href="' . $str_filename_get . '&amp;number=' . $h . '">Edit</a>
										</td>
									</tr>
								';
							}
							else
							{
								$str_table .= '
									<tr class="' . $class . '" style="height:50px;">
										<td>
											[PIC=' . 'mc'.$h . ']
										</td>
										<td style="min-width:300px;">
												Kein Bild hochgeladen!
										</td>
										<td>
											<a href="' . $str_filename_get . '&amp;number=' . $h . '">Neu</a>
										</td>
									</tr>
								';
							}
					}
					$str_table .= '
						</table>
					';
					if (true)
					{
						$str_output .= $str_table;
					}
				}
				else if ( $_GET['type'] == 'msgavatar' && isset($_GET['number']) && is_numeric($_GET['number']))
				{
					manage_single_picture('msgavatar',true,(int)$_GET['number']);
				}//end by bathi
				elseif( $_GET['type'] == 'bilder' && !isset($_GET['number']) )
				{
					$str_table .= '
						`^Übersicht der Kategorie:`0
						`c`b`^Bilder`0`b`c
						`n
						<table style="text-align:center;width:100%;">
							<tr class="trhead">
								<th>
									Bildk&uuml;rzel
								</th>
								<th>
									Bildvorschau
								</th>
								<th>
									Bearbeiten?
								</th>
							</tr>
					';

					$shown = $i = 0;
					$bilder = db_get_all("SELECT *  FROM user_uploads_pictures WHERE small_letter NOT IN ('p','h','d','s') AND small_letter NOT LIKE 'mc%' AND userid='".intval($session['user']['acctid'])."' ORDER BY CAST(small_letter AS SIGNED) ASC");

					foreach($bilder as $bild)
					{
						$number = $bild['small_letter'];
						$exist_bool = false;

						$src_act = CPicture::get_image_path($session['user']['acctid'],$number,0);
						$src = CPicture::get_image_path($session['user']['acctid'],$number,1);

						if (($src_act))
						{
							$path = $src_act;
							$exist_bool = true;
							$warning = '`4Bild muss erst von der Administration bestätigt werden!`0';
						}
						else if( ($src) )
						{
							$path = $src;
							$exist_bool = true;
							$warning = '';
						}
						$class = $i%2?'trlight':'trdark';
						$i++;

						if ($exist_bool)
						{
							$author = CPicture::picture_get_author($number);
							$str_table .= '
									<tr class="' . $class . '">
										<td>
											[PIC=' . $number . ']
										</td>
										<td>
											<img style="max-width:300px;" src="' . $path . '" alt= "" />`n
											&copy; ' . ($author ? utf8_htmlentities($author) : '`0`i`$K&uuml;nstler nicht eingetragen!`0`i') . '`n
											' . $warning . '
										</td>
										<td>
											<a href="' . $str_filename_get . '&amp;number=' . $number . '">Edit</a>
										</td>
									</tr>
								';
						}
						else
						{
							$str_table .= '
									<tr class="' . $class . '" style="height:50px;">
										<td>
											[PIC=' . $number . ']
										</td>
										<td style="min-width:300px;">
												Kein Bild hochgeladen!
										</td>
										<td>
											<a href="' . $str_filename_get . '&amp;number=' . $number . '">Neu</a>
										</td>
									</tr>
								';
						}
					}

					$str_table .= '
						</table>
					';
					if (true)
					{
						$str_output .= $str_table;
					}

				}
				else if (isset($_GET['number']) && is_numeric($_GET['number']))
				{
					manage_single_picture((int)$_GET['number'],true);
				}
				else
				{
					die();
				}

			break;

			case '':
				die();
			break;

			case 'useravatar':
				manage_single_picture('Charakter-Avatar');
			break;

			case 'knappenavatar':
				manage_single_picture('Knappen-Avatar');
			break;

			case 'tieravatar':
				manage_single_picture('Tier-Avatar');
			break;

			case 'hausavatar':
				manage_single_picture('Haus-Avatar');
			break;
			//Marktstand
			case 'standavatar':
				manage_single_picture('Marktstand-Avatar');
			break;

		}
	break;
	default:
		die();
	break;
}

output($str_output);
unset($str_output);
popup_footer();

?>
