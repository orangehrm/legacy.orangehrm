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
    if(code>=48 && code<=57)
	   flag=true;
	else
	   {
	   flag=false;
	   break;
	   }
	}
return flag;
}


function addEXT()
{
	if(document.frmEmpJobSpec.cmbJDCat.value=='0') {
		alert("Field should be selected! ");
		document.frmEmpJobSpec.cmbJDCat.focus();
		return;
	}
	
	if(document.frmEmpJobSpec.txtJobSpec.value=='') {
		alert("Decription not defined! ");
		return;
	}
	
  document.frmEmpJobSpec.STAT.value="ADD";
  document.frmEmpJobSpec.submit();
}

function edit()
{
	if(document.Edit.title=='Save') {
		editEXT();
		return;
	}
	
	var frm=document.frmEmpJobSpec;
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
		location.href = "./CentralController.php?reqcode=<?=$this->getArr['reqcode']?>&VIEW=MAIN";
	}

function editEXT()
{
	if(document.frmEmpJobSpec.txtJobSpec.value=='') {
		alert("Decription not defined! ");
		return;
	}
	
  document.frmEmpJobSpec.STAT.value="EDIT";
  document.frmEmpJobSpec.submit();
}

function delEXT()
{
      var check = 0;
		with (document.frmEmpJobSpec) {
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
    document.frmEmpJobSpec.STAT.value="DEL";
    document.frmEmpJobSpec.submit();
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
    <td width='100%'><h2>Employee Job Specification</h2></td>
    <td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'></td>
  </tr>
</table>
<p>
<p>
<table width="431" border="0" cellspacing="0" cellpadding="0" ><td width="177">
<form name="frmEmpJobSpec" method="post" action="<?=$_SERVER['PHP_SELF']?>?reqcode=<?=$this->getArr['reqcode']?>&id=<?=$this->getArr['id']?>">

  <tr>
    <td height="27" valign='top'> <p><img title="Back" onmouseout="this.src='../../themes/beyondT/pictures/btn_back.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_back_02.jpg';"  src="../../themes/beyondT/pictures/btn_back.jpg" onclick="goBack();">
        <input type="hidden" name="STAT" value="">
      </p></td>
    <td width="254" align='left' valign='bottom'> <font color="red" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
      </font> </td>
  </tr><td width="177">
</table>

<?
  $empdet = $this->popArr['empdet'];
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

              
 <?
   
 if(isset($this -> popArr['editArr']))
{
        $edit=$this -> popArr['editArr']; 

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
                      <td>Category</td>
    				  <td><input type="hidden" name="cmbJDCat" value="<?=$edit[0][1]?>"><strong>
<?					  
						
					$catlist=$this->popArr['catlist'];
						for($c=0;count($catlist)>$c;$c++)
							if($edit[0][1]==$catlist[$c][0])
							   echo $catlist[$c][1];
?>					  
						</strong></td>
					</tr>
					  <tr> 
						<td valign="middle">Description</td>
						<td align="left" valign="top"><textarea disabled name="txtJobSpec"><?=$edit[0][2]?></textarea>
						</td>
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

<? } else { ?>
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
                      <td>Category</td>
    				  <td>
					  <select <?=$locRights['add'] ? '':'disabled'?> name="cmbJDCat">
					  		<option value="0">-------Select Category-------</option>
<?					  
						 $catlist=$this -> popArr['unasscatlist'];
						for($c=0;$catlist && count($catlist)>$c;$c++)
							   echo "<option value=" . $catlist[$c][0] . ">" . $catlist[$c][1] . "</option>";

?>					  
					  </select></td>
					</tr>
					  <tr> 
						<td valign="middle">Description</td>
						<td align="left" valign="top"><textarea <?=$locRights['add'] ? '':'disabled'?> name="txtJobSpec"></textarea>
						</td>
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

    <td width='100%'><h3>Assigned Job Specifications</h3></td>
    <td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'></td>
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
						 <td><strong>JD Category</strong></td>
						 <td><strong>Description</strong></td>
					</tr>
<?
$rset =$this -> popArr['rset'];
$catlist= $this -> popArr['catlist'];


    for($c=0; $rset && $c < count($rset); $c++)
        {
        echo '<tr>';
            echo "<td><input type='checkbox' class='checkbox' name='chkdel[]' value='" . $rset[$c][1] ."'></td>";
			for($a=0;count($catlist)>$a;$a++)
			    if($catlist[$a][0]==$rset[$c][1])
				   $fname=$catlist[$a][1];
            echo "<td><a href='". $_SERVER['PHP_SELF'] ."?reqcode=" . $this->getArr['reqcode'] . "&id=" . $this->getArr['id']. "&editID=" . $rset[$c][1] . "'>" . $fname . "</a></td>";

			if(strlen($rset[$c][2])<50)
				$fname=$rset[$c][2];
			else {
				$fname = substr($rset[$c][2],0,50);
				$fname = $fname . '...';
				}
			
            echo '<td>' . $fname .'</td>';
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
