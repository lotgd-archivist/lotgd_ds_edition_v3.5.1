<?php
//Ein Array für die Opfer
//Mal ein wenig mit dem Namegenerator drüber gegangen um es größer zu machen
$folter_opfer = array('Knari', 'Halgar', 'Fari', 'Dangar', 'Thonnorek', 'Drorreid', 'Arim', 'Lyngvild', 'Hroki', 'Hroa', 'Aldi', 'Inguring', 'Gallding', 'Hiding', 'Gladrod', 'Eidleid', 'Guntar', 'Hjar', 'Hlothor', 'Bodmir', 'Heirkar', 'Ednitild', 'Knorri', 'Skulvar', 'Bostar', 'Greki', 'Hring', 'Ermir', 'Hirunn', 'Vaerek', 'Gakning', 'Sjorrolf', 'Borgny', 'Ottorek', 'Vaerrek', 'Lodir', 'Fjotlod', 'Onrjolf', 'Skuding', 'Haug', 'Horod', 'Geirir', 'Torir', 'Ronar', 'Skurolf', 'Bostein', 'Thrastlod', 'Hadrglund', 'Helgi', 'Etir', 'Glami', 'Frotti', 'Hastlof', 'Gjafrlod', 'Allof', 'Staki', 'Dryld', 'Hlaf', 'Thorrolf', 'Geigrid', 'Thorming', 'Kralfrir', 'Drild', 'Skunar', 'Thjaeund', 'Aeri', 'Svari', 'Fjoldir', 'Eitoki', 'Drostild', 'Hland', 'Sognar', 'Asludvar', 'Hirkmar', 'Grurlof', 'Hauk', 'Otrir', 'Fraeuld', 'Relksin', 'Sild', 'Hraki', 'Hagnauma', 'Hruki', 'Bridi', 'Gadring', 'Gauding', 'Kandar', 'Ingoti', 'Rolod', 'Haer', 'Grimsin', 'Hrollod', 'Alrid', 'Hjolgeir', 'Barri', 'Skjoll', 'Smelf', 'Snarri', 'Ilmgerd', 'Grundi', 'Cinarneg', 'Linneg', 'Hanas', 'Zîn', 'Phîn', 'Thanneg', 'Ranangad', 'Hlothraza', 'Lunaranda', 'Bingi', 'Bulban', 'Hîran', 'Barunas', 'Bindali', 'Talban', 'Othrangad', 'Kudnas', 'Sinnazîra', 'Sindab', 'Tatta', 'Batti', 'Tarni', 'Brân', 'Binganâtha', 'Karni', 'Ganur', 'Gamba', 'Branda', 'Bagamba', 'Caban', 'Bunga', 'Nîn', 'Galaban', 'Randa', 'Tûn', 'Bralda', 'Sûnur', 'Biata', 'Zîra', 'Banas', 'Kali', 'Zanas', 'Bunas', 'Simban', 'Tîn', 'Tûca', 'Canuladyan', 'Ziralas', 'Tapurnîn', 'Trama', 'Than', 'Halc', 'Lothrama', 'Puran', 'Zaranas', 'Tûk', 'Siramac', 'Sûza', 'Thanduk', 'Hlotho', 'Nâr', 'Baruntar', 'Rama', 'Handurâpha', 'Zaram', 'Nalc', 'Nân', 'Ralda', 'Basi', 'Brama', 'Gaban', 'Râpha', 'Sûribul', 'Haramac', 'Zarulib', 'Haran', 'Bas', 'Traza', 'Simac', 'Phungad', 'Batta', 'Hîma', 'Rinîn', 'Nâtha', 'Lin', 'Tar', 'Tîninur', 'Binas', 'Trâph', 'Rân', 'Baralas', 'Ban', 'Zinarân', 'Haranas', 'Sûran', 'Phûn', 'Zarannîn', 'Phuran', 'Baneg', 'Rattûca');

//Mischen wir das Array eben durch, damit wir nachher auch garantiert immer wieder mal was neues haben.
shuffle($folter_opfer); 

//Das $folterwerkzeug-Array enthält alle Informationen zu den unterschiedlichen Folterwerkzeugen. Wird automatisch ausgelesen.
//Die ID entspricht dem Item Template
$folterwerkzeug = array();

$folterwerkzeug['flterneu']['id']           = 'flterneu';
$folterwerkzeug['flterneu']['name']         = 'Streckbank';
$folterwerkzeug['flterneu']['beschreibung'] = '`TGrinsend schnappst du dir %victim% `Taus einem umliegenden Käfig und beginnst langsam damit, ihn auf die Streckbank zu spannen. Das Ganze beginnt dir richtig Spass zu machen, und du drehst und ziehst an Rädern und Hebeln, wie es dir gerade passt, um langsam, doch sicher die Knochen aus den Gelenken zu lösen. Wirklich vortrefflich!';
                                    
$folterwerkzeug['esrjngfr']['id']           = 'esrjngfr';       
$folterwerkzeug['esrjngfr']['name']         = 'Eiserne Jungfrau';  
$folterwerkzeug['esrjngfr']['beschreibung'] = '`TMit einem geübten Blick wählst du dir %victim% `T, und führst ihn zu dem großen Metallkasten. Langsam beginnst du schließlich, den Deckel zu schließen, und bald schon tröpfeln auch die ersten Blutstropfen heraus. Von einer entsprechenden Geräuschekulisse unterstrichen, kannst du dir lebhaft vorstellen, wie der Eingesperrte wohl aussieht. Die Dornen dringen nicht tief genug ein, um dein Opfer zu töten, sondern nur so tief, dass es möglicherweise einem qualvollem Verbluten erliegt. ';
 									 
$folterwerkzeug['khlbck']['id']				= 'khlbck';
$folterwerkzeug['khlbck']['name']			= 'Kohlebecken';
$folterwerkzeug['khlbck']['beschreibung']	= '`TDa du schon immer äußerst viel Spaß mit heißen Gegenständen gehabt hast, schnappst du dir kurzerhand einen glühenden Kohlebrocken, und beginnst genüsslich %victim% `Tzu "verwöhnen". Hier und da setzt du die glühende Spitze an, um das Fleisch zu versengen, dessen Geruch in deiner Nase kitzelt. `nWas für ein Spaß!';

$folterwerkzeug['flterbnk']['id']			= 'flterbnk';
$folterwerkzeug['flterbnk']['name']			= 'Folterbank';
$folterwerkzeug['flterbnk']['beschreibung']	= '`TAngenehm anzusehen, wie %victim% `Tdort auf der hölzernen Bank festgeschnallt ist. Die Lederriemen so straff gezogen, dass sie bei zu viel Bewegung sich ins Fleisch schneiden. Neben der Bank ein kleiner Holztisch, auf dem dir allerlei Utensilien zur Verfügung stehen. Vom einfachem Dolch, bis zu langen Nadeln ist alles dabei. Das könnte amüsant werden! ';

$folterwerkzeug['dmschrsrt']['id']				= 'dmschrsrt';
$folterwerkzeug['dmschrsrt']['name']			= 'Daumenschrauben';
$folterwerkzeug['dmschrsrt']['beschreibung']	= '`TVoller Vorfreude spannst du die Daumen von %victim% `Tin die Zwinge. Langsam, bösartig ziehst du die Windungen immer enger, ehe das erste Knacken von Knochen ertönt, und du dich an den Schmerzensschreien deines Opfers erfreuen kannst. Vielleicht bleiben Schäden zurück, die nicht mehr heilen, was den Spaß an der ganzen Sache nur noch erhöht, und es gibt ja noch mehr Finger, die man einspannen kann!';

$folterwerkzeug['ptsch']['id']				= 'ptsch';
$folterwerkzeug['ptsch']['name']			= 'Peitsche';
$folterwerkzeug['ptsch']['beschreibung']	= '`TDu erfasst gnadenlos die hübsche Riemenpeitsche, mit den straff geflochtnen Tauenden. Dein Blick erfasst %victim% `Twährend du ausholst. Der Aufprall ist Musik in deinen Ohren und du lässt die Peitsche immer wieder nieder sausen, auf das die Haut mit Striemen markiert, und gar hier und da aufspringt. Der Geruch des Blutes lockt dich fort zu fahren und dich der Ekstase des Peitschentanzes hinzugeben. ';

$folterwerkzeug['esnschll']['id']			= 'esnschll';
$folterwerkzeug['esnschll']['name']			= 'Eisenschellen';
$folterwerkzeug['esnschll']['beschreibung']	= '`TDie Eisenschellen um die Hand – und Fußgelenke %victim% `Ts sind fest verschraubt. Tief die Verankerungen in die Wand eingelassen, so dass dir dein Spielkamerad sicher nicht entfliehen kann. Du hast also genügend Zeit dir deine Werkzeuge auszusuchen und sie ausgiebig zu testen. Was verursacht wohl die größeren Schmerzen? Die Klinge oder das flüssige Eisen?';

$folterwerkzeug['blackquill'] ['id']		= 'blackquill';
$folterwerkzeug['blackquill'] ['expose']	= false;
$folterwerkzeug['blackquill'] ['name']		= '`~schwarze `7Feder`0';
$folterwerkzeug['blackquill'] ['beschreibung'] = '`TDie Feder ist vielleicht nicht eines der traditionellsten Folterinstrumente, aber mit Sicherheit nicht weniger effektiv, fordert sie doch dem Folterknecht eine hohe Ausdauer ab und beschert dem Opfer schiere Grauen. Muskelkrämpfe, vom hysterischen Lachen trockene und rissige Stimmbänder. Ein herrliches Spielzeug!';

//Ja sorry, ein paar Gags müssen sein
$folterwerkzeug['towel'] ['id']				= 'towel';
$folterwerkzeug['towel'] ['expose']			= false;
$folterwerkzeug['towel'] ['name']			= 'Badehandtuch';
$folterwerkzeug['towel'] ['beschreibung'] 	= '`TMit einem hinterhältigen Grinsen tauchst du das frische `bBadehandtuch`b in einen Kessel voll eiskaltem Wasser und lässt es danach einige Sekunden um die eigene Achse kreisen, bevor du es mit lautem Schnalzen %victim% `Tauf den nackten Arsch klatschen lässt.';
							 
$folterwerkzeug['squirra'] ['id']			= 'squirra';
$folterwerkzeug['squirra'] ['expose']			= false;
$folterwerkzeug['squirra'] ['name']			= '`tKiller-Eichhörnchen`0';
$folterwerkzeug['squirra'] ['beschreibung'] = '`TFür %victim% `Thast du dir heute eine ganz besondere Gemeinheit ausgedacht. Du greifst vorsichtig in eine der Innentaschen deines Gewandes und holst ein agressiv sabberndes `bKiller-Eichhörnchen`b hervor. Oh wie es %victim% `Tmit seinen scharfen Zähnchen und Klauen zerkratzen wird... ein Fest für die Sinne!';

$folterwerkzeug['sthlbn'] ['id']			= 'sthlbn';
$folterwerkzeug['sthlbn'] ['expose']			= false;
$folterwerkzeug['sthlbn'] ['name']			= 'Stuhlbein';
$folterwerkzeug['sthlbn'] ['beschreibung'] 	= '`TWarum immer so kompliziert? Du schnappst dir ein herumliegendes Stuhlbein und prügelst einfach drauf los. Würde Spass machen, wenn %victim% `Tnicht ständig Piñatas rufen würde...';

$folterwerkzeug['candycane'] ['id']				= 'candycane';
$folterwerkzeug['candycane'] ['expose']			= false;
$folterwerkzeug['candycane'] ['name']			= '`$Z`&uc`4k`&er`$s`&ta`4n`&ge`0';
$folterwerkzeug['candycane'] ['beschreibung'] 	= '`T`bOH MEIN GOTT, ES KLEBT!!!`b Das kriegt %victim% `Tdoch NIE wieder aus den Haaren heraus - Und erst die Haare auf dem Kopf, du bist wirklich ein Scheusal!';

$folterwerkzeug['squirrf'] ['id']			= 'squirrf';
$folterwerkzeug['squirrf'] ['expose']			= false;
$folterwerkzeug['squirrf'] ['name']			= '`%P`!a`@r`^t`4y`thörnchen`0';
$folterwerkzeug['squirrf'] ['beschreibung'] = '`TFür %victim% `Thast du dir heute eine ganz besondere Gemeinheit ausgedacht. Du greifst vorsichtig in eine der Innentaschen deines Gewandes und holst ein irre kicherndes `bParty-Eichhörnchen`b hervor. Oh ja, wenn es gleich anfängt zu singen wie ein Chipmunk werden %victim% `T\'s Ohren bluten! Stunden um Stunden. Du steckst dir vorsichtshalber etwas Brokkoli in die Ohren!';

$folterwerkzeug['squirrb'] ['id']			= 'squirrb';
$folterwerkzeug['squirrb'] ['expose']			= false;
$folterwerkzeug['squirrb'] ['name']			= '`4Todes`thörnchen`0';
$folterwerkzeug['squirrb'] ['beschreibung'] = '`TFür %victim% `Thast du dir heute eine ganz besondere Gemeinheit ausgedacht. Du greifst vorsichtig in eine der Innentaschen deines Gewandes und holst ein irre kicherndes `bTodeshörnchen`b hervor. Es wird %victim% `Tnicht nur die Haut zerreißen, sondern scheint gar Spaß daran zu haben, sein kleines Horn tief in das Fleisch zu drücken, um hinterher mit seinen scharfen Zähnen an der blutigen Kost zu nagen... Heute brauchst du es wohl nicht mehr zu füttern.';

$folterwerkzeug['squirrd'] ['id']			= 'squirrd';
$folterwerkzeug['squirrd'] ['expose']			= false;
$folterwerkzeug['squirrd'] ['name']			= '`&Baby`thörnchen`0';
$folterwerkzeug['squirrd'] ['beschreibung'] = '`TFür %victim% `Thast du dir heute eine ganz besondere Gemeinheit ausgedacht. Du greifst vorsichtig in eine der Innentaschen deines Gewandes und holst ein gurrendes `bBabyhörnchen`b hervor. Moment mal...ein Babyhörnchen? Was soll das denn? Was soll denn so klein kleines süßes DutschidutschiduuuuuUUUUAAAAAUUUAA! Dieses kleine Miestvieh krabbelt mit seinen babyfrischen und rasiermesserscharfen Krallen über %victim% `T\'s nackten Oberkörper und hinterlässt dabei nicht wenige rote Striemen!';

$folterwerkzeug['socken'] ['id']			= 'socken';
$folterwerkzeug['socken'] ['expose']		= false;
$folterwerkzeug['socken'] ['name']			= 'Socken';
$folterwerkzeug['socken'] ['beschreibung'] 	= '`THeute hast du dir etwas extra-fieses für %victim% `Tausgedacht: mit spitzen Fingern hältst du ein altes, stinkendes Paar Socken in der Hand, welches du einem alten Troll abgeschwatzt hast. Dieser hat sie vermutlich mehrere Monate an den Füßen getragen, was man sowohl ihrem Aussehen als auch ihrem Gestank anmerkt. Glücklicherweise bist du im Besitz einer Gesichtsmaske, die den Großteil des üblen Geruchs von dir abhält. %victim% `That da nicht so viel Glück. Er/sie erbleicht schon, als du die Folterkammer betrittst und ihm/ihr die Duftwolke entgegenschlägt. Je näher du kommst, desto angeekelter wird sein/ihr Gesichtsausdruck, und er wird sich ein Würgen nicht mehr lange verkneifen können. Du hingegen merkst nichts vom Gestank und gehst mit diabolischem Lächeln immer weiter auf dein armes Opfer zu. Wirst du es schaffen, bis an sein Gesicht heranzukommen, oder wird er/sie schon vorher ohnmächtig?';

?>
