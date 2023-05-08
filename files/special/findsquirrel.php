<?php
//Wiederverwertung herrenloser Eichhörnchen by Salator
//Einstellungen:
$tpl_class=21; //Tpl-Klasse der Eichhörnchen
//benötigt eine Zuchtfarm für User 1300000, deren Item-ID hier eintragen
$farm_id=2504548;

/** @noinspection PhpUndefinedVariableInspection */
$own_squirrs=item_count('it.tpl_class='.$tpl_class.' AND owner='.$session['user']['acctid'],true);
$squirr=item_get('it.tpl_class='.$tpl_class.' AND deposit1='.$farm_id.' ORDER BY RAND()');
//output($free_squirrs);
if($own_squirrs<3 && $squirr['id']>0)
{
	$squirr['owner']=$session['user']['acctid'];
	$squirr['deposit1']=0;
	item_set('ID='.$squirr['id'],$squirr,true,1);
	output('`uVor dir klettert ein Eichhörnchen einen Baumstamm hoch. Wie süß!
	`n`nWährend du diesem Eichhörnchen hinterherguckst bemerkst du nicht, wie '.(mb_strpos($squirr['name'],'(')?'':'ein ').$squirr['name'].'`u in deinen Beutel krabbelt und es sich dort bequem macht.');
}
else
{
	output('`uVor dir klettert ein Eichhörnchen einen Baumstamm hoch. Wie süß!');
	if(!$squirr['id'] || e_rand(1,10)==5)
	{ //nachgucken ob wirklich keins herrenlos ist
		$farmlist='0';
		$sql='SELECT id FROM items WHERE tpl_id = "squirrfarm"';
		$result=db_query($sql);
		while($farm=db_fetch_assoc($result))
		{
			$farmlist.=','.$farm['id'];
		}
		$sql='UPDATE items SET deposit1='.$farm_id.' WHERE owner=1300000 AND deposit1 NOT IN ('.$farmlist.')';
		db_query($sql);
		//output(db_affected_rows().' Eichhörnchen ins Tierheim gebracht');
	}
}
?>
