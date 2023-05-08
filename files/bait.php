<?php
/*********************************************
Lots of Code from: lonnyl69 - Thanks Lonny !
Also Thanks to Excalibur @ dragonprime for your help.
By: Kevin Hatfield - Arune v1.0
06-19-04 - Public Release
Written for Fishing Add-On - Poseidon Pool
Translation and simple modifications by deZent deZent@onetimepad.de
********************************************/

// Bugfix&Modification by Maris (Maraxxus@gmx.de)

require_once "common.php";

define('MAX_ITEMS',100);

checkday();

page_header("Kerras Angelladen");

output("`c`b`BK`§e`3r`#r`Fas Angell`#a`3d`§e`Bn`0`b`c`n");

$sql = "SELECT worms,minnows,boatcoupons FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
$result = db_query($sql);
$rowf = db_fetch_assoc($result);

if($session['user']['dragonkills'] < 2) {

        output('`§Kerra mustert dich und meint dann: `f"Du solltest erst mehr Erfahrung sammeln, ehe Du Dich an die Herausforderung des Angelns machst!"`n');
        addnav('Angeln!','fish.php');
        addnav('Zurück zum See','pool.php');

}
else {

        $inventory=$rowf['worms'];
        $inventory+=$rowf['minnows'];
        $inventory+=$rowf['boatcoupons'];

        $space= max(MAX_ITEMS - $inventory,0);

        $cost = array();
        $max = array();

        $cost['worms'] = 5;
        $cost['minnows'] = 6;
        $cost['boatcoupons'] = 10;
        $cost['wormsell'] = 4;
        $cost['minnowsell'] = 5;
        $cost['boatcouponsell'] = 8;
        $max['worms'] = min( floor($session['user']['gold'] / $cost['worms']) , $space );
        $max['minnows'] = min( floor($session['user']['gold'] / $cost['minnows']) , $space );
        $max['boatcoupons'] = min( floor($session['user']['gold'] / $cost['boatcoupons']) , $space );

        $op = ($_GET['op']) ? $_GET['op'] : '';

        switch($op)
        {
                case '':

                        output('`BN`§a`3h `#d`Fes Sees wurde eine kleine Hütte erbaut, die von außen her zwar etwas windschief scheint, doch völlig ausreicht, damit fleißige Angler ausgerüstet sind. Sollte man sich durch das bedrohlich wirkende Knarren der aus Brettern zusammen gezimmerten Tür nicht abschrecken lassen, so erblickt man im Inneren des kleinen Häuschens eine Theke, sowie zahlreiche Regale, auf denen etliche Behälter Platz finden. Was sich in diesen befindet, willst Du wohl kaum genauer erfahren - doch der Besitzer dieses Angelladens, Kerra, wird sicherlich gern Auskunft g`#e`3b`§e`Bn.`n`n');

                        output('`IIn deinem Beutel siehst du:`n');
                        output('`y'.$rowf['minnows'].' Fliegen, '.$rowf['worms'].' Angelwürmer und '.$rowf['boatcoupons'].' Coupons für ein Ruderboot.`n');
                        if ($inventory >= MAX_ITEMS)
                        {
                                output('`IDu bemerkst, dass dein Beutel schon voll ist.`n`n');
                        }
                        else
                        {
                                output('`§Du hast noch für '.$space.' Dinge Platz im Beutel.
                                `n`n`fWas möchtest du kaufen?
                                `n`n`§Fliegen zum Preis von `f'.$cost['minnows'].' Gold `§das Stück.
                                `0<form method="POST" action="bait.php?op=trade&amp;what=minnows">
                                <input type="text" name="count" value="'.$max['minnows'].'" size="4">
                                <input type="hidden" name="cost" value="'.$cost['minnows'].'">
                                <input type="submit" value="Fliegen kaufen" class="button">
                                </form>');
                                addnav('','bait.php?op=trade&what=minnows');

                                if($session['user']['dragonkills'] >= 2)
                                {
                                        output('`n`§Würmer zum Preis von `f'.$cost['worms'].' Gold `§das Stück.
                                        `0<form method="POST" action="bait.php?op=trade&amp;what=worms">
                                        <input type="text" name="count" value="'.$max['worms'].'" size="4">
                                        <input type="hidden" name="cost" value="'.$cost['worms'].'">
                                        <input type="submit" value="Würmer kaufen" class="button">
                                        </form>');
                                        addnav('','bait.php?op=trade&what=worms');
                                }
                                else
                                {
                                        output("`n`§Wenn du erfahrener wärst, könntest du mit Würmern angeln...");
                                }

                                if($session['user']['dragonkills'] >= 10)
                                {
                                        output('`n`§Bootscoupons zum Preis von `f'.$cost['boatcoupons'].' Gold `§das Stück.
                                        `0<form method="POST" action="bait.php?op=trade&amp;what=boatcoupons">
                                        <input type="text" name="count" value="'.$max['boatcoupons'].'" size="4">
                                        <input type="hidden" name="cost" value="'.$cost['boatcoupons'].'">
                                        <input type="submit" value="Bootscoupons kaufen" class="button">
                                        </form>');
                                        addnav('','bait.php?op=trade&what=boatcoupons');
                                }
                                else
                                {
                                        output("`n`§Wenn du noch erfahrener wärst, könntest du mit einem Boot auf den See hinaus rudern...");
                                }
                        }
                        if($inventory>0)
                        {
                                output('`n<hr>`n`§Oder möchtest du etwas verkaufen?`n');
                                if($rowf['minnows'] > 0)
                                {
                                        output('`n`§Fliegen zum Preis von `f'.$cost['minnowsell'].' Gold `§das Stück.
                                        `0<form method="POST" action="bait.php?op=sell&amp;what=minnows">
                                        <input type="text" name="count" value="'.$rowf['minnows'].'" size="4">
                                        <input type="hidden" name="cost" value="'.$cost['minnowsell'].'">
                                        <input type="submit" value="Fliegen verkaufen" class="button">
                                        </form>');
                                        addnav('','bait.php?op=sell&what=minnows');
                                }

                                if($rowf['worms'] > 0)
                                {
                                        output('`n`§Würmer zum Preis von `f'.$cost['wormsell'].' Gold `§das Stück.
                                        `0<form method="POST" action="bait.php?op=sell&amp;what=worms">
                                        <input type="text" name="count" value="'.$rowf['worms'].'" size="4">
                                        <input type="hidden" name="cost" value="'.$cost['wormsell'].'">
                                        <input type="submit" value="Würmer verkaufen" class="button">
                                        </form>');
                                        addnav('','bait.php?op=sell&what=worms');
                                }

                                if($rowf['boatcoupons'] > 0)
                                {
                                        output('`n`§Bootscoupons zum Preis von `f'.$cost['boatcouponsell'].' Gold `§das Stück.
                                        `0<form method="POST" action="bait.php?op=sell&amp;what=boatcoupons">
                                        <input type="text" name="count" value="'.$rowf['boatcoupons'].'" size="4">
                                        <input type="hidden" name="cost" value="'.$cost['boatcouponsell'].'">
                                        <input type="submit" value="Bootscoupons verkaufen" class="button">
                                        </form>');
                                        addnav('','bait.php?op=sell&what=boatcoupons');
                                }
                        }
                        addnav('Zurück zum See','pool.php');
                break;

                case 'trade':
                        $sql = "SELECT worms,minnows,boatcoupons FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
                        $result = db_query($sql);
                        $rowf = db_fetch_assoc($result);

                        $what = $_GET['what'];
                        $count = min($max[$what],$_POST['count']);
                        if($count<0) $count=0;
                        $cost = $_POST['cost'] * $count;
                        $totalcount=$rowf[$what];
                        $totalcount+=$count;

                        if ($what=='minnows')  { $bname='Fliegen'; }
                        elseif ($what=='worms')  { $bname='Würmer'; }
                        elseif ($what=='boatcoupons')  { $bname='Bootscoupons'; }

                        $sql = "UPDATE account_extra_info SET $what=$totalcount WHERE acctid=".$session['user']['acctid']."";
                        db_query($sql);

                        $session['user']['gold'] -= $cost;

                        output('`§Du kaufst `f'.$count.' '.$bname.'`§ für `f'.$cost.'`§ Gold!
                        `n`fKerra schiebt dir einen kleinen Beutel herüber, nimmt das Gold entgegegen und schaut dich abwartend an.');

                        addnav('Noch mehr kaufen','bait.php');
                        addnav('Auf zum angeln!','fish.php');
                        addnav('Zurück zum See','pool.php');

                        break;

                case 'sell':
                        $sql = "SELECT worms,minnows,boatcoupons FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
                        $result = db_query($sql);
                        $rowf = db_fetch_assoc($result);

                        $what = $_GET['what'];
                        $count = min($rowf[$what],$_POST['count']);
                        if($count<0) $count=0;
                        $cost = $_POST['cost'] * $count;
                        $totalcount=$rowf[$what];
                        $totalcount-=$count;

                        if ($what=='minnows')  { $bname='Fliegen'; }
                        elseif ($what=='worms')  { $bname='Würmer'; }
                        elseif ($what=='boatcoupons')  { $bname='Bootscoupons'; }

                        $sql = "UPDATE account_extra_info SET $what=$totalcount WHERE acctid=".$session['user']['acctid']."";
                        db_query($sql);

                        $session['user']['gold'] += $cost;

                        output('`§Du verkaufst `f'.$count.' '.$bname.'`§ für `f'.$cost.'`§ Gold!
                        `n`fKerra schiebt dir das Gold entgegegen und schaut dich abwartend an.');

                        addnav('Noch mehr kaufen','bait.php');
                        addnav('Auf zum angeln!','fish.php');
                        addnav('Zurück zum See','pool.php');
                        break;
        }
}

page_footer();
?>