<?php

// 22062004

if (!isset($session))
{
    exit();
}
if ($_GET['op']=='')
{
    output('`@Du begegnest einem merkwürdigen alten Mann!`n`n"`#Ich hab mich verlaufen.`@", sagt er, "`#Kannst du mich in die Stadt zurückbringen?`@"`n`n
	Du weißt, dass du einen Waldkampf für heute verlieren wirst, wenn du diesen alten Mann in die Stadt bringst. Wirst du ihm helfen?');
    addnav('Führe ihn in die Stadt','forest.php?op=walk');
    addnav('Lass ihn stehen','forest.php?op=return');
    $session['user']['specialinc'] = 'oldmantown.php';
}
elseif ($_GET['op']=='walk')
{
    $session['user']['turns']--;
    if (e_rand(0,1)==0)
    {
        output('`@Du nimmst dir die Zeit, ihn zurück in die Stadt zu geleiten.`n`nAls Gegenleistung schlägt er dich mit seinem hübschen Stock und du erhältst `%einen Charmepunkt`@!');
        $session['user']['charm']++;
    }
    else
    {
        output('`@Du nimmst dir die Zeit, ihn zurück in die Stadt zu geleiten.`n`nAls Dankeschön gibt er dir `%einen Edelstein`@!');
        $session['user']['gems']++;
        //debuglog('got 1 gem for walking old man to village');
    }
    //addnav('Return to the forest','forest.php');
    $session['user']['reputation']++;
    $session['user']['specialinc']='';
	if($session['user']['exchangequest']==1)
	{
		$session['user']['specialinc']='village_marblegame.php';
		addnav('Weiter','village.php');
	}
}
else
{
    output('`@Du erklärst dem Opa, daß du viel zu beschäftigt bist, um ihm zu helfen.`n`n
	Keine große Sache, er sollte in der Lage sein, den Weg zurück in die Stadt selbst zu finden. Immerhin hat er es ja auch von der Stadt hierher geschafft, oder? Ein Wolf heult links von dir in der Ferne und wenige Sekunden später antwortet ein anderer Wolf viel näher von rechts. Jup, der Mann sollte in Sicherheit sein.');
    //addnav('Zurück in den Wald','forest.php');
    $session['user']['specialinc']='';
}
?>
