<?php

class MAbo
{
	function __construct($do, $ispopup = false)
	{
		global $session,$access_control,$Char;
		$filename = 'bathorys_popups.php?mod=abo&mdo='.$do.'&';

        popup_header('Abonnements');

        CBookmarks::cleanUp();

        $out = '<table width="100" border="0">
                            <tr>
                                <td><a href="bathorys_popups.php?mod=abo&mdo=" class="motd">Übersicht (neu)</a></td>
                                <td><a href="bathorys_popups.php?mod=abo&mdo=&all=true" class="motd">Übersicht (alle)</a></td>
                                <td><a href="bathorys_popups.php?mod=abo&mdo=verw" class="motd">Verwalten</a></td>
                            </tr>
                        </table>';

        output($out);

		switch($do)
		{
            case 'verw':
                if(isset($_GET['op']) && $_GET['op']=='del')CBookmarks::del(0,$_GET['section']);
                CBookmarks::verwaltung($filename);
                break;
            default:
                if(isset($_GET['op']) && $_GET['op']=='read')CBookmarks::read(0,$_GET['section']);
                if(isset($_GET['op']) && $_GET['op']=='readall')CBookmarks::readAll();
                CBookmarks::getList($filename);
                break;
		}

        popup_footer();
	}
}
?>