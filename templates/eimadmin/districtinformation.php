<?
/*
OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures 
all the essential functionalities required for any enterprise. 
Copyright (C) 2006 hSenid Software International Pvt. Ltd, http://www.hsenid.com

OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
the GNU General Public License as published by the Free Software Foundation; either
version 2 of the License, or (at your option) any later version.

OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program;
if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
Boston, MA  02110-1301, USA
*/

require_once ROOT_PATH . '/lib/confs/sysConf.php';
require_once ROOT_PATH . '/lib/controllers/ViewController.php';

function populateStates($value) {
	
	$view_controller = new ViewController();
	$provlist = $view_controller->xajaxObjCall($value,'DIS','province');
	
	$response = new xajaxResponse();
	$xajaxFiller = new xajaxElementFiller();
	$response = $xajaxFiller->cmbFiller($response,$provlist,1,'frmDistrictInformation','selProvinceID');
	$response->addAssign('status','innerHTML','');
	
return $response->getXML();
}

$objAjax = new xajax();
$objAjax->registerFunction('populateStates');
$objAjax->processRequests();

	$sysConst = new sysConf(); 
	$locRights=$_SESSION['localRights'];

	
if ((isset($this->getArr['capturemode'])) && ($this->getArr['capturemode'] == 'addmode')) {
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<? $objAjax->printJavascript(); ?>
<script>			
function alpha(txt)
{
var flag=true;
var i,code;

if(txt.value=="")
   return false;

for(i=0;txt.value.length>i;i++)
	{
	code=txt.value.charCodeAt(i);
    if((code>=65 && code<=122) || code==32 || code==46)
	   flag=true;
	else
	   {
	   flag=false;
	   break;
	   }
	}
return flag;
}
	
	function goBack() {
		location.href =  "./CentralController.php?uniqcode=<?=$this->getArr['uniqcode']?>&VIEW=MAIN";
	}

	function addSave() {
		var txt=document.frmDistrictInformation.txtDistrictDesc;
		if (!alpha(txt)) {
			alert ("Description Error!");
			txt.focus();
			return false;
		}
		
		if(document.frmDistrictInformation.cmbCountry.value=='0') {
			alert("Field should be selected");
			document.frmDistrictInformation.cmbCountry.focus();
			return;
		}

		if(document.frmDistrictInformation.selProvinceID.value=='0') {
			alert("Field should be selected");
			document.frmDistrictInformation.selProvinceID.focus();
			return;
		}

		document.frmDistrictInformation.sqlState.value = "NewRecord";
		document.frmDistrictInformation.submit();		
	}
	
	function clearAll() {
		document.frmDistrictInformation.txtDistrictDesc.value = '';
		document.frmDistrictInformation.cmbCountry.options[0].selected=true;
		document.frmDistrictInformation.selProvinceID.options[0].selected=true;
	}
				
</script>
<link href="../../themes/beyondT/css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">@import url("../../themes/beyondT/css/style.css"); </style>
</head>
<body>
<table width='100%' cellpadding='0' cellspacing='0' border='0' class='moduleTitle'>
  <tr>
    <td valign='top'> </td>
    <td width='100%'><h2><?=$heading?></h2></td>
    <td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'>
    <b><div  id="status"></div></b></td>
  </tr>
</table>
<p>
<p> 
<table width="431" border="0" cellspacing="0" cellpadding="0" ><td width="177">
<form name="frmDistrictInformation" method="post" action="<?$_SERVER['PHP_SELF']?>?capturemode=addmode&uniqcode=<?=$this->getArr['uniqcode']?>">
  <tr> 
    <td height="27" valign='top'> <p> <img title="Back" onmouseout="this.src='../../themes/beyondT/pictures/btn_back.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_back_02.jpg';"  src="../../themes/beyondT/pictures/btn_back.jpg" onclick="goBack();">
        <input type="hidden" name="sqlState" value="">
      </p></td>
    <td width="254" align='left' valign='bottom'> <font color="red" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
      </font> </td>
  </tr><td width="177">
</table>
              <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="13"><img name="table_r1_c1" src="../../themes/beyondT/pictures/table_r1_c1.gif" width="13" height="12" border="0" alt=""></td>
                  <td width="339" background="../../themes/beyondT/pictures/table_r1_c2.gif"><img name="table_r1_c2" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td width="13"><img name="table_r1_c3" src="../../themes/beyondT/pictures/table_r1_c3.gif" width="13" height="12" border="0" alt=""></td>
                  <td width="11"><img src="../../themes/beyondT/pictures/spacer.gif" width="1" height="12" border="0" alt=""></td>
                </tr>
                <tr>
                  <td background="../../themes/beyondT/pictures/table_r2_c1.gif"><img name="table_r2_c1" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="">
							  <tr> 
							    <td><?=$code?></td>
							    <td><strong><?=$this->popArr['newID']?></strong></td>
							  </tr>
							  <tr> 
							    <td><?=$description?></td>
							    <td> <textarea name='txtDistrictDesc' rows="3" tabindex='3' cols="30"><?=isset($this->popArr['txtDistrictDesc']) ? $this->popArr['txtDistrictDesc'] :''?></textarea></td>
							  </tr>
							  <tr> 
							    <td><?=$country?></td>
							    <td> <select  name="cmbCountry" onchange="document.getElementById('status').innerHTML = 'Please Wait....'; xajax_populateStates(this.value);"> 
							    			<option value="0"><?=$selectcounlist?></option>
							    <?
							    $countrylist = $this->popArr['countrylist'];
							    for ($j=0; $countrylist && $j<count($countrylist);$j++) 
							    	if(isset($this->popArr['cmbCountry']) && $this->popArr['cmbCountry']==$countrylist[$j][0])
							    	  echo '<option selected value=' . $countrylist[$j][0] . '>' . $countrylist[$j][1] . '</option>';
							    	else
							    	  echo '<option value=' . $countrylist[$j][0] . '>' . $countrylist[$j][1] . '</option>';
							    ?>
							    </select></td>
							 </tr>
							  <tr> 
							    <td><?=$state?></td>
							    <td> <select name="selProvinceID"> 
							    			<option value="0"><?=$selstatelist?></option>
							    </select></td>
							  </tr>
					  <tr><td></td><td align="right" width="100%"><img onClick="addSave();" onmouseout="this.src='../../themes/beyondT/pictures/btn_save.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_save_02.jpg';" src="../../themes/beyondT/pictures/btn_save.jpg">
        <img onClick="clearAll();" onmouseout="this.src='../../themes/beyondT/pictures/btn_clear.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_clear_02.jpg';" src="../../themes/beyondT/pictures/btn_clear.jpg"></td></tr>

                  </table></td>
                  <td background="../../themes/beyondT/pictures/table_r2_c3.gif"><img name="table_r2_c3" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td><img src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                </tr>
                <tr>
                  <td><img name="table_r3_c1" src="../../themes/beyondT/pictures/table_r3_c1.gif" width="13" height="16" border="0" alt=""></td>
                  <td background="../../themes/beyondT/pictures/table_r3_c2.gif"><img name="table_r3_c2" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td><img name="table_r3_c3" src="../../themes/beyondT/pictures/table_r3_c3.gif" width="13" height="16" border="0" alt=""></td>
                  <td><img src="../../themes/beyondT/pictures/spacer.gif" width="1" height="16" border="0" alt=""></td>
                </tr>
              </table>

</form>
</body>
</html>
<? } else if ((isset($this->getArr['capturemode'])) && ($this->getArr['capturemode'] == 'updatemode')) {
$message = $this->popArr['editArr'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<? $objAjax->printJavascript(); ?>
<script>			
function alpha(txt)
{
var flag=true;
var i,code;

if(txt.value=="")
   return false;

for(i=0;txt.value.length>i;i++)
	{
	code=txt.value.charCodeAt(i);
    if((code>=65 && code<=122) || code==32 || code==46)
	   flag=true;
	else
	   {
	   flag=false;
	   break;
	   }
	}
return flag;
}


	function goBack() {
		location.href =  "./CentralController.php?uniqcode=<?=$this->getArr['uniqcode']?>&VIEW=MAIN";
	}

function mout() {
	if(document.Edit.title=='Save') 
		document.Edit.src='../../themes/beyondT/pictures/btn_save.jpg'; 
	else
		document.Edit.src='../../themes/beyondT/pictures/btn_edit.jpg'; 
}

function mover() {
	if(document.Edit.title=='Save') 
		document.Edit.src='../../themes/beyondT/pictures/btn_save_02.jpg'; 
	else
		document.Edit.src='../../themes/beyondT/pictures/btn_edit_02.jpg'; 
}
	
function edit()
{
	if(document.Edit.title=='Save') {
		addUpdate();
		return;
	}
	
	var frm=document.frmDistrictInformation;
//  alert(frm.elements.length);
	for (var i=0; i < frm.elements.length; i++)
		frm.elements[i].disabled = false;
	document.Edit.src="../../themes/beyondT/pictures/btn_save.jpg";
	document.Edit.title="Save";
}

	function addUpdate() {
		
		var txt=document.frmDistrictInformation.txtDistrictDesc;
		if (!alpha(txt)) {
			alert ("Description Error!");
			txt.focus();
			return false;
		}

		if(document.frmDistrictInformation.cmbCountry.value=='0') {
			alert("Field should be selected");
			document.frmDistrictInformation.cmbCountry.focus();
			return;
		}
		
		if(document.frmDistrictInformation.selProvinceID.value=='0') {
			alert("Field should be selected");
			document.frmDistrictInformation.selProvinceID.focus();
			return;
		}
		
		document.frmDistrictInformation.sqlState.value = "UpdateRecord";
		document.frmDistrictInformation.submit();		
	}
	
	function clearAll() {
		if(document.Edit.title!='Save') 
			return;

		document.frmDistrictInformation.txtDistrictDesc.value = '';
	}			
</script>
<link href="../../themes/beyondT/css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">@import url("../../themes/beyondT/css/style.css"); </style>
</head>
<body>
<table width='100%' cellpadding='0' cellspacing='0' border='0' class='moduleTitle'>
  <tr>
    <td valign='top'> </td>
    <td width='100%'><h2><?=$heading?></h2></td>
    <td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'>
    <b><div  id="status"></div></b><A href='index.php?module=Contacts&action=index&return_module=Contacts&return_action=DetailView&&print=true' class='utilsLink'></td>
  </tr>
</table>
<p>
<p> 
<table width="431" border="0" cellspacing="0" cellpadding="0" ><td width="177">
<form name="frmDistrictInformation" method="post" action="<?=$_SERVER['PHP_SELF']?>?id=<?=$this->getArr['id']?>&capturemode=updatemode&uniqcode=<?=$this->getArr['uniqcode']?>">
  <tr> 
    <td height="27" valign='top'> <p> <img title="Back" onmouseout="this.src='../../themes/beyondT/pictures/btn_back.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_back_02.jpg';" src="../../themes/beyondT/pictures/btn_back.jpg" onclick="goBack();">
        <input type="hidden" name="sqlState" value="">
      </p></td>
    <td width="254" align='left' valign='bottom'> <font color="red" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
      </font> </td>
  </tr><td width="177">
</table>

           <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="13"><img name="table_r1_c1" src="../../themes/beyondT/pictures/table_r1_c1.gif" width="13" height="12" border="0" alt=""></td>
                  <td width="339" background="../../themes/beyondT/pictures/table_r1_c2.gif"><img name="table_r1_c2" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td width="13"><img name="table_r1_c3" src="../../themes/beyondT/pictures/table_r1_c3.gif" width="13" height="12" border="0" alt=""></td>
                  <td width="11"><img src="../../themes/beyondT/pictures/spacer.gif" width="1" height="12" border="0" alt=""></td>
                </tr>
                <tr>
                  <td background="../../themes/beyondT/pictures/table_r2_c1.gif"><img name="table_r2_c1" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="">
							  <tr> 
							    <td><?=$code?></td>
							    <td><input type="hidden" name="txtDistrictID" value=<?=$message[0][0]?>>
							    <strong><?=$message[0][0]?></strong></td>
							  </tr>
							  <tr> 
							    <td><?=$description?></td>
							  	<td><textarea name='txtDistrictDesc' <?=isset($this->popArr['txtDistrictDesc']) ? '':'disabled'?> rows="3" tabindex='3' cols="30"><?=isset($this->popArr['txtDistrictDesc']) ? $this->popArr['txtDistrictDesc'] : $message[0][1]?></textarea></td>
							  <tr> 
							    <td><?=$country?></td>
							    <td> <select <?=isset($this->popArr['cmbCountry']) ? '':'disabled'?> onchange="document.getElementById('status').innerHTML = 'Please Wait....'; xajax_populateStates(this.value);" name="cmbCountry"> 
							    			<option value="0"><?=$selectcounlist?></option>
							    <?
								if(isset($this->popArr['cmbCountry'])) {
								    $countrylist = $this->popArr['countrylist'];
								    for ($j=0; $countrylist && $j<count($countrylist);$j++) 
								    	if($countrylist[$j][0]==$this->popArr['cmbCountry'])
								    	  echo '<option selected value=' . $countrylist[$j][0] . '>' . $countrylist[$j][1] . '</option>';
								    	else
								    	  echo '<option value=' . $countrylist[$j][0] . '>' . $countrylist[$j][1] . '</option>';
								} else {
								    $countrylist = $this->popArr['countrylist'];
								    for ($j=0; $countrylist && $j<count($countrylist);$j++) 
								    	if($countrylist[$j][0]==$this->popArr['selcountry'])
								    	  echo '<option selected value=' . $countrylist[$j][0] . '>' . $countrylist[$j][1] . '</option>';
								    	else
								    	  echo '<option value=' . $countrylist[$j][0] . '>' . $countrylist[$j][1] . '</option>';
								}
							    ?>
							    </select></td>
							 </tr>
							  <tr> 
							    <td><?=$state?></td>
							    <td> <select <?=isset($this->popArr['cmbCountry']) ? '':'disabled'?> name="selProvinceID"> 
							    			<option value="0"><?=$selstatelist?></option>
							    <? if(isset($this->popArr['cmbCountry'])) {
							    $provlist = $this->popArr['provlist'];
							    for ($j=0; $provlist && $j<count($provlist);$j++) 
							    	if($provlist[$j][1]==$message[0][2])
							    	   echo '<option selected value=' . $provlist[$j][1] . '>' . $provlist[$j][2] . '</option>';
							    	else
							    	   echo '<option value=' . $provlist[$j][1] . '>' . $provlist[$j][2] . '</option>';
							    } else {
							    $provlist = $this->popArr['provlist']; 
							    for ($j=0; $provlist && $j<count($provlist);$j++) 
							    	if($provlist[$j][1]==$message[0][2])
							    	   echo '<option selected value=' . $provlist[$j][1] . '>' . $provlist[$j][2] . '</option>';
							    	else
							    	   echo '<option value=' . $provlist[$j][1] . '>' . $provlist[$j][2] . '</option>';
							    }
							    ?>
							    </select></td>
							  </tr>
  			  <tr><td></td><td align="right" width="100%">
<?			if($locRights['edit']) { ?>
			        <img src="../../themes/beyondT/pictures/btn_edit.jpg" title="Edit" onmouseout="mout();" onmouseover="mover();" name="Edit" onClick="edit();">
<?			} else { ?>
			        <img src="../../themes/beyondT/pictures/btn_edit.jpg" onClick="alert('<?=$sysConst->accessDenied?>');">
<?			}  ?>
					  <img src="../../themes/beyondT/pictures/btn_clear.jpg" onmouseout="this.src='../../themes/beyondT/pictures/btn_clear.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_clear_02.jpg';" onClick="clearAll();" >
</td>
					  </tr>				  
                  </table></td>
                  <td background="../../themes/beyondT/pictures/table_r2_c3.gif"><img name="table_r2_c3" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td><img src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                </tr>
                <tr>
                  <td><img name="table_r3_c1" src="../../themes/beyondT/pictures/table_r3_c1.gif" width="13" height="16" border="0" alt=""></td>
                  <td background="../../themes/beyondT/pictures/table_r3_c2.gif"><img name="table_r3_c2" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td><img name="table_r3_c3" src="../../themes/beyondT/pictures/table_r3_c3.gif" width="13" height="16" border="0" alt=""></td>
                  <td><img src="../../themes/beyondT/pictures/spacer.gif" width="1" height="16" border="0" alt=""></td>
                </tr>
              </table>
</form>
</body>
</html>
<? } ?>
