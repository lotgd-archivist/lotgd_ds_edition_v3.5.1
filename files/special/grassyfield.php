<?php
//Original idea by Sean McKillion
//modifications by Eric Stevens
//further modifications by JT Traub

/** @noinspection PhpUndefinedVariableInspection */
debuglog('grassyfield op="'.$_GET['op'].'": hp: '.$session['user']['hitpoints'].'/'.$session['user']['maxhitpoints'].', mount: '.(int)$session['bufflist']['mount']['rounds'].', specialmisc: '.$session['user']['specialmisc']);
if ($_GET['op']=="return")
{
	$session['user']['specialmisc']="";
	$session['user']['specialinc']="";
	output("`#Nach dieser kleinen Pause fühlst du dich gut gestärkt für deinen weiteren Weg.");
}
else
{
	checkday();

	$session['user']['specialinc']="grassyfield.php";
	output("`c`#Du stolperst auf eine grasbewachsene Lichtung.`0`c");
	addnav("Zurück in den Wald","forest.php?op=return");
	if ((string)$session['user']['specialmisc'] != "grassyfield")
	{
		if ($session['user']['hashorse']>0)
		{
			getmount($session['user']['hashorse'],true);
            /** @noinspection PhpUndefinedVariableInspection */
            $buff = utf8_unserialize($playermount['mountbuff']);
			if ($session['bufflist']['mount']['rounds'] > $buff['rounds']*0.5)
			{
				if ($playermount['partrecharge'])
				{
					output("`n".$playermount['partrecharge']);
				}
				else
				{
					output("`n`&Du erlaubst deinem ".$playermount['mountname']."`&, sich hier zu stärken und herumzutollen.");
				}
			}
			else
			{
				if ($playermount['recharge'])
				{
					output("`n".$playermount['recharge']);
				}
				else
				{
					output("`n`&Du erlaubst deinem ".$playermount['mountname']."`&, zu jagen und sich auszuruhen.");
				}
			}
		
			$session['bufflist']['mount']=$buff;
			
			$sql = 'SELECT mountextrarounds,hasxmount,xmountname FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
			$result = db_query($sql);
			$rowm = db_fetch_assoc($result);
			$session['bufflist']['mount']['rounds']+=$rowm['mountextrarounds'];
			if ($rowm['hasxmount']==1)
			{
				$session['bufflist']['mount']['name']=$rowm['xmountname']." `&(".$session['bufflist']['mount']['name']."`&)`0";
			}

			if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
			{
				output("`n`^Dein Nickerchen hat dich vollständig geheilt!");
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
			}
			$session['user']['turns']--;
			output("`n`n`^Du verlierst einen Waldkampf für heute.");
		}
		else
		{
			output("`n`n`&Du beschließt, dir einen Moment Zeit zu nehmen und deinen armen Füßen eine kurze Pause von deinen riskanten Abenteuern zu gönnen. Du genießt die wunderschöne Umgebung.
			`n`n`^Du regenerierst vollständig!");
			if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
			{
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
			}
		}
		$session['user']['specialmisc'] = "grassyfield";
	}

	else
	{
		output("`n`n`&Du ruhst dich eine Weile hier aus und genießt die Sonne und den Schatten.");
	}

	output("`n`n`@Rede mit den anderen, die hier herumlungern.`n");
	viewcommentary("grassyfield","Hinzufügen",10,"sagt");

}
?>
