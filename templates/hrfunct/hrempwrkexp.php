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
?>
<script language="JavaScript">

function editWrkExp() {

	if(document.EditWrkExp.title=='Save') {
		editEXTWrkExp();
		return;
	}

	var frm=document.frmEmp;
	for (var i=0; i < frm.elements.length; i++)
		frm.elements[i].disabled = false;

	document.EditWrkExp.src="../../themes/beyondT/pictures/btn_save.jpg";
	document.EditWrkExp.title="Save";
}

function moutWrkExp() {
	if(document.EditWrkExp.title=='Save')
		document.EditWrkExp.src='../../themes/beyondT/pictures/btn_save.jpg';
	else
		document.EditWrkExp.src='../../themes/beyondT/pictures/btn_edit.jpg';
}

function moverWrkExp() {
	if(document.EditWrkExp.title=='Save')
		document.EditWrkExp.src='../../themes/beyondT/pictures/btn_save_02.jpg';
	else
		document.EditWrkExp.src='../../themes/beyondT/pictures/btn_edit_02.jpg';
}

function createDate(str) {
		var yy=eval(str.substr(0,4));
		var mm=eval(str.substr(5,2)) - 1;
		var dd=eval(str.substr(8,2));

		var tempDate = new Date(yy,mm,dd);

		return tempDate;
}

function addEXTWrkExp() {

 	var txt = document.frmEmp.txtEmpExpEmployer;
	if (txt.value == '') {
		alert("<?php echo $lang_Common_FieldEmpty; ?>!");
		txt.focus();
		return false;
	}

    var txt = document.frmEmp.txtEmpExpJobTitle;
	if (txt.value == '') {
		alert("<?php echo $lang_Common_FieldEmpty; ?>!");
		txt.focus();
		return false;
	}

	var fromDate = createDate(document.getElementById('atxtEmpExpFromDate').value)
	var toDate = createDate(document.getElementById('atxtEmpExpToDate').value);
	var currentDate = document.getElementById('atxtEmpExpToDate').value;

	if (!(currentDate == "0000-00-00")){
		if(fromDate >= toDate){
			alert("<?php echo $lang_hremp_FromDateShouldBeBeforeToDate; ?>");

			return;
		}
	}

  document.frmEmp.wrkexpSTAT.value="ADD";
  qCombo(17);
}

function editEXTWrkExp() {

 	var txt = document.frmEmp.txtEmpExpEmployer;
	if (txt.value == '') {
		alert ("<?php echo $lang_Common_FieldEmpty; ?>!");
		txt.focus();
		return false;
	}

    var txt = document.frmEmp.txtEmpExpJobTitle;
	if (txt.value == '') {
		alert ("<?php echo $lang_Common_FieldEmpty; ?>!");
		txt.focus();
		return false;
	}

	var fromDate = createDate(document.getElementById('etxtEmpExpFromDate').value)
	var toDate = createDate(document.getElementById('etxtEmpExpToDate').value);
	var currentDate = document.getElementById('etxtEmpExpToDate').value;


///////////To validate the date fields

if (!(currentDate == "0000-00-00")){

	if(fromDate >= toDate){
		alert("<?php echo $lang_hremp_FromDateShouldBeBeforeToDate; ?>");

		return;
	}
	}
/////////////////////


  document.frmEmp.wrkexpSTAT.value="EDIT";
  qCombo(17);
}

function delEXTWrkExp() {

	var check = false;
	with (document.frmEmp) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].name == 'chkwrkexpdel[]') && (elements[i].checked == true)) {
				check = true;
			}
		}
	}

	if(!check) {
		alert('<?php echo $lang_Error_SelectAtLeastOneRecordToDelete; ?>')
		return;
	}

    document.frmEmp.wrkexpSTAT.value="DEL";
    qCombo(17);
}

function viewWrkExp(wrkexp) {

	document.frmEmp.action = document.frmEmp.action + "&WRKEXP=" + wrkexp;
	document.frmEmp.pane.value = 17;
	document.frmEmp.submit();
}

</script>
<span id="parentPaneWorkExperience" >
<?php if(isset($this->getArr['capturemode']) && $this->getArr['capturemode'] == 'updatemode') { ?>

    <input type="hidden" name="wrkexpSTAT" value="">
<?php
if(isset($this->popArr['editWrkExpArr'])) {
    $edit = $this->popArr['editWrkExpArr'];
?>
<div id="editPaneWorkExperience" >
      <input type="hidden" name="txtEmpExpID" value="<?php echo $this->getArr['WRKEXP']?>">
      <table border="0" cellpadding="5" cellspacing="0">
                    <tr>
                      <td><?php echo $lang_hrEmpMain_employer?></td>
    				  <td><input type="text" name="txtEmpExpEmployer" disabled value="<?php echo $edit[0][2]?>"></td>
    				  <td width="50">&nbsp;</td>
					  <td nowrap><?php echo $lang_hrEmpMain_startdate?></td>
					  <td nowrap>
					  	<input type="text" readonly name="txtEmpExpFromDate" id="etxtEmpExpFromDate" value="<?php echo (strtotime($edit[0][4]) == 943898400)?"0000-00-00":date("Y-m-d", strtotime($edit[0][4])); ?>" size="12" />
					  	<input disabled type="button" class="button" value="..." onclick="YAHOO.OrangeHRM.calendar.pop('etxtEmpExpFromDate', 'cal1Container', 'yyyy-MM-dd'); return false;"></td>
					</tr>
					  <tr>
						<td><?php echo $lang_empview_JobTitle?></td>
						<td> <input type="text" disabled name="txtEmpExpJobTitle" value="<?php echo $edit[0][3]?>"></td>
    				  <td width="50">&nbsp;</td>
						<td nowrap><?php echo $lang_hrEmpMain_enddate?></td>
						<td nowrap>
							<input type="text" name="txtEmpExpToDate" id="etxtEmpExpToDate" readonly value="<?php echo (strtotime($edit[0][5]) == 943898400)?"0000-00-00":date("Y-m-d", strtotime($edit[0][5])); ?>" size="12" />
							<input disabled type="button" class="button" value="..." onclick="YAHOO.OrangeHRM.calendar.pop('etxtEmpExpToDate', 'cal1Container', 'yyyy-MM-dd'); return false;"></td>
					  </tr>
					  <tr valign="top">
						<td><?php echo $lang_Leave_Common_Comments; ?></td>
						<td> <textarea disabled name="txtEmpExpComments"><?php echo $edit[0][6]?></textarea></td>
    				  	<td width="50">&nbsp;</td>
						<td width="50"><?php echo $lang_hrEmpMain_internal?></td>
						<td width="50"><input type="checkbox" name="chkEmpExpInternal" disabled value="1" <?php echo (isset($edit[0][7]) && ($edit[0][7] == 1)) ? 'checked' : '' ?>/></td>
						<td width="50">&nbsp;</td>
					 </tr>
					 <tr>
						<td valign="top"></td>
						<td align="left" valign="top">
		<?php		if($locRights['edit']) { ?>
			        <img src="../../themes/beyondT/pictures/btn_edit.jpg" title="Edit" onmouseout="moutWrkExp();" onmouseover="moverWrkExp();" name="EditWrkExp" onClick="editWrkExp();">
		<?php		} 	 ?>
						</td>
	    </tr>
	</table>
</div>
<?php } else { ?>
<div id="addPaneWorkExperience" class="<?php echo ($this->popArr['rsetWrkExp'] != null)?"addPane":""; ?>" >
    	<input type="hidden" name="txtEmpExpID"  value="<?php echo $this->popArr['newWrkExpID']?>">
		<table width="100%" border="0" cellpadding="5" cellspacing="0" class="">
          <tr>
            <td><?php echo $lang_hrEmpMain_employer?></td>
            <td><input type="text" name="txtEmpExpEmployer" <?php echo $locRights['add'] ? '':'disabled'?> /></td>
            <td width="50">&nbsp;</td>
            <td nowrap><?php echo $lang_hrEmpMain_startdate?></td>
            <td nowrap>
            	<input type="text" name="txtEmpExpFromDate" id="atxtEmpExpFromDate" readonly value="0000-00-00" size="12" />
           		<input name="button" type="button" class="button" onclick="YAHOO.OrangeHRM.calendar.pop('atxtEmpExpFromDate', 'cal1Container', 'yyyy-MM-dd'); return false;" value="..." <?php echo $locRights['add'] ? '':'disabled'?> /></td>
          </tr>
          <tr>
            <td><?php echo $lang_empview_JobTitle?></td>
            <td><input type="text" name="txtEmpExpJobTitle" <?php echo $locRights['add'] ? '':'disabled'?> /></td>
            <td width="50">&nbsp;</td>
            <td nowrap><?php echo $lang_hrEmpMain_enddate?></td>
            <td nowrap>
            	<input type="text" name="txtEmpExpToDate" id="atxtEmpExpToDate" readonly value="0000-00-00" size="12" />
              	<input name="button" type="button" class="button" onclick="YAHOO.OrangeHRM.calendar.pop('atxtEmpExpToDate', 'cal1Container', 'yyyy-MM-dd'); return false;" value="..." <?php echo $locRights['add'] ? '':'disabled'?> /></td>
            <td width="50">&nbsp;</td>
          </tr>
          <tr valign="top">
            <td><?php echo $lang_Leave_Common_Comments; ?></td>
            <td><textarea <?php echo $locRights['add'] ? '':'disabled'?> name="txtEmpExpComments"></textarea></td>
            <td width="50">&nbsp;</td>
			<td width="50"><?php echo $lang_hrEmpMain_internal?></td>
			<td width="50"><input type="checkbox" name="chkEmpExpInternal" <?php echo $locRights['add'] ? '':'disabled'?> value="1"/></td>
			<td width="50">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"></td>
            <td align="left" valign="top"><?php	if($locRights['add']) { ?>
                <img border="0" title="Save" onclick="addEXTWrkExp();" onmouseout="this.src='../../themes/beyondT/pictures/btn_save.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_save_02.jpg';" src="../../themes/beyondT/pictures/btn_save.jpg" />
                <?php	} ?>
            </td>
          </tr>
        </table>
</div>
<?php } ?>
<?php
    $rset = $this->popArr['rsetWrkExp'];

    // check if there are any defined work experiences
    if( $rset && count($rset) > 0 ){
        $assignedExperiences = true;
    } else {
        $assignedExperiences = false;
    }
?>
<?php if($assignedExperiences) { ?>

<h3><?php echo $lang_hrEmpMain_assignworkex?></h3>
<?php	if($locRights['add']) { ?>
		<img border="0" title="Add" onClick="showAddPane('WorkExperience');" onmouseout="this.src='../../themes/beyondT/pictures/btn_add.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_add_02.jpg';" src="../../themes/beyondT/pictures/btn_add.jpg">
<?php } ?>
<?php	if($locRights['delete']) { ?>
        <img title="Delete" onclick="delEXTWrkExp();" onmouseout="this.src='../../themes/beyondT/pictures/btn_delete.jpg';" onmouseover="this.src='../../themes/beyondT/pictures/btn_delete_02.jpg';" src="../../themes/beyondT/pictures/btn_delete.jpg">
<?php 	} ?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="">

                  <tr>
                      	<td></td>
						 <td width="125"><strong><?php echo $lang_hrEmpMain_workexid?></strong></td>
						 <td width="110"><strong><?php echo $lang_hrEmpMain_employer; ?></strong></td>
						 <td width="100"><strong><?php echo $lang_hremp_jobtitle; ?></strong></td>
						 <td width="65"><strong><?php echo $lang_hrEmpMain_startdate; ?></strong></td>
						 <td width="65"><strong><?php echo $lang_hrEmpMain_enddate; ?></strong></td>
						 <td><strong><?php echo $lang_hrEmpMain_internal; ?></strong></td>
				</tr>
<?php
    for($c=0; $rset && $c < count($rset); $c++) {
        echo '<tr>';
            echo "<td><input type='checkbox' class='checkbox' name='chkwrkexpdel[]' value='" . $rset[$c][1] ."'></td>";
            ?><td><a href="javascript:viewWrkExp('<?php echo $rset[$c][1]?>')"><?php echo $rset[$c][1]?></a></td><?php
            echo '<td>' . $rset[$c][2] .'</td>';
            echo '<td>' . $rset[$c][3] .'</td>';
            $str = explode(" ",$rset[$c][4]);
            echo '<td>' . $str[0] .'</td>';
            $str = explode(" ",$rset[$c][5]);
            echo '<td>' . $str[0] .'</td>';
			$str = (isset($rset[$c][7]) && ($rset[$c][7] == 1))? '<img src="../../themes/beyondT/icons/flag.gif" alt="internal" width="22" height="19" title="Internal"/>' : '';
			echo '<td>' .$str.'</td>';
        echo '</tr>';
        }
?>
</table>
<?php } ?>
<?php } ?>
</span>