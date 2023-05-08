<?php
/**
 * @desc Die Hausratte begrüßt die Bewohner
 * @author Dragonslayer
 * @copyright Atrahor, DS V3
 */


/** @noinspection PhpUndefinedVariableInspection */
if(house_has_extension($session['housekey'], 'rathole')>0)
{
	$str_sql = 'SELECT content FROM house_extensions WHERE houseid='.$session['housekey'].' AND type="rathole" ORDER BY RAND()';
	$db_res = db_query($str_sql);
	$arr_result = db_fetch_assoc($db_res);
	$arr_content = utf8_unserialize($arr_result['content']);

	$arr_content['ratname'] = (empty($arr_content['ratname'])?'Die Hausratte':$arr_content['ratname']);
	switch (e_rand(0,9))
	{
		case 0:

			insertcommentary(1,'/msg '.$arr_content['ratname'].'`t kommt kurz vorbei getrappelt und begrüßt '.$session['user']['name'],'house-'.$session['housekey']);
			break;
		case 1:
			insertcommentary(1,'/msg '.$arr_content['ratname'].'`t schaut dich aus einer Ecke des Hauses an und wackelt mit den Schnurrhaaren - süß.','house-'.$session['housekey']);
			break;
		case 2:
			insertcommentary(1,'/msg `tDu hörst ein freundliches Willkommen-fiepsen von '.$arr_content['ratname'].'`t. Freundlich und wohlerzogen, das musst du zugeben.','house-'.$session['housekey']);
			break;
		case 3:
			insertcommentary(1,'/msg '.$arr_content['ratname'].'`t nickt dir beim eintreten kurz zu und vertieft sich dann wieder in seine Arbeit...dem Nagen eines Stück Käses.','house-'.$session['housekey']);
			break;
		case 4:
			insertcommentary(1,'/msg '.$arr_content['ratname'].' `tschlüpft unter dein Hosenbein, krabbelt an dir hoch und setzt sich auf deine Schulter.','house-'.$session['housekey']);
			break;
		case 5:
			insertcommentary(1,'/msg `tDu kannst '.$arr_content['ratname'].' `terblicken, wie er/sie versucht ein Stück Käse durch ein viel zu kleines Mauseloch wegzuschaffen.','house-'.$session['housekey']);
			break;
		case 6:
			insertcommentary(1,'/msg '.$arr_content['ratname'].' `tläuft an dir vorbei, bleibt kurz stehen und macht Männchen - wenn er/sie dir nun auch noch den Mantel abnimmt hast du es geschafft!','house-'.$session['housekey']);
			break;
		case 7:
			insertcommentary(1,'/msg '.$arr_content['ratname'].' `tquiekt vergnügt als du den Raum betrittst.','house-'.$session['housekey']);
			break;
		case 8:
			insertcommentary(1,'/msg `tDu siehst '.$arr_content['ratname'].' `tbeim Nickerchen auf dem Fußboden.','house-'.$session['housekey']);
			break;
		case 9:
			insertcommentary(1,'/msg `tDu betrittst den Raum, aber nichtmal '.$arr_content['ratname'].' `tlässt sich blicken - na mal bloss nicht alle auf einmal!','house-'.$session['housekey']);
			break;
	}
}
?>