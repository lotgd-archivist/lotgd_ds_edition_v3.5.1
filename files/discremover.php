<?php
/*
* Script zur Entlassung des Knappen
* Fügt eine extra Tabelle der entlassenen Knappen hinzu
* @author: Asgarath
* @site: atrahor.de
* @idea: Meg Beth
* @date: 29/09/2008
* @version: 1.1
* thx to Dragonslayer and Jenutan!
*
CREATE TABLE disc_rem_list(
id INT NOT null AUTO_INCREMENT PRIMARY KEY,
lastuser VARCHAR(120) NOT null,
discname VARCHAR(90) NOT null,
disclevel INTEGER(10) NOT null,
remdate VARCHAR(90) NOT null,
owner_id INT(11) NOT null,
);
*/

require_once('common.php');
page_header('Die Ritter');
$filename = basename(__FILE__);

// Array des Knappen laden
$arr_disc = get_disciple($Char->acctid);
$str_output .= get_title('`yD`si`ee `7Rit`et`se`yr');
// Knappe wird entlassen
if ($_GET['op']=='rem_disc')
{
	$str_output .= '`)Du entscheidest dich, deinen Knappen gehen zu lassen. Mit traurigem Blick, aber einer blühenden Zukunft voller Abenteuer in einem fernen Land in Aussicht umarmt dich dein Knappe noch einmal und dankt dir überschwenglich für deine Geduld und Lehrstunden.`n';
  $gems = ceil($arr_disc['level']/10);
	if($Char->gems>0 && $gems>0)
	{
		$str_output .= 'In einem plötzlichen Anfall von Sentimentalität überlegst du, ob '.$arr_disc['name'].'`) denn wohl auch genug zu essen haben wird. Um dein Gewissen zu beruhigen gibst du ihm '.$gems.' Edelsteine in die Hand und wünschst ihm Erfolg, Sicherheit unter allen Umständen und dass er stets vor allem Unheil bewahrt bleiben möge.';
		$Char->charm+=$gems;
		$Char->gems = max(0,$Char->gems-$gems);
		
	}
	else if($Char->gold>0)
	{
    $str_output .= 'In einem plötzlichen Anfall von Sentimentalität überlegst du, ob '.$arr_disc['name'].'`) denn wohl auch genug zu essen haben wird. Um dein Gewissen zu beruhigen gibst du ihm alles Gold aus deinen Taschen in die Hand und wünschst ihm Erfolg, Sicherheit unter allen Umständen und dass er stets vor allem Unheil bewahrt bleiben möge.';
		$Char->charm+=2;
		$Char->gold = 0;
	}
	addnews($session['user']['name'].'`0 hat seinen Knappen '.$arr_disc['name'].'`0 für immer aus seinem Dienst entlassen!');
	
	addnav('Lebe wohl!');
	addnav('Zurück zu den Toren','dorftor.php');
	// Schreibe Werte (Name des Knappenbesitzers, Knappenname, Knappenlevel, aktuelles Datum, UserID) in Knappeliste
	// UserID wird jetzt mitgeschrieben, damit verglichen werden kann, ob User Knappennamen schon einmal verwendet hat
	$sql = 'INSERT INTO disc_rem_list(lastuser, discname, disclevel, remdate, owner_id)
            VALUES ("'.db_real_escape_string($Char->name).'",
                    "'.db_real_escape_string($arr_disc['name']).'",
                    '.$arr_disc['level'].',
                    "'.getgamedate().'",
										"'.db_real_escape_string($Char->acctid).'")';
	db_query($sql);
	// Lösche den Knappen endgültig
	db_query('UPDATE disciples SET state=0,oldstate=0 WHERE master = '.$Char->acctid.'');
	// Confbit zum Sperren verwenden
	$Char->setConfBit(UBIT_DISABLE_DISCREM,1);
	
}
elseif($_GET['op'] == 'list')
{
	addnav('Zurück zu den Rittern','discremover.php');
	$str_output .= '`)Als du dich weiter umsiehst, kannst du an einen Sockel gebettet das Buch der ehrenhaft entlassenen Knappen sehen.';
	
    $res = db_query('SELECT count(*) AS amount FROM disc_rem_list');
    $arr_result = db_fetch_array($res);

	//Tabelle der entlassenen Knappen
	$arr_page_res = page_nav('discrem.php?op=list',$arr_result['amount']);
	$sql = 'SELECT * FROM disc_rem_list LIMIT '.$arr_page_res['limit'];
	$res = db_query($sql);
	$str_output .= '<span style="color: #9900FF">';
	$str_output .= '<table border="0" cellpadding="5" cellspacing="2" bgcolor="#999999" align="center"><tr class="trhead"><td>Knappe</td><td>Level</td><td>Besitzer</td><td>Datum</td>';
	$lst = 0;
	while($row = db_fetch_assoc($res))
	{
		$str_output .= '<tr class="'.($lst++%2?'trdark':'trlight').'"><td>`&'.$row['discname'].'</td><td>`&'.$row['disclevel'].'</td><td>`&'.$row['lastuser'].'</td><td>`&'.$row['remdate'].'</td>';
	}
	$str_output .= '</table>';
	$str_output .= '</span>';
}
else
{	
	// Hat User Knappen schon einmal abgegeben?
	if($Char->getConfBit(UBIT_DISABLE_DISCREM) == 0)
	{
		//Abfrage ob User einen Knappen besitzt
		if (is_array($arr_disc) && $arr_disc['state'] > 0 && $arr_disc['level'] > 29){

			$str_output .= '`n`yD`si`er`7ekt neben dem Stadttor haben sich einige Ritter mit ihren Knappen nieder gelassen. Sie scheinen auf der Durchreise und auf dem Weg in große Abenteuer zu sein.
      	              Du erkennst, dass einige Ritter jedoch ohne Knappen sind. Hier wäre ein geeigneter Ort um deinen Knappen für immer gehen zu lassen. Er würde bestimmt schnell einen Ritter finden, der ihn mit sich nimmt.`n`n
        	            Doch bedenke, dass du deinen Knappen dann wahrscheinlich nie wieder sehen wirst! Außerdem wirst du hier keinen Knappen mehr entlassen können und ein neuer Knappe dürfte nicht den selben Namen annehmen. Wie entscheidest du dich a`el`ss`yo?`n';
	
			addnav('Was tust du?');
			$str_sure = 'Achtung! Du wirst deinen Knappen nie mehr wieder sehen! Bist du dir sicher, dass du ihn weggeben möchtest?';
			addnav('Knappen entlassen','discremover.php?op=rem_disc',false,false,false,false,$str_sure);
		}
		else
		{
			$str_output .= '`n`yD`si`er`7ekt neben dem Stadttor haben sich einige Ritter mit ihren Knappen nieder gelassen. Sie scheinen auf der Durchreise und auf dem Weg in große Abenteuer zu sein.
      	              Du erkennst, dass einige Ritter jedoch ohne Knappen sind. '.(is_array($arr_disc) && $arr_disc['state'] > 0 && $arr_disc['level'] <= 30?'Wenn dein Knappe erfahren genug wäre, könntest du ihn hier wohl seinen eigenen Weg gehen lassen. Doch dazu hat es noch etwas Z`ee`si`yt.'
											:'Wenn du einen Knappen hättest, wäre hier wohl ein geeigneter Ort um ihn seine eigenen Wege gehe zu las`es`se`yn.');
		}
	}
	else
	{
		$str_output .='`n`)Direkt neben dem Stadttor haben sich einige Ritter mit ihren Knappen nieder gelassen. Sie scheinen auf der Durchreise und auf dem Weg in große Abenteuer zu sein.
      	              Du erkennst, dass einige Ritter jedoch ohne Knappen sind. Du schwelgst in Erinnerungen an deinen alten Knappen...
											`n`n`$Du hast schon einmal einen Knappen entlassen. Ein weiteres Mal gestattet es dir die Zunft nicht!`0`n`n';
	}
	addnav('Liste der Knappen', 'discremover.php?op=list');
	addnav('Zurück zum Stadttor','dorftor.php');
	

}

output($str_output);
page_footer();
?>