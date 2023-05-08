<?php

// 17072009

// created by Lonny Luberts for http://www.pqcomp.com/logd, built on idea from quest's giftshop with all new code.
// this file needs customization before use and is designed to be added in many places if need be
// as different gift shops.
// search and replace (newgiftshop.php) with what you name the giftshop php file
// search and replace (gift 1)-(your gift) with your gifts - make sure you use the space inbetween gift & 1 etc...
// if you do an auto replace with your editor.
// be sure to edit the return nav
// please feel free to use and edit this file, any major upgrades or improvements should be
// mailed to logd@pqcomp.com for consideration as a permenant inclusion
// please do not remove the comments from this file.
// Version: 03212004
//
// changes to fit ext (GER) and translation by anpera
// added items with buffs
//
// Bugfix u. Kerker-Addon by Maris (Maraxxus@gmx.de)
// Nachrichten zusammen mit Geschenken versenden by talion (t@ssilo.de)
// Umgestellt auf neues Itemsystem
// YOM-Adressbuch als Favoritenliste by talion
// Massenabfertigung (gleiches/anderes Geschenk an andere/gleiche Person) by Takehon (n2code@herr-der-mails.de)

require_once "common.php";
checkday();
page_header("Geschenkeladen");
$str_filename = basename(__FILE__);

switch ( $_GET['op'] )
{
        case 'send':
        {
        		output(get_title('`IGeschenkeladen`0'));
                $gift = $_GET['op2'];
                if (isset($_POST['search']) || $_GET['search']>"")
                {
                        if ($_GET['search']>"") $_POST['search']=urldecode($_GET['search']);
                        $search = str_create_search_string($_POST['search']);
                        $search = 'name LIKE "'.$search.'" AND ';
                        if ($_POST['search']=="weiblich") $search="sex=1 AND ";
                        if ($_POST['search']=="männlich") $search="sex=0 AND ";
                }
                else
                {
                        $search="";
                }
                $ppp=25; // Player Per Page to display
                if (!$_GET['limit'])
                {
                        $page=0;
                }
                else
                {
                        $page=(int)$_GET['limit'];
                        addnav('Vorherige Seite','newgiftshop.php?op=send&op2='.$gift.'&limit='.($page-1).'&search='.urlencode($_POST['search']));
                }
                
                // Rufe Adressbuch-Kontakte ab
                $sql = 'SELECT y.player	FROM yom_adressbuch y WHERE	y.acctid='.$session['user']['acctid'];
                $fav_ids = '';
                $res = db_query($sql);
                if(!db_num_rows($res)) {
                	$fav_ids_sql = '';
                }
                else {
                	$fav_ids_sql = '(acctid IN(-1';
	                while($a = db_fetch_array($res)) {
	                	$fav_ids_sql .= ','.$a[0];
	                }
	                $fav_ids_sql .= ')) DESC, ';
                }
                // END Rufe Adressbuch-Kontakte ab
                
                
                $limit="".($page*$ppp).",".($ppp+1);
                $sql = "SELECT login,name,level,sex,acctid
                        FROM accounts
                        WHERE $search
                        locked=0
                        AND acctid<>".$session['user']['acctid']."
                        AND charm>-1
                        ORDER BY (login='".db_real_escape_string($_POST['search'])."') DESC, acctid=".$session['user']['marriedto']." DESC, ".$fav_ids_sql."login,level
                        LIMIT $limit";
                $result = db_query($sql);
                $count = db_num_rows($result);
                if ($count>$ppp) addnav('Nächste Seite','newgiftshop.php?op=send&op2='.$gift.'&limit='.($page+1).'&search='.$_POST['search']);
                $link = 'newgiftshop.php?op=send&op2='.$gift;
                addnav('',$link);
                $item=item_get_tpl('tpl_id="'.$gift.'"');
                $arr_placeholder = array('{name}'=>$session['user']['name'],
                                                                '{shortname}'=>$session['user']['login'],
                                                                '{date}'=>getgamedate(),
                                                                '{recipient_name}'=>'(Empfänger)',
                                                                '{gift_name}'=>$item['tpl_name']
                                                                );
                $arr_search = array_keys($arr_placeholder);
                $arr_rpl = array_values($arr_placeholder);
                $item['tpl_description'] = words_by_sex(str_replace($arr_search,$arr_rpl,$item['tpl_description']));
                $str_out.='`b'.$item['tpl_name'].'`0`b
                `n'.$item['tpl_description'].'
                `n
                `n`0Wem willst du das Geschenk schicken? Du hast außerdem die Möglichkeit, eine nette Botschaft beizulegen.
                `n`n<form action="'.utf8_htmlentities($link).'" method="POST">
                Nach Name suchen: '.JS::Autocomplete('search',false, true).'
                <input type="submit" class="button" value="Suchen">
                </form>
                `n`n<table cellpadding="3" cellspacing="0" border="0">
                <tr class="trhead">
                <th>Name</th>
                <th>Level</th>
                <th>Geschlecht</th>
                <th>Versenden</th>
                </tr>';
                for ($i=0;$i<$count;$i++)
                {
                        $row = db_fetch_assoc($result);
                        $link = 'newgiftshop.php?op=send2&op2='.$gift.'&name='.$row['acctid'];
                        $str_out.='<tr class="'.($i%2?'trlight':'trdark').'">
                        <td>'.$row['name'].'`0</td>
                        <td>'.$row['level'].'</td>
                        <td align="center"><img src="./images/'.($row['sex']?'female':'male').'.gif"></td>
                        <td>
                                [ '.create_lnk('Ohne',$link).' ]
                                [ '.create_lnk('Mit',$link.'&send_msg=1').' ]
                                Nachricht
                        </td>
                        </tr>';
                }
                output($str_out.'</table>');
                addnav('Zurück zum Laden','newgiftshop.php');
                break;
        }

        case 'send2':
        {
        		output(get_title('`IGeschenkeladen`0'));
                $name = (int)$_GET['name'];
                $giftmsg = $_POST['message'];
                $sq3 = "SELECT name,acctid,sex FROM accounts WHERE acctid=".$name."";
                $result3=db_query($sq3);
                $row3 = db_fetch_assoc($result3);
                if($_GET['send_msg'])
                {
                        $link = 'newgiftshop.php?op=send2&op2='.$_GET['op2'].'&name='.$name;
                        addnav('',$link);
                        output("`0Du kannst hier ".$row3['name']."`0 eine nette Botschaft beilegen:`n`n");
                        $form = array('Vorschau:,preview,message', 'message'=>'Deine Botschaft:,textarea,50,3');
                        output('<form action="'.utf8_htmlentities($link).'" method="POST">');
                        showform($form,$persons,false,'Geschenk abschicken');
                        //500-Zeichen-Begrenzung der Botschaft entfällt durch Benutzung von textarea
                        $check = 1;
                }
                if ($check!=1)
                {
                        $gift = item_get_tpl ( ' tpl_id="'.$_GET['op2'].'"' );
                        // Platzhalter in den Beschreibungen, die in Geschenkitems verwendet werden können
                        // Wenn Verwendung in Geschenkehook: global nicht vergessen!
                        $arr_placeholder = array('{name}'=>$session['user']['name'],
                                                                        '{shortname}'=>$session['user']['login'],
                                                                        '{date}'=>getgamedate(),
                                                                        '{recipient_name}'=>$row3['name'],
                                                                        '{gift_name}'=>$gift['tpl_name']
                                                                        );
                        $arr_search = array_keys($arr_placeholder);
                        $arr_rpl = array_values($arr_placeholder);
                        $gift['tpl_description'] = words_by_sex(str_replace($arr_search,$arr_rpl,$gift['tpl_description']));

                        $item_hook_info ['mailmsg'] = '';
                        $item_hook_info ['failmsg'] = '';
                        $item_hook_info ['effect'] = '';
                        $item_hook_info ['acctid'] = $name;
                        $item_hook_info ['rec_name'] = $row3['name'];
                        $item_hook_info ['rec_sex'] = $row3['sex'];
                        $item_hook_info ['check'] = 0;

                        if ( $gift ['gift_hook'] != '' )
                        {
                                item_load_hook ( $gift ['gift_hook'] , 'gift' , $gift );
                        }
						
						$gift_costs = array('gold' => $gift['tpl_gold'], 'gems' => $gift['tpl_gems']);
						
                        if(!$item_hook_info['hookstop'])
                        {
                                $item_hook_info['effect'] = $gift['tpl_description'];
                                $session['user']['gold'] -= $gift['tpl_gold'];
                                $session['user']['gems'] -= $gift['tpl_gems'];
                                $gift['tpl_gold'] = 1;
                                $gift['tpl_gems'] = 0;
                                item_add ( $item_hook_info['acctid'] , 0 , $gift );
                        }

                        if($item_hook_info['check'] != 1)
                        {
                                $item_hook_info ['mailmsg'] .= $session['user']['name'];
                                $item_hook_info ['mailmsg'] .= '`7 hat dir ein Geschenk geschickt.  Du öffnest es. Es ist ein/e `6'
                                . $gift['tpl_name'] . '`7 aus dem Geschenkeladen.`n'
                                . $item_hook_info ['effect'];
                                if($giftmsg != '')
                                {
                                        $item_hook_info ['mailmsg'] .= '`0`n`nAls du die Verpackung näher betrachtest, fällt dir eine handgeschriebene Botschaft auf:`n'.$giftmsg.'`7';
                                }
                                systemmail($name,"`2Geschenk erhalten!`2",$item_hook_info ['mailmsg']);
                                debuglog('Hat Geschenk '.$gift['tpl_name'].' versendet an: ',$name);
                                output('`0Dein '.$gift['tpl_name'].'`0 wurde als Geschenk verschickt!');
                              	
								output('`n`nWillst du noch mehr Geschenke verschicken?`n'.form_header('newgiftshop.php?name='.$_GET['name']).'<input type="submit" class="button" value="Anderes Geschenk an gleiche Person"></form>'.(($session['user']['gold']>=$gift_costs['gold'] && $session['user']['gems']>=$gift_costs['gems'])?form_header('newgiftshop.php?op=send&op2='.$_GET['op2']).'<input type="submit" class="button" value="Gleiches Geschenk an andere Person"></form>':'`&`iDu kannst nicht das gleiche Geschenk an eine andere Person senden, da du es dir nicht mehr leisten kannst!`i`0'));
                        }
                }
                addnav("Zum Laden","newgiftshop.php");
                break;
        }
        
        case 'xmascard':
        	{
        		$f_userdropdown = create_function('&$arr_receiver,&$arr_form','
	        			foreach($arr_receiver as $arr_user)
	        			{
	        				$str_select .= ",".$arr_user["acctid"].",".strip_appoencode($arr_user["name"]);
	        			}
	        			$arr_form["receiver_id"] = "Der Empfänger,select".$str_select;
	        						
	        			unset($arr_form["receiver"]);
        		');     		
        	
        		page_header('Eine Weihnachtskarte');
        		$str_out .= '';
        		$str_link = $str_filename.'?op=xmascard';
        		$arr_form = array(
        			'receiver_id'=> 'Die Empfänger-ID,hidden',
        			'receiver'	=> 'Der Empfänger,usersearch',								
					'image'		=> 'Welches Bild soll die Karte zieren?,select,,--Bitte wähle aus--,bell.png,Glocke,box.png,Geschenk,santa.png,Der Weihnachtsmann,snowman.png,Schneemann,tree.png,Weihnachtsbaum',
					'message_preview' => ',preview,message',
					'message'	=> 'Deine Nachricht für die Karte,textarea,60,7',
					'submit_preview'	=> 'Vorschau,submit_button,submit'
				);
				$arr_data = persistent_nav_vars(array_keys($arr_form));
        		addnav('Zurück',$str_filename);
        		addnav('Neu beginnen',$str_link.'&act=new');
        		
        		switch ($_GET['act'])
        		{
        			default:
        			case '':
        				{        								
        					if(isset($_REQUEST['submit_send']))
        					{
        						redirect($str_link.'&act=save');
        					}				
        					
							//Überprüfen ob wir absenden oder ein Preview machen wollen
        					if(isset($_REQUEST['submit_preview']))
        					{
        						$str_receiver = $arr_data['receiver'];
        						$int_receiver = (int)$arr_data['receiver_id'];
        						$str_message  = $arr_data['message'];
        						$str_post_image = (is_null_or_empty($arr_data['image'])? 'box.png' : $arr_data['image']);
        						$str_image = './images/xmas/'.$str_post_image;
        						$bool_preview = false;
        						
        						if($int_receiver > 0)
        						{
        							$arr_receiver = CCharacter::getChars($int_receiver,'acctid,name',array('acctid' => array('type'=>CCharacter::SEARCH_EXACT) ),'','',1);
        						}
        						else 
        						{
        							$arr_receiver = CCharacter::getChars($str_receiver,'acctid,name',array('name' => array('type'=>CCharacter::SEARCH_LIKE_EXT) ),'','',50);
        						}
        						$arr_form['submit_send'] = 'Versenden,submit_button,submit,Möchtest du die Karte so absenden?';
        						
        						if(count($arr_receiver) == 0)
        						{
        							setStatusMessage('Tut mir leid, so einen Bewohner haben wir in '.getsetting('townname','atrahor').' nicht.');
        							unset($arr_form['submit_send']);
        						}
        						elseif( (count($arr_receiver) > 1 && $int_receiver == 0) )
        						{
        							setStatusMessage('Es gibt mehrere Bewohner die einen ähnlichen Namen haben, bitte wähle den entsprechenden Nutzer aus der Liste aus, oder verfeinere die Suche.');
        							$f_userdropdown($arr_receiver,$arr_form);
        							
        						}
        						elseif($int_receiver > 0)
        						{
        							$f_userdropdown($arr_receiver,$arr_form);
        							$bool_preview = true;
        						}
        						else 
        						{
        							$bool_preview = true;
        						}     						
        						
        						if($bool_preview == true) 
        						{
        							$int_receiver = $int_receiver > 0 ? $int_receiver : $arr_receiver[0]['acctid'];
        							$arr_count = db_get('SELECT id FROM xmas_card_log WHERE sender='.$Char->acctid.' AND receiver='.$int_receiver.' AND year='.date('Y'));
	        						if($arr_count !== null)
	        						{
	        							setStatusMessage('Du hast '.$arr_receiver[0]['name'].'`$ dieses Jahr schon eine Karte gesendet.');
	        							unset($arr_form['submit_send']);
	        						}
	        						else 
	        						{
	        							$arr_data['receiver_id'] = $arr_receiver[0]['acctid'];
	        							
	        							$str_preview .= '<br /><hr /><br />';
	        							$str_preview .= '`c'.print_frame('
	        							<table style="width:600px;">
	        								<tr>
	        									<td colspan="2" style="text-align:center; border-bottom:1px solid gold; padding-bottom:5px;">
	        										`IEine Weihnachtskarte von '.$Char->name.'`I aus dem Jahr '.date('Y').'
	        									</td>
	        								</tr>
			        						<tr>
			        							<td  valign="top" style="width:256px;"><img id="preview_image" width="256" height="256" src="'.$str_image.'" /></td>
			        							<td valign="top">			        								
			        								'.stripslashes($str_message).'
			        							</td>
			        						</tr>
			        					</table>
			        					','`IFrohohohe Weihnachten`0',0,true)
	        							.'`c';
	        						}
        						}
        					}        					
        					
        					//Farbtags für Ausgabe in Form vorbereiten
							array_walk($arr_data,create_function('&$val','$val = str_replace("`","``",$val);'));
        					        					        					
        					$str_out .= get_title('`IWeihnachtskarten').'
        					<table>
        						<tr>
        							<td valign="top"><img id="image_preview_image" width="256" height="256" src="./images/xmas/box.png" /></td>
        							<td valign="top">';
        					$str_out .= 'Eine Weihnachtskarte an die Liebsten versenden, das hat schöne Tradition. Das weiß auch der Geschenkeladen und versendet an Weihnachten kostenlose Karten. Sende doch auch eine. Wähle den Empfänger und ein passendes Motiv für die Karte aus und schreibe einen netten Gruß. Die Karte ist selbstverständlich völlig kostenlos, es kann aber nur eine Karte pro Empfänger von dir geschrieben werden.`n`n';
        					        					
        					$str_out .= form_header($str_link,'POST',true,'xmascard');
        					$str_out .= generateform($arr_form,$arr_data,true);
        					$str_out .= form_footer();
        					$str_out .= '
        							</td>
        						</tr>
        					</table>';
        					
        					$str_out .= '
        					'.JS::encapsulate('
							<!--
							LOTGD.addEvent( $("image"), "change", function( e ) { if($("image").selectedIndex != 0) $("image_preview_image").src="./images/xmas/"+$("image")[$("image").selectedIndex].value; } );
							if($("image").selectedIndex != 0)
							{
								$("image_preview_image").src="./images/xmas/"+$("image")[$("image").selectedIndex].value;
							}
							-->

        					');
        					
        					if(!is_null_or_empty($str_preview))
        					{
        						$str_out .= $str_preview;
        					}			
        					break;
        				}
        			case 'save':
        				{
        					if($arr_data['receiver_id'] > 0)
        					{
        						$arr_user = db_get('SELECT acctid FROM accounts WHERE acctid='.$arr_data['receiver_id']);
        					}
        					else 
        					{
        						$arr_user = db_get('SELECT acctid FROM accounts WHERE name LIKE "'.str_create_search_string($arr_data['receiver']).'"');
        					}
        				
        					if($arr_user === null)
        					{
        						setStatusMessage('Da lief etwas verkehrt, die User ID '.$arr_data['receiver_id'].' passt nicht mit dem Namen '.$arr_data['receiver'].' zusammen.');	
        						redirect($str_link);
        					}
        					else 
        					{
	        					db_insert('xmas_card_log',
	        						array(
	        							'sender' => $Char->acctid,
	        							'receiver' => (int)$arr_data['receiver_id'],
	        							'year' => date('Y')
	        						)
	        					);
	        					
	        					$arr_item_info = array();
	        					$arr_item_info['tpl_name'] = add_0_to_string(db_real_escape_string('`IEine Weihnachtskarte von '.$Char->name));
	        					$arr_item_info['tpl_description'] = db_real_escape_string('`IEine wundervolle, individuelle Weihnachtskarte aus dem Geschenkladen von '.$Char->name.'`I aus dem Jahr '.date('Y').'`0');
	        					$arr_item_info['content'] = array();
	        					$arr_item_info['content']['image'] 		= $arr_data['image'];
	        					$arr_item_info['content']['message'] 	= $arr_data['message'];
	        					$arr_item_info['content']['sender'] 	= $Char->name;
	        					$arr_item_info['content']['year'] 		= (int)date('Y');
	        					
	        					$arr_item_info['content'] = db_real_escape_string(utf8_serialize($arr_item_info['content']));
	        					
	        					item_add((int)$arr_data['receiver_id'],'xmas_card',$arr_item_info);
	        					systemmail((int)$arr_data['receiver_id'],'`IEine Weihnachtskarte von '.$Char->name.'`0','`IDu hast soeben eine Weihnachtskarte aus dem Geschenkeladen geschickt bekommen.Du kannst sie in deinem Inventar finden.`nFrohe Weihnachten wünscht dir`n'.$Char->name);
	        					
	        					user_set_stats(array('xmas_cards_sent' => 'xmas_cards_sent+1'));
	        					
	        					$arr_data = persistent_nav_vars(array_keys($arr_form),true);
	        					setStatusMessage('Deine Karte wurde erfolgreich versendet');
	        					redirect ($str_link);
        					}
        					break;
        				}
        			case 'new':
        				{
        					$arr_data = persistent_nav_vars(array_keys($arr_form),true);	        				
	        				redirect ($str_link);
        					break;
        				}
        		}
        		output($str_out);
        		break;
        	}

        default:
        {

                        output('`(E`)tw`7a`es `fa`0bgelegen im Garten befindet sich ein einfaches, schlichtes Ziegelhaus, dessen Innenraum vollkommen mit Holzregalen ausgefüllt ist. Auf den Brettern liegen unzählige Geschenke nach Preisklassen sortiert. Hier findet bestimmt jeder ein passendes Present, denn von Abartigkeiten bis hin zu kostbarem Schmuck und hilfreichen Utensilien ist hier wirklich alles vertreten.`n');
                        output('Ein'.($session['user']['sex']?' junger Mann':'e junge Frau').' steht hinter der Ladentheke und beoachtet aufmerksam, was du im Laden treibst und dir ansiehst. Sobald du aber kurz zu '.($session['user']['sex']?'ihm':'ihr').' schaust, bekommst du ein geschäftsmäßig freundliches Lächeln.`n');
                        output('Ein Schild an der Wand verspricht "`iGeschenkverpackung und Lieferu`fn`eg `7f`)re`(i.`0`i"`n`n`n',true);
                        addnav('Geschenke');
                        // Itemliste aller Geschenke
                        $res = item_tpl_list_get ( ' giftshop>0 ' , ' ORDER BY tpl_gold ASC, tpl_gems ASC ' );
						$str_person = '';
						if (array_key_exists('name', $_GET)){
							$result = db_query('SELECT `login` FROM `accounts` WHERE `acctid` = '.$_GET['name'].' LIMIT 1');
							$arr_target = db_fetch_assoc($result);
							$str_person = 'An '.$arr_target['login'].' versenden';
						}
						output('<table cellpadding="3" cellspacing="0" border="0"><tr class="trhead"><th>Geschenk auswählen</th><th>'.$str_person.'</th></tr>',true);
						$i = 0;
                        while ( $g = db_fetch_assoc ( $res ) )
                        {
								$i++;
                                //if( $session['user']['gold'] >= $g['tpl_gold'] && $session['user']['gems'] >= $g['tpl_gems'] ) {
								$bool_enough_money = ( $session['user']['gold'] >= $g['tpl_gold'] && $session['user']['gems'] >= $g['tpl_gems'] );
                                        $link = 'newgiftshop.php?op=send&op2=' . $g['tpl_id'];
                                        output( '<tr class="'.($i%2?'trlight':'trdark').'"><td><ul><li>' .
                                                        ($bool_enough_money
                                                                ? create_lnk($g['tpl_name'].'`0', $link, true, true)
                                                                : '`i'.$g['tpl_name'].'`0`i') .
                                                                ($g['tpl_gold'] > 0 ? '`0 ( '. $g['tpl_gold'] . ' Gold ) ' : '').
                                                                ($g['tpl_gems'] > 0 ? '`0 ( '. $g['tpl_gems'] . ' Edelsteine ) ' : '').
														'</li></ul></td><td>'.((array_key_exists('name', $_GET) && $bool_enough_money)?'[ '.create_lnk('Ohne','newgiftshop.php?op=send2&op2='.$g['tpl_id'].'&name='.$_GET['name']).' ][ '.create_lnk('Mit','newgiftshop.php?op=send2&op2='.$g['tpl_id'].'&name='.$_GET['name'].'&send_msg=1').' ] Nachricht':(array_key_exists('name', $_GET)?'`iDas kannst du dir nicht leisten!`i':'')).'</td></tr>'
                                                        , true );
                                //}
                        }
						output('</table>',true);
                        // END Geschenkliste
                        addnav('Sonstiges');
                        if (getsetting("activategamedate","0")>0){
                                $cakecost=$session['user']['level']*15;
                                //addnav("Torte werfen ($cakecost Gold)","newgiftshop.php?op=cake");
                                
                        }
                        
                        // Weihnachtskarten
                        if(isBetween(1,date('j'),26) && date('n') == 12)
                        {
                        	addnav("Weihnachtskarte versenden","newgiftshop.php?op=xmascard");
                        }
                        
                        addnav('Zurück');
                
                $show_invent = true;
                addnav('G?Zum Garten','gardens.php');
                addnav('d?Zum Stadtzentrum','village.php');
                break;        // END default
        }
}

page_footer();
?>