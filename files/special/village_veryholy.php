<?php
/**
 * @desc Der Geist des magischen Dragonslayers
 * @author Joshua Schmidtke [alias Mikay Kun]
 */

page_header('Das Verwaltungsteam der Stadt');

$str_output='
`&Du wanderst gemütlich durch die Gegend, als auf einmal Schreie zu hören sind. Der Himmel verdunkelt sich und es scheint, als ob eine böse Macht herauf zieht. Du siehst von allen Seiten stark gepanzerte Krieger in schweren Rüstungen aufmarschieren. Sie umkreisen den kleinen Platz und versperren dir und ein paar wenigen Leuten die Fluchtwege. Mit den Waffen auf euch gerichtet tritt eine Gestalt aus der Menge und baut sich vor euch allen auf.<br>
<br>
`4"Hier sind alle verschiedenen Rassen; ein spontaner Ausbruch und wider der Natur. Eine Laune des Schicksals. Unsere Absicht ist es, diesen Fehler zu korrigieren. Es gibt eine weitere Rasse. Eine, die das Leben begrüsst mit einer immer neuen Welt. Doch um dieser Rasse anzugehören, muss man die Schwelle überschreiten."`&, spricht der Mann.<br>
<br>
`^"Oder wie ihr es nennt, den Tod! Seht euch um. Jeder Grottenolm, jeder Einzelne in dieser riesigen Legion, welche eure Gegenwehr in nur einer Nacht niedergemetzelt hat, war mal so wie ihr. Kämpfte so schwächlich wie ihr. Jeder Grottenolm der heute lebt ist ein Konvertierter. Fallt nieder auf die Knie und fleht uns an, um konvertiert zu werden."`&, sprach der Dragonslayer, Meister der Grottenolme.<br>
<br>
Ein mutiger Mann tritt vor und spricht: `@"Niemand wird tun was ihr verlangt. Das ist undenkbar. Dies ist eine Welt aus vielen Rassen und wir wollen nicht, können nicht und werden nicht konvertieren."`&<br>
<br>
`^"Dann nehme ich deine Seele."`&, sprach Dragonslayer, entreißt dem Mann seine Seele und verstaut sie in einem gläsernern Gefäß. Der Körper des Mannes sackt zusammen und die Stimme erhebt sich erneut. `^"Folgt ihm oder folgt mir!"`&.<br>
<br>
Erst jetzt erkennst du einige Gesichter unter der Masse. Dort steht Alucard und Talion. Ja auch Salator, Maris und Fossla sind zu erkennen. Den ersten Redner identifizierst du als Mikay Kun. Alles seelenlose Konvertierte des Dragonslayers. Um dich herum knien sie alle nieder nur du stehst noch. Einer der Grottenolme kommt auf dich zu und ehe du dich versiehst rollt dein Kopf wohl schon auf dem Boden. Was immer passiert sein mag, nun kennst du die ganze Wahrheit und kannst diese unter den Leuten verbreiten, solltest du je wieder aus dem Grab steigen können. Doch was ist das? Du lebst ja wirklich noch. Es scheint, als hätte dich eine Macht gerettet. Na dann nichts wie los. Von der ganze Erfahrung sieht man erstmal ab und schon kann die Weltrettung beginnen. Sogar ein Geist der Verlorenen begleitet dich. Wie praktisch.<br>
<br>
<br>
`@Du erhälst die <i>Manifestation des Talion</i>.
';

$session['bufflist']['taliongeist'] = array(
"name"=>"`4Manifestation des Talion",
"rounds"=>40,
"wearoff"=>"`^Talion hat sich mit einem Hauch in Luft aufgelöst",
"defmod"=>1.3,
"roundmsg"=>"`4Der Geist von Talion gibt dir Schutz.",
"activate"=>"roundstart");

output($str_output);

?>