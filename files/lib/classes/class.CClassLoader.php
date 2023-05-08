<?php
/**
 * Statische Klasse, zum Automatischen Klassen nachladen
 *
 */
class CClassLoader {
		
	/**
	 * Klassen, die in dateien stehen, die nicht class.[KlassenName].php heissen
	 *
	 * @var array
	 */
	private static $arr_classes = array(
			'CNode'				=> 'class.CTree.php'
	);
	
	public static function loadClass( $str_className ) {
		$returnValue 	= false;

        if("OC" == substr($str_className,0,2))
        {
            $str_filename 	= ORTE_CLASS_PATH.'class.'.$str_className.'.php';
        }
        else if("OM" == substr($str_className,0,2))
        {
            $str_filename 	= ORTE_MOD_CLASS_PATH.'class.'.$str_className.'.php';
        }
        else
        {
            if( array_key_exists($str_className, CClassLoader::$arr_classes) ){
                $str_filename 	= CLASS_PATH.CClassLoader::$arr_classes[$str_className];
            }
            else{
                $str_filename 	= CLASS_PATH.'class.'.$str_className.'.php';
            }
        }

    	
    	if( include_once($str_filename) ){
    		$returnValue = true;
    	}
	    else{
	        trigger_error("Could not load class '".$str_className."' form file '".$str_filename."'.", E_USER_WARNING);
	    }
	    return $returnValue;
	}
}


//CClassloader mit Autoloader verbinden
if( !spl_autoload_register('CClassLoader::loadClass') ){
	trigger_error("Could not hook up auto class loader.", E_USER_ERROR);
}
?>