<?php 

/**
 * Wetterklasse verwaltet das Wetter in Atrahor
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 */
class Weather implements Countable, Iterator, ArrayAccess
{
	/**
	 * Neblig
	 */
	const WEATHER_FOGGY = 0x001;
	/**
	 * Heiss und sonnig
	 */
	const WEATHER_HOT = 0x002;
	/**
	 * Wechselhaft und kühl, mit sonnigen Abschnitten
	 */
	const WEATHER_COLD = 0x004;
	/**
	 * Regnerisch
	 */
	const WEATHER_RAINY = 0x008;
	/**
	 * Warm und sonnig
	 */
	const WEATHER_WARM = 0x010;
	/**
	 * Kalt bei klarem Himmel
	 */
	const WEATHER_COLDCLEAR = 0x020;
	/**
	 * Starker Wind mit vereinzelten Regenschauern
	 */
	const WEATHER_WINDY = 0x040;
	/**
	 * Gewittersturm
	 */
	const WEATHER_TSTORM = 0x080;
	/**
	 * Schneeregen
	 */
	const WEATHER_SNOWRAIN = 0x100;
	/**
	 * Schneefälle
	 */
	const WEATHER_SNOW = 0x200;
	/**
	 * Orkanartige Sturmböen
	 */
	const WEATHER_STORM = 0x400;
	/**
	 * Sintflutartige Regenfälle
	 */
	const WEATHER_HEAVY_RAIN = 0x800;
	/**
	 * Frostig mit schmerzhaft beißendem Wind
	 */
	const WEATHER_FROSTY = 0x1000;
	/**
	 * Starke Hagelschauer
	 */
	const WEATHER_HAIL = 0x2000;
	/**
	 * Klarer Himmel mit seltsamem Wetterleuchten
	 */
	const WEATHER_BOREALIS = 0x4000;
	/**
	 * Blutroter Himmel mit leichtem Flammenregen
	 */
	const WEATHER_FLAMES = 0x8000;
	/**
	 * Sonnenfinsternis
	 */
	const WEATHER_ECLIPSE = 0x10000;
	/**
	 * Wolkenloser Himmel und Sonnenschein
	 */
	const WEATHER_CLOUDLESS = 0x20000;
	/**
	 * Stark bewölkt
	 */
	const WEATHER_CLOUDY = 0x40000;
	/**
	 * Schwül-heiss
	 */
	const WEATHER_MUGGINESS = 0x80000;
	/**
	 * Schwach bewölkt
	 */
	const WEATHER_CLOUDY_LIGHT = 0x100000;
	
	/**
	 * Warm und diesig
	 */
	const WEATHER_HOT_MISTY = 0x200000;
	
	/**
	 * Kalt und diesig
	 */
	const WEATHER_COLD_MISTY = 0x400000;
	
	/**
	 * Sandsturm
	 */
	const WEATHER_SAND_STORM = 0x800000;
	
	/**
	 * Enthält nähere Infos über die Wettertypen und deren Beziehungen zueinander
	 * @var array
	 */
	static $weather = array(
					self::WEATHER_SAND_STORM  => array(
									'name'=>'Sandsturm',
									'months'=>array(1=>1, 2=>1, 3=>1, 4=>1, 5=>1, 6=>1, 7=>1, 8=>1, 9=>1, 10=>1, 11=>1, 12=>1),
									'follows_after'=>array(
													self::WEATHER_HOT, self::WEATHER_SAND_STORM
													)
									), 
					self::WEATHER_COLD_MISTY  => array(
									'name'=>'Kalt und diesig',
									'months'=>array(1=>4, 2=>3, 3=>1, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>1, 10=>2, 11=>3, 12=>4),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_RAINY,self::WEATHER_CLOUDY,
													self::WEATHER_CLOUDY_LIGHT,self::WEATHER_HEAVY_RAIN,self::WEATHER_FLAMES,
													self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN,self::WEATHER_COLD_MISTY
													)
									),
					self::WEATHER_HOT_MISTY  => array(
									'name'=>'Warm und diesig',
									'months'=>array(1=>0, 2=>0, 3=>1, 4=>1, 5=>3, 6=>4, 7=>4, 8=>4, 9=>3, 10=>1, 11=>0, 12=>0),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_HOT,self::WEATHER_MUGGINESS,
													self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,
													self::WEATHER_HEAVY_RAIN,self::WEATHER_FLAMES,self::WEATHER_ECLIPSE,
													self::WEATHER_HOT_MISTY
													)
									),
					self::WEATHER_FOGGY => array(
									'name'=>'Neblig',
									'months'=>array(1=>1, 2=>1, 3=>2, 4=>2, 5=>1, 6=>1, 7=>1, 8=>1, 9=>3, 10=>4, 11=>5, 12=>3),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_MUGGINESS,self::WEATHER_RAINY,
													self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_SNOW,
													self::WEATHER_HEAVY_RAIN,self::WEATHER_FLAMES,self::WEATHER_ECLIPSE,
													self::WEATHER_SNOWRAIN,self::WEATHER_HOT_MISTY,self::WEATHER_COLD_MISTY
													)
									),
					self::WEATHER_HOT => array(
									'name'=>'Heiß und sonnig',
									'name_night'=>'Drückend schwüle Nacht',
									'months'=>array(1=>0, 2=>0, 3=>0, 4=>0, 5=>1, 6=>2, 7=>3, 8=>4, 9=>3, 10=>0, 11=>0, 12=>0),
									'follows_after'=>array(
													self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_RAINY,self::WEATHER_CLOUDY,
													self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_WINDY,self::WEATHER_TSTORM,
													self::WEATHER_BOREALIS,self::WEATHER_FLAMES,self::WEATHER_ECLIPSE,self::WEATHER_SAND_STORM
													)
									),
					self::WEATHER_MUGGINESS => array(
									'name'=>'Schwül-heiß',
									'name_night'=>'Schwül-warme Nacht',
									'months'=>array(1=>0, 2=>0, 3=>0, 4=>0, 5=>1, 6=>2, 7=>3, 8=>3, 9=>3, 10=>0, 11=>0, 12=>0),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_STORM,
													self::WEATHER_HAIL,self::WEATHER_BOREALIS,self::WEATHER_FLAMES,self::WEATHER_ECLIPSE
													)
									),
					self::WEATHER_COLD => array(
									'name'=>'Wechselhaft und kühl, mit sonnigen Abschnitten',
									'name_night'=>'Wechselhaft und kühl, mit sternenklaren Abschnitten',
									'months'=>array(1=>1, 2=>2, 3=>2, 4=>3, 5=>1, 6=>1, 7=>1, 8=>1, 9=>2, 10=>3, 11=>4, 12=>1),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_BOREALIS,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN,self::WEATHER_COLD_MISTY,
													)
									),
					self::WEATHER_RAINY => array(
									'name'=>'Regnerisch',
									'months'=>array(1=>2, 2=>2, 3=>2, 4=>4, 5=>2, 6=>2, 7=>2, 8=>2, 9=>2, 10=>3, 11=>4, 12=>2),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,
													self::WEATHER_WARM,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_SNOWRAIN,self::WEATHER_HOT_MISTY,self::WEATHER_COLD_MISTY
													)
									),
					self::WEATHER_CLOUDY => array(
									'name'=>'Stark bewölkt',
									'name_night'=>'Dunkle Nacht',
									'months'=>array(1=>3, 2=>3, 3=>3, 4=>2, 5=>2, 6=>1, 7=>1, 8=>1, 9=>2, 10=>3, 11=>3, 12=>3),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,
													self::WEATHER_HAIL,self::WEATHER_BOREALIS,self::WEATHER_FLAMES,self::WEATHER_SNOWRAIN,self::WEATHER_COLD_MISTY,self::WEATHER_SAND_STORM
													)
									),
					self::WEATHER_CLOUDY_LIGHT => array(
									'name'=>'Schwach bewölkt',
									'name_night'=>'Nacht mit vereinzelten Sternen',
									'months'=>array(1=>3, 2=>3, 3=>3, 4=>2, 5=>2, 6=>2, 7=>2, 8=>2, 9=>3, 10=>3, 11=>3, 12=>3),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_BOREALIS,self::WEATHER_FLAMES,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN,self::WEATHER_SAND_STORM
													)
									),
					self::WEATHER_WARM => array(
									'name'=>'Warm und sonnig',
									'name_night'=>'Lauwarme Nacht',
									'months'=>array(1=>0, 2=>0, 3=>1, 4=>2, 5=>3, 6=>3, 7=>4, 8=>4, 9=>3, 10=>1, 11=>0, 12=>0),
									'follows_after'=>array(
													self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,
													self::WEATHER_HAIL,self::WEATHER_BOREALIS,self::WEATHER_FLAMES,self::WEATHER_ECLIPSE,self::WEATHER_HOT_MISTY,self::WEATHER_SAND_STORM
													)
									),
					self::WEATHER_CLOUDLESS => array(
									'name'=>'Wolkenloser Himmel und Sonnenschein',
									'name_night'=>'Wolkenloser Himmel mit glitzernden Sternen',
									'months'=>array(1=>0, 2=>0, 3=>1, 4=>2, 5=>3, 6=>3, 7=>4, 8=>4, 9=>3, 10=>1, 11=>0, 12=>0),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_BOREALIS,self::WEATHER_FLAMES,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN,self::WEATHER_HOT_MISTY,self::WEATHER_COLD_MISTY,self::WEATHER_SAND_STORM
													)
									),
					self::WEATHER_COLDCLEAR => array(
									'name'=>'Kalt bei klarem Himmel',
									'months'=>array(1=>4, 2=>4, 3=>3, 4=>1, 5=>0, 6=>0, 7=>0, 8=>0, 9=>3, 10=>4, 11=>4, 12=>4),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_BOREALIS,self::WEATHER_FLAMES,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN,self::WEATHER_COLD_MISTY
													)
									),
					self::WEATHER_WINDY => array(
									'name'=>'Starker Wind mit vereinzelten Regenschauern',
									'months'=>array(1=>2, 2=>2, 3=>3, 4=>3, 5=>1, 6=>2, 7=>1, 8=>1, 9=>3, 10=>4, 11=>4, 12=>3),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_BOREALIS,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN
													)
									),
					self::WEATHER_TSTORM => array(
									'name'=>'Gewittersturm',
									'months'=>array(1=>0, 2=>0, 3=>1, 4=>2, 5=>2, 6=>2, 7=>2, 8=>2, 9=>2, 10=>1, 11=>0, 12=>0),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDLESS,self::WEATHER_CLOUDY_LIGHT,
													self::WEATHER_WARM,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,
													self::WEATHER_HAIL,self::WEATHER_FLAMES,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN
													)
									),
					self::WEATHER_SNOW => array(
									'name'=>'Schneefälle',
									'months'=>array(1=>4, 2=>4, 3=>2, 4=>1, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>1, 11=>2, 12=>4),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,
													self::WEATHER_WINDY,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN,self::WEATHER_COLD_MISTY
													)
									),
					self::WEATHER_STORM => array(
									'name'=>'Orkanartige Sturmböen',
									'months'=>array(1=>2, 2=>2, 3=>2, 4=>2, 5=>1, 6=>0, 7=>0, 8=>2, 9=>3, 10=>3, 11=>3, 12=>2),
									'follows_after'=>array(
													self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN
													)
									),
					self::WEATHER_HEAVY_RAIN => array(
									'name'=>'Sintflutartige Regenfälle',
									'months'=>array(1=>1, 2=>1, 3=>2, 4=>2, 5=>2, 6=>1, 7=>0, 8=>0, 9=>1, 10=>2, 11=>2, 12=>2),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,
													self::WEATHER_WARM,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,
													self::WEATHER_HAIL,self::WEATHER_BOREALIS,self::WEATHER_FLAMES,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN,self::WEATHER_COLD_MISTY
													)
									),
					self::WEATHER_FROSTY => array(
									'name'=>'Frostig mit schmerzhaft beißendem Wind',
									'months'=>array(1=>4, 2=>4, 3=>3, 4=>1, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>3, 11=>4, 12=>4),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_BOREALIS,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN,self::WEATHER_COLD_MISTY
													)
									),
					self::WEATHER_HAIL => array(
									'name'=>'Starke Hagelschauer',
									'months'=>array(1=>1, 2=>1, 3=>2, 4=>2, 5=>2, 6=>2, 7=>2, 8=>2, 9=>2, 10=>1, 11=>1, 12=>1),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,
													self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_HEAVY_RAIN,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_SNOWRAIN,self::WEATHER_COLD_MISTY
													)
									),
					self::WEATHER_BOREALIS => array(
									'name'=>'Klarer Himmel mit seltsamem Wetterleuchten',
									'months'=>array(1=>1, 2=>1, 3=>1, 4=>1, 5=>0, 6=>1, 7=>0, 8=>1, 9=>1, 10=>1, 11=>1, 12=>2),
									'follows_after'=>array(
													self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_FROSTY,
													self::WEATHER_BOREALIS,self::WEATHER_HOT_MISTY
													)
									),
					self::WEATHER_FLAMES => array(
									'name'=>'Blutroter Himmel mit leichtem Flammenregen',
									'months'=>array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
									'follows_after'=>array(
													self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_STORM,self::WEATHER_FROSTY,
													self::WEATHER_FLAMES,self::WEATHER_HOT_MISTY,self::WEATHER_SAND_STORM
													)
									),
					self::WEATHER_ECLIPSE => array(
									'name'=>'Sonnenfinsternis',
									'name_night'=>'Besonders dunkle Nacht',
									'months'=>array(1=>0, 2=>0, 3=>0, 4=>1, 5=>0, 6=>1, 7=>0, 8=>1, 9=>0, 10=>0, 11=>1, 12=>0),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_HOT,self::WEATHER_MUGGINESS,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_WARM,self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_ECLIPSE,self::WEATHER_SNOWRAIN,self::WEATHER_COLD_MISTY,self::WEATHER_HOT_MISTY 
													)
									),
					self::WEATHER_SNOWRAIN => array(
									'name'=>'Schneeregen',
									'months'=>array(1=>4, 2=>4, 3=>2, 4=>1, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>1, 11=>2, 12=>4),
									'follows_after'=>array(
													self::WEATHER_FOGGY,self::WEATHER_COLD,self::WEATHER_RAINY,self::WEATHER_CLOUDY,self::WEATHER_CLOUDY_LIGHT,self::WEATHER_CLOUDLESS,
													self::WEATHER_COLDCLEAR,self::WEATHER_WINDY,self::WEATHER_TSTORM,self::WEATHER_SNOW,self::WEATHER_STORM,self::WEATHER_FROSTY,
													self::WEATHER_HAIL,self::WEATHER_SNOWRAIN,self::WEATHER_COLD_MISTY
													)
									)
	);
	
	/**
	 * Das aktuelle Wetter
	 *
	 * @var int
	 */
	static $actual_weather;
	
	///Iterator Interface
	
	public function rewind() {
		reset(self::$weather);
	}

	public function current() {
		$var = current(self::$weather);
		return $var;
	}

	public function key() {
		$var = key(self::$weather);
		return $var;
	}

	public function next() {
		$var = next(self::$weather);
		return $var;
	}

	public function valid() {
		$var = self::current() !== false;
		return $var;
	}
	
	///END Iterator Interface
	
	///Countable Interface
	public function count()
	{
		$var = count(self::$weather);
		return $var;
	}
	///END Countable Interface
	
	///ArrayAccess Interface
	public function offsetExists ($offset)
	{
		return isset(self::$weather[$offset]);
	}
 	public function offsetGet ($offset)
 	{
 		return self::$weather[$offset];
 	}
 	public function offsetSet ($offset, $value)
 	{
 		self::$weather[$offset] = $value;
 	}
 	public function offsetUnset ($offset)
 	{
 		unset(self::$weather[$offset]);
 	}
 	///END ArrayAccess Interface

	/**
	 * Ermittelt neues Wetter und speichert dieses in DB; gibt ID des neuen Wetters zurück
	 *
	 * @param int $weather_id ID des zu setzenden Wetters. Falls 0, wird dieses ermittelt (optional, Standard 0)
	 * @return int Neue Wetter-ID
	 */
	public static function set_weather ($weather_id=0) 
	{
		$arr_act_weather = self::get_weather();
	
		if(!$weather_id)
		{	// Ermitteln
	
			$month = get_gamedate_part('m');
			$list = array();
	
			foreach(self::$weather as $id => $w) 
			{
				if($w['months'][$month] > 0 && in_array($arr_act_weather['id'],$w['follows_after']))
				{
					for($i=0;$i < $w['months'][$month];$i++)
					{
						$list[] = $id;
					}
				}
	
			}
			
			$weather_id = $list[ e_rand(0,sizeof($list)-1) ];
	
		}
	
		savesetting('weather',$weather_id);
		
		self::$actual_weather = $weather_id;
	
		return(self::$weather[$weather_id]);
	
	}
	
	/**
	 * Aktualisiere das im Objekt abgespeicherte Wetter
	 */
	public static function read_actual_weather()
	{
		self::$actual_weather = getsetting('weather',0);
	}

	/**
	 * Ermittelt aktuelles Wetter
	 *
	 * @return array Wetter-Array des aktuell vorherrschenden Wetters
	 */
	public static function get_weather () 
	{
	
		$int_saved_weather = self::$actual_weather;
		$time = getgametime(true);
		$hour = get_gametime_part("h");

		$arr_w = self::$weather[$int_saved_weather];
	
		$arr_w['id'] = $int_saved_weather;
	
		// Nacht
		if( ($hour > 20 || $hour < 6) && isset($arr_w['name_night']) )
		{
			$arr_w['name'] = $arr_w['name_night'];
		}
	
		return($arr_w);
	
	}
	
	/**
	 * Überprüft, ob das aktuelle Wetter dem übergebenen Wetterarten gleicht
	 *
	 * @param int $int_weather
	 * @return bool
	 */
	public static function is_weather($int_weather)
	{
		return self::$actual_weather & $int_weather;
	}
	
	/**
	 * Gibt einen Array zurück der den Wetterbuff für den Kampf enthält falls definiert
	 * @return Array Array der den Wetterbuff für den Kampf enthält falls definiert, sonst leerer Array!
	 */
	public static function get_battle_influence_buff()
	{
		$arr_return_buff = array();
		
		switch (self::$actual_weather)
		{
			case self::WEATHER_WINDY:
			{
				if (mt_rand(1,2)==1)
				{
					$arr_return_buff = array('name'=>'`6Wetter','rounds'=>1,'wearoff'=>'','atkmod'=>0,'roundmsg'=>'`6Ein starker Windstoss läßt dich dein Ziel verfehlen.','activate'=>'offense');
				}
				else
				{
					$arr_return_buff = array('name'=>'`6Wetter','rounds'=>1,'wearoff'=>'','badguyatkmod'=>0,'roundmsg'=>'`6Ein starker Windstoss hindert {badguy} daran, dich zu treffen.','activate'=>'defense');
				}
				break;
			}
			case self::WEATHER_SNOWRAIN:
			{
				if (mt_rand(1,2)==1)
				{
					$arr_return_buff = array('name'=>'`6Wetter','rounds'=>1,'wearoff'=>'','defmod'=>0,'roundmsg'=>'`6Durch den Schneeregen siehst du den Schlag deines Gegners nicht kommen.','activate'=>'defense');
				}
				else
				{
					$arr_return_buff = array('name'=>'`6Wetter','rounds'=>1,'wearoff'=>'','badguydefmod'=>0,'roundmsg'=>'`6Durch den Schneeregen sieht dein Gegner deinen Schlag nicht kommen.','activate'=>'offense');
				}
				break;
			}
		}
		return $arr_return_buff;
	}
	
	/**
	 * Gibt einen zufälligen Wettertext wieder, der durch den Wettereditor in der DB hinterlegt wurde
	 *
	 * @param string $strCategory Die gewünschte Kategorie
	 * @return unknown
	 */
	public static function get_weather_text($strCategory)
	{	
		global $access_control;
		
		//Wettertext holen nach Ort und aktuellem Datum. Falls für aktuelles Wetter kein Text existiert hole einen evtl vorhandenen text für "beliebiges Wetter"
		$arrWeather = db_get('
			SELECT w.id AS id ,text 
				FROM weather_texts w 
					LEFT JOIN 
						weather_texts_categories c 
					ON (c.id = w.category) 
				WHERE 
					c.category = "'.$strCategory.'" AND 
					enabled="1" AND 
					revised="1" AND
					(w.weather LIKE "%|'.self::$actual_weather.'|%" OR w.weather LIKE "%|0|%")
				ORDER BY w.weather DESC, RAND()
				LIMIT 1'
		);
		if($arrWeather === null || is_null_or_empty($arrWeather['text']))
		{
			$arrWeather['text'] = '`$Für die von dir gewünschte Kategorie: '.$strCategory.' gibt es leider noch keinen Wettertext. Da sollte aber schleunigst einer hinzugefügt werden, oder?`0';
		}
		else
		{		
			//Edit-Link anzeigen
			if($access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS))
			{
				$strLink = 'su_weather_texts_editor.php?op=edit&id='.$arrWeather['id'];
				allownav($strLink);
				$arrWeather['text'] = '<br clear=all />[<span class="colWhiteBlack "><a href="'.$strLink.'">Ändern</a></span>]<br clear=all />'.$arrWeather['text'];
			}
		}
		
		if(!is_null_or_empty($arrWeather['php']))
		{
			$strCodePrepend = 'global $Char,$session,$arrWeather; ';
            eval(utf8_eval($strCodePrepend.$arrWeather['php']));
		}
		
		//Replacing all PHP Sourcecode
		$strTmpText = $arrWeather['text'];
		utf8_preg_match_all('/{{(.*)}}/sU',$strTmpText,$arrMatches,PREG_SET_ORDER);
		foreach($arrMatches as $arr_match)
		{
			$arr_match[1] = eval(utf8_eval($arr_match[1]));
			$strTmpText = str_replace($arr_match[0],$arr_match[1],$strTmpText);
		}
		
		$arrWeather['text'] = $strTmpText;
		
		return $arrWeather['text'];
	}
	
} //End Class weather

Weather::read_actual_weather();

?>
