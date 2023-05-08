<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//DE">
<html>
<head>
<title><?=$template['title']?></title>
<link href='templates/yar2/yar2.css' rel='stylesheet' type='text/css'>
<?=$template['headscript']?><?=$template['script']?>
</head>
<body bgcolor='#000000' text='#CCCCCC'><?=$template['bodystart']?><?=$template['JS_LIB']?>
<table border="0" cellpadding="4" cellspacing="0" width="100%">
  <tr>
    <td colspan="3" class='pageheader' valign='bottom'>
      <img src='./images/title.gif' align='right'>
      <span class='pagetitle'><br><?=$template['title']?></span><br />
        <?=$template['motd']?> &#149;  <?=$template['motc']?> &#149; <?=$template['mail']?> &#149; <?=$template['petition']?> &#149; <?=$template['faq']?> &#149; <?=$template['ignore']?>
    </td>
  </tr>
  <tr>




      <td width="145" bgcolor='#433828' valign='top' align="left">
          <table border="0" width="142" cellpadding="0" cellspacing="0">
              <tr><td align="center"><img src='./images/uscroll.GIF' width='181' height='11' alt=''></td></tr>
              <tr><td class="nav"><?=$template['nav']?></td></tr>
              <tr><td align="center"><img src='./images/lscroll.GIF' width='181' height='11' alt=''></td></tr>
          </table>
      </td>


      <td width="*" bgcolor='#352D20' valign='top' align="left">
          <?=$template['output']?>
      </td>


      <td width="145" bgcolor='#352D20' valign='top' align="right">
          <table border="0" cellpadding="0" cellspacing="0" align="right">
              <tr><td align="center"><img src='./images/uscroll.GIF' width='181' height='11' alt=''></td></tr>
              <tr>
                  <td align="center">
                      <table cellspacing='0' cellpadding='0' class='nav' width="121" border="0">
                          <tr><td bgcolor="#003300" align="center"><?=$template['stats']?></td></tr>
                      </table>
                  </td>
              </tr>
              <tr><td align="center"><img src='./images/lscroll.GIF' width='181' height='11' alt=''></td></tr>
              <tr><td><?=$template['paypal']?><center><?=$template['referral']?></center></td></tr>
          </table>
      </td>
  </tr>



  <tr>
    <td colspan='3' class='footer'>
      <table border='0' cellpadding='0' cellspacing='0' width='100%' class='noborder'>
        <tr>
          <td class='noborder'><?=$template['copyright']?>, Design: Chris Yarbrough (<?=$template['pagegen']?>) </td>
          <td align='right' class='noborder'><?=$template['source']?></td>
        </tr>
        <tr>
        	<td colspan='2' align="center">
				<?=$template['ad']?>
        	</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?=$template['bodyend']?><?=$template['scriptfile']?><?=$template['scriptprio']?><?=$template['scriptend']?>
</body>
</html>