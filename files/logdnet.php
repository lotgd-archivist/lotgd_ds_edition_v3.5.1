<?php
/**
 * Darstellung des LOGD Net
 */

require_once 'common.php';

$servers = null;
page_header('LoGD Netz in '.getsetting('townname','Atrahor'));

if (isset($session) && $session['user']['loggedin']==0)
{
    addnav('Zurück');
    addnav('Zurück zum '.getsetting('townname','Atrahor').' Login','index.php?r='.intval($_GET['r']));
}
else
{
    addnav('Zurück ins Stadtzentrum','village.php');
}

{
    $str_out .= get_title('Das LoTGD Netz in '.getsetting('townname','Atrahor'));
    $str_out .= '`c`tHier findet ihr eine Liste mit anderen LoGD Servern, die im LoGD-Netz registriert sind.`0`c`n`n';
    $str_out .= '<center>
	<table>
		<tr class="trhead">
			<th>`tServername und Link`0</th>
			<th width="130">`tVersion`0</th>
		</tr>';


    if(function_exists('curl_init'))
    {
        $url=(getsetting("logdnetserver","http://lotgd.net/")."logdnet.php?op=net");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resulturl=trim(curl_exec($ch));
        curl_close($ch);
        $servers=explode("\n", $resulturl);
    }
//Ansonsten file
    elseif(ini_get('allow_url_fopen') == 'On')
    {
        $servers=file(getsetting("logdnetserver","http://lotgd.net/")."logdnet.php?op=net");
    }

    if(is_array($servers))
    {
        foreach ($servers as $key => $val)
        {
            $row=unserialize($val);
            $row['description'] = utf8_encode($row['description']);
            if (trim($row['description'])=='')
            {
                $row['description']='Another LoGD Server';
            }
            if (mb_substr(utf8_encode($row['address']),0,7)=='http://')
            {
                $str_class = ($str_class == 'trlight')?'trdark':'trlight';
                $str_out .= "
				<tr class='".$str_class."'>
					<td valign='top'>
						<a href='".utf8_htmlentities(strip_tags(utf8_encode($row['address'])))."' target='_blank'>".stripslashes(utf8_htmlentities(strip_tags($row['description'])))."`0</a>
					</td>
					<td valign='top' width='130'>".utf8_htmlentities(strip_tags(utf8_encode($row['version'])))."</td>
				</tr>";
            }
        }
    }
    else
    {
        $str_out .= "
		<tr>
			<td valign='top' colspan='2'>Hier ging jetzt irgendwas schief...</td>
		</tr>";
    }

    $str_out .= '</table></center>';
    output($str_out);
}

page_footer();