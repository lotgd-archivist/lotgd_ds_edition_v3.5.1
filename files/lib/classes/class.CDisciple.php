<?php
/**
* class.CDisciples.php: Knappen und Co
* @author maris <Maraxxus@gmx.de>, dragonslayer
* @version DS-E V/3
*/
class CDisciple
{


	///
	/// Wenn ein neues Extra hinzugefügt wird bitte auch daran
	/// denken die getExtraList,getStatus Funktionen zu updaten
	///

	/**
	 * Knappe wurde in Sünden eingeweiht
	 */
	const DISCPIPLE_EXTRA_SIN =			0x1;
	/**
	 * Knappe wurde in Tugenden geweiht
	 */
	const DISCPIPLE_EXTRA_VIRTUE =		0x2;

	/* Ich weiß nicht ob man besessen, pelzig und autistisch evtl auch als extra umsetzen sollte
	const DISCIPLE_EXTRA_UNDEAD = 		0x4;
	const DISCIPLE_EXTRA_HAUNTED = 		0x8;
	const DISCIPLE_EXTRA_AUTIST = 		0x10;
	*/

	public static function getExtraArray()
	{
		$arrReturn=array(

		);

		return $arrReturn;
	}

	public static function setExtra($intId,$intExtra)
	{
		db_query('UPDATE disciples SET extra=extra|'.$intExtra.' WHERE master='.$intId);
	}

	public static function removeExtra($intId,$intExtra)
	{
		db_query('UPDATE disciples SET extra=extra&(~0^'.$intExtra.') WHERE master='.$intId);
	}

	/**
	* Lädt einen Knappen aus der DB und legt dessen Buff an
	*
	* @param int AcctID des Eigentümers; optional, Standard 0 = Sessionuser
	* @return array Daten des Knappen als assoz. Array; Knappenbuff liegt in ['buff'], null wenn Knappe frei hat oder User keinen hat
	* @author maris / modified by talion and fussel
	*/
	public static function get ($int_acctid=0)
	{
		global $Char;

		if((int)$int_acctid == 0) {
			$int_acctid = $Char->acctid;
		}
		$gamedate=getsetting('gamedate','0005-01-01').'-'.getsetting('actdaypart',1);

		$row = db_get("SELECT * FROM disciples WHERE master=".$int_acctid." LIMIT 1");

		//Falls der user gar keinen Knappen hat wird null zurückgegeben
		if($row === null)
		{
			return null;
		}

		$row['name']=stripslashes($row['name']);
		$state=$row['state'];

		if($row['free_day']<$gamedate)
		{
			switch ($state) {
				case 1 : //jung
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht dir zur Seite.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"regen"=>2,
					"rounds"=>75,
					"defmod"=>1.05,
					"atkmod"=>1.05,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe versorgt deine Wunden.",
					"wearoff"=>"Dein Knappe sackt erschöpft in sich zusammen.",
					"activate"=>"roundstart");
				break;

				case 2 : //dürr
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht dir zur Seite.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"badguyatkmod"=>0.85,
					"rounds"=>150,
					"defmod"=>1.05,
					"atkmod"=>1.05,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe drängt den Gegner mit dem Schild ab.",
					"wearoff"=>"Dein Knappe sackt erschöpft in sich zusammen.",
					"activate"=>"roundstart");
				break;

				case 3 : //langwüchsig
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht dir zur Seite.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>170,
					"defmod"=>1.10,
					"atkmod"=>1.05,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe hält dir den Rücken frei.",
					"wearoff"=>"Dein Knappe sackt erschöpft in sich zusammen.",
					"activate"=>"roundstart");
				break;

				case 4 : //kräftig
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht dir zur Seite.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>300,
					"defmod"=>1.05,
					"atkmod"=>1.05,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe kämpft tapfer.",
					"wearoff"=>"Dein Knappe sackt erschöpft in sich zusammen.",
					"activate"=>"roundstart");
				break;

				case 5 : //hübsch
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht dir zur Seite.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>120,
					"defmod"=>1.05,
					"atkmod"=>1.05,
					"badguydmgmod"=>0.9,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe lenkt den Gegner ab.",
					"wearoff"=>"Dein Knappe sackt erschöpft in sich zusammen.",
					"activate"=>"roundstart");
				break;

				case 6 : //stolz
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht dir zur Seite.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>170,
					"defmod"=>1.05,
					"atkmod"=>1.10,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe stürzt sich tapfer in die Schlacht.",
					"wearoff"=>"Dein Knappe sackt erschöpft in sich zusammen.",
					"activate"=>"roundstart");
				break;

				case 7 : //vorlaut
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht dir zur Seite.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>100,
					"defmod"=>1.05,
					"atkmod"=>1.05,
					"badguydefmod"=>0.7,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe verspottet deinen Gegner und macht ihn unaufmerksam.",
					"wearoff"=>"Dein Knappe sackt erschöpft in sich zusammen.",
					"activate"=>"roundstart");
				break;

				case 8 : //verträumt
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht dir zur Seite.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>120,
					"defmod"=>1.05,
					"atkmod"=>1.05,
					"badguyatkmod"=>0.7,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe klammert sich an deinen Gegner und behindert ihn bei der Attacke.",
					"wearoff"=>"Dein Knappe sackt erschöpft in sich zusammen.",
					"activate"=>"roundstart");
				break;

				case 9 : //neunmalklug
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht dir wortreich zur Seite.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>120,
					"defmod"=>1.05,
					"atkmod"=>1.05,
					"badguyatkmod"=>0.9,
					"badguydefmod"=>0.9,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe überfordert deinen Gegner mit klugen Sprüchen.",
					"wearoff"=>"Dein Knappe sackt erschöpft in sich zusammen.",
					"activate"=>"roundstart");
				break;

				case 10 : //dicklich
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe bewirft deinen Gegner mit Steinen.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>120,
					"defmod"=>1.05,
					"atkmod"=>1.05,
					"wearoff"=>"Dein Knappe sackt erschöpft in sich zusammen.",
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level),
					"maxbadguydamage"=>round($Char->level*2),
					"effectmsg"=>"`&Dein Knappe trifft deinen Gegner für `4{damage}`& Schadenspunkte.",
					"activate"=>"roundstart");
				break;

				case 11 : //nichtsnutzig
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe ist zwar da, zeigt jedoch nicht viel Interesse an dir.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>300,
					"defmod"=>1.02,
					"atkmod"=>1.02,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe steht untätig rum und bohrt in der Nase.",
					"wearoff"=>"Dein Knappe setzt sich auf den Boden.",
					"activate"=>"roundstart");
				break;

				case 12 : //treu
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht dir treu zur Seite.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>400,
					"defmod"=>1.04,
					"atkmod"=>1.04,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe unterstützt dich im Kampf.",
					"wearoff"=>"Dein Knappe ist nun erschöpft.",
					"activate"=>"roundstart");
				break;

				case 13 : //hinterhältig
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe versteckt sich im Gebüsch und attackiert deinen Gegner mit seinem Blasrohr.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>80,
					"defmod"=>1.01,
					"atkmod"=>1.01,
					"wearoff"=>"Dein Knappe ist nun müde.",
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level),
					"maxbadguydamage"=>round($Char->level*4),
					"effectmsg"=>"`&Dein Knappe trifft mit `4{damage}`& Schadenspunkten!",
					"activate"=>"roundstart");
				break;

				case 14 : //listig
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe hält sich bedeckt.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>300,
					"defmod"=>1.02,
					"atkmod"=>1.02,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe ist dir kaum eine Hilfe.",
					"wearoff"=>"Dein Knappe ist nun müde und lässt dich ganz allein.",
					"activate"=>"roundstart");
				break;

				case 15 : //flott
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe steht hinter dir.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>400,
					"defmod"=>1.00,
					"atkmod"=>1.00,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe feuert dich an.",
					"wearoff"=>"Dein Knappe ist erschöpft.",
					"activate"=>"roundstart");
				break;

				case 19 : //pelzig
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe heult laut los.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>250,
					"defmod"=>1.00,
					"atkmod"=>1.15,
					"minioncount"=>1,
					"minbadguydamage"=>round($Char->level*0),
					"maxbadguydamage"=>round($Char->level*0),
					"roundmsg"=>"Dein Knappe stürzt sich zähnefletschend auf deinen Gegner.",
					"wearoff"=>"Dein Knappe zieht sich knurrend zurück.",
					"activate"=>"roundstart");
				break;

				case 20 : //untot
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe folgt dir willenlos.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>500,
					"minioncount"=>1,
					"defmod"=>1.01,
					"atkmod"=>1.01,
					"minbadguydamage"=>round($Char->level*0.5),
					"maxbadguydamage"=>round($Char->level),
					"effectmsg"=>"`&Dein Knappe kaut deinen Gegner für `4{damage} `&Schadenspunkte an.",
					"wearoff"=>"Dein Knappe kann nun nicht mehr und schont seine morschen Knochen.",
					"activate"=>"roundstart",
					"survive_death" => 1);
				break;

				case 21 : //besessen
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe folgt dir mit rotglühenden Augen.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>400,
					"minioncount"=>1,
					"defmod"=>1.01,
					"atkmod"=>1.02,
					"minbadguydamage"=>round($Char->level*0.5),
					"maxbadguydamage"=>round($Char->level),
					"effectmsg"=>"`&Dein Knappe spuckt Käfer auf deinen Gegner und macht `4{damage} `&Schadenspunkte.",
					"wearoff"=>"Der Geist deines Knappen kann sich aus dem dämonischen Griff befreien.",
					"activate"=>"roundstart",
					"survive_death" => 1);
				break;

				case 22 : //autistisch
				$decbuff = array(
					"startmsg"=>"`n`^Dein Knappe zählt die Blätter an den Bäumen und kann dich deswegen nicht im Kampf unterstützen.`&`n`n",
					"name"=>"`%Knappe ".$row['name']."`&",
					"rounds"=>42,
					"minioncount"=>1,
					"defmod"=>1,
					"atkmod"=>1,
					"roundmsg"=>"Dein Knappe bemerkt deinen Gegner gar nicht.",
					"wearoff"=>"Dein Knappe geht jetzt seine Buntstifte suchen.",
					"activate"=>"roundstart");
				break;

				default :
				$decbuff = array();
				break;
			}
			
			//fix by bathi für die Trophäen
			if(isset($decbuff['name']))$decbuff['name'] .= " ->Lvl ".$row['level']."`0";
			if(isset($decbuff['atkmod']))$decbuff['atkmod'] += ($row['level']*0.005);
			if(isset($decbuff['defmod']))$decbuff['defmod'] += ($row['level']*0.005);
			if(isset($decbuff['rounds']))$decbuff['rounds'] += ($row['level']*2);

			$decbuff['state'] = $state;
			$decbuff['realname'] = $row['name'];
			$decbuff['extra'] = $row['extra'];
		}
		else
		{
			return null; // statt leerem Array, da Knappe einen freien Tag hat (fussel)
		}
		$row['buff'] = $decbuff;

		self::calculateExtras($row);

		return($row);
	}

	private static function calculateExtras(&$arrDisciple)
	{
		global $Char;

		//Knappe hat Weihe der Sünden erhalten
		if(getBitBool($arrDisciple['extra'],self::DISCPIPLE_EXTRA_SIN))
		{
			//fix by bathi für die Trophäen
			if(isset($arrDisciple['buff']['name']))
			{
				$arrDisciple['buff']['name'] = '`)†`0'.$arrDisciple['buff']['name'];
				$arrDisciple['buff']['rounds'] += 100;
				$arrDisciple['buff']['atkmod'] += 0.05;
	
				if($arrDisciple['state']==13)
				{
					$arrDisciple['buff']['maxbadguydamage'] = $Char->level*5;
				}
	
				elseif($arrDisciple['state']==10)
				{
					$arrDisciple['buff']['maxbadguydamage'] = round($Char->level*3.5);
				}
				//fix by bathi für die Trophäen
				elseif($arrDisciple['state']==20 || $arrDisciple['state']==21)
				{
					$arrDisciple['buff']['maxbadguydamage'] = round($Char->level*2.5);
				}
	
				else
				{
					$arrDisciple['buff']['maxbadguydamage'] = round($Char->level*2);
				}
			}
		}

		//Knappe hat Weihe der Tugenden erhalten
		if(getBitBool($arrDisciple['extra'],self::DISCPIPLE_EXTRA_VIRTUE))
		{
			//fix by bathi für die Trophäen
			if(isset($arrDisciple['buff']['name']))
			{
				$arrDisciple['buff']['name'] = '`&°`0'.$arrDisciple['buff']['name'];
				$arrDisciple['buff']['rounds'] += 100;
				$arrDisciple['buff']['defmod'] += 0.05;
	
				if($arrDisciple['state']==13)
				{
					$arrDisciple['buff']['maxbadguydamage'] = round($Char->level*5);
				}
	
				elseif($arrDisciple['state']==10)
				{
					$arrDisciple['buff']['maxbadguydamage'] = round($Char->level*3.5);
				}
				//fix by bathi für die Trophäen
				elseif($arrDisciple['state']==20 || $arrDisciple['state']==21)
				{
					$arrDisciple['buff']['maxbadguydamage'] = round($Char->level*2.5);
				}
	
				else
				{
					$arrDisciple['buff']['maxbadguydamage'] = round($Char->level*2);
				}
			}
		}
	}

	/**
	* Liefert das zum Status des Knappen passende Adjektiv
	*
	* @param int Status
	* @param string Endung
	* @return string Adjektiv
	* @author maris
	*/
	public static function getStatus($state, $end='en') {
		switch ($state)
		{
			case -1 : $adj="tot$end"; break;
			case 0 : $adj="verschollen$end"; break;
			case 1 : $adj="jung$end"; break;
			case 2 : $adj="dürr$end"; break;
			case 3 : $adj="langwüchsig$end"; break;
			case 4 : $adj="kräftig$end"; break;
			case 5 : $adj="hübsch$end"; break;
			case 6 : $adj="stolz$end"; break;
			case 7 : $adj="vorlaut$end"; break;
			case 8 : $adj="verträumt$end"; break;
			case 9 : $adj="neunmalklug$end"; break;
			case 10 : $adj="dicklich$end"; break;
			case 11 : $adj="nichtsnutzig$end"; break;
			case 12 : $adj="treu$end"; break;
			case 13 : $adj="hinterhältig$end"; break;
			case 14 : $adj="listig$end"; break;
			case 15 : $adj="flott$end"; break;
			case 19 : $adj="pelzig$end"; break;
			case 20 : $adj="untot$end"; break;
			case 21 : $adj="besessen$end"; break;
			case 22 : $adj="untätig$end"; break;
			case 23 : $adj="tugendhaft$end"; break;
			case 24 : $adj="sündhaft$end"; break;
			//kein default
		}
		return ($adj);
	}

	/**
	* Steigert den Knappen des Users um eine Stufe
	* @param array Knappendaten (optional)
	* @return string Ergebnistext
	* @author maris
	*/
	public  static function levelup($arr_disc=0) {
		global $Char,$session;

		//Knappendaten gegeben? sonst abrufen
		if(!is_array($arr_disc) || $arr_disc['name']=='' || $arr_disc['state']===false || $arr_disc['level']===false)
		{
			$arr_disc=self::get();
		}

		//Knappe abwesend?
		$gamedate=getsetting('gamedate','0005-01-01').'-'.getsetting('actdaypart',1);
		if($arr_disc['free_day']==$gamedate)
		{
			return('Dein Knappe hätte jetzt ein Level aufsteigen können, aber du hast ihn ja fortgeschickt.');
		}

		//maximal erreichbares Level für diverse Knappentypen
		$arr_maxlevel=array(20=>30, 21=>30, 22=>0);
		if(array_key_exists($arr_disc['state'],$arr_maxlevel) && $arr_disc['level']>=$arr_maxlevel[$arr_disc['state']])
		//if (($arr_disc['level']>=30 && ($arr_disc['state']==20 || $arr_disc['state']==21)) || $arr_disc['state']==22)
		{
			$str_out='`^Dein `4'.get_disciple_stat($arr_disc['state'],'').'er Knappe`^ kann keinen weiteren Level aufsteigen.`0`n';
			if($arr_disc['level']>$arr_maxlevel[$arr_disc['state']])
			{
				$sql = 'UPDATE disciples SET level='.$arr_maxlevel[$arr_disc['state']].' WHERE master='.$Char->acctid;
				db_query($sql);
			}
			if($arr_disc['state']==20)
			{
				$str_output .= ''.$arr_disc['name'].'`^ ist stärker als jeder andere untote Knappe im Land!`n';
				$sql = 'UPDATE disciples SET best_one=0 WHERE best_one=2';
				db_query($sql);
				$sql = 'UPDATE disciples SET best_one=2 WHERE master='.$Char->acctid;
				db_query($sql);
			}
		}
		elseif ($arr_disc['level']>=45)
		{
			$str_out='`^Dein Knappe kann keinen weiteren Level aufsteigen.`0`n'.$arr_disc['name'].'`^ ist stärker als jeder andere Knappe im Land!`n';
			$sql = 'UPDATE disciples SET best_one=0 WHERE best_one=1';
			db_query($sql);
			$sql = 'UPDATE disciples SET best_one=1 WHERE master='.$Char->acctid;
			db_query($sql);
		}
		else
		{
			$arr_disc['level']++;
			$sql = 'UPDATE disciples SET level='.$arr_disc['level'].' WHERE master='.$Char->acctid;
			db_query($sql);
			$str_out='`^Dein Knappe '.$arr_disc['name'].'`^ steigt auf Level '.$arr_disc['level'].'`^ auf!`n';

			if(!$arr_disc['buff'])
			{
				$arr_disc = get_disciple();
			}
			$session['bufflist']['decbuff'] = $arr_disc['buff'];

			// check best one
			$sql = 'SELECT id,level FROM disciples WHERE best_one=1';
			$result = db_query($sql);
			$rowb = db_fetch_assoc($result);
			if ($arr_disc['level']>=$rowb['level'])
			{
				$str_out.='`n`^'.$arr_disc['name'].'`^ ist stärker als jeder andere Knappe im Land!`n';
				$sql = 'UPDATE disciples SET best_one=0 WHERE best_one=1';
				db_query($sql);
				$sql = 'UPDATE disciples SET best_one=1 WHERE master='.$Char->acctid;
				db_query($sql);
			}

			if($arr_disc['state']==20)
			{
				// Prüfe besten untoten Knappen
				$sql = 'SELECT id,level FROM disciples WHERE best_one=2';
				$result = db_query($sql);
				$rowb = db_fetch_assoc($result);
				if ($arr_disc['level']>=$rowb['level'])
				{
					$str_out.='`n`^'.$arr_disc['name'].'`^ ist stärker als jeder andere untote Knappe im Land!`n';
					$sql = 'UPDATE disciples SET best_one=0 WHERE best_one=2';
					db_query($sql);
					$sql = 'UPDATE disciples SET best_one=2 WHERE master='.$Char->acctid;
					db_query($sql);
				}
			}
		}
		return($str_out);
	}

	/**
	* Setzt Knappen auf inaktiven Zustand, erledigt auch Feststellung des besten Knappen im Land
	*
	* @param bool Endgültig fort (optional, Standard false)
	* @author maris / modified by talion
	*/
	public static function remove($death=false) {
		global $session,$Char;

		$gamedate=getsetting('gamedate','0005-01-01').'-'.getsetting('actdaypart',1);
		$sql = 'UPDATE disciples
			SET oldstate=IF(state>0,state,oldstate),state='.($death?'-1,level=0':'0').',best_one=0
			WHERE master = '.$Char->acctid.'
				AND state !=22
				AND free_day!="'.$gamedate.'"';
		db_query($sql);

		if(db_affected_rows())
		{
			$sql = "UPDATE account_extra_info
				SET disciples_spoiled=disciples_spoiled+1
				WHERE acctid = ".$Char->acctid;
			db_query($sql);

			$sql = "UPDATE disciples
				SET best_one=1
				WHERE level>0
					AND state>0
				ORDER BY level DESC, best_one=1 DESC
				LIMIT 1";
			db_query($sql);

			// Ermittle den besten untoten Knappen im Land
			$sql = "UPDATE disciples
				SET best_one=2
				WHERE level>0
					AND state=20
				ORDER BY level DESC, best_one=2 DESC
				LIMIT 1";
			db_query($sql);
		}

		unset($session['bufflist']['decbuff']);
	}
}

?>