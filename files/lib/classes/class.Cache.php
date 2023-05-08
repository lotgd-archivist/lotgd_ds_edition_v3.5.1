<?php

/**
 * Enthält alle für Atrahor relevanten Caching Methoden
 *
 */
final class Cache
{
	/**
	 * Der Cache wird in der Session angelegt
	 */
	const CACHE_TYPE_SESSION 	= 0x1;
	
	/**
	 * Der Cache wird auf der Festplatte angelegt
	 */
	const CACHE_TYPE_HDD 		= 0x2;
	
	/**
	 * Der Cache verwendet den Arbeitsspeicher und den Memcached
	 *
	 */
	const CACHE_TYPE_MEMORY 	= 0x4;
	
	/**
	 * Wie lang soll der Speicher bestehen
	 */
	const CACHE_LIFETIME = 300;
	
	
	///
	/// Öffentliche Funktionen für den gesamten Cachezugriff
	///
	
	public static function set($intCacheType,$strName,$objValue)
	{
		if(($intCacheType & self::CACHE_TYPE_MEMORY) == self::CACHE_TYPE_MEMORY ){
			$strName = 'mem_'.$strName;
		}
		else if(($intCacheType & self::CACHE_TYPE_SESSION) == self::CACHE_TYPE_SESSION ){
			$strName = 'sess_'.session_id().'_'.$strName;
		}
		else if(($intCacheType & self::CACHE_TYPE_HDD) == self::CACHE_TYPE_HDD ){
			$strName = 'hdd_'.$strName;
		}
        $file = CACHE_PATH .$strName . '_cache.php';
        $stringData = '<?PHP $str_data=\''.utf8_serialize(array('data' => $objValue)).'\';';
        return file_put_contents($file,$stringData);
	}
	
	public static function get($intCacheType,$strName)
	{
		if(($intCacheType & self::CACHE_TYPE_MEMORY) == self::CACHE_TYPE_MEMORY ){
			$strName = 'mem_'.$strName;
		}
		else if(($intCacheType & self::CACHE_TYPE_SESSION) == self::CACHE_TYPE_SESSION ){
			$strName = 'sess_'.session_id().'_'.$strName;
		}
		else if(($intCacheType & self::CACHE_TYPE_HDD) == self::CACHE_TYPE_HDD ){
			$strName = 'hdd_'.$strName;
		}
        $file = CACHE_PATH .$strName . '_cache.php';
        if(file_exists($file)){
            $str_data = '';
            require($file);
            $array = utf8_unserialize($str_data);
            if(isset($array['data'])){
                return $array['data'];
            }
        }
        return false;
	}
	
	public static function delete($intCacheType,$strName)
	{
		if(($intCacheType & self::CACHE_TYPE_MEMORY) == self::CACHE_TYPE_MEMORY ){
			$strName = 'mem_'.$strName;
		}
		else if(($intCacheType & self::CACHE_TYPE_SESSION) == self::CACHE_TYPE_SESSION ){
			$strName = 'sess_'.session_id().'_'.$strName;
		}
		else if(($intCacheType & self::CACHE_TYPE_HDD) == self::CACHE_TYPE_HDD ){
			$strName = 'hdd_'.$strName;
		}
        $file = CACHE_PATH .$strName . '_cache.php';
        if (file_exists($file)){
            return unlink($file);
        }
        return false;
	}
	
	public static function purge($intCacheType)
	{
        $strName = '';
        if(($intCacheType & self::CACHE_TYPE_MEMORY) == self::CACHE_TYPE_MEMORY ){
            $strName = 'mem_';
        }
        else if(($intCacheType & self::CACHE_TYPE_SESSION) == self::CACHE_TYPE_SESSION ){
            $strName = 'sess_'.session_id().'_';
        }
        else if(($intCacheType & self::CACHE_TYPE_HDD) == self::CACHE_TYPE_HDD ){
            $strName = 'hdd_';
        }
        $files = glob(CACHE_PATH .$strName.'*_cache.php');
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }
        return true;
	}
	
}

?>