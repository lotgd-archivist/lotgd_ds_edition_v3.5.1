<?php
// Ein waschechtes Mausloch
// by Dragonslayer

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_rathole ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);

	switch($str_case) {

		// Innen
		case 'in':

			$str_out = '';

			switch($_GET['act']) {

				case 'rename':
					$str_ratname = (empty($arr_content['ratname']))?'Den Nager':$arr_content['ratname'];
					$str_out .= house_get_title($str_ratname.' umbenennen');
					if(count($_POST)>0)
					{
						$arr_content['ratname'] = $_POST['new_name'];
						db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);

						$str_out .= '`^Du gibst dem Nager den wundervollen Namen '.$arr_content['ratname'] .'`0`n`n`n';
					}

					$str_out .= '`tIst das nicht putzig? Du kannst ihm einen Namen geben!`n';

					addnav('',$str_base_file.'&act=rename');
					$str_out .= '<form method="post" action="'.$str_base_file.'&act=rename">';
					$form[]="Der neue Name,title";
					$form['name_pr'] = 'Vorschau:,preview,new_name';
					$form['new_name']="Neuer Name für dein Nagerli!,text|?Ein neuer Name für deinen kleinen nagenden Schatz!";
					$values['new_name']=(empty($arr_content['ratname']))?'Ein Nager':$arr_content['ratname'];
					output($str_out);
					showform($form,$values);
					$str_out = '</form>';

					if($bool_rowner)
					{
						addnav('Name geben',$str_base_file.'&act=rename');
					}
					addnav('Füttern',$str_base_file.'&act=feed');
					addnav('Zurück',$str_base_file);

					break;
				case 'feed':
					$str_ratname = (empty($arr_content['ratname']))?'Nager':$arr_content['ratname'];
					$str_out .= house_get_title($str_ratname.' füttern');
					$str_out .= '`tDu kniest Dich hin und schaust erwartungsvoll in das kleine Loch hinein. Schnuppernd kommt eine kleine pelzige Nase zum Vorschein.
					Och wie süüüß, '.$str_ratname.'`t erkennt dich sogar schon am Geruch. Du hälst deinem kleinen Schatz ein Leckerli hin und siehst mit Freude zu wie
					sich es deine Gabe mit beiden Vorderpfoten greift, daran schnuppert und dann zügig aufmümmelt.`n
					Einfach herzergreifend, du könntest stundenlang zusehen...und äh, das tust du auch gerade.';
					$session['user']['turns']=max(0,$session['user']['turns']-1);
					if($bool_rowner)
					{
						addnav('Name geben',$str_base_file.'&act=rename');
					}
					addnav('Füttern',$str_base_file.'&act=feed');
					addnav('Zurück',$str_base_file);
					break;

				case '':
					$str_ratname = (empty($arr_content['ratname']))?'Ein Nager':$arr_content['ratname'];
					$str_out .= house_get_title($str_ratname.' wohnt hier');
					$str_out .= 'Jaja, was wäre ein Haus ohne unerwünschten Nager. Besonders die kleinen sind wichtig! Wer würde sonst mit den Katzen spielen?
					Und da du dich als echter Nagerfreund erwiesen hast, ist dir dieses Exemplar hier auch sehr dankbar!';
					if($bool_rowner)
					{
						addnav('Name geben',$str_base_file.'&act=rename');
					}
					addnav('Füttern',$str_base_file.'&act=feed');
					break;

				default:
					break;
			}

			output($str_out);
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;
			// END case in

			// Bau gestartet
		case 'build_start':

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;

			// Bau fertig
		case 'build_finished':

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;

			// Abreißen
		case 'rip':

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;

	}	// END Main switch
}


?>
