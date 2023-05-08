<?php
/**
 * houses_view.inc.php: Gemeinsame Komponente der Wohnviertel-Listenansicht
 * 						Wird benutzt von: houses.php, houses_httpreq.php
 * 						Stellt über REQUEST Suchstring zusammen, ruft Daten ab
 * 						und zeigt diese an.
 * 						Benötigt:
 * 							$int_p: 	Aktuelle Seite
 * 							$int_maxp: 	Gesamtseitenanzahl
 * @author talion <t@ssilo.de>
 * @version DS-E V/3
*/

$int_htype = -1;
$str_raw_search = '';
$bool_tosell = false;

function houses_view_get_search () {
	// Diese Vars werden hier als Nebenprodukt gesetzt
	global $int_htype,$str_raw_search,$bool_tosell;
	
	// Suche nach Hausname / -nummer
	$str_raw_search = trim(stripslashes($_REQUEST['search']));
	
	if (!empty($str_raw_search))
	{
		if (utf8_strcspn($str_raw_search,'0123456789')<=1)
		{
			$search='houseid='.intval($str_raw_search).' AND ';
		}
		else
		{
			$search = str_create_search_string($str_raw_search);
			$search='housename LIKE "'.$search.'" AND ';
		}
		// Damit Suchergebnis auch sichtbar ist..
		$_POST['page'] = null;
	}
	else
	{
		$search='';
	}
	
	// Suche in grünen Seiten (nach Haustyp)
	if(isset($_REQUEST['htype'])) {
		$int_htype = (int)$_REQUEST['htype'];
		$search .= ' status='.$int_htype.' AND ';
	}
	if(isset($_REQUEST['tosell'])) {
		$bool_tosell = (bool)$_REQUEST['tosell'];
		$search .= ' build_state='.HOUSES_BUILD_STATE_SELL.' AND ';
	}
	// END Suche in grünen Seiten
	
	return($search);
}

function houses_view_get_out ($int_p,$int_maxp,$search) {

	global $bool_tosell,$int_htype,$str_raw_search,$session;
	
	// Wird zum Erstellen des Zurücklinks nach Hausbio-Aktionen gebraucht
	$session['houses_bio_ret_querystring'] = 'page='.$int_p.'&search='.urlencode($str_raw_search)
															.($int_htype > -1 ? '&htype='.$int_htype : '')
															.($bool_tosell ? '&tosell=1' : '');
	
	$str_tmp = '';
	
	$sql = 'SELECT h.housename,h.houseid,h.owner,h.status,h.build_state,h.gold,h.gems FROM houses h
			WHERE '.$search.' 1 ORDER BY houseid ASC LIMIT '.(($int_p - 1) * 9).',9';
	$result = db_query($sql);
	$int_count = db_num_rows($result);
	 if(!isset($str_output))$str_output='';
	$str_output .= '
				<table border="0" cellpadding="0" cellspacing="5" width="500">
				<tr>
					<td align="center" colspan="7" width="100%">

						<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr class="frame_label">
										<td class="frame_label_l" width="46"/>
										<td class="frame_label" height="24">'.(empty($search) ? $int_p.'. Straße' : 'Das Wohnviertel').'</td>
										<td class="frame_label_r" width="46"/>
									</tr>
								</table>
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td class="frame_border_l" />
										<td class="frame_main" align="center" valign="top" id="h_bio_box">
										
											<i>';
	if(empty($search)) {
		$str_output .= 'Vor dir siehst Du die '.$int_p.'. Straße des Wohnviertels. In ihr befinden sich '.$int_count.' Anwesen.';
	}
	else {
		$str_output .= 'Du suchst nach einem Haus, auf das die folgende Beschreibung zutrifft: <br>';
		if(!empty($str_raw_search)) {
			$str_output .= $str_raw_search;
		}
		if($int_htype > -1) {
			$str_output .= get_house_state($int_htype,0,false,false);
		}
		if($bool_tosell) {
			$str_output .= 'Zu verkaufen.';
		}
	}

	$str_output .= 							'</i>	
										
										</td>
										<td class="frame_border_r" />
									</tr>
								</table>
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr class="frame_label_b">
										<td class="frame_label_lb" width="46"/>
										<td class="frame_label" height="24"><img src="./images/frame/zier_b2.png"/></td>
										<td class="frame_label_rb" width="46"/>
									</tr>
								</table>
							</td>
						</tr>
						</table>				
					</td>
				</tr>
				<tr>
					<td colspan="7">
						
					</td>
				</tr>
				<tr>
					<td colspan="7" valign="bottom"><hr></td>
				</tr>
				<tr>
					<td align="left" valign="top" width="100">
						'.($int_p > 1 
								? '<a href="javascript:void(0);" class="hs" data-id="'.($int_p-1).'"><img src="./images/h_back.png" border="0" title="Vorherige Straße" alt="Vorherige Straße"></a>'
								: ''
							).
					'</td>
					<td colspan="5" valign="top" align="center">
						'.plu_mi('houses',1,false).'
						<table width="250" id="'.plu_mi_unique_id('houses').'">
							<tr>';
				for ($i=1; $i<=$int_count; $i++)
				{
															
					$row = db_fetch_assoc($result);
															
					$str_h_ava = CPicture::get_image_path($row['owner'],'h',1);
					
				    $str_output .= '
				    <td align="center" valign="bottom" width="80" class="hb" data-id="'.$row['houseid'].'" style="cursor:pointer;font-weight:bold;">`0
				    <p>`&- '.$row['houseid'].' -`0</p>';
				    // Nächste Zeile
				    $str_tmp .= '<td align="center" valign="top" width="80" class="hb" data-id="'.$row['houseid'].'" style="cursor:pointer;">
				    `&'.$row['housename'].'`0<br>('.get_house_state($row['status'],$row['build_state'],false,false).')';
				    if($bool_tosell)
					{
						if($row['owner'] == 0) {
							extract(house_get_price($row));
						}
						else {
							$gold = 0;
							$gems = 0;
						}
			
						$gold += $row['gold'];
						$gems += $row['gems'];
						$str_tmp .= '<br><small><img src="./images/icons/gold.gif" alt="Gold" title="Gold" width="10"> '.$gold.'
									 <br><img src="./images/icons/gem.gif" alt="Edelsteine" title="Edelsteine" width="10"> '.$gems.'</small>';
					}
				    $str_tmp .= '</td>';
				    
				    if(($str_h_ava)) {
			       		$str_output .= '<img src="'.$str_h_ava.'" alt="Haus # '.$row['houseid'].'" height="50" width="50" style="border:1px dotted #CCCCCC;">`n';
					}
					else {
						$str_output .= '<img src="./images/h_ava_defkl.jpg" alt="Haus # '.$row['houseid'].'" height="50" width="78" style="border:1px dotted #CCCCCC;">`n';
					}
					$str_output .= '</td>';
					
					if(($i % 3) == 0)
					{
						$str_output .= '</tr><tr>'.$str_tmp.'</tr><tr><td colspan="3"><hr /></td></tr><tr>';	
						$str_tmp = '';
					}

				}
				$str_output .= '</tr>
								<tr>' . $str_tmp . '</tr>
						</table>
					</td>
					<td align="right" valign="top" width="100">
					'.($int_maxp > $int_p 
								? '<a href="javascript:void(0);" class="hs" data-id="'.($int_p+1).'"><img src="./images/h_forw.png" border="0" title="Nächste Straße" alt="Nächste Straße"></a>'
								: ''
							).
					'</td>
				</tr>
				</table>';
				
	unset($str_tmp);
	
	return($str_output);
}

?>