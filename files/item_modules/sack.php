<?php

function sack_hook_process($item_hook , &$item )
{

	global $session,$item_hook_info;

	switch ($item_hook )
	{

		case 'furniture':

			$desc=mb_substr($item['description'],42);
			$desc=mb_substr($desc,0,$desc-11);


			if ($session['user']['turns']>1)
			{
				output("`&Du trittst vor den Sandsack, holst weit aus und ");
				switch (e_rand(1,10))
				{
					case 1:
					case 2:
					case 3:
					case 4:
						output("`&verfehlst $desc `&um Längen. Tja, das war wohl nichts.");
						break;
					case 5:
						output("`&rammst $desc `&dein Knie direkt in den Magen. Das hat gut getan!");
						break;
					case 6:
						output("`&schlägst $desc `&deinen Ellenbogen ins Gesicht.");
						break;
					case 7:
						output("`&triffst $desc `&mit der Faust mitten ins Gesicht.");
						break;
					case 8:
						output("`&triffst $desc `&bei einem gewagten Sprungtritt an der Brust. Nicht schlecht!");
						break;
					case 9:
						output("`&schlägst $desc `&die Faust ans Kinn. 8-9-10 - KO!");
						break;
					case 10:
						if ($item['value2']==2)
						{
							output("`&und prügelst wild auf $desc `&ein. `^Das war zu viel für den armen Sandsack und er platz knirschend auf und entleert sich vor deinen Füßen. Da kommt jede Hilfe zu spät!`&");
							$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'".$item_hook_info['section']."',".$session['user']['acctid'].",': `@hat in einem Tobsuchtanfall einen Sandsack zerfetzt!')";
							db_query($sql);

							item_delete('id='.$item['id'] );

							output("`n`n`^Du brennst darauf, Gleiches mit dem lebenden Original zu tun und deine Motivation gewährt dir weitere `@3 Waldkämpfe!`^");
							$session['user']['turns']+=3;
						}
						else
						{
							output("`&und prügelst wild auf $desc `&ein. `^Du hörst es knirschen und bersten, der Sandsack platzt sogar an einer kleinen Stelle auf!`&");
							$dam=$item['value2']+1;

							item_set('id='.$item['id'] , array('value2'=>$dam) );

						}
						break;
				}
			}
			else
			{
				output("`&So gern du $desc `&auch mal wieder eine rein hauen willst, musst du doch feststellen, dass dir dazu völlig die Kraft fehlt. Versuchs doch morgen noch einmal!");
			}

			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);

			break;
	}
}

?>