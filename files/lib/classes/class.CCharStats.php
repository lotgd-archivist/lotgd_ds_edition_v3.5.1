<?php
/**
* Advanced CharStats
* @author  Báthory
* @version DS-E V/3.x
*/
class CCharStats
{
	/**
	 * Enthält die Charstat Struktur
	 * @var array
	 */
	public $arr_default = array(
			0 => array('Vital-Info',
				array
				(
					array('title'=>'Name','type'=>3,'su'=>false,'free'=>false,'value'=>'name')
					,array('title'=>'Lebenspunkte','type'=>1,'su'=>false,'free'=>false,'value'=>'hitpoints')
					,array('title'=>'Seelenpunkte','type'=>2,'su'=>false,'free'=>false,'value'=>'soulpoints')
					,array('title'=>'Runden','type'=>1,'su'=>false,'free'=>false,'value'=>'turns')
					,array('title'=>'Gefallen','type'=>2,'su'=>false,'free'=>false,'value'=>'deathpower')
					,array('title'=>'Schlossrunden','type'=>1,'su'=>false,'free'=>false,'mindk'=>10,'value'=>'castleturns')
					,array('title'=>'Foltern','type'=>2,'su'=>false,'free'=>false,'value'=>'gravefights')
					,array('title'=>'Stimmung','type'=>3,'su'=>false,'free'=>false,'value'=>'spirits')
					,array('title'=>'Level','type'=>3,'su'=>false,'free'=>false,'value'=>'level')
					,array('title'=>'Erfahrung','type'=>3,'su'=>false,'free'=>false,'value'=>'exp')
					,array('title'=>'Angriff','type'=>1,'su'=>false,'free'=>false,'value'=>'atk')
					,array('title'=>'Verteidigung','type'=>1,'su'=>false,'free'=>false,'value'=>'def')
					,array('title'=>'Ansehen','type'=>1,'su'=>false,'free'=>false,'value'=>'repu')
					,array('title'=>'Psyche','type'=>2,'su'=>false,'free'=>false,'value'=>'psy')
					,array('title'=>'Geist','type'=>2,'su'=>false,'free'=>false,'value'=>'geist')
				)
			)
		, 1 => array('Besitz',
				array
				(
					array('title'=>'Gold','type'=>3,'su'=>false,'free'=>false,'value'=>'gold')
					,array('title'=>'Edelsteine','type'=>3,'su'=>false,'free'=>false,'value'=>'gems')
					,array('title'=>'Waffe','type'=>3,'su'=>false,'free'=>false,'value'=>'weapon')
					,array('title'=>'Rüstung','type'=>3,'su'=>false,'free'=>false,'value'=>'armour')
					,array('title'=>'Ausrüstung','type'=>3,'su'=>false,'free'=>false,'value'=>'equipment')
					,array('title'=>'Haustier','type'=>3,'su'=>false,'free'=>false,'value'=>'animal')
					,array('title'=>'Debug','type'=>3,'su'=>true,'free'=>false,'value'=>'debug')
					,array('title'=>'Quick Nav','type'=>3,'su'=>true,'free'=>true,'value'=>'quicknav')
				)
			)
		, 2 => array('Profil und Info',
				array
				(
					array('title'=>'Weiteres','type'=>3,'su'=>false,'free'=>false,'value'=>'profil')
					,array('title'=>'Nächster Tag','type'=>3,'su'=>false,'free'=>false,'value'=>'nextd')
				)
			)
		, 3 => array('Aktionen',
				array
				(
					array('title'=>'Aktionen','type'=>3,'su'=>false,'free'=>true,'value'=>'buffs')
				)
			)
		, 4  => array('Wer ist hier? <span id="ool_status_div"><img id="ool_status" style="cursor: pointer;" src="./images/icons/visible.gif"></span>',
				array
				(
					array('title'=>'Wer ist hier','type'=>3,'su'=>false,'free'=>true,'value'=>'whoishere')
				)
			)
		);
	/**
	 * Enthält die Daten die dargestellt werden sollen
	 * @var array
	 */
	public $arr_data = array();
	
	
	/**
	 * Bei Änderungen am Array $arr_default version um 1 erhöhen!
	 */
	static $int_version = 2;
	
	/**
	 * Enthält die Usereinstellungen
	 *
	 * @var unknown_type
	 */
	private $arr_charstat = array();
	private $str_charstat = "";

	public function __construct()
	{
		//Default constructor
	}
	
	/**
	 * Initialisiert das Objekt mit den notwendigen Information zu Struktur und Daten
	 *
	 * @param array $form enthält die Auflistung der Charstats
	 * @param Array $data enthält die Daten die dann in den Charstats dargestellt werden sollen
	 */
	public function initialize_data($form,$data)
	{
		$this->arr_data = $data;
		$this->arr_default = $form;
	}

	////////////////////////////////////////////////////////////////////
	// Ab hier muss bei Änderungen nichts mehr angepasst werden
	////////////////////////////////////////////////////////////////////

	/**
	 * Erzeugt den Charstats string der ausgegeben wird
	 *
	 * @return string
	 */
	public function get_char_stats()
	{
		global $Char;

		$this->initialize();
		$int_count = count($this->arr_charstat);
		for($k = 0 ; $k < $int_count ; $k++)
		{
			$this->str_charstat .= $this->build($this->arr_charstat[$k][0],$this->arr_charstat[$k][1]);
		}

		return $this->str_charstat;
	}

	private function build($key,$val)
	{
		global $Char;
		$charstat = appoencode(templatereplace('statstart'),true);
		if($Char->prefs['charstat_aus']['aus_header_'.$key] != '1')
		{
			$bit = plu_mi_get_val('show_'.$key);
			$headstr = plu_mi('show_'.$key, $bit).'&nbsp;'.$this->arr_default[$key][0];
			$charstat.=appoencode(templatereplace('stathead',array('title'=>$headstr)),true);

			for($j=0;$j<count($val);$j++)
			{
				if($Char->prefs['charstat_aus']['aus_body_'.$key.'_'.$val[$j]] != '1')
				{
					$val2 = $this->arr_default[$key][1][$val[$j]];
					if(!$Char->alive && $val2['type'] == 1){}
					else if($Char->alive && $val2['type'] == 2){}
					else if(!access_control::is_superuser() && $val2['su'] == true){}
					else if(isset($val2['mindk']) && $Char->dragonkills < $val2['mindk']) {}
					else if(is_null_or_empty($val2['value']) || !isset($this->arr_data[ $val2['value'] ]) ||adv_empty($this->arr_data[ $val2['value'] ]) ){}
					else
					{
						$charstat.=$this->do_stat($key,$bit,$val2['title'],$this->arr_data[ $val2['value'] ],$val2['free']);
					}
				}
			}
		}
		$charstat.=appoencode(templatereplace('statend'),true);
		return $charstat;
	}

	private function do_stat($key,$bit,$val,$return,$free=false)
	{
		if($free)
		{
			return appoencode(templatereplace('freedata',array('id'=>plu_mi_unique_id('show_'.$key), 'style'=>($bit?'':'display:none;'), 'title'=>$val,'free_data'=>$return)),true);
		}
		return appoencode(templatereplace('statrow',array('id'=>plu_mi_unique_id('show_'.$key), 'style'=>($bit?'':'display:none;'), 'title'=>$val,'value'=>$return)),true);
	}

	private function initialize()
	{
		global $Char;
		if(is_array($Char->prefs['charstat']))
		{
			$this->arr_charstat = $Char->prefs['charstat'];

			if($Char->prefs['charstat_version'] != self::$int_version)
			{
				if(count($this->arr_default) > count($this->arr_charstat))
				{
					for($c=count($this->arr_charstat);$c<count($this->arr_default);$c++)
					{
						$this->arr_charstat[$c] = array($c,array());
					}
				}
				else if(count($this->arr_default) < count($this->arr_charstat))
				{
					for($d=0;$d<count($this->arr_charstat);$d++)
					{
						if($this->arr_charstat[$d][0] >= count($this->arr_default)) unset($this->arr_charstat[$d]);
					}
				}

				for($i=0;$i<count($this->arr_charstat);$i++)
				{
					if(count($this->arr_default[$i][1]) > count($this->arr_charstat[$i][1]))
					{
						for($a=count($this->arr_charstat[$i][1]);$a<count($this->arr_default[$i][1]);$a++)
						{
							$this->arr_charstat[$i][1][$a] = $a;
						}
					}
					else if(count($this->arr_default[$i][1]) < count($this->arr_charstat[$i][1]))
					{
						for($b=0;$b<count($this->arr_charstat[$i][1]);$b++)
						{
							if($this->arr_charstat[$i][1][$b] >= count($this->arr_default[$i][1])) unset($this->arr_charstat[$i][1][$b]);
						}
					}
				}
				$Char->prefs['charstat'] = $this->arr_charstat;
				$Char->prefs['charstat_version'] = self::$int_version;
			}
		}
		else
		{
			for($i=0;$i<count($this->arr_default);$i++)
			{
				$arr_sub = array();
				for($k=0;$k<count($this->arr_default[$i][1]);$k++){$arr_sub[$k]=$k;}
				$this->arr_charstat[$i] = array($i,$arr_sub);
			}
			$Char->prefs['charstat'] = $this->arr_charstat;
			$Char->prefs['charstat_version'] = self::$int_version;
		}
	}

	public function get_prefs()
	{
		$this->initialize();
		$charForm = $this->get_char_form();
		$return = array(
		"CharStat,title"
		,'charForm' => $charForm.',viewonly');
		return $return;
	}

	public function check_prefs_save()
	{
		global $Char;
		if($_POST)
		{
			$temp = array();
			for($i=0;$i<count($this->arr_default);$i++)
			{
				if($_POST['aus_header_'.$i.''] == 1)
				{
					$Char->prefs['charstat_aus']['aus_header_'.$i] = 1;
				}
				else
				{
					$Char->prefs['charstat_aus']['aus_header_'.$i] = 0;
				}
				unset($_POST['aus_header_'.$i.'']);
				$subtemp = array();

				for($k=0;$k<count($this->arr_default[$i][1]);$k++)
				{
					if($_POST['aus_body_'.$i.'_'.$k.''] == 1)
					{
						$Char->prefs['charstat_aus']['aus_body_'.$i.'_'.$k.''] = 1;
					}
					else
					{
						$Char->prefs['charstat_aus']['aus_body_'.$i.'_'.$k.''] = 0;
					}
					unset($_POST['aus_body_'.$i.'_'.$k.'']);

					if(isset($subtemp[(((int)$_POST['pos_body_'.$i.'_'.$k.''])-1)]))
					{
						$subtemp[] = $k;
					}
					else
					{
						$subtemp[(((int)$_POST['pos_body_'.$i.'_'.$k.''])-1)] = $k;
					}
					unset($_POST['pos_body_'.$i.'_'.$k.'']);
				}

				if(isset($temp[(((int)$_POST['pos_header_'.$i.''])-1)]))
				{
					$temp[] = array($i,$subtemp);
				}
				else
				{
					$temp[(((int)$_POST['pos_header_'.$i.''])-1)] = array($i,$subtemp);
				}
				unset($_POST['pos_header_'.$i.'']);
			}
			$Char->prefs['charstat'] = $temp;
			$this->arr_charstat = $temp;
		}
		if((int)$_GET['charstats'] == 1)
		{
			$Char->prefs['charstat'] = $temp;
			$this->arr_charstat = $temp;
		}
	}

	private function get_char_form()
	{
		global $Char;
		$return = '
      <table align="center">';
		for($i=0;$i<count($this->arr_charstat);$i++)
		{
			$return .= '<tr>
         <td> <select name="pos_header_'.$this->arr_charstat[$i][0].'">';

			for($a=1;$a<=count($this->arr_charstat);$a++)
			{
				$return .= '<option value="'.$a.'" '.( ($a==($i+1)) ? 'selected="selected"' : '' ).'>'.$a.'</option>';
			}

			$return .= '</select> </td>
         <td>'.$this->arr_default[$this->arr_charstat[$i][0]][0].'</td>
         <td></td>
         <td><input name="aus_header_'.$this->arr_charstat[$i][0].'" type="checkbox" value="1" '.( ($Char->prefs['charstat_aus']['aus_header_'.$this->arr_charstat[$i][0]] == 1) ? 'checked="checked"' : '').' > ausblenden</td>
         </tr>';
			for($k=0;$k<count($this->arr_charstat[$i][1]);$k++)
			{
				if(!$this->arr_default[$this->arr_charstat[$i][0]][1][$this->arr_charstat[$i][1][$k]]['su'])
				{
					$return .= '<tr>';
				}
				else
				{
					$return .= '<tr style=" display:none;">';
				}
				$return .= '
            <td></td>
            <td> <select name="pos_body_'.$this->arr_charstat[$i][0].'_'.$this->arr_charstat[$i][1][$k].'">';

				for($b=1;$b<=count($this->arr_charstat[$i][1]);$b++)
				{
					$return .= '<option value="'.$b.'" '.( ($b==($k+1)) ? 'selected="selected"' : '' ).'>'.$b.'</option>';
				}

				$return .= '</select> </td>
            <td>'.$this->arr_default[$this->arr_charstat[$i][0]][1][$this->arr_charstat[$i][1][$k]]['title'].'</td>
            <td><input name="aus_body_'.$this->arr_charstat[$i][0].'_'.$this->arr_charstat[$i][1][$k].'" type="checkbox" value="1" '.( ($Char->prefs['charstat_aus']['aus_body_'.$this->arr_charstat[$i][0].'_'.$this->arr_charstat[$i][1][$k]] == 1) ? 'checked="checked"' : '').' > ausblenden</td>
            </tr>';
			}

		}
		$return .= '</table>
      <br><br>
      <center><a href="prefs.php?charstats=1">Standardwerte für Reihenfolge zurücksetzen</a></center><br><br>';
		return $return;
	}
}
?>