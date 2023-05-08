<?php

/**
 * Skins freischalten und deaktivieren
 *
 *  coded by Jenutan [at] ist-einmalig [dot] de
 *
 */
require_once('common.php');
page_header('Skins freischalten');
$access_control->su_check(access_control::SU_RIGHT_DEV, true);

$str_filename = basename(__FILE__);
$str_output = '';

grotto_nav();
addnav('Skinverwaltung');
addnav('Neue Skins eintragen', $str_filename . '?op=new');
addnav('Skins verwalten', $str_filename . '?op=manage');

switch ($_GET['op'])
{
    default:
        $str_output .= '`4Diesen $_GET["op"] Parameter (' . $_GET['op'] . ') gibt es noch nicht!`0`n';
    // Bewusst auf >break< verzichtet!

    case '':
        $str_output .= 'Was willst du tun?';
        break;

    case 'new':
        if ($handle = dir(TEMPLATE_PATH))
        {
            $sql = "
				SELECT
					`folder`,
					`type`
				FROM
					`skins`
			";
            $res = db_query($sql);

            while ($row = db_fetch_object($res))
            {
                if ($row->folder && $row->type)
                {
                    $skins_available[$row->type][$row->folder] = true;
                }
            }


            $skins = array();
            while ($item = $handle->read())
            {
                if ($item != '.' && $item != '..' && $item != '.svn' && is_dir(TEMPLATE_PATH . $item))
                {
                    $skins[] = $item;
                }
            }
            $handle->close();
            if (count($skins) == 0)
            {
                $str_output .= '`b`@Argh, gar keine Skins gefunden!`n';
            } else
            {
                $skins_new = array();
                foreach ($skins as $skin)
                {
                    // Erst die "normalen" Skins...
                    if (is_file(TEMPLATE_PATH . $skin . '/tpl.php') && !$skins_available['skin'][$skin])
                    {
                        $skins_new['skin'][$skin] = true;
                    }

                    // ...dann die Popup-Skins =D
                    if (is_file(TEMPLATE_PATH . $skin . '/tpl_popup.php') && !$skins_available['popup'][$skin])
                    {
                        $skins_new['popup'][$skin] = true;
                    }
                }

                foreach ($skins_new as $type => $folder)
                {
                    foreach ($folder as $folder_name => $bool)
                    {
                        $sql = "
							INSERT INTO
								`skins`
							SET
								`type`		= '" . $type . "',
								`folder`	= '" . $folder_name . "',
								`name`		= '" . $folder_name . "'
						";
                        if (!db_query($sql))
                            $str_output .= '`4Fehler beim Einfügen!`n';
                    }
                }
                $str_output .= count($skins_new, true) . ' neue Skins importiert!';
            }
        }
        break;

    case 'manage':
        $sql = "
			SELECT
				*
			FROM
				`skins`
			ORDER BY
				`type` DESC,
				`name`
		";
        $res = db_query($sql);

        $str_output .= '
		`c<table style="text-align:left;">
				<tr class="trhead">
					<th>&nbsp;Id&nbsp;</th>
					<th>&nbsp;Name&nbsp;</th>
					<th>&nbsp;Verzeichnis&nbsp;</th>
					<th>&nbsp;Typ&nbsp;</th>
					<th>&nbsp;SU-Lv.&nbsp;</th>
					<th>&nbsp;Aktiv?&nbsp;</th>
					<th>&nbsp;Editieren&nbsp;</th>
					<th>&nbsp;Löschen&nbsp;</th>
				</tr>
		';


        $i = 0;
        $ids = array();
        while ($row = db_fetch_object($res))
        {
            $classname = $i++ % 2 ? 'trlight' : 'trdark';
            $ids[] = $row->id;

            $str_output .= '
				<tr class="' . $classname . '">
					<td>' . $row->id . '</td>
					<td>' . $row->name . '</td>
					<td>' . $row->folder . '</td>
					<td>' . $row->type . '</td>
					<td>' . ($row->superuser ? '`^Grotties' : '`@Spieler') . '`0</td>
					<td>' . ($row->activated ? '`@Ja!' : '`4Nein!') . '`0</td>
					<td><a href="' . $str_filename . '?op=manage_single&amp;id=' . $row->id . '">Editieren</a></td>
					<td><a href="' . $str_filename . '?op=delete&amp;id=' . $row->id . '" onclick="return confirm(\'Wirklich den Skin ' . $row->name . ' löschen?\');">Löschen</a></td>
				</tr>
			';
        }
        $str_output .= '</table>`c';

        addpregnav('/' . $str_filename . '\?op=manage_single&id=(' . implode('|', $ids) . ')/');
        addpregnav('/' . $str_filename . '\?op=delete&id=(' . implode('|', $ids) . ')/');
        break;

    case 'manage_single':
        if (count($_POST))
        {
            $sql = "UPDATE `skins` SET `id` = `id`";

            foreach ($_POST AS $key => $val)
            {
                if ($key == 'form_submit')
                {
                    continue;
                }

                $sql .= " ,`" . $key . "` = '" . db_real_escape_string(stripslashes($val)) . "' ";
            }

            $sql .= "
				WHERE
					`id` = '" . $_GET['id'] . "'
			";
            if (db_query($sql))
            {
                redirect($str_filename . '?op=manage'); //$str_output .= '`@Speichern erfolgreich!`0';
            }
        }

        $sql = "
			SELECT
				*
			FROM
				`skins`
			WHERE
				`id` = '" . $_GET['id'] . "'
		";
        $res = db_query($sql);
        $row = db_fetch_assoc($res);

        $link = $str_filename . '?op=manage_single&id=' . $_GET['id'];
        addnav('', $link);

        $form = array(
            'Skin editieren,title'
            , 'id' => 'Id,viewonly'
            , 'name' => 'Name'
            , 'folder' => 'Verzeichnis,viewonly'
            , 'type' => 'Typ,viewonly'
            , 'superuser' => 'Mind. SU-Level,select,0,Spieler,1,Grottenolme'
            , 'activated' => 'Aktiviert?,bool'
        );

        output('<form action="' . $link . '" method="post">');
        showform($form, $row, false, 'Speichern!');
        output('</form>');

        output($str_output);
        unset($str_output);
        break;

    case 'delete':
        $sql = "DELETE FROM `skins` WHERE `id` = '" . $_GET['id'] . "'";

        if (db_query($sql))
        {
            $str_output .= 'Erfolgreich gelöscht!';
        }
        break;
}

output($str_output);
unset($str_output);
page_footer();
