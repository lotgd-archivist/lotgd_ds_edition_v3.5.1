<?php

class MQuest
{
	private $PATH = 'quest/';
	
	function __construct($do, $ispopup = false)
	{
		global $session,$access_control,$show_invent;

		$filename = 'bathorys_module.php?mod=quest&mdo='.$do.'&';

		switch($do)
		{
            case 'superuser':
                require_once($this->PATH.'su_quest.php');
                break;

            case 'book':
                (!CQuest::is_activ()) ? exit() : false;
                require_once($this->PATH.'book.php');
                break;

			default:
				die(' Eine unsichtbare Stimme sagt: Nichts zu sehen - Weiter gehen! ');
			break;
		}
	}
}
?>