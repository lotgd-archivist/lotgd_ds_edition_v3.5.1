<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php
$template['title']?></title>
<?=$template['css']?>
<link href="./templates/atrahor_main_styles.css" rel="stylesheet" type="text/css">
<style type="text/css">
	@import url(/templates/colors.css);
</style>
<?=$template['headscript']?>
</head>
<body bgcolor="#000000" text="#CCCCCC">
	<?=$template['script']?>
	<?=$template['bodystart']?><?=$template['JS_LIB']?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr class="frame_label">
			<td class="frame_label_l" width="46"></td>
			<td class="frame_label" height="24"><span class="c73"><?=$template['title']?></span></td>
			<td class="frame_label_r" width="46"></td>
		</tr>
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="frame_border_l"></td>
			<td class="frame_main" valign="top" style="text-align:left;	padding-left:3px;">
			<?=$template['output']?>
			</td>
			<td class="frame_border_r"></td>
		</tr>
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr class="frame_label_b">
			<td class="frame_label_lb" width="46"></td>
			<td class="frame_label" height="24">Copyright <?php
echo date('Y').' '.getsetting('townname','Atrahor'); ?></td>
			<td class="frame_label_rb" width="46"></td>
		</tr>
	</table>
	<?=$template['ad']?>
<?=$template['bodyend']?><?=$template['scriptfile']?><?=$template['scriptprio']?><?=$template['scriptend']?>
</body>
</html>		