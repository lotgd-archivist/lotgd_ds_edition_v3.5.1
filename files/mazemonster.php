<?php
/*
mazemonster.php part of the Abandonded Castle Mod By Lonnyl @ http://www.pqcomp.com/logd
Author Lonnyl
version 1.01
June 2004
*/

// MOD by tcb, 15.5.05: Monster kosten WK
//MOD by Alucard, 14.02.06: Anpassung an Irrgarten
require_once "common.php";
checkday();
page_header("Labyrinth Monster");

if ($_GET['op']=="fight" or $_GET['op']=="run")
{
	$battle=true;
}

elseif ($_GET['op']=="lost_soul")
{
	$badguy = array("creaturename"=>"`@Verlorene Seele`0"
	,"creaturelevel"=>5
	,"creatureweapon"=>"Geisterkraft"
	,"creatureattack"=>10
	,"creaturedefense"=>15
	,"creaturehealth"=>200
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.5);
	$badguy['creaturehealth']+=$userhealth;
	$badguy['creaturedefense']+=($userdefense*2);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="devoured_soul")
{
	$badguy = array("creaturename"=>"`@Verdorrte Seele`0"
	,"creaturelevel"=>10
	,"creatureweapon"=>"Seelendurst"
	,"creatureattack"=>15
	,"creaturedefense"=>15
	,"creaturehealth"=>300
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.5);
	$badguy['creaturehealth']+=$userhealth;
	$badguy['creaturedefense']+=($userdefense*2);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="ghost1")
{
	$badguy = array("creaturename"=>"`@Durchsichtiges Spektre`0"
	,"creaturelevel"=>0
	,"creatureweapon"=>"Geisterkraft"
	,"creatureattack"=>1
	,"creaturedefense"=>2
	,"creaturehealth"=>1000
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.5);
	$badguy['creaturehealth']+=$userhealth;
	$badguy['creaturedefense']+=($userdefense*2);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="ghost2")
{
	$badguy = array("creaturename"=>"`@Wütendes Spektre`0"
	,"creaturelevel"=>0
	,"creatureweapon"=>"Geisterkraft"
	,"creatureattack"=>1
	,"creaturedefense"=>2
	,"creaturehealth"=>400
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.5);
	$badguy['creaturehealth']+=$userhealth;
	$badguy['creaturedefense']+=($userdefense*1.5);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="ghost3")
{
	$badguy = array("creaturename"=>"`@erbostes Spektre`0"
	,"creaturelevel"=>10
	,"creatureweapon"=>"Geisterkraft"
	,"creatureattack"=>5
	,"creaturedefense"=>20
	,"creaturehealth"=>400
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.5);
	$badguy['creaturehealth']+=$userhealth;
	$badguy['creaturedefense']+=($userdefense*1.5);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="bat")
{
	$badguy = array("creaturename"=>"`@Fledermaus`0"
	,"creaturelevel"=>0
	,"creatureweapon"=>"Scharfe Zähne"
	,"creatureattack"=>1
	,"creaturedefense"=>2
	,"creaturehealth"=>1
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.5);
	$badguy['creaturehealth']+=($userhealth*0.5);
	$badguy['creaturedefense']+=($userdefense*0.5);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="bigbat")
{
	$badguy = array("creaturename"=>"`@Riesige Fledermaus`0"
	,"creaturelevel"=>0
	,"creatureweapon"=>"Scharfe Zähne"
	,"creatureattack"=>3
	,"creaturedefense"=>5
	,"creaturehealth"=>40
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.5);
	$badguy['creaturehealth']+=($userhealth*0.5);
	$badguy['creaturedefense']+=($userdefense*0.5);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="rat")
{
	$badguy = array("creaturename"=>"`@Riesige Ratte`0"
	,"creaturelevel"=>0
	,"creatureweapon"=>"Scharfe Zähne"
	,"creatureattack"=>1
	,"creaturedefense"=>2
	,"creaturehealth"=>1
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.75);
	$badguy['creaturehealth']+=($userhealth*0.75);
	$badguy['creaturedefense']+=($userdefense*0.75);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="minotaur")
{
	$badguy = array("creaturename"=>"`@Minotaurus`0"
	,"creaturelevel"=>0
	,"creatureweapon"=>"Hörner"
	,"creatureattack"=>1
	,"creaturedefense"=>40
	,"creaturehealth"=>1000
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack-4);
	$badguy['creaturehealth']+=$userhealth;
	$badguy['creaturedefense']+=$userdefense;
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

//welche für irrgarten
elseif ($_GET['op']=="bigspider")
{
	$badguy = array("creaturename"=>"`^Riesige Spinne`0"
	,"creaturelevel"=>0
	,"creatureweapon"=>"Spinnengift und klebrige Fäden"
	,"creatureattack"=>3
	,"creaturedefense"=>5
	,"creaturehealth"=>40
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.5);
	$badguy['creaturehealth']+=($userhealth*0.5);
	$badguy['creaturedefense']+=($userdefense*0.5);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="zyklop")
{
	$badguy = array("creaturename"=>"`^Zyklop`0"
	,"creaturelevel"=>10
	,"creatureweapon"=>"Stachelkeule"
	,"creatureattack"=>15
	,"creaturedefense"=>15
	,"creaturehealth"=>300
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/2);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.5);
	$badguy['creaturehealth']+=$userhealth;
	$badguy['creaturedefense']+=($userdefense*2);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="gardner")
{
	$badguy = array("creaturename"=>"`^irrer Gärtner`0"
	,"creaturelevel"=>10
	,"creatureweapon"=>"blutige Heckenschere"
	,"creatureattack"=>5
	,"creaturedefense"=>20
	,"creaturehealth"=>400
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']/1.5);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack*0.7);
	$badguy['creaturehealth']+=$userhealth;
	$badguy['creaturedefense']+=($userdefense*1.7);
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

elseif ($_GET['op']=="snakegod")
{
	$badguy = array("creaturename"=>"`@Wadjet Schlangengöttin`0"
	,"creaturelevel"=>0
	,"creatureweapon"=>"Giftzähne"
	,"creatureattack"=>1
	,"creaturedefense"=>40
	,"creaturehealth"=>1000
	,"maze"=>1
	,"diddamage"=>0);

	$userattack=$session['user']['attack']+e_rand(2,5);
	$userhealth=round($session['user']['hitpoints']/1.25);
	$userdefense=$session['user']['defense']+e_rand(1,4);
	$badguy['creaturelevel']=$session['user']['level'];
	$badguy['creatureattack']+=($userattack-4);
	$badguy['creaturehealth']+=$userhealth;
	$badguy['creaturedefense']+=$userdefense;
	$session['user']['badguy']=createstring($badguy);
	$battle=true;
}

if ($battle)
{
	$maze_type = empty($session['user']['maze_visited']) ? 0 : 1; //0=schloss, 1=irrgarten
	include_once ("battle.php");

	if ($victory)
	{
		$session['user']['turns'] = max($session['user']['turns']-1,0);

		output("`0`b`4Du hast `^".$badguy['creaturename']."`4 besiegt.`0`b`n");
		$badguy=array();
		$session['user']['badguy']="";
		$session['user']['specialinc']="";
		$gold=e_rand(100,500);
		$experience=$session['user']['level']*e_rand(37,80);
		output("`#Du erhältst `6".$gold." `#Gold!`n");
		$session['user']['gold']+=$gold;
		output("`#Du erhältst `6".$experience." `#Erfahrung!`n");
		$session['user']['experience']+=$experience;
		
		if( !$maze_type )
		{
			addnav("Weiter","abandoncastle.php?loc=".$session['user']['pqtemp']);
		}
		else
		{
			addnav("Weiter","gardenmaze.php?pos=".$session['user']['pqtemp']);
		}

		if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!="")
		{
			// Knappe nicht vergessen!
			if (is_array($session['bufflist']['decbuff']))
			{
				$decbuff=$session['bufflist']['decbuff'];
			}
			// Edelsteinelsterbuff
			if ($session['bufflist']['gemelster'])
			{
				$arr_gemelster_buff = $session['bufflist']['gemelster'];
			}
			$_GET['skill']="";
			$session['bufflist']=array();
			if (is_array($decbuff))
			{
				$session['bufflist']['decbuff']=$decbuff;
			}
			if (is_array($arr_gemelster_buff))
			{
				$session['bufflist']['gemelster'] = $arr_gemelster_buff;
			}
		}

	}
	elseif ($defeat)
	{
		output("`&Als Du auf dem Boden aufschlägst, rennt `^".$badguy['creaturename']."");

		$arr_results = killplayer(0, 0, true, 'shades.php', 'Weiter');
		if ($arr_results['disciple']) {
			output(" `&mit `^" . $arr_results['disciple']['name'] . " `&");
			debuglog("Verlor einen Knappen bei einer Niederlage im Schloss/Irrgarten.");
		}
		output("`& weg.");

		addnews("`%".$session['user']['name']."`5 wurde von ".$badguy['creaturename']."`% im ".($maze_type? "Irrgarten des verlassenen Schlosses" : "Verlassenen Schloss")." erschlagen.");
		if(!$maze_type)
		{
			savesetting('CASTLEMOVES',getsetting('CASTLEMOVES',0)+$session['user']['mazeturn']);
			user_set_aei(array('castlemaze_visited' => implode(',',$session['mazevisited'])));
			unset($session['mazevisited']);
		}
		$badguy=array();
		
	}
	else
	{
		fightnav(true,false);
		if ($badguy['creaturehealth'] > 0)
		{
			$hp=$badguy['creaturehealth'];
		}
	}
}
else
{
	if( !$maze_type )
	{
		redirect("abandoncastle.php?loc=".$session['user']['pqtemp']);
	}
	else
	{
		redirect("gardenmaze.php?pos=".$session['user']['pqtemp']);
	}
}
//I cannot make you keep this line here but would appreciate it left in.
//rawoutput("<div style=\"text-align: left;\"><a href=\"http://www.pqcomp.com\" target=\"_blank\">Abandonded Castle by Lonny @ http://www.pqcomp.com</a><br>");
page_footer();
?>
