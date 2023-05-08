<?php
$template = array(
'statstart' => "<table cellpadding=2 cellspacing=0 class='charinfo' width='150'>",
'stathead' => "<tr><td class='charhead' colspan='2'><b>{title}</b></td></tr>",
'statrow' => "<tr style='{style}' id='{id}'><td class='charinfo'><b>{title}</b></td><td class='charinfo'>`^{value}</td></tr>",
'statbuff' => "<tr style='{style}' id='{id}'><td class='charinfo' colspan='2'><b>{title}:</b>`n{value}</td></tr>",
'statend' => "</table>",
'navhead' => "<span class='navhead'>&mdash;{title}&mdash;<br></span>",
'navhelp' => "<span class='navhelp'>{text}<br></span>",
'navitem' => "<a href='{link}'{accesskey}class='nav' {popup} {script}>{text}<br></a>",
'freedata' => "<tr style='{style}' id='{id}'><td class='charinfo' colspan='2'>{free_data}</td></tr>",
'login' => "
<table width='212' height='234' border='0' cellpadding='0' cellspacing='0' background='./images/logindragon.gif' class='noborder'>
	<tr>
		<td valign='bottom' align='center' class='noborder'>
			{username}: <br><input name='name' accesskey='u' size='10'><br>
			{password}:<br><input name='password' accesskey='p' type='password' size='10'><br>
			<input type='submit' value='{button}' class='button'><br>
			<img src='./images/trans.gif' width='1' height='37' align='absmiddle' alt=''>
			<img src='./images/trans.gif' width='1' height='15'>
		</td>
	</tr>
</table>");
?>