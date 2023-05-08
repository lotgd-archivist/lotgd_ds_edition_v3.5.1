<?php

if (!isset($session))
{
    exit();
}  

$session['user']['specialinc']='easter.php';
$str_output = '';

switch ($_GET['op'])
{ 
    case 'run': 
    {
      switch (e_rand(1,3))
      {
      case 1:
      case 2:
        $str_output .= '`gDa dir das Erscheinen der Frau mehr als seltsam vorkommt, drehst du dich ohne ein Wort zu sagen um und suchst dein Heil in der Flucht. Der Wald ist schließlich nah und sie wird dich ja wohl kaum verfolgen, nicht wahr?
Diese Vermutung erweist sich als richtig, allerdings gilt dies nicht für jene Klagelaute, welche noch einige Zeit zwischen den Bäumen widerhallen. Wider Erwarten fühlst du dich schuldig.`n`n`iDu verlierst einen Charmepunkt.`i`n`n';
        $session['user']['specialinc']='';
        $session['user']['charm']=max(0,$session['user']['charm']-1);
        break; 
        
      case 3:
        $str_output .= '`gUm dem starren Blick zu entfliehen, drehst du dich um und willst gerade in den Wald flüchten, als aus dem Boden Schlingpflanzen sprießen, welche deine Beine umwickeln und dich zu Fall bringen. Der Aufprall raubt dir das Bewusstsein, sodass du nicht einmal weißt, was genau dich getötet hat, als Ramius dich in Empfang nimmt.`n`n';
        killplayer();
        break;      
      }
      break;
    }

    case 'give':
    {
      if (e_rand(0,1)==0)
      {
       $str_output .= words_by_sex('`gDu erwiderst den Blick, denn eine Frau, die aus dem Nichts auftaucht, möchtest du nicht einmal eine Sekunde aus den Augen lassen. Als sie weder etwas sagt, noch sich rührt, beschließt du die Initiative zu ergreifen und gehst vorsichtig in die Knie, um die abgebrochen Osterglocke aufzuheben und ihr schließlich anzubieten.`n
Sie nickt schließlich und spricht: `/"Dir sei verziehen, [Sohn|Tochter], behalte sie als Erinnerung und nun geh, du gehörst nicht an diesen Ort."`g`n 
Schnell entsprichst du ihren Worten, bevor sie doch noch etwas als Tribut für die Blume fordert. Später ist noch genügend Zeit sie näher zu betrachten.`0`n`n');
        $session['user']['specialinc']='';
        item_add($session['user']['acctid'],'osterglocke');
      }
      
      else
      {
        $str_output .= words_by_sex('`gOhne zu zögern gehst du auf die Knie und reichst ihr die abgebrochene Osterglocke wie eine Opfergabe dar. Es dauert nicht lang, da wird jene dir aus der Hand genommen, kurz berührt ihre Haut die deine, sie fühlt sich unglaublich leblos und kalt an.`n
`/"Einst waren sie alle wunderschöne Männer und Frauen, du würdest es nicht glauben, selbst wenn ich es dir ein allen Einzelheiten beschreiben würde, [Sterblicher|Sterbliche]. Doch dies ist vergangen, genau wie jene Osterglocke nun vor ihrer Zeit verwelken wird."`n
`gDoch da ist keine Trauer in ihren Augen, als schließlich die Blume in Flammen aufgeht und sie dir die Asche ins Gesicht bläst.`n`n

Als du wieder etwas sehen kannst, ist sie fort und du fühlst dich ein kleines bisschen schöner…`0`n`n');
        $session['user']['specialinc']='';
        $session['user']['charm']+=1;    
      }
      break;
    }

    case 'sacrifice':
    {

        $str_output .= '`gOhne zu zögern, denn diese Frau ist gewiss eine Waldfee oder ein anderes Zauberwesen, holst du einen deiner Edelsteine hervor und bietest ihr jenen als Opfer dar. Einen Augenblick scheint es so, als würde sie etwas sagen wollen, doch sie wendet sich nur ab und verschwindet so plötzlich wie sie aufgetaucht ist.`n
Auch du beschließt, dass es das Beste ist diesen Ort so schnell wie möglich zu verlassen.`n`n';
        $session['user']['specialinc']='';
        $session['user']['gems']-=1;
        
        break;
    }

    default:
    {
        $str_output .= '`/W`yä`ph`grend eines Streifzugs durch den Wald gelangst du zu einem umgestürzten Baumstamm, welcher zum Verweilen einlädt. So lässt du dich auf der rauen Rinde nieder und schöpfst neuen Atem. Ruhig ist es hier, fast schon zu idyllisch. Ein vorsichtiger Blick über die Schulter offenbart dir, dass dort der Wald lichter wird und sich in der Nähe ein wahres Blütenmeer befindet. Neugierig machst du dich auf, jenes genauer zu erkunden und erkennst, dass es sich um Osterglocken handelt, mehr als du jemals zählen konntest. Von diesem Anblick gebannt, trittst du doch wirklich auf eine der Blumen, welche anscheinend die Vorhut zum Wald bildet. Ein Missgeschick, sicherlich, doch wird dies auch die Frau so sehen, welche plötzlich inmitten der Blumen erscheint und dich mit starrem Blick muste`pr`yt`/?`n`n';
        addnav('Wegrennen','forest.php?op=run');
        addnav('Blume überreichen','forest.php?op=give');
        if ($session['user']['gems']>0)
        {
          addnav('Einen Edelstein opfern','forest.php?op=sacrifice');
        }
        break;
    }
}

output ($str_output); 



?>
