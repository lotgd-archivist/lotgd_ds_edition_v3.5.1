<?php
/*
* description: Verschiedene RPG-Orte im Wald, teils mit Events
* copyright: Atrahor-Team
*/
require_once 'common.php';

if(!isset($session))
{
	echo('$session nicht definiert in '.$filename.'');
	exit();
}

$filename=basename(__FILE__);
page_header('Der Wald');


addcommentary();

switch ($_GET['op'])
{
	case 'deepforest':
	{
		//- Wald -> Ein Ort, der Wald an sich ist und keine Lichtung, ähnlich dem Wald im Nebelgebirge, nur an einem anderen, näher an Atrahor gelegenen Ort. Einfach dunkler Wald.

		output(get_title('`|D`Be`Nr `(tiefe dunkle W`Na`Bl`|d').'
		`|E`Bi`Nn`( schwerer Waldduft schlägt dir entgegen, als du diesen Teil des Waldes erreichst. Dunkel und dicht erheben sich die scheinbar hundertjährigen Tannen. Eng an eng gedrängt bieten die uralten Bäume wunderbare Möglichkeiten sich zu verstecken..
`nDer wenige Lichteinfall macht es kleineren Gewächsen kaum möglich hier ihre Wurzeln zu schlagen. Hier hat sich jeder sichtbare Pfad im Humus verloren. Nur vereinzelt kann man Blattträger erkennen, die sich nahe der dicken Wurzeln nieder gelassen haben.
`nDer Boden ist dennoch überdeckt von Moos in einem satten Grün. Dieses findet hier ideale Bedingungen, ebenso wie die vereinzelnden Pilzsorten hier einen Winkel zum Leben gefunden haben und oftmals grotesk versteckt am Boden zu finden sind. Wohin man den Blick auch wendet, die Tiefe des Waldes scheint alles zu verschlucken, irgendwo in der Nähe ist ein dumpfes Grollen zu hören. Eine unheimliche, düstere Atmosphäre liegt in der Luft. Sicher treibt hier das ein oder andere flinke Raubtier sein Unwesen, kann es sich doch so gut zwischen den morgendlichen Nebelschwaden verstecken.
`n`nNa, hoffentlich hast du ein paar Brotkrumen gestreut, um unter dem weit herabreichenden Tannengeäst den Weg zurück zu finden. Oder du folgst dem einzigen Flackern in der Fe`Nr`Bn`|e.`0`n`n');
		viewcommentary('wald_dunklerwald');

		//(Möglich hier auch, Pilze Sammeln, eventuell Kräuter pflücken/ vielleicht sogar giftige? – weitere Stellen würde ich wenn nur noch ein „Felsenmeer“ so etwas wie eine Klippe vielleicht vorschlagen, bzw einen Steinvorsprung -> Ruine)
		
		addnav('G?Folge dem Grollen',$filename.'?op=cliff');
		//addnav('Tiefer in den Wald',$filename.'?op=darklands');
		addnav('Zur Seilbrücke','ropeway.php');
		addnav('Pilze suchen','pilzsuche.php');
		addnav('Zurück');
		addnav('Zurück zur Kreuzung',$filename.'');
		break;
	}

	case 'cliff':
	{
		//- Felsenabhang. (dass man von da aus  zur Ruine + Moor kommt. – eventuell hier auch die Möglichkeit zur „Höhle“ zu kommen. )

		output(get_title('`NE`(i`)n felsiger Abgru`(n`Nd').'
		`NK`(a`)ntiges, uraltes Gestein, das vom Regen freigelegt wurde, die Erde abgetragen, fällt ein schwieriger Felsenabhang vor dir in die Tiefe.
`n Du kannst einen seltsamen `eP`sfa`ed `)dort unten erkennen, der doch sicher irgendwohin führen muss.
`nVereinzelte, umgestürzte Bäume zeugen vom Erdabgang, dornige Büsche machen den Ab- oder Aufstieg schwer. Sicher nichts für schwache Nerven. Kein senkrechtes Gefälle, aber durch die vielen matschigen, durch Regenwasser getränkte Zwischenräume und den scharfen Gesteinskanten ist es gar nicht so leicht, dieses Stück zu bewältigen. Immer wieder brechen Gesteinsbrocken heraus und donnern in die Tiefe..
`nSeltene Kräuter sind in den kleinen Felsspalten verborgen, und der Wald um dich ist nur ein wenig lichter geworden.
 `n`nDein Forscherdrang zwingt dich allerdings weiter, denn der `eP`sfa`ed `)dort unten muss ganz sicher zu einem seltsamen Ort führen. Oder vielleicht doch lieber zu dem `eFe`slsvorspru`eng`) dort, der ein Geheimnis zu bergen scheint?`n`n');
		viewcommentary('wald_felshang');

		//(Möglichkeit hier eventuell auch Kräuter zu sammeln; oder Gesteinsbrocken sammeln.. Funktionslos; vll für 1- 2 Gold zu verkaufen, oder verschenkbar  allerdings denke ich, ist hier kein Muss an „Klickevents“ )
		addnav('Hinabsteigen',$filename.'?op=leak');
		addnav('F?Zum Felsvorsprung',$filename.'?op=cave');
		addnav('Zurück');
		addnav('Zurück zum tiefen Wald',$filename.'?op=deepforest');
		break;
	}

	case 'leak':
	{
		//- Weggabelung -> (Ne Weggabelung halt.. xD)

		output(get_title('`SE`Ti`;n`Ye Weggabel`;u`Tn`Sg').'
		`SD`Te`;r `YWald um dich herum hat sich kaum gelichtet. Die Tannen stehen immer noch dicht an dicht. Doch hier erscheinen die riesigen Wüchse nicht ganz so hoch. Das Tageslicht erreicht dürftig die Erde, und nur ein paar Grashalme bedecken den moosigen Boden. Nicht ein Vogel tiriliert in diesem Walde und die Stille, die diesen Ort umwebt, ist regelrecht erdrückend. Hier wird sicher das ein oder anderen dunkle Geschäft außerhalb Atrahors abgewickelt.
		`nIn dem Wegwinkel liegt ein großer Felsbrocken, der sich als Sitzgelegenheit nutzen lässt, die ein oder andere Einkerbung im Gestein zeugt von längeren Verweilzeiten an diesem Ort.
		`nVielleicht ist die Entscheidung so schwierig? Zwei Pfade stehen dir zur Auswahl, einer der weiter gen Norden führt, und einer der eher gen Süden deutet, die Entscheidung wohin du gehst, liegt an dir. Ein Weg seltsamer als der andere, führt der nördliche noch tiefer in das Dickicht des Waldes. Der andere scheint eher zu einer Lichtung zu führen, soweit du es erkennen kan`;n`Ts`St`n`n');
		viewcommentary('wald_weggabelung');

		//(vielleicht die Möglichkeit auch etwas auf den Stein zu schreiben? Obwohl da sicher mit unnützem Spam zu rechnen ist.)
		addnav('Nördlicher dunkler Weg',$filename.'?op=ritualplace');
		addnav('Südlicher heller Weg',$filename.'?op=abbey');
		addnav('b?Stein bekritzeln',$filename.'?op=writestone');
		addnav('Zurück');
		addnav('Zurück zum Felshang',$filename.'?op=cliff');
		break;
	}

	case 'abbey':
	{
		//- Ruine ->Eine halb verfallene (Kloster)Ruine, die günstigerweise auch im Wald zu finden sein könnte. ( zu finden wenn man die hellere Abzweigung nimmt)

		output(get_title('`gD`pa`8s `yv`terfallene Kl`to`ys`8t`pe`gr').'
		`gD`pe`8r `yW`taldwuchs um dich nimmt ab, und du erreichst eine große Lichtung, auf der eine alte, mit Efeu bewachsene Ruine steht. Je näher du das zerfallene Gebäude betrachtest, um so sicherer weißt du, dass es sich um ein einstiges Kloster handeln muss.
		`nDas Tageslicht wird bunt schillernd von den großen, farbigen Fenstern reflektiert, von denen einige noch intakt sind..
		`nDu kannst ganz genau das Kirchenschiff erkennen, und den Glockenturm, in dem sicher vor langer Zeit eine stattliche Glocke gedonnert haben muss, um zur Messe zu rufen. Das Dach der Klosterruine ist eingefallen, und auch das Seitenschiff, in dem die Priester gewohnt haben müssen, ist fast bis auf die Grundmauern zusammengestürzt. Geröll und Schutt.
		`nDer Ort ist behaftet mit einer scheinbar heiligen Atmosphäre, viele bunte Blumen wachsen um die alten Mauern herum, hier und da fehlt ein großes Stück des Bauwerks. Der Efeu der hier in rauen Mengen wächst, verschließt hier und da die Löcher. Hin und wieder kannst du einen Schwarm Schmetterlinge erkennen. Dieser Ort ist etwas Besonderes.
		`nOb du vielleicht einmal in die Kirche sch`ya`8u`ps`gt?`n`n');
		viewcommentary('wald_ruine');

		//(Blumen pflücken? Bunte Glasstücke mitbringen? Schmetterlinge fangen und sie ähnlich wie einen Blumenstrauß versenden? )
		addnav('Ruine betreten',$filename.'?op=inside_abbey');
		addnav('Zurück');
		addnav('Zurück zur Weggabelung',$filename.'?op=leak');
		break;
	}

	case 'inside_abbey':
	{
		//- Kirchenschiff

		output(get_title('`gI`pm `8K`yi`trchenschiff des verfallen Klos`yt`8e`pr`gs').'
		`gD`pu `8h`ya`tst einen Eingang in den Innenraum der Klosterkirche gefunden, das morsche Holz der Türe ist längst der Witterung zum Opfer gefallen und der Efeu hat dir den Zugang beinahe verwährt. Doch hast du dich durch das dichte Gewächs schlagen können.
		`nDurch das eingefallene Dach fallen die Sonnenstrahlen in den Innenraum. Die Kirchenbänke sind aus festem Holz, doch hat der Wurm sich wohl auch schon an ihnen gelabt. Der Altar ist der Witterung ebenso zum Opfer gefallen, wie das Holzkreuz hinter ihm. Hier findest du einige Schlingpflanzen die sich an den stützenden Säulen empor recken, und hin und wieder ertönt das Gurren einer Taube, die sich wohl im restlichen Dachgebälk versteckt hält. Einige der Träger reichen fast bis zur Erde und ein immer wieder eintretendes Knacken lässt erahnen wie marode diese Ruine schon ist.
		`nJeglicher Tand ist im Laufe der Jahre gestohlen worden, und nur Holz und Gestein sind übrig geblieben. Trotzdem zaubert das Lichterspiel der Fenster eine heilsame Atmosphäre der Ruhe. Hin und wieder heult der Wind schauerlich durch das verlassene Gemäuer.
		`nWeiter hinten im Kirchenschiff kannst du eine Treppe erspähen.. sie führt wohl zum Glockenturm hinauf, doch die Stufen sind sicher sehr morsch, willst du es dennoch w`ya`8g`pe`gn?`n`n');
		viewcommentary('wald_ruine_innen');

		//( würde hier von etwaigen Funktionen absehen)
		addnav('G?Auf den Glockenturm',$filename.'?op=belfry');
		addnav('L?`(Lichkönig`0',$filename.'?op=lichking');

		addnav('Zurück');
		addnav('Zurück vor das Kloster',$filename.'?op=abbey');
		break;
	}

    /** @noinspection PhpMissingBreakStatementInspection */
    /** @noinspection PhpMissingBreakStatementInspection */
    case 'ringbell':
	{
		output('Du schlägst den Klöppel der Glocke. Einen scheppernder Ton, der den morschen Turm erzittern lässt, ist das Ergebnis.`n`n
		'.JS::encapsulate('
		wackel();
		function wackel() {
			for (i = 6; i > 0; i--)
			{
				for (j = 6; j > 0; j--)
				{
					parent.moveBy(0,i);
					parent.moveBy(i,0);
					parent.moveBy(0,-i);
					parent.moveBy(-i,0);
				}
			}
		}
		'));
		//wenn sound erlaubt einen schrägen Glockenton ausgeben
		//kein break;
	}

	case 'belfry':
	{
		//- Glockenturm

		output(get_title('`gD`pe`8r G`slockenturm des verfallenen Klo`sst`8e`pr`gs').'
		`gU`pn`8t`ser lautem Knacken und Lamentieren der alten Holzstufen, die hier und da durchgebrochen waren, hast du dich dennoch empor in den Glockenturm gewagt. Ein kreisrundes Gebilde an dem das Schieferdach fast vollständig verschwunden ist. Unzählige Federn und andere Exkremente zeugen davon, dass der Turm ein beliebter Treffpunkt von Tauben und anderen Vögeln sein muss.
		`nDie große Glocke hängt unbewegt in ihrer Halterung. Vom Grünspan fast gänzlich in selbiger Farbe gefärbt, sind nur noch einige, kleine Silberstellen und Einkerbungen zu erkennen. Sie muss lange nicht mehr geläutet haben. Der Dielenboden knirscht unter jedem Schritt der hier getan wird und man kann sich wohl nicht sicher sein, ob das Holz noch lange halten wird.
		`nDurch die kleinen offenen Seitenfenster kann man hinaus schauen und es ist, als könne man das ganze Umland erblicken.
		`nDie friedliche Stille dieses Ortes wird nur durch das Jaulen des Windes gestört. Doch kann dieses wohl kaum den fantastischen Ausblick trüben, den man von hier aus über den Wal`sd `8h`pa`gt.`n`n');
		viewcommentary('wald_glockenturm');

		//(würde das wenn auch gerne als reinen RP- Ort nutzen lassen )
		addnav('G?Glocke läuten',$filename.'?op=ringbell');
		addnav('Zurück');
		addnav('Zurück nach unten',$filename.'?op=inside_abbey');
		break;
	}

	case 'lichking':
	{
		output(get_title('`)Der Lichkönig').'`eMit einem Male beginnt die Luft um dich herum stetig kälter zu werden. Dein Atem, schon lange als weißer Rauch sichtbar, wird immer schneller und die feinen Härchen auf Arm und Nacken richten sich auf, um dich vor dem unnatürlichen Gefühl zu schützen - erfolglos. `7Mit jeder Sekunde die du hier verweilst, kriecht die Kälte etwas tiefer in deinen Körper und dein Herz hinein. Schließlich, nach wenigen Augenblicken oder unzähligen Sekunden, nimmst du aus dem Augenwinkel eine Bewegung wahr. `)Eine schlurfende Gestalt aus halb zerfallenen, einst königlichen Gewändern bewegt sich an dir vorbei. Die Gestalt ist dürr und eingefallen, aber strahlt noch immer eine längst vergangene majestätische Grazie aus. Es ist ein Liche. Körper und Seele einer armen Kreatur, die ihr Leben auf unsägliche Weise mit ihrem Körper verband, um eine perverse Form der Unsterblichkeit zu erlangen.`n`n
		Die Kälte und der modrige Geruch des Wesens lassen dich erschauern und mit einem Male steigt in dir ein Würgen herauf, das dich kurz aber merklich zucken lässt.`n`n`(Mit ungeahnter Geschwindigkeit wendet sich der Liche dir zu. In seinem eingefallenen, toten Gesicht glitzern die schwarzen Augen jung und wahnsinnig. Kein Ton ist zu vernehmen, doch scheint es so als ob du eingehend geprüft wirst, ja regelrecht gemustert.`n`n
		`)Glücklicherweise scheinst du keine Gefahr für den Liche darzustellen, denn nach wenigen Augenblicken wendet sich dieser ab und wandert nun wieder langsam in Richtung des Turmes, wo er nach einigen Sekunden verschwindet.`7 Erst nach etlichen Sekunden scheint die Starre aus deinem Körper zu verschwinden und die Härchen auf deinem Körper sich wieder anzulegen. Die Kälte, die noch eben dein Herz im Griff hielt, flaut nun langsam wieder ab.`7 Um diesem wahrhaft mächtigen Liche den Garaus zu machen, bedarf es wohl einiger besonderer Vorbereitungen. Aber welcher? Nun, zumindest weißt du, wo du ihn finden kannst... wenn du ihn denn jemals suchen solltest...`n`n`e');
		//Evtl den Lichking einfügen, wenn nicht, dann einen Hinweis darauf einfügen wie man zu diesem gelangt.
		if(item_count('owner='.$session['user']['acctid'].' AND tpl_id="analloni_s" AND deposit1=0')>0)
		{
			output('Da beginnt mit einem Male etwas in deiner Tasche sanft zu vibrieren. Du greifst ein wenig überrascht nach deinem Beutel und holst das Anallôni-Amulett heraus, das in lebendigen Farben pulsiert. Aber natürlich! Was sagte die alte Frau doch gleich? Das Anallôni-Amulett besitzt die Kraft selbst starke Geister zu bannen. Und wer, wenn nicht dieser Lichekönig, besitzt einen starken Geist?');

			include(LIB_PATH.'boss.lib.php');
			if(!boss_get_nav('lichking'))
			{
				output('`n`nDa gibt es nur noch ein Problem, nämlich deine eigene Unzulänglichkeit. Derart untrainiert solltest du keinesfalls dem Lichkönig entgegentreten, das würde ein böses Ende nehmen.');
			}
		}
		else
		{
			output('Wahrscheinlich benötigt man irgendein magisches Utensil, um wahrhaft starke Geister zu bannen, denkst du noch bei dir, doch schon hat der Raum um dich wieder deine volle Aufmerksamkeit errungen.');
		}
		addnav('Zurück');
		addnav('Zurück vor das Kloster',$filename.'?op=abbey');
	break;
	}

	case 'cave':
	{
		//- Höhle ->Eine Höhle, auch außerhalb des Gebirges, da ein Char da nicht so oft hinkommt. Gewünscht wurde zum Beispiel ein unterirdischer See in ebenjener.

		output(get_title('`(I`)n `7d`eer Felsenhö`7h`)l`(e').'
		`(D`)e`7n `eFelsabhang hast du hinter dir gelassen und bist einem scheinbar unendlich langen Weg durch völlige Dunkelheit in die Tiefe gefolgt. Schon nach den ersten Metern ward das Tageslicht längst verschluckt worden.
        `nAn einigen Abzweigungen bist du vorbei gekommen, doch hast du dich anscheinend immer richtig entschieden, denn nun eröffnet sich vor deinen Augen eine riesige Höhle. Stalaktiten ragen in die Tiefe, Stalagmiten erheben sich in die Höhe..
        `nDie Decke schimmert in unzähligen Blautönen. Und als du den Vorsprung hinab blickst, kannst du einen unterirdischen See erkennen. Das Wasser scheint klar und vielleicht ein bisschen kalt. Man kann bis zum Grund schauen. Kleine Kristalle reflektieren das anfallende Licht, woher es auch immer kommen mag und verleihen diesem Ort etwas mystisch Anmutendes.
        `nDen Felsweg hinab hat man schnell geschafft. Vielleicht war dies einst eine Zwergenmine? Am steinigen Ufer verweilst du, die Höhle noch ein wenig betrachtend. Das Gestein der Wände ist fast weiß und fühlt sich glatt und angenehm an. Wenn du empor blickst, kannst du die Höhlendecke erkennen. Du stellst fest, dass sich auch dort unzählige kleine Kristalle wie ein Sternenhimmel befinden und das Licht brechen. Das Plätschern des Wassers hallt laut durch diese Höhle, doch ist es seltsam beruhigend und entspannend.
        `nVielleicht ein schöner Ort für `fein Bad? Allein oder zu zweit`e, diesen bezaubernden Ort sollte man nicht ungenutzt lassen. Nach einem erstem Antesten merkst du sogar, dass das Wasser gar nicht so kalt ist. Und dort hinten, da scheint es einen wahrlich ruhigen Ort zu ge`7b`)e`(n.`n`n`n`n');
		viewcommentary('wald_hoehle');

		//(eventuell Kristalle mitnehmen? Vielleicht wie die Blinklichter zu Weihnachten in verschiedenen Farben – wertlos und nur zur Zierde? )
		addnav('Tiefer in die Höhle',$filename.'?op=upstairs');
		if ($session['hutpartner']!='')
		{
			addnav('Mit '.$session['hutname'].' baden',$filename.'?op=hot_springs');
		}
		if(!$session['daily']['farbkrist'] && $session['user']['weapondmg']>0 && mb_strpos($session['user']['weapon'],' -1')==false)
		{
			addnav('Kristall abkratzen',$filename.'?op=farbkrist');
		}
		addnav('Zurück');
		addnav('Zurück zum Felshang',$filename.'?op=cliff');
		break;
	}

	case 'hot_springs':
	{
		//- Grotte
		$chat = 'wald_'.min($session['user']['acctid'],$session['hutpartner']).'_'.max($session['user']['acctid'],$session['hutpartner']);

		output(get_title('`NE`(i`)n `7T`eh`serm`ea`7l`)b`(a`Nd').'
		`ND`(a`)s `7W`ea`ssser ist hier brusttief für einen ausgewachsenen Mann. Die Decke ragt weit hinab, und man kann sie gar berühren. Eine versteckte Höhlengrotte. Nicht besonders riesig im Ausmaß, reicht sie höchstens für zwei.
        `nEs gibt kleine Felsvorsprünge unter Wasser, auf die man sich bequem setzen kann. Viele kleine Lichtspiele lassen das Halbdunkel in einer romantischen Dimmung erscheinen. Ein Ort für Verliebte, die diese Abenteuerreise gewagt haben, sich nun entspannend einander widmen möchten und eine Pause einlegen wollen.
        `nEine kleinere heiße Quelle im unteren Teil sorgt für die angenehme Temperatur und dir wird bewusst, dass deshalb das Wasser nicht so kalt ist. Allerdings ist es hier, direkt an der Quelle noch um einige Grad wärmer und gleicht fast einem vollen Badezuber.
        `nHier kann man sich entspannen, abschalten und auftanken, um den langen Rückweg wieder auf sich nehmen zu k`eö`7n`)n`(e`Nn.`n`n');
		viewcommentary($chat,'Leise unterhalten',30,'flüstert',false,true,false,true,false,true,2);
		output('`n(Dieser Raum ist privat, kein störender Besucher kann euch hier belauschen)`n');

		//(Diesen Raum würde ich, wie die Hütte im Nebelwald Privat einstufen, wegen der zwangsläufigen/beabsichtigten Assoziationen)
		addnav('Zurück zum Eingang',$filename.'?op=cave');
		break;
	}

	case 'ritualplace':
	{
		//- Ritualplatz-> Nicht näher definiert, aber irgendein Ort für finstere Gesellen (zweite Abzweigung an der Weggabelung.

		output(get_title('`SD`me`Ur `uRitualpl`Ua`mt`Sz').'
		`SD`me`Ur `uWald ist immer dichter geworden, und langsam weiß man nicht mehr, wohin man gehen soll, denn der Pfad, den du eben noch gegangen bist, ist verschwunden. Doch du setzt deinen Weg tapfer in die Richtung fort, in die der Pfad führte.
		`nDer Nebel wird dichter und dichter, und es erscheint dir, als würde diesem Ort etwas Dunkles und Böses anhaften. Erst nach einer ganzen Weile erreichst du, eine Lichtung. Der Wald endet hier abrupt, wie eine Schneise, die eine dunkle Macht in das Gehölz geschlagen hat.
		`nDie Luft ist getränkt von der nebligen Feuchte, und Krähen lassen ihren Botenruf immer wieder erklingen, so dass es so einen Manchen schaudern lässt. Große Trauerweiden lassen blattlos, und von fauligem Grün bewachsen ihre Äste hängen. In der Mitte der Lichtung erkennst du einen Steinkreis und in dessen Mitte einen abgeflachten Stein, der mit merkwürdigen, uralten Symbolen bepflastert ist. Außerdem haften jenem einige dunkel Flecken an, und du bist dir sicher, dass dies ein Opferaltar ist &mdash; doch nicht zu guten Zwecken.
		`nDie finstere Atmosphäre legt sich schwer über die Glieder und man mag gar meinen, hunderte und aberhunderte Augen beobachten jede Bewegung die man macht. Des Nachts kann man hier sicher die Schreie der längst vergessenen Opfer wahrnehmen. Wer hier zu Opfern wagt, wird sicher verflucht, da bist du dir sicher, denn die dunklen Mächte, sind fast fassbar.
		`nIn der Ferne tönt ein unkiges Quaken. Ob man diesem nicht lieber nachge`Uh`mt`S?`n`n');
		viewcommentary('wald_ritualplatz');

		//(würde ich auch als reinen RPort anrechnen)
		addnav('Folge dem Quaken',$filename.'?op=moor');
		if($session['bufflist']['decbuff']['state']>0)
		{
			addnav($session['bufflist']['decbuff']['realname'].'`0 opfern',$filename.'?op=dbite',false,false,false,false,'Achtung! Dies macht deinen Knappen zu einem reinen RPG-Knappen, der absolut nichts kann und auf Level 0 bleibt! Willst du das wirklich?');
		}
		addnav('Zurück');
		addnav('Zurück zur Weggabelung',$filename.'?op=leak');
		break;
	}

	case 'moor':
	{
		//- Moor

		output(get_title('`mD`Ta`Ss Mo`To`mr').'
		`mD`Ta`Sher stammte also das Quaken. Du stehst schon knöcheltief im Morast, und wenn man nicht aufpasst wird es einen sicher verschlingen. Der Nebel ist dicht, und die Luft feucht, und geschwängert vom erdigem Geruch des Moors. Bäumstämme sind erkennbar, die halb im Schlick versunken, und nur auf einigen trockenen Stellen haart die ein oder andere Weide, trauernd die langen Äste hinab hängen lassend. Gelbliche, formlose Gräser, sind alles, was hier zu wachsen scheint.
		`nWenn man nicht vorsichtig ist und seinen Weg sicher über die trockenen Abschnitte setzt, wird man verschluckt und erstickt jämmerlich in der zähflüssigen Masse.
		`nEigentlich hattest du dir ein Moor immer freundlich und bewachsen vorgestellt, doch dieses ist vollkommen anders, trist und kalt. Wie vielen Menschen und anderen Kreaturen es wohl schon den Tod gebracht hat? Darüber denkt man am besten nicht nach, vor allem nicht, wenn man einen Opferplatz im Nacken h`Ta`mt.`n`n');
		viewcommentary('wald_moor');

		//(auch nur RPOrt)
		addnav('Zurück zum Ritualplatz',$filename.'?op=ritualplace');
		break;
	}

	case 'upstairs': // Wozu das Rad neu erfinden? Privates Obergeschoss vom Nebelgebirge
	{
		if($_GET['act'] == 'search' && mb_strlen($_POST['search']) > 0)
		{
			$search = str_create_search_string($_POST['search']);
			$sql = 'SELECT name,acctid FROM accounts WHERE name LIKE "'.$search.'" AND acctid!='.$session['user']['acctid'].' ORDER BY login="'.db_real_escape_string($_POST['search']).'" DESC, login ASC';
			$res = db_query($sql);
			$link = $filename.'?op=upstairs&act=id';
			output('<form action="'.$link.'" method="POST">');
			output(' <select name="ziel">');

			while ( $p = db_fetch_assoc($res) )
			{
				output('Mit <option value="'.$p['acctid'].'">'.strip_appoencode($p['name'],3).'</option>');
			}

			output('</select>`n`n');
			output('<input type="submit" class="button" value="baden"></form>');
			addnav('',$link);
			addnav('Doch nicht');
			addnav('Zurück zum Eingang',$filename.'?op=cave');
		} // Ende if

		elseif($_GET['act'] == 'id' && $_POST['ziel'])
		{
			$ziel = (int)$_POST['ziel'];
			$sql = 'SELECT name FROM accounts WHERE acctid='.$ziel;
			$res = db_query($sql);
			$name = db_fetch_assoc($res);

			$session['hutpartner']=$ziel;
			$session['hutname']=$name['name'];

			redirect($filename.'?op=hot_springs');

		} // Ende elseif

		else
		{

			output('`c`b`NV`(o`)r `7d`ee`sm Therm`ea`7l`)b`(a`Nd`b`c
			`n`NS`(o`)s`7o, `ed`su willst also ganz... ungestört sein? Mit wem willst du ins warme Wasser st`ee`7i`)g`(e`Nn?`n`n');
			$link = $filename.'?op=upstairs&act=search';
			output('<form action="'.$link.'" method="POST">');
			output('`sName eingeben: <input type="input" name="search">');
			output('`n`n');
			output('<input type="submit" class="button" value="Suchen"></form>');
			addnav('',$link);
			addnav('Doch nicht');
			addnav('Zurück zum Eingang',$filename.'?op=cave');
		} // Ende else

		break;

	} // Ende Obergeschoss

	case 'writestone': //den Stein an der Weggabelung bekritzeln
	{
		$str_stonetext=stripslashes(getsetting('wald_steintext',''));
		if(isset($_POST['message']) && $_POST['message']!='') //Geschriebenes prüfen und in Settings einfügen, WK abziehen
		{
			$str_message=stripslashes($_POST['message']);
			$int_strlen=mb_strlen($str_message);
			for($i=0; $i<$int_strlen; $i++)
			{
				$char=mb_substr($str_message,$i,1);
				if($char==' ') //Leerzeichen ohne WK-Abzug
				{
					$str_newtext.=' ';
					$wordwrap=0;
				}
				elseif($char=='`') //logd-Codes rauswerfen
				{
					$bol_cont=true;
				}
				elseif($bol_cont)
				{
					$bol_cont=false;
					if($char=='n') //Zeilenumbruch jedoch erlauben
					{
						$str_newtext.='`n';
					}
				}
				elseif($int_turns < $session['user']['turns']*2)
				{
					if($wordwrap>=30)
					{
						$str_newtext.=' ';
						$wordwrap=0;
					}
					$str_newtext.=utf8_htmlspecialchars($char);
					$int_turns++;
					$wordwrap++;
				}
			}
			$str_stonetext.=$str_newtext.' ';
			if(mb_strlen($str_stonetext)>1000)
			{
				$str_stonetext=mb_substr($str_stonetext, -1000);
			}
			$int_turns=ceil($int_turns/2);
			output('Du opferst '.$int_turns.' Waldkämpfe, um etwas in den Stein zu ritzen.');
			$session['user']['turns']-=$int_turns;
			savesetting('wald_steintext',($str_stonetext));
			debuglog('ritzte in den Stein: '.$str_newtext);
		}
		else //Text beim Betreten
		{
			output('Hier hast du die Möglichkeit, etwas in den Stein zu ritzen. Jedoch wird dich jeder Buchstabe die `4Zeit für einen halben Waldkampf`0 kosten.');
		}

		output('`nBis jetzt steht auf dem Stein'.($str_stonetext!='' ? ':`n`n`&...'.$str_stonetext : ' noch nichts.'));
		if($session['user']['turns']>0)
		{
			$maxlength=floor($session['user']['turns']*2.2);
			output('`0`n`nWas willst du ritzen? (Text wird hinter den vorhergehenden geschrieben, neue Zeile mit &#96;n)
			<form action="'.$filename.'?op=writestone" method="post">
			<input name="message" size='.min($maxlength,80).' maxlength='.$maxlength.'>
			`n<input type="submit" class="button" value="Ritzen">
			</form>');
			addnav('',$filename.'?op=writestone');
		}
		else
		{
			output('`0`n`nLeider bist du schon zu müde um heute noch etwas zu ritzen.');
		}
		addnav('Nördlicher (dunkler) Weg',$filename.'?op=ritualplace');
		addnav('Südlicher (lichter) Weg',$filename.'?op=abbey');
		if ($access_control->su_check(access_control::SU_RIGHT_GAMEOPTIONS))
		{
			addnav('Admin');
			addnav('Text löschen','su_configuration.php?op=notlisted',false,false,false,false);
		}
		addnav('Zurück');
		addnav('Zurück zum Felshang',$filename.'?op=cliff');
		break;
	}

	case 'farbkrist': //einen Kristall aus der Wand der Felsenhöhle herausbrechen
	{
		$number=e_rand(1,7);
		switch ($number)
		{
			case 1:
				$str_color='`ygelblich';
				break;
			case 2:
				$str_color='`drötlich';
				break;
			case 3:
				$str_color='`pgrünlich';
				break;
			case 4:
				$str_color='`fsilbern';
				break;
			case 5:
				$str_color='`*bläulich';
				break;
			case 6:
				$str_color='`&glasklar';
				break;
			case 7:
				$str_color='`xpinkfarben';
				break;
		}
		$item['tpl_name']='ein '.$str_color.'er Kristall`0';
		$item['tpl_description']='Ein '.$str_color.'er Kristall`0, welchen du aus der Felsenhöhle in den östlichen Wäldern mitgenommen hast. Er sieht zwar schön aus, scheint aber sonst zu nichts nütze.';
		item_add($session['user']['acctid'],'farbkrist',$item);
		output(get_title('`eAlles meins!').'`eDir ist es völlig schnuppe, ob andere diese Höhle vielleicht auch so schön vorfinden wollen, hauptsache du bekommst einen Kristall. Es gibt ja genug davon... Also setzt du deine '.$session['user']['weapon'].'`e an einen '.$str_color.'en Kristall`e und hebelst ihn aus dem Felsen. Es macht leise \'Knack\' und der Kristall fällt dir in die Hände.');
		if(e_rand(1,5)==3)
		{
			output('`n`n`&KNACK?!`e Du betrachtest deine Waffe. Verdammt, sie ist tatsächlich durch diese unsachgemäße Behandlung beschädigt worden.');
			$name = $session['user']['weapon'].' -1';
			$skill = $session['user']['weapondmg'] - 1;
			$val = $session['user']['weaponvalue'] * 0.75;
			item_set_weapon($name, $skill, $val, 0, 0, 1);
		}
		addnav('Zurück zum Eingang',$filename.'?op=cave');
		$session['daily']['farbkrist']=1;
		break;
	}

	case 'darklands': //Die Grenze zu den Dunklen Landen
	{
		function kyrillize($string)
		{
			$arr_search=array(
			'ach'=>'&#1072;&#1093;',
			'sch'=>'&#1096;',
			'ch'=>'&#1093;',
			'ck'=>'&#1082;',
			'ie'=>'&#1080;&#1081;',
			'ja'=>'&#1103;',
			'je'=>'&#1077;',
			'ji'=>'&#1081;',
			'jo'=>'&euml;',
			'ju'=>'&#1102;',
			'ss'=>'&#1079;',
			'ts'=>'&#1094;',
			'a'=>'&#1072;',
			'b'=>'&#1073;',
			'c'=>'&#1094;',
			'd'=>'&#1076;',
			'e'=>'&#1101;',
			'f'=>'&#1092;',
			'g'=>'&#1075;',
			'h'=>'&#1093;',
			'i'=>'&#1080;',
			'j'=>'&#1081;',
			'k'=>'&#1082;',
			'l'=>'&#1083;',
			'm'=>'&#1084;',
			'n'=>'&#1085;',
			'o'=>'&#1086;',
			'p'=>'&#1087;',
			'qu'=>'&#1082;&#1074;',
			'r'=>'&#1088;',
			's'=>'&#1089;',
			't'=>'&#1090;',
			'u'=>'&#1091;',
			'v'=>'&#1074;',
			'w'=>'&#1074;',
			'x'=>'&#1082;&#1089;',
			'y'=>'&#1099;',
			'z'=>'&#1094;',
			'ä'=>'&#1101;',
			'ö'=>'&#1086;',
			'ü'=>'&#1080;',
			'ß'=>'&#1097;',
			'Sch'=>'&#1064;',
			'Ch'=>'&#1061;',
			'Ja'=>'&#1071;',
			'Je'=>'&#1045;',
			'Ji'=>'&#1049;',
			'Jo'=>'&Euml;',
			'Ju'=>'&#1070;',
			'Ts'=>'&#1062;',
			'A'=>'&#1040;',
			'B'=>'&#1041;',
			'C'=>'&#1062;',
			'D'=>'&#1044;',
			'E'=>'&#1069;',
			'F'=>'&#1060;',
			'G'=>'&#1043;',
			'H'=>'&#1061;',
			'I'=>'&#1048;',
			'J'=>'&#1049;',
			'K'=>'&#1050;',
			'L'=>'&#1051;',
			'M'=>'&#1052;',
			'N'=>'&#1053;',
			'O'=>'&#1054;',
			'P'=>'&#1055;',
			'Qu'=>'&#1050;&#1074;',
			'R'=>'&#1056;',
			'S'=>'&#1057;',
			'T'=>'&#1058;',
			'U'=>'&#1059;',
			'V'=>'&#1060;',
			'W'=>'&#1042;',
			'X'=>'&#1050;&#1089;',
			'Y'=>'&#1067;',
			'Z'=>'&#1062;',
			'\''=>'&#1100;',
			'Ä'=>'&#1069;',
			'Ö'=>'&#1054;',
			'Ü'=>'&#1048;',
			'`&#1085;'=>'<br>'
			);
			foreach($arr_search as $lat => $kyr)
			{
				$string = str_replace($lat, $kyr, $string);
			}
			return($string);
		}
		$string=get_title(kyrillize('Die Grenze'));
		$string.=kyrillize('Nach einer langen Reise gelangst du an eine Stelle, wo ein rot-weiß angemalter Baum als Schranke quer über den Weg gelegt wurde, der fon bewaffneten Soldaten umstellt ist.
		`nEiner der Soldaten fragt dich: "Stoi! Wohin wollt Ijr gehen, '.($session['user']['sex']?'dorogaja padruga':'dorogoj drug').'?"
		`nDu überlegst kurz, was du antworten sollst.');
		if(isset($_POST['answer']) && $_POST['answer']>'')
		{
			$string.='`n`nDu antwortest: `&'.kyrillize(strip_appoencode($_POST['answer']).'`0
			`n`nAber für die Soldaten ist dein Grund nicht wichtig genug. Sie lassen dich nicht weiter reisen.');
		}
		output($string.'
		`n`n<form action="'.$filename.'?op=darklands" method="post">
		<input type="text" name="answer">
		<input type="submit" class="button" value="&#1040;&#1085;&#1090;&#1074;&#1086;&#1088;&#1090;&#1101;&#1085;">
		</form>');
		addnav('',$filename.'?op=darklands');

		addnav('Zurück',$filename.'?op=deepforest');
		break;
	}

	case 'lumber': //Feuerholz für den Kamin machen
	{
		$str_output  = get_title('`tHolz fällen');
		if($_GET['work'] != 1)
		{
			$str_output .= '`TDu schulterst deine Axt und gehst auf einige der gekennzeichneten Bäume zu. Dort angekommen suchst du dir einen passenden Baum aus und beginnst, ihn mit der Axt zu bearbeiten. Wie lange möchtest du daran arbeiten?';
			$str_output .= form_header($filename.'?op=lumber&work=1').
				generateform(
					array('lumber_duration'=>'Wieviele Runden möchtest du arbeiten?,int,focus'),array(),false,'Bestätigen'
				).
			'</form>';
		}
		else
		{
			$int_lumber_duration = abs((int)$_POST['lumber_duration']);
			$int_lumber_duration = min($int_lumber_duration,$session['user']['turns']);
			$session['user']['turns'] -= $int_lumber_duration;
			$int_lumber_amount = floor($int_lumber_duration/2);

			if($int_lumber_duration>0)
			{
				$arr_item = item_get('owner = '.$session['user']['acctid'].' AND tpl_id="feuerholz"');

				if($arr_item !== false)
				{
					$arr_item['gold']	+= 10*$int_lumber_amount;
					$arr_item['value1']	+= $int_lumber_amount;
					item_set('id='.$arr_item['id'],$arr_item);
				}
				else
				{
					item_add($session['user']['acctid'],'feuerholz',array('gold'=>10*$int_lumber_amount,'tpl_value1'=>$int_lumber_amount));
					$arr_item['value1']	= $int_lumber_amount;
				}

				$str_output .= '`TDu arbeitest lang und hart und das Ergebnis deiner Arbeit kann sich sehen lassen. Mit der Zeit hast du einiges an Holz gesammelt, so dass sich `0'.$int_lumber_amount.'`T Holzscheite ansammeln. Damit trägst du nun insgesamt `0'.$arr_item['value1'].'`T bei dir.';

				addnav('Mehr Holz fällen',$filename.'?op=lumber');
			}
			else
			{
				$str_output.='`TObwohl du selbst kaum noch stehen kannst versuchst du, etwas Feuerholz zu machen. Es kommt wie es kommen muss, die Axt gleitet dir aus der Hand.
				`n`0Weißt du eigentlich wie gefährlich das ist!? Für heute solltest du das Holzfällen besser sein lassen.';
			}
		}
		output($str_output);
		addnav('Zurück',$filename);
		break;
	}

	case 'lumbermill': //Sägemühle (reiner RPG-Ort)
	{
		output('`c`b`tDie Holzmühle`0`b`c
		`n`n`&Du folgst dem Flusslauf und gelangst zu einer Sägemühle.
		`nEindrucksvoller Text der die Sägemühle beschreibt... Und wo erwähnt wird dass der Besitzer der schwerkranke Michel ist *g*
		`n`n`@auch Kleinholz machen.`n`n');
		viewcommentary('Sägewerk','Hinzufügen',10,'sagt');
		addnav('Zurück zur Kreuzung',$filename);
		break;
	}

	case 'analloni': //jeden Monat eine Anallôni-Blüte sammeln
	{
		$arr_item = item_get('owner='.$session['user']['acctid'].' AND tpl_id="analloni_f"');
		$arr_item['content'] = utf8_unserialize($arr_item['content']);
		$indate = getsetting('gamedate','0005-01-01');
		$date = explode('-',$indate);
		$monat = (int)$date[1];
		$arr_months = array(1=>'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');

		$str_output .= get_title('`GD`gi`pe `8A`/nallôni-Pfl`/a`8n`pz`ge`Gn');
		$str_output .= '`GD`gu `pl`8ä`/ufst über ein Feld, welches über und über mit den verschiedensten Blumensorten bedeckt ist und denkst bei dir, dass es wohl keinen besseren Platz für die seltenen Anallôni-Blüten geben kann außer `bdiesem verdammten Feld voll anderer Blumen`b...`n
						Aber wer Sud brauen will muss leiden. Folglich kniest du dich mitten in die schönen Blumen und beginnst akribisch zu suchen.`n`n';
		if(is_array($arr_item['content']))
		{
			$arr_flower_names = array_keys($arr_item['content']['blossoms']);
		}
		else
		{

		}
		if($arr_item['content']['blossoms'][ $arr_flower_names[$monat-1] ] == true)
		{
			$str_output .= 'So sehr du dich auch anstrengst, leider findest du nicht eine einzige Blüte. Es scheint wie verhext. Glücklicherweise verrät dir der absichernde Blick in deinen Beutel, dass du die Sorte Anallôni, die im `y'.$arr_months[$monat].'`t blüht, bereits besitzt. Nun gut, dann eben nächsten Monat w`/i`8e`pd`ge`Gr...';
			$session['user']['turns'] = max(0,$session['user']['turns']-1);
		}
		else
		{
			$str_output .= 'So sehr du dich auch anstrengst, leider findest du ni- halt, da hinten, dieses kleine, unscheinbare...Juchuuu! Du hast eine Anallôni-Blüte gefunden - eine einzige Blüte! Es handelt sich um die `y'.$arr_flower_names[$monat-1].'`t `/B`8l`pü`gt`Ge.';
			$arr_item['content']['blossoms'][ $arr_flower_names[$monat-1] ] = true;
			$arr_item['content']['count']++;
			$arr_item['content'] = db_real_escape_string(utf8_serialize($arr_item['content']));
			item_set('id='.$arr_item['id'],$arr_item);
			$session['user']['turns'] = max(0,$session['user']['turns']-3);
		}
		output($str_output);
		addnav('Zurück',$filename);

		break;
	}

	case 'dbite': //Knappen zum RPG-Knappen machen
	{
		$sql = 'SELECT name,state,level FROM disciples WHERE master='.$session['user']['acctid'];
		$result = db_query($sql);
		$rowk = db_fetch_assoc($result);
		output(get_title('`SE`mi`Un `uMenschenop`Uf`me`Sr').'
		`SD`mu `Uü`uberredest '.$rowk['name'].'`U dazu, auf dem Opferstein Platz zu nehmen. Er sträubt sich zwar dagegen, aber dein '.$session['user']['weapon'].'`U ist dann doch das bessere Argument. Wenig später ist '.$rowk['name'].'`U auf dem Stein fixiert.`n`n');
		if($rowk['state']>0 && $rowk['state']!=22) //überhaupt ein beißfähiger Knappe da?
		{
			output('Dann versetzt du dich in Trance, bis du vermeintlich die Stimmen der spirituellen Mächte hören kannst, die dir befehlen, '.$rowk['name'].'`U den Schädel einzuschlagen. Ohne einen weiteren Gedanken tust du das dann auch...
			`n`nAls du wieder Herr deiner Sinne bist, siehst du dir an, was du getan hast.
			`nAuf dem Opferstein liegt der völlig blutverschmierte '.$rowk['name'].'`U. Er lebt zwar noch, schaut dich aber irgendwie ... geistesabwesend ... an.
			`nNa prima, dein einst so '.get_disciple_stat($rowk['state'],'er').' Knappe ist nur noch ein Hohlkörper, den niemand freiwillig mitnehmen würde...
			`nErzähl das bloß niemandem in der Stadt, am Ende kommst du noch wegen versuchten Mordes in den Ker`Uk`me`Sr!');
			unset($session['bufflist']['decbuff']);
			$rowk['state']=22;
			$rowk['level']=0;
		$sql = 'UPDATE disciples SET state='.$rowk['state'].', level='.$rowk['level'].' WHERE master = '.$session['user']['acctid'];
		db_query($sql);
		debuglog('hat jetzt einen autistischen Knappen');
		}
		else
		{
			output('Du betrachtest eingehend deinen '.get_disciple_stat($rowk['state']).' Knappen und kommst zu dem Ergebnis, der taugt nichtmal zum Opfern...');
		}
		addnav('Zurück zum Ritualplatz',$filename.'?op=ritualplace');
		break;
	}

	case 'grassyfield': //- selber RPG-Ort wie Specialevent grassyfield
	{
		if (date('m')>=11 || date('m')<=2)
		    {
				output(get_title('`sD`fie F`&rühstückswi`fes`se').' 
				`n`c`fDu stolperst auf eine verschneite Lichtung.`0`c
				`n`n`sE`fin `&wenig verwundert blinzelst du, denn während im Wald die dicht stehenden Bäume dafür sorgen, dass nur ein Bruchteil des Sonnenlichts zum Erdboden gelangt, hast du hier den Himmel direkt über dir. Die Lichtung ist ein idyllischer Ort, der unberührte Schnee glitzert im Sonnenschein.
				Im Sommer würde dieser Ort sicherlich dazu einladen, sich hier eine Weile aufzuhalten, doch gerade ist es leider zu eisig, um länger draußen zu bleiben. Es sei denn, dich schreckt die Kälte nicht, dann kannst du den Zauber dieses Ortes auch nun ein wenig länger genießen. Vielleicht eignet sich die Lichtung ja auch für eine fröhliche Schneeballschla`fch`st...?`n`n');			
				}
		
		else
				{
				output (get_title('`jD`@i`Ge F`grühstücksw`Gie`@s`je').'
				`n`c`gDu stolperst auf eine grasbewachsene Lichtung`0`c');
				
				output (Weather::get_weather_text('Wiese'));
				}
			
			viewcommentary("grassyfield","Hinzufügen",10,"sagt");
			addnav("Zum kleinen grünen Burschen","leprechaun.php");
			addnav('Zurück in den Wald','forest.php');
			break;
	}

	default:
	{
		output(get_title('`uE`Ii`tn`ye Kreuz`tu`In`ug').'
		`uN`Ia`tc`yh einem kurzen Fußmarsch erreichst du eine Kreuzung und musst dich nun entscheiden, in welche Richtung du deine Reise fortsetzen willst. Leicht fällt dir dies nicht, schließlich hat jede Himmelsrichtung etwas anderes zu bieten.');
		
			output('`n
			`nNach `INorden`y erstreckt sich ein von Muscheln gesäumter Sandweg, der direkt zum Meer führt. Fast kannst du Salz auf den Lippen schmecken und die Möwen kreischen hören, allerdings entspringen diese Eindrücke ganz eindeutig deiner Phantasie, befindet sich die See doch noch viele Stunden Fußmarsch entfernt.
			`n
			`nEin matschiger Feldweg verläuft nach `IOsten`y. Dort liegt ein dunkler Wald, in dem sich ein verlassenes Kloster befinden soll. Auch die Grenzen zu den Dunklen Landen ist nicht fern.
			`n
			`nIm `ISüden`y erstreckt sich in einiger Entfernung ein Gebirge, dessen Bergspitzen in die Nebelwolken eintauchen. Unweit der Kreuzung kannst du einen kleinen Abzweig in den dunklen Wald erkennen'.($session['user']['marks'] < CHOSEN_FULL ? ', der dir jedoch nicht geheuer scheint' : '').'.
			`n
			`nIm `IWesten`y liegt der dir bereits allzu vertraute Teil des Waldes und deine Heimatstadt Atr`ya`th`Io`ur.
			`n`n');

			addnav('Norden');
			if(access_control::is_superuser())
			{
				addnav('H?Zur Holzmühle(SU)',$filename.'?op=lumbermill');
			}
			addnav('M?Zum Meer','hafen.php');

			addnav('Osten');
			addnav('Tiefer dunkler Wald',$filename.'?op=deepforest');
			if ($access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ENTER) || $Char->expedition>0)
			{
				addnav('Expedition','expedition.php');
				if($access_control->su_check(access_control::SU_RIGHT_COMMENT)) addnav('Steintest(SU)',$filename.'?op=writestone');
			}
			else
			{
				addnav('Expedition','expedition_guest.php');
			}

			addnav('Süden');
			addnav('Gebirge (15 Meilen)','nebelgebirge.php');
			if ($Char->marks >= CHOSEN_FULL)
			{
				addnav('k?Der dunkle Pfad','thepath.php');
			}

			addnav('Westen');

			if(item_count('owner='.$session['user']['acctid'].' AND tpl_id="axt"')>0)
			{
				addnav('Holz fällen',$filename.'?op=lumber');
			}
			if(item_count('owner='.$session['user']['acctid'].' AND tpl_id="analloni_f"')>0)
			{
				addnav('Anallôni suchen',$filename.'?op=analloni');
			}
		
		
		addnav('Zum Wald von '.getsetting('townname','Atrahor'),'forest.php');
	}
}

addnav('Stadtzentrum von '.getsetting('townname','Atrahor'),'village.php');
page_footer();
?>
