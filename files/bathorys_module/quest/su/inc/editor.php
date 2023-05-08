<?php
 //bathi
$build = true;

if($build)
{
    /** @noinspection PhpUndefinedVariableInspection
    db_query("CREATE TABLE IF NOT EXISTS ".$table." (
			  `id` int(255) NOT null AUTO_INCREMENT,
			  `activ` TINYINT( 1 ) NOT null DEFAULT '0',
			  `sort` INT( 255 ) NOT null DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

     */

	if($_GET['do']=='save')
	{
		unset($_POST['form_submit']);
		
		$rowtest = db_query("SHOW COLUMNS FROM  ".$table."");
		$cols = array();
		while($rt = db_fetch_assoc($rowtest))
		{
			$cols[]= $rt['Field'];
		}
		
		foreach($_POST as $key => $val) 
		{
			if(!in_array($key,$cols))
			{
                /** @noinspection PhpUndefinedVariableInspection */
                $split = explode(',',$head[$key]);
				
				if($split[1] == 'text' || !isset($split[1]))
				{
					db_query("ALTER TABLE ".$table." ADD ".$key." VARCHAR( 255 ) NOT null");
				}
				else if ($split[1] == 'textarea' || mb_strpos($key,'implode_')!==false)
				{
					db_query("ALTER TABLE ".$table." ADD ".$key." TEXT NOT null");
				}
				else if ($split[1] == 'bool')
				{
					db_query("ALTER TABLE ".$table." ADD ".$key." TINYINT( 1 ) NOT null DEFAULT '0'");
				}
                else
                {
                    db_query("ALTER TABLE ".$table." ADD ".$key." INT( 255 ) NOT null DEFAULT '0'");
                }
			}
		}
	}
}

/*
if($build)
{

    $rowtest = db_query("SHOW COLUMNS FROM  ".$table."");
    $cols = array();
    while($rt = db_fetch_assoc($rowtest))
    {
        if(!isset($head[$rt['Field']])   &&  $rt['Field']!= 'id'   &&  $rt['Field']!= 'activ'  &&  $rt['Field']!= 'sort' && $_GET['op'] != 'crkt')
        {
            output("ALTER TABLE ".$table." DROP ".$rt['Field'].";`n");
            db_query("ALTER TABLE ".$table." DROP ".$rt['Field'].";");
        }
    }
}
  */

if($_GET['sop']=='')
{
	if($_GET['do']=='up')
	{
		$id=(int)$_GET['id'];
        $sort=(int)$_GET['t'];
        $questid=(int)$_GET['q'];
        db_query("UPDATE ".$table." SET interactid = interactid - 1 WHERE interactid = '".($sort+1)."' AND questid='".$questid."'  LIMIT 1");
        db_query("UPDATE ".$table." SET interactid = interactid + 1 WHERE id = '".$id."' AND questid='".$questid."'  LIMIT 1");

	}
	else if($_GET['do']=='down')
	{
		$id=(int)$_GET['id'];
		$sort=(int)$_GET['t'];
        $questid=(int)$_GET['q'];
        if($sort>1)
        {
            db_query("UPDATE ".$table." SET interactid = interactid + 1 WHERE interactid = '".($sort-1)."' AND questid='".$questid."' LIMIT 1");
            db_query("UPDATE ".$table." SET interactid = interactid - 1 WHERE id = '".$id."' AND questid='".$questid."'  LIMIT 1");
        }

	}
	else if($_GET['do']=='deac')
	{
		$id=(int)$_GET['id'];
		db_query("UPDATE ".$table." SET activ = 0  WHERE id = '".$id."' LIMIT 1");
	}
	else if($_GET['do']=='ac')
	{
		$id=(int)$_GET['id'];
		db_query("UPDATE ".$table." SET activ = 1  WHERE id = '".$id."' LIMIT 1");
	}

    /** @noinspection PhpUndefinedVariableInspection */
    $atm_r = db_query("SELECT * FROM ".$table." ".$orderby);

    /** @noinspection PhpUndefinedVariableInspection */
    /** @noinspection PhpUndefinedVariableInspection */
    $str_output = '`$`b`c'.$name.'`c`b`0`n'.$header;

    /** @noinspection PhpUndefinedVariableInspection */
    addpregnav('/'.utf8_preg_quote($filename).'op='.$_GET['op'].'&sop=edit&id=\d+/');
	addpregnav('/'.utf8_preg_quote($filename).'op='.$_GET['op'].'&do=deac&id=\d+/');
	addpregnav('/'.utf8_preg_quote($filename).'op='.$_GET['op'].'&do=ac&id=\d+/');
	addpregnav('/'.utf8_preg_quote($filename).'op='.$_GET['op'].'&do=up&q=\d+&id=\d+&t=\d+/');
	addpregnav('/'.utf8_preg_quote($filename).'op='.$_GET['op'].'&do=down&q=\d+&id=\d+&t=\d+/');
	$i = 0;
	while($r = db_fetch_assoc($atm_r))
	{
		$str_output .=editorprocess($r,$filename,$i);
		$i++;
	}
	
	$str_output .= '</table>`n`n`n';
	output($str_output);
}
else if($_GET['sop']=='new' || $_GET['sop']=='edit')
{
    $id=$_GET['id'];
    $edit = ($_GET['sop']=='edit');

    if($_GET['do']=='save')
    {
        unset($_POST['form_submit']);

        if($edit)
        {
            $sql = "UPDATE ".$table." SET ";

            foreach($_POST as $key => $val)
            {
                if(mb_strpos($key,'implode_')!==false){
                    $val = implode(',',$val);
                }
                $sql .= "".$key." = '".db_real_escape_string(stripslashes($val))."',";
            }

            $sql .= "id = '".$id."' WHERE id = '".$id."' ";
            db_query($sql);

        }
        else
        {
            foreach($_POST as $key => $val) {

                if(mb_strpos($key,'implode_')!==false){
                    $val = implode(',',$val);
                }

                /** @noinspection PhpUndefinedVariableInspection */
                $keys .= ','.$key;
                /** @noinspection PhpUndefinedVariableInspection */
                $vals .= ',\''.db_real_escape_string(stripslashes($val)).'\'';
            }

            $activ=1;
            if($_GET['op']=='quests')$activ=0;

            $sql = "INSERT INTO ".$table."(id ".$keys.",activ)
						VALUES ('' ".$vals.",'".$activ."')";
            db_query($sql);
            $id = db_insert_id();
            $edit = true;
            $_GET['sop']='edit';
        }
        /** @noinspection PhpUndefinedVariableInspection */
        output('`$`b`c'.$name.' erfolgreich gespeichert!`c`b`n');
    }

    $val = array();
		
	if($edit)
	{
		$val = db_fetch_assoc(db_query("SELECT * FROM ".$table." WHERE id = '".$id."'"));
		
		foreach($val as $k => $v) 
			{
				if(mb_strpos($k,'implode_')!==false){
					$val[$k] = explode(',',$v);
				}
				
			}
		
	}

	// Formular anzeigen
    /** @noinspection PhpUndefinedVariableInspection */
    $str_lnk = ''.$filename.'&op='.$_GET['op'].'&sop='.$_GET['sop'].'&do=save&id='.$id;
	output('`n<form action="'.$str_lnk.'" method="POST" enctype="multipart/form-data">');
	showform($head,$val,false,'Speichern',20);
	output('</form>');
	// END Formular anzeigen
	
	addnav('',$str_lnk);
	
	if($_GET['sop']=='edit')
	{
		addnav('Quicknav');
		$prev_row = db_fetch_assoc(db_query("SELECT id FROM ".$table." WHERE id < '".intval($id)."' ORDER BY id DESC LIMIT 1"));
		$next_row = db_fetch_assoc(db_query("SELECT id FROM ".$table." WHERE id > '".intval($id)."' ORDER BY id ASC LIMIT 1"));
		$prev = isset($prev_row['id']) ? $prev_row['id'] : 0;
		$next = isset($next_row['id']) ? $next_row['id'] : 0;
		addnav('+?vor',''.( ($next > 0) ? $filename.'op='.$_GET['op'].'&sop=edit&id='.$next : '' ));
		addnav('-?zurück', ( ($prev > 0) ? ''.$filename.'op='.$_GET['op'].'&sop=edit&id='.$prev : '' ));
	}
}

?>