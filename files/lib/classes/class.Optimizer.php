<?php

final class Optimizer{
	const NO_TITLES		= 	2;
	
	
	private static $arr_optimize = array();
	
	public static function optimize(){
		$params =& func_get_args();
		foreach( $params as $p ){
			self::$arr_optimize[ $p ] = true;
		}
	}
	
	public static function optimizing( $check ){
		if(!isset(self::$arr_optimize[ $check ])) return false;
		return (bool)self::$arr_optimize[ $check ];
	}
}

?>