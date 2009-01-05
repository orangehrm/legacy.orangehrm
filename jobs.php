<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

define ( 'ROOT_PATH', dirname ( __FILE__ ) );
define ( 'PARENT_SITE', "http://www.orsgroup.net.au" );

require_once ROOT_PATH . '/lib/common/Language.php';
$lan = new Language ( );
require_once ROOT_PATH . '/language/default/lang_default_full.php';
require_once ($lan->getLangPath ( "full.php" ));

$url = 'lib/controllers/PublicController.php?recruitcode=ApplicantViewJobs';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="en-gb" xmlns="http://www.w3.org/1999/xhtml" lang="en-gb">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="robots" content="index, follow">
<meta name="keywords"
	content="ORS, Vocational Rehabilitation, Job Network, Employment Services, Occupational Health and Safety, Therapist, Psychologist, Disability.">
<meta name="description" content="The ORS Group">
<meta name="generator"
	content="Joomla! 1.5 - Open Source Content Management">
<title><?php
echo PARENT_SITE . " - " . $lang_Recruit_ApplicantVacancyList_Title;
?></title>
<link
	href="<?php
	echo PARENT_SITE?>/index.php?format=feed&amp;type=rss"
	rel="alternate" type="application/rss+xml" title="RSS 2.0">


<link
	href="<?php
	echo PARENT_SITE?>/index.php?format=feed&amp;type=atom"
	rel="alternate" type="application/atom+xml" title="Atom 1.0">


<link rel="stylesheet"
	href="<?php
	echo PARENT_SITE?>/templates/system/css/system.css"
	type="text/css">
<link rel="stylesheet"
	href="<?php
	echo PARENT_SITE?>/templates/system/css/general.css"
	type="text/css">
<link rel="stylesheet"
	href="<?php
	echo PARENT_SITE?>/templates/rhuk_milkyway/css/template.css"
	type="text/css">
<link rel="stylesheet"
	href="<?php
	echo PARENT_SITE?>/templates/rhuk_milkyway/css/blue.css"
	type="text/css">
<link rel="stylesheet"
	href="<?php
	echo PARENT_SITE?>/templates/rhuk_milkyway/css/blue_bg.css"
	type="text/css">
<link rel="shortcut icon"
	href="<?php
	echo PARENT_SITE?>/templates/rhuk_milkyway/Icon.ico"
	type="image/x-icon">
<!--[if lte IE 6]>
<link href="<?php
echo PARENT_SITE?>/templates/rhuk_milkyway/css/ieonly.css" rel="stylesheet" type="text/css" />
<![endif]-->

</head>
<body id="page_bg" class="color_blue bg_blue width_fmax">
<script>
function resizeFrame(frameId){
	var the_height=document.getElementById(frameId).contentWindow.document.body.scrollHeight;
	document.getElementById(frameId).contentWindow.document.body.style.backgroundColor='transparent';
	document.getElementById(frameId).height=the_height;
}
</script>
<a name="up" id="up"></a>
<div class="center" align="center">
<div id="wrapper">
<div id="wrapper_r">
<div id="header">
<div id="header_l">
<div id="header_r">
<div id="logo"></div>
<p></p>
<table style="width: 285px; height: 104px;" border="0">
	<tbody>
		<tr>
			<td><a
				href="<?php
				echo PARENT_SITE?>/index.php?option=com_content&amp;view=frontpage&amp;Itemid=1"><img
				src="<?php
				echo PARENT_SITE?>/images/stories/logo.bmp"
				align="right" border="0" height="87" width="263"></a>&nbsp;</td>
		</tr>
	</tbody>
</table>
&nbsp;&nbsp;&nbsp;&nbsp;</div>
</div>
</div>
<div id="tabarea">
<div id="tabarea_l">
<div id="tabarea_r">
<div id="tabmenu">
<table class="pill" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td class="pill_l">&nbsp;</td>
			<td class="pill_m">
			<div id="pillmenu">
			<table border="0" cellpadding="0" cellspacing="1" width="100%">
				<tbody>
					<tr>
						<td nowrap="nowrap"><a
							href="<?php
							echo PARENT_SITE?>/index.php?option=com_content&amp;view=frontpage&amp;Itemid=1"
							class="mainlevel" id="active_menu">Home</a><a
							href="<?php
							echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=3&amp;Itemid=4"
							class="mainlevel">Company Profile</a><a
							href="<?php
							echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=4&amp;Itemid=7"
							class="mainlevel">Services</a><a
							href="<?php
							echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=1&amp;Itemid=3"
							class="mainlevel">Employment Opportunities</a><a
							href="<?php
							echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=5&amp;Itemid=5"
							class="mainlevel">News</a><a
							href="<?php
							echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=40&amp;Itemid=35"
							class="mainlevel">Referral</a><a
							href="<?php
							echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=44&amp;Itemid=6"
							class="mainlevel">Contact Us</a><a
							href="<?php
							echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=42&amp;Itemid=40"
							class="mainlevel">Testimonials</a></td>
					</tr>
				</tbody>
			</table>
			</div>
			</td>
			<td class="pill_r">&nbsp;</td>
		</tr>
	</tbody>
</table>
</div>
</div>
</div>
</div>

<div id="search"></div>

<div id="pathway"></div>

<div class="clr"></div>

<div id="whitebox">
<div id="whitebox_t">
<div id="whitebox_tl">
<div id="whitebox_tr"></div>
</div>
</div>
<div class="componentheading">Home</div>					
	<div class="contentheading" style="margin-left: 150px;"><?php echo $lang_Recruit_ApplicantVacancyList_Title ?></div>						
	<iframe style="width: 100%; margin-left: 5px;" src="<?php echo $url; ?>" id="vacanyFrame"	name="vacanyFrame" frameborder="0" onload="javascript: resizeFrame('vacanyFrame')"></iframe>
<div id="footerspacer"></div>
</div>
<div id="footer">
<div id="footer_l">
<div id="footer_r">
<p id="syndicate"></p>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
	<tbody>
		<tr>
			<td nowrap="nowrap" style="font-size: 11px"><a
				href="<?php
				echo PARENT_SITE?>/index.php?option=com_content&amp;view=frontpage&amp;Itemid=1"
				class="mainlevel" id="active_menu">Home</a> | <a
				href="<?php
				echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=3&amp;Itemid=4"
				class="mainlevel">Company Profile</a> | <a
				href="<?php
				echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=4&amp;Itemid=7"
				class="mainlevel">Services</a> | <a
				href="<?php
				echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=1&amp;Itemid=3"
				class="mainlevel">Employment Opportunities</a> | <a
				href="<?php
				echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=5&amp;Itemid=5"
				class="mainlevel">News</a> | <a
				href="<?php
				echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=40&amp;Itemid=35"
				class="mainlevel">Referral</a> | <a
				href="<?php
				echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=44&amp;Itemid=6"
				class="mainlevel">Contact Us</a> | <a
				href="<?php
				echo PARENT_SITE?>/index.php?option=com_content&amp;view=article&amp;id=42&amp;Itemid=40"
				class="mainlevel">Testimonials</a> | </td>
		</tr>
	</tbody>
</table>

<div id="Copyright">Copyright (c) 2008 The ORS Group. All rights
reserved</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>