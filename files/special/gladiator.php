<?php
/* *************************************************** 
Find an old gladiator in forest.
This special requires battlearena.php from lonnyl
and pvparena.php from anpera
*************************************************** */  

if (!isset($session)) exit();
$session['user']['specialinc'] = 'gladiator.php';

if ($_GET['op']=='ok'){
	$gold=max(50,$session['user']['level']*20);
	output('`8Der alte Gladiator gibt dir `^'.$gold.' `8Gold für die Gebühr und nimmt dich wie ein Kind an der Hand - nur fester! Zusammen geht ihr in die Arena. Dort schubst er dich Richtung Arena und geht selbst auf die Zuschauertribüne zu.`nFür einen Moment stehst du unbewacht.');
	$session['user']['gold']+=$gold;
	$session['user']['specialinc']='';
	if (file_exists('battlearena.php')) addnav('Einen Gladiator herausfordern','battlearena.php?op=pay');
	if (file_exists('pvparena.php')) addnav('Einen Spieler herausfordern','pvparena.php');
	if (!file_exists('battlearena.php') && !file_exists('pvparena.php')){
		output('`$Ups! Sieht so aus, als ob keine Arena installiert ist.`n');
		addnav('Zurück in den Wald','forest.php');
	}
} 

elseif ($_GET['op']=='abhaun') {
	output('`8Du hast keine Lust, dich auf heruntergekommene Gladiatoren einzulassen und verabschiedest dich schnell.');
	$session['user']['specialinc']='';
} 

else {
  	output('`8Mitten im Wald begegnest du einem alten Gladiator. Du wolltest dich schon auf ihn stürzen, aber er winkt nur mit einer Hand ab und gibt dir so zu verstehen, dass er nicht feindlich gesinnt ist.`n');
	if ($session['user']['battlepoints'] > 3 && $session['user']['battlepoints']<=200){
		output('Er spricht dich an: "`qJa, Dich hab ich schonmal in der Arena gesehen. Keine besondere Leistung bisher, aber jeder Anfang ist ein guter Anfang. Du solltest öfter dort kämpfen, Du kannst es zu was bringen. Warte, ich spendiere Dir einen Kampf, bei dem ich zuschauen werde. Einverstanden?`8"');
		$session['user']['specialinc']='gladiator.php';
		addnav('Einverstanden','forest.php?op=ok');
		addnav('Zurück in den Wald','forest.php?op=abhaun');
	}
	elseif ($session['user']['battlepoints']>200){
		$session['user']['specialinc']='';
		$gold=max(100,$session['user']['level']*40);
		output('`8Der Alte verneigt sich vor dir. Als du nach dem Grund fragst, erzählt er dir, dass er jeden deiner Kämpfe in der Arena gesehen hat. "`QDu hast mich schwer beeindruckt, mein '.($session['user']['sex']?'Fräulein':'Junge').'. Hier, ich zahle Dir die nächsten 2 Kämpfe`8"`nEr gibt dir `^'.$gold.' `8Gold und verabschiedet sich von dir.');
		$session['user']['gold']+=$gold;
		$session['user']['charm']++;
		addnav('Weiter','forest.php');
	}
	else{
		output('`8"`qDu siehst recht schwächlich aus. Hast wohl noch keine Kampferfahrung in der Arena, was?`8", spricht er dich an, "`QWas würde ich dafür geben, noch einmal einen Kampf in der Arena zu gewinnen. Aber ich bin zu alt. Nun, gegen Dich würde ich es noch schaffen, aber zum Feigling werde ich nicht. Naja, wenn Du ein paar der Angeber, die jetzt in der Arena kämpfen, besiegt hast, hab ich vielleicht was für Dich. Mach sie einfach fertig und komm wieder, ja?`8"`nMit diesen Worten wendet er sich ab und verschwindet im Wald.');
		$session['user']['specialinc']='';
		addnav('Weiter','forest.php');
	}
}
?>
