<?php

//@author Eleya für atrahor.de, Texte by Callyshee

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

page_header('Der Metzger');

if ($Char->alive==0)
{
	redirect('shades.php');
}
if($Char->prangerdays>0){
	redirect('pranger.php');
}

$str_out = get_title ('`$D`4e`Ar `,Metz`Ag`4e`$r');

$str_out .= '`$S`4c`Ah`,on bevor du die Eingangstür öffnest, bemerkst du einen leicht süßlichen Duft, der nur von Blut stammen kann. Anscheinend wurde im Hinterhof gerade ein Tier geschlachtet und so erklärt es sich auch, dass niemand im Laden ist und erst ein paar Minuten nach deinem Eintreten eine dicke Frau mit Hackebeil in den Händen aus dem angrenzenden Zimmer kommt. Ihr unterhaltet euch ein wenig und du musst sie einfach anstarren, nicht wegen des Blutes an der Schürze, sondern weil sie dir bekannt vorkommt. Ist sie vielleicht verwandt mit der … nein, bestimmt nic`Ah`4t`$!`n
`,Bevor du dieses Geheimnis lüften kannst, nennt sie dir das Angebot des Tages. `n`n`0';

output($str_out);

viewcommentary('butcher','Hinzufügen',25,'sagt');

addnav('M?Zurück zum Markt','market.php');

page_footer();
?>

