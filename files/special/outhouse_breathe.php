<?php
/**
 * @desc Gaaaanz lange Luft anhalten! (Das ist also sozusagen ein Scheiss-Special :-D )
 * @author Dragonslayer
 * @copyright Atrahor, DS V2.5
 */

page_header('Ein halbe Tonne Trollmist zum mitnehmen bitte...');

$str_backlink = 'outhouse.php';
$str_backtext = 'Vor die Toiletten treten';

$str_output = '`c`^Igittigitt`0`c`n';

$str_output .= '`tDer furchtbare Gestank in dieser Klärgrube treibt dir Tränen in die Augen und Du fragst Dich gerade, warum Mutter Natur die Augen verschließbar gemacht hat, die Nase aber nicht, als dir die Luft wortwörtlich weg bleibt!`n "`$Bei allen Göttern, was ist denn DAS ?!?`t"`nDoch eigentlich ist es dir klar: Vor dir muss mindestens ein Rudel Trolle hier gewesen sein! Himmel was ist DAS für ein Haufen...wenn Du doch nicht so nötig müsstest...da hilft nix, Luft anhalten und durch!`n`n';
if(!isset($session['bufflist']['`FLanger Atem']))
{
	switch(e_rand(0,3))
	{
		case 0:
			{
				$str_output .= 'Du versuchst krampfhaft den Atem anzuhalten und das hier unbeschadet zu überleben. Musst jedoch zu Deinem blanken Entsetzen feststellen, dass Du doch kein guter Perlentaucher geworden wärst, dein Atem ist viel zu schnell verbraucht!`n
				Achja, derjenige, der den Spruch `%Es ist noch niemand erstunken....`t geprägt hat...der hat gelogen. `bDu bist tot - erstunken!`b`n`n';

				addnews($session['user']['name'].' `tist erstunken`0');

				killplayer(100,5,0,'','');

				$str_output .= create_lnk('Ab ins Totenreich, da stinkts wenigstens nicht so','shades.php',true,true,'',false,'T?Ab ins Totenreich',true);
			}
			break;
		default:
			{
				$str_output .= 'Du versuchst krampfhaft den Atem anzuhalten und das hier unbeschadet zu überleben. Mit letzter Mühe und Not rettest Du Dich nach deinem Geschäft wieder ins ins Freie. Minutenlang jappst Du noch und kämpfst mit einer schleichenden Übelkeit, Achja, wo kann man seine Kleidung doch gleich preisgünstig verbrennen lassen?`n`n';
				buff_add(array('name'=>'`tFluch der Porzellangöttin','rounds'=>8,'wearoff'=>'Du wagst deine Nase wieder zu öffnen!','defmod'=>0.7,'roundmsg'=>'`tDu hast das Gefühl noch immer Trollmist zu riechen und atmest deswegen flach und hektisch.','activate'=>'offense'));
				$str_output .= create_lnk('Schnell weit weg von hier, ab in den Wald!','forest.php',true,true,'',false,'W?In den Wald',true);
			}
			break;
	}
}
else
{
	$str_output .= 'Mit einem gewinnenden Lächeln verrichtest Du in aaaaller Seelenruhe Dein Geschäft. Der Toilettengnom mag es kaum glauben. Mit offenem Munde steht er einfach nur da und starrt dich an...sowas kommt ihm auch nur selten unter!`n`n';
	$str_output .= create_lnk('Zurück in den Wald schlendern','forest.php',true,true,'',false,'W?Ab in den Wald');

	unset($session['bufflist']['`FLanger Atem']);

}

spc_delete_navs_for_special_inclusion();

output($str_output);
?>