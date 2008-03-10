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

$picDir = '../../themes/' . $styleSheet . '/pictures/';
?>
<script language="JavaScript">

function moutBenefit() {
    if(document.editBenefitBtn.title=='<?php echo $lang_Common_Save;?>') {
        document.editBenefitBtn.src='<?php echo $picDir;?>btn_save.gif';
    } else {
        document.editBenefitBtn.src='<?php echo $picDir;?>btn_edit.gif';
    }
}

function moverBenefit() {
    if(document.editBenefitBtn.title=='<?php echo $lang_Common_Save;?>') {
        document.editBenefitBtn.src='<?php echo $picDir;?>btn_save_02.gif';
    } else {
        document.editBenefitBtn.src='<?php echo $picDir;?>btn_edit_02.gif';
    }
}

function editBenefit() {

	if(document.editBenefitBtn.title=='<?php echo $lang_Common_Save;?>') {
		updateBenefit();
		return;
	}

	var frm=document.frmEmp;
	for (var i=0; i < frm.elements.length; i++) {
		frm.elements[i].disabled = false;
    }

	document.editBenefitBtn.src="<?php echo $picDir;?>btn_save.gif";
	document.editBenefitBtn.title="<?php echo $lang_Common_Save;?>";
}

function validate() {

    err = false;
    msg = '<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

    errors = new Array();

    if ($('cmbBenefitId').value == '0') {
        err = true;
        msg += "\t- <?php echo $lang_hrEmpMain_PleaseSpecifyBenefit; ?>\n";
    }

    var amount = $('txtBenefitAmount').value.trim();
    if (amount == '') {
        err = true;
        msg += "\t- <?php echo $lang_hrEmpMain_PleaseSpecifyAmount; ?>\n";
    } else if (!isDecimal(amount)) {
        err = true;
        msg += "\t- <?php echo $lang_hrEmpMain_PleaseSpecifyValidAmount; ?>\n";
    }

    if ($('cmbCurrencyCode').value == '0') {
        err = true;
        msg += "\t- <?php echo $lang_hrEmpMain_PleaseSpecifyCurrency; ?>\n";
    }

    if (err) {
        alert(msg);
        return false;
    } else {
        return true;
    }

    return false;
}

function addBenefit() {

    if (!validate()) {
        return false;
    }
	document.frmEmp.benefitSTAT.value="ADD";
	qCombo(18);
}

function decimal(txt) {
	regExp = /^[0-9]+(\.[0-9]+){0,1}$/;

	if (regExp.test(txt)) {
		return true;
	}

	return false;
}

function updateBenefit() {

    if (!validate()) {
        return false;
    }
    document.frmEmp.benefitSTAT.value="EDIT";
    qCombo(18);
}

function deleteBenefit() {

	var check = false;
	with (document.frmEmp) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].name == 'chkBenefitDel[]') && (elements[i].checked == true)) {
				check = true;
			}
		}
	}

	if(!check) {
		alert('<?php echo $lang_Error_SelectAtLeastOneRecordToDelete; ?>')
		return;
	}

    document.frmEmp.benefitSTAT.value="DEL";
    qCombo(18);
}

function viewBenefit(benefitId) {
	document.frmEmp.action = document.frmEmp.action + "&benefit=" + benefitId;
	document.frmEmp.pane.value = 18;
	document.frmEmp.submit();
}
</script>
<span id="parentPaneBenefits">
<?php if(isset($this->getArr['capturemode']) && $this->getArr['capturemode'] == 'updatemode') { ?>
    <input type="hidden" name="benefitSTAT" value="">
<?php
if(isset($this->popArr['empBenefit'])) {
    $empBenefit = $this->popArr['empBenefit'];
?>
	<div id="editPaneBenefits" >
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="">
        <tr>
          <td width="200"><?php echo $lang_hrEmpMain_BenefitType?></td>
		  <td><input type="hidden" id="txtEmpBenefitId" name="txtEmpBenefitId" value="<?php echo $empBenefit->getId();?>">
            <select name="cmbBenefitId" id="cmbBenefitId"
                <?php echo isset($this->popArr['cmbBenefitId']) ? '':'disabled'?>
                <option selected value="0">--- <?php echo $lang_hrEmpMain_BenefitSelectBenefit;?> ---</option>
<?php
        $benefitList = $this->popArr['benefitList'];
        $selBenefit = $empBenefit->getBenefitId();
        foreach($benefitList as $benefit) {
            $selected = ($selBenefit == $benefit->getId()) ? 'selected' : '';
            echo "<option $selected value=" . $benefit->getId() . ">" . CommonFunctions::escapeHtml($benefit->getName()) . "</option>";
        }
?>
            </select></td>
		</tr>
        <tr>
            <td><?php echo $lang_hrEmpMain_BenefitDescription?></td>
            <td><textarea <?php echo isset($this->popArr['txtBenefitDesc']) ? '':'disabled'?> name="txtBenefitDesc"
                id="txtBenefitDesc"><?php echo isset($this->popArr['txtBenefitDesc']) ? $this->popArr['txtBenefitDesc'] : CommonFunctions::escapeHtml($empBenefit->getDescription());?></textarea>
            </td>
          <td width="50">&nbsp;</td>
         </tr>
		<tr>
          <td><?php echo $lang_hrEmpMain_BenefitAmount?></td>
		  <td><input type="text" name="txtBenefitAmount" id="txtBenefitAmount"
                <?php echo isset($this->popArr['txtBenefitAmount']) ? '':'disabled'?>
                value="<?php echo isset($this->popArr['txtBenefitAmount']) ? $this->popArr['txtBenefitAmount'] : CommonFunctions::escapeHtml($empBenefit->getAmount());?>">
          </td>
		  <td width="50">&nbsp;</td>
		</tr>
        <tr>
          <td><?php echo $lang_hrEmpMain_BenefitCurrency?></td>
          <td>
            <select name="cmbCurrencyCode" id="cmbCurrencyCode" <?php echo isset($this->popArr['cmbCurrencyCode']) ? '':'disabled'?> >
                <option value="0">-- <?php echo $lang_hrEmpMain_BenefitCurrency; ?> --</option>
<?php
            $curlist = $this->popArr['currAlllist'];
            $selCurrency = $empBenefit->getCurrencyId();
            foreach($curlist as $currency) {
                $selected = $currency[0] == $selCurrency ? 'selected' : '';
                echo "<option $selected value=" . $currency[0] . ">" . $currency[1] . "</option>";
            }
?>
                </select>
          </td>
          <td width="50">&nbsp;</td>
        </tr>

    	  <tr>
    		<td valign="top"></td>
    		<td align="left" valign="top">
		<?php			if($locRights['edit']) { ?>
	        <img src="<?php echo $picDir;?>btn_edit.gif" title="<?php echo $lang_Common_Edit;?>"
                onmouseout="moutBenefit();" onmouseover="moverBenefit();"
                name="editBenefitBtn" onClick="editBenefit();">
		<?php			}  ?>
			</td>
		  </tr>
  </table>
</div>
<?php } else { ?>
<div id="addPaneBenefits" class="<?php echo (!empty($this->popArr['empBenefitList']))?"addPane":""; ?>" >
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="">
    <tr>
      <td width="200"><?php echo $lang_hrEmpMain_BenefitType; ?></td>
	  <td><select name="cmbBenefitId" id="cmbBenefitId" <?php echo $locRights['add'] ? '':'disabled'?>>
	  		<option selected value="0">--- <?php echo $lang_hrEmpMain_BenefitSelectBenefit;?> ---</option>
<?php
        $benefitList = $this->popArr['benefitList'];
		foreach($benefitList as $benefit) {
		  echo "<option value=" . $benefit->getId() . ">" . CommonFunctions::escapeHtml($benefit->getName()) . "</option>";
        }
?>
	  </select></td>
	</tr>
    <tr>
        <td><?php echo $lang_hrEmpMain_BenefitDescription?></td>
        <td><textarea <?php echo $locRights['add'] ? '':'disabled'?> name="txtBenefitDesc"
            id="txtBenefitDesc"><?php echo isset($this->popArr['txtBenefitDesc']) ? $this->popArr['txtBenefitDesc'] : '';?></textarea>
        </td>
      <td width="50">&nbsp;</td>
     </tr>
    <tr>
      <td><?php echo $lang_hrEmpMain_BenefitAmount?></td>
      <td><input type="text" name="txtBenefitAmount" id="txtBenefitAmount"
            <?php echo $locRights['add'] ? '':'disabled'?>
            value="<?php echo isset($this->popArr['txtBenefitAmount']) ? $this->popArr['txtBenefitAmount'] : '';?>">
      </td>
      <td width="50">&nbsp;</td>
    </tr>
    <tr>
      <td><?php echo $lang_hrEmpMain_BenefitCurrency?></td>
      <td>
        <select name="cmbCurrencyCode" id="cmbCurrencyCode"
            <?php echo $locRights['add'] ? '':'disabled'?>>
            <option value="0">-- <?php echo $lang_hrEmpMain_BenefitCurrency; ?> --</option>
<?php
            $curlist = $this->popArr['currAlllist'];
            foreach($curlist as $currency) {
                echo "<option value=" . $currency[0] . ">" . $currency[1] . "</option>";
            }
?>
            </select>
      </td>
      <td width="50">&nbsp;</td>
    </tr>
	  <tr>
		<td valign="top"></td>
		<td align="left" valign="top">
	<?php	if($locRights['add']) { ?>
	        <img border="0" title="<?php echo $lang_Common_Save;?>" onClick="addBenefit();" onmouseout="this.src='<?php echo $picDir;?>btn_save.gif';" onmouseover="this.src='<?php echo $picDir;?>btn_save_02.gif';" src="<?php echo $picDir;?>btn_save.gif">
	<?php	} ?>
				</td>
	  </tr>
  </table>
</div>
<?php } ?>
<?php
$empBenefitList = $this->popArr['empBenefitList'] ;

if (!empty($empBenefitList)) { ?>
<h3><?php echo $lang_hrEmpMain_AssignedBenefits?></h3>
<?php	if($locRights['add']) { ?>
		<img border="0" title="Add" onClick="showAddPane('Benefits');" onmouseout="this.src='<?php echo $picDir;?>btn_add.gif';" onmouseover="this.src='<?php echo $picDir;?>btn_add_02.gif';" src="<?php echo $picDir;?>btn_add.gif">
<?php	} ?>
<?php	if($locRights['delete']) { ?>
        <img title="Delete" onclick="deleteBenefit();" onmouseout="this.src='<?php echo $picDir;?>btn_delete.gif';" onmouseover="this.src='<?php echo $picDir;?>btn_delete_02.gif';" src="<?php echo $picDir;?>btn_delete.gif">
<?php 	} ?>

<?php
    /* Check if all benefits use the same currency and if so show the currency on the heading*/
    $usedCurrencies = array();
    foreach($empBenefitList as $empBenefit) {
        $curId = $empBenefit->getCurrencyId();
        $usedCurrencies[$curId] = $curId;
    }
    if (count($usedCurrencies) === 1) {
        $showCurrencyInHeader = true;
        $amountHeader = $lang_hrEmpMain_BenefitAmount . " (".$curId.")";
    } else {
        $showCurrencyInHeader = false;
        $amountHeader = $lang_hrEmpMain_BenefitAmount;
    }

?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tabForm">
	<tr>
      	<td></td>
		 <td><strong><?php echo $lang_hrEmpMain_Benefit?></strong></td>
		 <td><strong><?php echo $amountHeader?></strong></td>
         <td><strong><?php echo $lang_hrEmpMain_BenefitDescription?></strong></td>
	</tr>
<?php
    $localeUtil = LocaleUtil::getInstance();
    $sysConf = new sysConf();
    foreach($empBenefitList as $empBenefit) {
        $amount = $localeUtil->formatMoney($empBenefit->getAmount());
        if (!$showCurrencyInHeader) {
            $amount .= ' ' . $empBenefit->getCurrencyId();
        }
?>
    <tr>
        <td>
<?php   if($locRights['delete']) { ?>
        <input type='checkbox' class='checkbox' name='chkBenefitDel[]' value="<?php echo $empBenefit->getId();?>">
<?php } ?>
        </td>
        <td><a href="javascript:viewBenefit('<?php echo $empBenefit->getId(); ?>')">
            <?php echo CommonFunctions::escapeHtml($empBenefit->getBenefitName());?></a></td>
        <td><?php echo CommonFunctions::escapeHtml($amount); ?></td>
        <td><?php echo CommonFunctions::escapeHtml(CommonFunctions::getFirstNChars($empBenefit->getDescription(), $sysConf->viewDescLen, '...')); ?></td>
    </tr>
<?php } ?>
  </table>
<?php
   }
}
?>
</span>