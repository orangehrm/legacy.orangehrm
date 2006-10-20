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

	$sysConst = new sysConf(); 
	$locRights=$_SESSION['localRights'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>

<script language="JavaScript">
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

function numeric(txt)
{
var flag=true;
var i,code;

if(txt.value=="")
   return false;

for(i=0;txt.value.length>i;i++)
	{
	code=txt.value.charCodeAt(i);
    if(code>=48 && code<=57 || code==46)
	   flag=true;
	else
	   {
	   flag=false;
	   break;
	   }
	}
return flag;
}

function edit()
{
	if(document.Edit.title=='Save') {
		editEXT();
		return;
	}
	
	var frm=document.frmEmpQualSub;
	for (var i=0; i < frm.elements.length; i++)
		frm.elements[i].disabled = false;
	document.Edit.src="../../themes/beyondT/pictures/btn_save.jpg";
	document.Edit.title="Save";
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

function goBack() {
		location.href = './CentralController.php?reqcode=QUA&id=<?=$this->getArr['id']?>';
	}

function addEXT()
{
	if(document.frmEmpQualSub.cmbSubject.value=='0') {
		alert('Field should be selected');
		document.frmEmpQualSub.cmbSubject.focus();
		return;
	}

	var cnt=document.frmEmpQualSub.txtMarks;
	if(!numeric(cnt)) {
		alert("Field should be Numeric");
		cnt.focus();
		return;
	}
	
	var cnt=document.frmEmpQualSub.txtYear;
	if(!numeric(cnt)) {
		alert("Field should be Numeric");
		cnt.focus();
		return;
	}

	if(document.frmEmpQualSub.cmbRatGrd.value=='0') {
		alert('Field should be selected');
		document.frmEmpQualSub.cmbRatGrd.focus();
		return;
	}

  document.frmEmpQualSub.STAT.value="ADD";
  document.frmEmpQualSub.submit();
}

function editEXT()
{
	if(document.frmEmpQualSub.cmbSubject.value=='0') {
		alert('Field should be selected');
		document.frmEmpQualSub.cmbSubject.focus();
		return;
	}

	var cnt=document.frmEmpQualSub.txtMarks;
	if(!numeric(cnt)) {
		alert("Field should be Numeric");
		cnt.focus();
		return;
	}
	
	var cnt=document.frmEmpQualSub.txtYear;
	if(!numeric(cnt)) {
		alert("Field should be Numeric");
		cnt.focus();
		return;
	}

	if(document.frmEmpQualSub.cmbRatGrd.value=='0') {
		alert('Field should be selected');
		document.frmEmpQualSub.cmbRatGrd.focus();
		return;
	}

  document.frmEmpQualSub.STAT.value="EDIT";
  document.frmEmpQualSub.submit();
}

function delEXT()
{
      var check = 0;
		with (document.frmEmpQualSub) {
			for (var i=0; i < elements.length; i++) {
				if ((elements[i].type == 'checkbox') && (elements[i].checked == true)){
					check = 1;
				}
			}
        }

        if(check==0)
            {
              alert("Select atleast one check box");
              return;
            }


    //alert(cntrl.value);
    document.frmEmpQualSub.STAT.value="DEL";
    document.frmEmpQualSub.submit();
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link href="../../themes/beyondT/css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">@import url("../../themes/beyondT/css/style1.css"); </style>
</head>
<body>
<table width='100%' cellpadding='0' cellspacing='0' border='0' class='moduleTitle'>
  <tr>
    <td valign='top'>&nbsp; </td>
    <td width='100%'><h2>Employee Qualifications</h2></td>
    <td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'><A href='index.php?module=Contacts&action=index&return_module=Contacts&return_action=DetailView&&print=true' class='utilsLink'></td>
  </tr>
</table>
<p>
<p>
<table width="431" border="0" cellspacing="0" cellpadding="0" ><td width="177">
<form name="frmEmpQualSub" method="post" action="<?=$_SERVER['PHP_SELF']?>?reqcode=<?=$this->getArr['reqcode']?>&id=<?=$this->getArr['id']?>&QUA=<?=$this->getArr['QUA']?>">

  <tr>
    <td height="27" valign='top'> <p> <img title="Back" onmouseout="this.src='../../themes/beyondT/pictures/btn_back.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_back_02.jpg';"  src="../../themes/beyondT/pictures/btn_back.jpg" onclick="goBack();">
        <input type="hidden" name="STAT" value="">
      </p></td>
    <td width="254" align='left' valign='bottom'> <font color="red" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
      </font> </td>
  </tr><td width="177">
</table>
<? 
  $empdet = $this -> popArr['empdet'];
  $qualdet = $this -> popArr['qualDet'];
?>
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
                      <td>Employee ID</td>
    				  <td width="75"><font color="#204242"><strong><?=$empdet[0][0]?></strong></font></td>
    				  <td width="50">&nbsp;</td>
					  <td>Surname</td>
						<td width="300"><font color="#204242"><strong><?=$empdet[0][3]?></strong></font></td>
					</tr>
					  <tr> 
						<td>Calling Name</td>
						<td><font color="#204242"><strong><?=$empdet[0][2]?></strong></font></td>
    				  <td width="50">&nbsp;</td>
						<td>Initials</td>
						<td><font color="#204242"><strong><?=$empdet[0][5]?></</font></td>
					  </tr>
					  <tr> 
						<td>Qualification ID</td>
						<td><font color="#204242"><strong><?=$qualdet[0][0]?></strong></font></td>
    				  <td width="50">&nbsp;</td>
						<td>Qualification</td>
						<td><font color="#204242"><strong><?=$qualdet[0][2]?></</font></td>
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

              <input type="hidden" name="txtEmpID" value="<?=$empdet[0][0]?>">
              <input type="hidden" name="txtQualID" value="<?=$qualdet[0][0]?>">

<?
if(isset($this->popArr['editArr']))
{
    $edit=$this-> popArr['editArr'];
?>

         
<br><br>
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
                      <td>Subject</td>
    				  <td><input type="hidden" name="cmbSubject" value="<?=$edit[0][2]?>"><strong>
<?					    $sublist=$this-> popArr['sublistAll'];
						for($c=0;count($sublist)>$c;$c++)
						    if($sublist[$c][1]==$edit[0][2])
						    	echo $sublist[$c][2];
?>					  
					  </strong></td>
					</tr>
					  <tr> 
						<td>Marks</td>
						<td><input type="text" disabled name="txtMarks" value="<?=$edit[0][3]?>"></td>
					  </tr>
					  <tr> 
						<td>Year</td>
						<td><input type="text" disabled name="txtYear" value="<?=$edit[0][4]?>"></td>
					  </tr>
					  <tr> 
						<td>Comments</td>
						<td><textarea name="txtComment" disabled><?=$edit[0][5]?></textarea></td>
					  </tr>
					  <tr> 
						<td>Rating Grade</td>
						<td><select disabled name="cmbRatGrd">
								<option value="0">-Select Grade-</option>
<?
						$ratlist = $this->popArr['ratlist'];
						for($c=0;count($ratlist)>$c;$c++) 
							if($ratlist[$c][0]==$edit[0][6])
								echo "<option selected value='" . $ratlist[$c][0] . "'>" . $ratlist[$c][1] . "</option>";
							else 
								echo "<option value='" . $ratlist[$c][0] . "'>" . $ratlist[$c][1] . "</option>";
?>						
						</select></td>
					  </tr>
					  <tr> 
						<td valign="top"></td>
						<td align="left" valign="top"> 
		<?			if($locRights['edit']) { ?>
							        <img src="../../themes/beyondT/pictures/btn_edit.jpg" title="Edit" onmouseout="mout();" onmouseover="mover();" name="Edit" onClick="edit();">
			<?			} else { ?>
							        <img src="../../themes/beyondT/pictures/btn_edit.jpg" onClick="alert('<?=$sysConst->accessDenied?>');">
			<?			}  ?>
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

<?
} else {
?>
&nbsp;
        
<br><br>
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
                      <td>Subject</td>
    				  <td><select <?=$locRights['add'] ? '':'disabled'?> name="cmbSubject">
					  					<option value=0>-Select Subject-</option>
<?					  
					$sublist = $this->popArr['sublistUnAss'];
					for($c=0;count($sublist)>$c;$c++) 
						echo "<option value='" . $sublist[$c][0] . "'>" . $sublist[$c][1] . "</option>";
?>					  
					  </select></td>
					</tr>
				  <tr> 
						<td>Marks</td>
						<td><input type="text" <?=$locRights['add'] ? '':'disabled'?> name="txtMarks"></td>
					  </tr>
					  <tr> 
						<td>Year</td>
						<td><input type="text" <?=$locRights['add'] ? '':'disabled'?> name="txtYear"></td>
					  </tr>
					  <tr> 
						<td>Comments</td>
						<td><textarea name="txtComment" <?=$locRights['add'] ? '':'disabled'?>></textarea></td>
					  </tr>
					  <tr> 
						<td>Rating Grade</td>
						<td><select <?=$locRights['add'] ? '':'disabled'?> name="cmbRatGrd">
									<option value="0">-Select Grade-</option>
<?
						$ratlist = $this->popArr['ratlist'];
						for($c=0;count($ratlist)>$c;$c++) 
								echo "<option value='" . $ratlist[$c][0] . "'>" . $ratlist[$c][1] . "</option>";
?>						
						</select></td>
					  </tr>
						  <tr> 
						<td valign="top"></td>
						<td align="left" valign="top">
<?	if($locRights['add']) { ?>
        <img border="0" title="Save" onClick="addEXT();" onmouseout="this.src='../../themes/beyondT/pictures/btn_save.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_save_02.jpg';" src="../../themes/beyondT/pictures/btn_save.jpg">
<? 	} else { ?>
        <img onClick="alert('<?=$sysConst->accessDenied?>');" src="../../themes/beyondT/pictures/btn_save.jpg">
<?	} ?>
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
<? } ?>

<table width='100%' cellpadding='0' cellspacing='0' border='0'>
  <tr>
    <td valign='top'>&nbsp; </td>
    <td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'></td>
  </tr>

  <tr>

    <td width='100%'><h3>Assigned Qualifications</h3></td>
    <td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'><A href='index.php?module=Contacts&action=index&return_module=Contacts&return_action=DetailView&&print=true' class='utilsLink'></td>
  </tr>
  <tr>
  <td>
<?	if($locRights['delete']) { ?>
        <img title="Delete" onclick="delEXT();" onmouseout="this.src='../../themes/beyondT/pictures/btn_delete.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_delete_02.jpg';" src="../../themes/beyondT/pictures/btn_delete.jpg">
<? 	} else { ?>
        <img onClick="alert('<?=$sysConst->accessDenied?>');" src="../../themes/beyondT/pictures/btn_delete.jpg">
<? 	} ?>
  </td>
  </tr>
<tr><td>&nbsp;</td></tr>
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
                      	<td></td>
						 <td><strong>Subject</strong></td>
						 <td><strong>Marks</strong></td>
						 <td><strong>Year</strong></td>
						 <td><strong>Rating Grade</strong></td>
					</tr>
<?
$rset = $this->popArr['sublistAss'];
$sublist = $this->popArr['sublistAll'];
$ratlist = $this->popArr['ratlist'];

    for($c=0;$rset && $c < count($rset); $c++)
        {
        echo '<tr>';
            echo "<td><input type='checkbox' class='checkbox' name='chkdel[]' value='" . $rset[$c][2] . "'></td>";
			for($a=0;count($sublist)>$a;$a++)
			    if($sublist[$a][1]==$rset[$c][2]) 
		            echo "<td><a href='". $_SERVER['PHP_SELF'] ."?reqcode=" . $this->getArr['reqcode'] . "&id=" . $this->getArr['id']."&QUA=" . $this->getArr['QUA']. "&editID=" . $rset[$c][2] . "'>" . $sublist[$a][2] . "</a></td>";
            echo '<td>' . $rset[$c][3] .'</td>';
            echo '<td>' . $rset[$c][4] .'</td>';
			for($a=0;count($ratlist)>$a;$a++)
				if($ratlist[$a][0] == $rset[$c][6])
            		echo '<td>' . $ratlist[$a][1] .'</td>';
        echo '</tr>';
        }

?>
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
