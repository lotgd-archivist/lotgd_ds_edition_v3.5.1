<?php

/**
 * Special im Garten: Die Elster, 26.05.2007
 * @author Fingolfin für Dragonslayer
 */

page_header('Die Elster');

$session['user']['specialinc'] = basename(__FILE__);
$str_filename = basename($_SERVER['SCRIPT_FILENAME']);
$str_backlink = 'gardens.php';

$str_output = '';

switch($_GET['op'])
{
   case '':

      $str_output .= '`0Du betrittst den Garten und gehst ein paar Schritte, als vor dir plötzlich eine Elster auf den Weg gehüpft kommt und dich mit schiefem Kopf anschaut. Du gehst einige Schritte weiter doch die Elster weicht kein bischen zurück.`n`n
      `&Was wirst du tun?';

      addnav('Ein Goldstück hergeben',$str_filename.'?op=give');
      addnav('Verscheuchen',$str_filename.'?op=scare');
      break;

   case 'give':

      if($session['user']['gold']>0)
      {
         $str_output .= '`0Du kramst ein Goldstück aus deiner Tasche heraus und wirfst es der Elster hin. Sofort schnappt diese danach und ist so schnell wieder davon geflogen wie sie gekommen ist.`n`n
         `&Du fühlst dich besser und könntest wieder ein wenig Aufregung gebrauchen.';

         $session['user']['gold'] -= 1;
         $session['user']['turns'] += 1;
      }
      else
      {
         $str_output .= '`0Du suchst in deinen Taschen nach Gold doch du kannst nichts außer ein paar Brotkrümeln finden die du der Elster hinwirfst. Diese scheint dich nur noch schiefer anzuschauen und verschwindet flatternd.`n`n
         `&Du gehst etwas enttäuscht weiter in den Garten.';
      }
      addnav('Weiter',$str_backlink);

      $session['user']['specialinc'] = '';
      break;

   case 'scare':

      $str_output .= '`0Du verscheuchst die Elster rücksichtslos mit wilden Bewegungen und lautem Geschrei. Anschließend gehst du weiter durch den Garten.';

      if($session['user']['turns']>0 && e_rand(1,5) == 2)
      {
         $str_output .= '`&Du verlierst einen Waldkampf.';

         $session['user']['turns'] -= 1;
      }

      addnav('Weiter',$str_backlink);

      $session['user']['specialinc'] = '';
      break;
}
output($str_output);

page_footer();

?>