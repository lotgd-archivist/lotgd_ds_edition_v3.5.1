<?php
/**
* disciples.lib.php: Knappen und co
* Die komplette Funktionalität wurde ausgelagert in die class.CDisciples.php
* @author maris <Maraxxus@gmx.de>
* @version DS-E V/2
*/

/**
* Lädt einen Knappen aus der DB und legt dessen Buff an
*
* @param int AcctID des Eigentümers; optional, Standard 0 = Sessionuser
* @return array Daten des Knappen als assoz. Array; Knappenbuff liegt in ['buff']
* @author maris / modified by talion
*/
function get_disciple ($int_acctid=0) {
	return CDisciple::get($int_acctid);
}

/**
* Liefert das zum Status des Knappen passende Adjektiv
*
* @param int Status
* @param string Endung
* @return string Adjektiv
* @author maris
*/
function get_disciple_stat($state, $end='en') {
	return CDisciple::getStatus($state,$end);
}

/**
* Steigert den Knappen des Users um eine Stufe
* @param array Knappendaten (optional)
* @return string Ergebnistext
* @author maris
*/
function disciple_levelup($arr_disc=0) {
	return CDisciple::levelup($arr_disc);
}

/**
* Setzt Knappen auf inaktiven Zustand, erledigt auch Feststellung des besten Knappen im Land
*
* @param bool Endgültig fort (optional, Standard false)
* @author maris / modified by talion
*/
function disciple_remove($death=false) {
	CDisciple::remove($death);
}
?>