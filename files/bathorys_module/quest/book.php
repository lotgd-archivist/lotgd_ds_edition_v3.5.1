<?php

popup_header('Questbuch',true);

$filename = 'bathorys_popups.php?mod=quest&mdo=book';

$out = '<br /><table width="100" border="0" style="margin:auto;">
                            <tr>
                                <td><a href="'.$filename.'" class="motd">Offen</a></td>
                                <td><a href="'.$filename.'&do=done" class="motd">Erledigt</a></td>
                                <td><a href="'.$filename.'&do=lost" class="motd">Verfallen</a></td>
                            </tr>
                        </table><br />';

output($out);

switch($_GET['do'])
{
    case 'done':
        output(CQuest::makelist(CQuest::DONE));
        break;
    case 'lost':
        output(CQuest::makelist(CQuest::LOST));
        break;
    default:
        output(CQuest::makelist(CQuest::OPEN));
        break;
}

popup_footer();

?>