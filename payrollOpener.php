<?php
define('ROOT_PATH', dirname(__FILE__));

include "lib/common/CommonFunctions.php";

$styleSheet = CommonFunctions::getTheme(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>OrangeHRM</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="themes/<?php echo $styleSheet;?>/css/style.css" rel="stylesheet" type="text/css"/>
<link href="favicon.ico" rel="icon" type="image/gif"/>
<script type="text/javaScript" src="scripts/archive.js"></script>

</head>

<body>
<div id="companyLogoHeader"></div><div id="rightHeaderImage"></div>

<div id="main-content" style="float:left;height:640px;text-align:center;padding-left:0px;">
<iframe style="display:block;margin-left:auto;margin-right:auto;width:100%;" src="payroll.php" id="rightMenu" name="rightMenu" height="100%;" frameborder="0">
    
</iframe>

</div>



<div id="main-footer" style="clear:both;text-align:center;height:20px;">
<a href="http://www.orangehrm.com" target="_blank">OrangeHRM</a> ver 2.5.0.4 &copy; OrangeHRM Inc. 2005 - 2009 All rights reserved.
</div>
<script type="text/javascript">
//<![CDATA[
function exploitSpace() {
    dimensions = windowDimensions();

	if (document.getElementById("main-content")) {
		document.getElementById("main-content").style.height = (dimensions[1]  - 100 - <?php echo $menuObj->getMenuHeight();?>) + 'px';
    }

       if (document.getElementById("main-content")) {
       			if (dimensions[0] < 940) {
       			    dimensions[0] = 940;
       			}

               document.getElementById("main-content").style.width = (dimensions[0] - <?php echo $menuObj->getMenuWidth();?>) + 'px';
       }
}

exploitSpace();
window.onresize = exploitSpace;
//]]>
</script>

</body>
</html>