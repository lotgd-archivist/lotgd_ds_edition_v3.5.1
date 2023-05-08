<?php

/**
 * Special im Wald: Dieser komische kerl läuft durch den wald und verkauft Buchstaben. sehr suspekt.
 * @copyright Dragonslayer for Atrahor
 * @author Dragonslayer
 */

page_header('Der Buchstabensalat');

/** @noinspection PhpUndefinedVariableInspection */
$Char->specialinc = 'forest_scumm.php';
$str_backlink = 'forest.php';

$arr_item = item_get('i.tpl_id="scumm_logo" AND i.owner='.$Char->acctid);
if($arr_item !== false) 
{
    $arr_item['content'] = adv_unserialize($arr_item['content']);
}

$str_out = get_title('Der Buchstabensalat');

switch($_GET['sop'])
{
    default:
    case '':
        {
            if($arr_item['content']['new'] == true || count($arr_item['content']) == 5)
            {
                $str_out .= '`J"`GPsssst`J" hörst du es mit einem Male aus dem Gebüsch zischen und bleibst verwundert stehen. "`GPsssst, duuuu?`J" Entwas verwundert antwortest du "`aWer? Ich?`J" - "`GPssssssst, genaaaaaaaau. Du hast da so ein schönes Schild mit so tollen Buchstaben...willst du es mir verkaufen?`J" Aus dem Gebüsch tritt ein kleiner Homunkulus und stakst auf spindeldürren Beinen ein paar Schritte auf dich zu. "`GIch gebe dir auch 10 Edelsteine dafür!`J" Also? was sagst du?`0';
                
                addnav('Pffft, 10 ES...','forest.php?sop=leave');
                addnav('Klingt fair','forest.php?sop=sell');
            }
            else
            {
                $str_scumm = 'SCUMM';
                $int_char = count($arr_item['content']);
                $str_sell = $str_scumm[$int_char];
                $str_out .= '`J"`GPsssst`J" hörst du es mit einem Male aus dem Gebüsch zischen und bleibst verwundert stehen. "`GPsssst, duuuu?`J" Entwas verwundert antwortest du "`aWer? Ich?`J" - "`GPssssssst, genaaaaaaaau. willst du ein \''.$str_sell.'\' kaufen?`J" Aus dem Gebüsch tritt ein kleiner Homunkulus und stakst auf spindeldürren Beinen ein paar Schritte auf dich zu. "`aIch?!? ein \''.$str_sell.'\' kaufen?!?`J" -"`GPssssssst, genaaaaaaaau! Ich würde es dir für drei Edelsteine abtreten. Überleg nur mal was du damit alles anstellen könnte...`J" Man, das kleine Männchen kommt ja aus dem Schwärmen gar nicht mehr raus. Wie siehts aus? Willst du ihm ein \''.$str_sell.'\' abkaufen?`0';
                addnav('Pffft, 3 ES...','forest.php?sop=leave');
                if($Char->gems >= 3) addnav('Klingt fair','forest.php?sop=buy&number='.$int_char);
            }
            
            break;
        }
    case 'buy':
        {
            if($arr_item === false)
            {
                item_add($Char->acctid,'scumm_logo',array('content'=>array(),'tpl_name'=>'`IDas unvollständige Logo der S.C.U.M.M. Bar'));
                $arr_item = item_get('i.tpl_id="scumm_logo" AND i.owner='.$Char->acctid);
                $arr_item['content'] = utf8_unserialize($arr_item['content']);
            }
            
            $str_out .= '`J Och, wieso eigentlich nicht, denkst du dir und gibst dem kleinen Homunkulus drei Edelsteine. Im Gegenzug schmeisst dieser dir den Buchstaben hin und hüpft mit seinen Edelsteinen von dannen. Was du wohl jetzt damit anfangen sollst? Buchstabennudelsuppe? Mal jemanden fragen der sich damit auskennt...`0';
            $arr_item['content'][(int)$_GET['number']] = true;
            item_set($arr_item['id'],array('content'=>$arr_item['content']));
            $Char->gems -= 3;
            addnav('Danke!','forest.php?sop=leave');
            break;
        }
    case 'sell':
        {
            $str_out .= '`J Och, wieso eigentlich nicht, denkst du dir und gibst dem kleinen Homunkulus sein Schild. Dieser schmeisst dir die 10 Edelsteine hin und beginnt sofort mit Hingabe die Buchstaben davon abzulösen. "`GNjaaaaaa, so viele schöne Buchstaben`J" hörst du ihn noch plappern, aber da haben dich deine Füße schon wieder einige Schritte weiter getragen.`0';
            
            item_delete($arr_item['id']);
            $Char->gems += 10;
            addnav('Danke!','forest.php?sop=leave');
            break;
        }
    case 'leave':
        {
            $Char->specialinc = '';
            redirect('forest.php');
            break;
        }
}
output(words_by_sex($str_out));
?>