<?php

function teddy_hook_process($item_hook , &$item )
{

	global $session,$item_hook_info;

	switch ($item_hook )
	{

		case 'furniture':

			$desc=mb_substr($item['description'],56);
			$desc=mb_substr($desc,0,$desc-1).'`&-'.$item['name'];


			if ($session['user']['turns']>2)
			{
				output('`&Völlig deprimiert von den Misserfolgen des Tages greifst du dir '.($item['hvalue']?'die':'den').' '.$desc.'`& und lässt deinen Gefühlen freien Lauf. ');
				switch (e_rand(1,10))
				{
					case 1:
					case 2:
					case 3:
					case 4:
						output('Plötzlich wird dir bewusst, dass sich so etwas für '.($session['user']['sex']?'eine Heldin':'einen Helden').' nicht gehört und du hoffst, dass dich niemand dabei beobachtet hat.');
						if($session['user']['reputation']>25 && e_rand(1,5)==3)
						{
							$session['user']['reputation']--;
						}
						break;
					case 5:
						output('Etwas später ist das arme Ding völlig durchnässt, aber du fühlst dich besser.');
						if($session['user']['spirits']==-2)
						{
							$session['user']['spirits']++;
							$session['user']['turns']++;
							output(' Du könntest jetzt einen Kampf vertragen.');
						}
						break;
					case 6:
						output('`nWas war das eben für ein Schatten vor dem Fenster? Du ziehst die Bettdecke bis über deinen Kopf und drückst '.($item['hvalue']?'die Puppe':'den Teddy').' fester an dich.');
						break;
					case 7:
						output('Auf einmal kannst du hören, wie '.($item['hvalue']?'die Puppe':'der Teddy').' mit dir spricht: "`^Ja, '.$session['user']['name'].'`^, du bist '.($session['user']['sex']?'die':'der').' Beste!`&"
						`nFaszinierend... Oder war es doch nur Einbildung?');
						break;
					case 8:
						if($session['user']['dragonkills']>10)
						{
							$sql='SELECT a.name 
								FROM keylist k 
								LEFT JOIN accounts a ON a.acctid=k.owner 
								WHERE value1='.(int)$item['deposit1'].'
								AND type='.HOUSES_KEY_DEFAULT.'
								AND owner<> '.$session['user']['acctid'].'
								ORDER BY a.restatlocation='.(int)$item['deposit1'].' DESC, rand()
								LIMIT 1';
								$result=db_query($sql);
							if(db_num_rows($result)==1)
							{
								$row=db_fetch_assoc($result);
								output('Plötzlich steht '.$row['name'].'`& in der Tür. Oops, wie peinlich!');
								$session['user']['charm']=max(0,$session['user']['charm']-1);
							}
							else
							{
								output('Wie gut, dass du allein im Haus bist. Wenn jetzt unverhofft jemand reinkommen und dich so sehen würde, das wäre doch peinlich, oder?');
							}
						}
						else
						{
							output('Du träumst davon, einmal groß und stark zu werden und die Welt zu verbessern. Und tatsächlich fühlst du dich jetzt etwas stärker.');
							$session['user']['attack']++;
						}
						break;
					case 9:
						output('Zufrieden schläfst du ein...');
						$session['user']['turns']-=2;
						break;
					case 10:
						if ($item['value2']>=3)
						{
							output('Dabei gehst du recht wild zur Sache. `^Das war zu viel für '.($item['hvalue']?'den armen Teddy und er':'die arme Puppe und sie').' platzt. Hoppla!
							`&`n`nDie Sache ist dir hochgradig peinlich und du beeilst dich, Ersatz zu beschaffen um die Sache zu vertuschen. Trotzdem glaubst du, der Ersatz sieht irgendwie DIR sehr ähnlich...');
							$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'".$item_hook_info['section']."',".$session['user']['acctid'].",': `@hat in wilder Ekstase ".($item['hvalue']?'eine Puppe':'einen Teddy')." zerstört!')";
							db_query($sql);

							$item['description']=mb_substr($item['description'],0,56).$session['user']['name'].'.';
							$item['value1']=$session['user']['acctid'];
							$item['value2']=0;
							item_set('id='.$item['id'],$item );
						}
						else
						{
							output('Dabei gehst du recht wild zur Sache. `^'.($item['hvalue']?'Der Teddy':'Die Puppe').' gibt leise berstende Geräusche von sich.');
							$dam=$item['value2']+1;

							item_set('id='.$item['id'] , array('value2'=>$dam) );

						}
						break;
				}
			}
			else
			{
				output('`&So gern du auch mit '.($item['hvalue']?'der':'dem').' '.$desc.'`& knuddeln willst, fühlst du dich leider viel zu müde dafür...');
			}

			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);

			break;

	}


}

?>