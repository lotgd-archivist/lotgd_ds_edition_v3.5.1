<?php
/**
 * Originaldatei von Dragnir.de
 * halbe Datei aus der Anfrage bearbeitet
 *
 */

require_once('common.php');

popup_header('Namensgenerator');

$vorsilben = array(1=>'Bel','Lu','Dant','Rik','Tal','Dre','Rhag','Hord','Meib','Ast','Kor','Ver','Krag','Kyth','Alb','Tig','Aver','Bor','My','Ang','Dil','Sar','Or','Dra','Drik','Ruk','Nib','Man','Da','Nil','Art','Lak','Tith','Tumk','Est','Erc','Proc','Mar','Cael','Ag','Khaz','Ach','Kal','Art','Ask','Ka','Miy','Bik','Mik','Tar','Wol','Ray','Hal','Rob','Tak','Kar','As','Zor','Nogl','Sedi','Werl','Dir','Bone','Dark','Cap','Ver','Besid','Hage','Cunpol','Deriter','Sawan','Pes','Moad','Crim','Lyni','Ast','Mer','Ror','Des','Vert','War','Lan');
$nachsilben = array(1=>'nu','is','us','ilo','ker','yanki','uz','ius','ven','ar','lay','var','hut','ic','rav','rol','kul','kal','ven','sharr','cil','rak','ahm','lino','ibo','ivo','filo','avo','in','sard','ys','ar','ir','lion','er','ak','tram','icule','enay','ian','acs','har','orus','ka','onis','pil','icles','ra','in','us','ilo','is','as','ik','ak','at','it','ard','ar','ak','re','vreal','ustil','lisdo','vrel','werd','kryon','rit','mak','alk','zar','ad','id','et','wik','lik','dil','lin','en','ketch','asad','lon','gon','ron','rin','lion');

$int_vorsilben = count($vorsilben);
$int_nachsilben = count($nachsilben);
$vorsilbe=e_rand(1,$int_vorsilben);
$nachsilbe=e_rand(1,$int_nachsilben);

output('`nVorsilben: '.$int_vorsilben);
output('`nEine zufällige Vorsilbe: '.$vorsilben[$vorsilbe]);

output('`nNachsilben: '.$int_nachsilben);
output('`nEine zufällige Nachsilbe: '.$nachsilben[$nachsilbe]);

output('`n`n`^'.$vorsilben[$vorsilbe].$nachsilben[$nachsilbe].'`& ist also eine von '.$int_vorsilben*$int_nachsilben.' möglichen Kombinationen für einen Namen dieses Generators');
output('`n`n`c`^'.$vorsilben[$vorsilbe].$nachsilben[$nachsilbe].'`&');
output('`n`n`n`n<button onClick="javascript:location.reload();" class="input">Anderer Name</button>`c');


popup_footer();
?>