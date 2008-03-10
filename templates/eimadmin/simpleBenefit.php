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

$formAction="{$_SERVER['PHP_SELF']}?uniqcode={$this->getArr['uniqcode']}";
$new = true;
$disabled = '';
$btnAction="addSave()";
if ((isset($this->getArr['capturemode'])) && ($this->getArr['capturemode'] == 'updatemode')) {
	$formAction="{$formAction}&id={$this->getArr['id']}&capturemode=updatemode";
	$btnAction="addUpdate()";
	$new = false;
	$disabled = "disabled='true'";
}

$benefit = $this->popArr['benefit'];
$benefits = $this->popArr['benefitList'];
$locRights=$_SESSION['localRights'];

$picDir = "../../themes/{$styleSheet}/pictures/";
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../../scripts/archive.js"></script>
<script type="text/javascript" src="../../scripts/octopus.js"></script>
<script>

	var editMode = <?php echo $new ? 'true' : 'false'; ?>;

    var names = new Array();
<?php
	$nameOfThisBen = $benefit->getName();
	foreach($benefits as $ben) {
		$name = $ben->getName();
		if ($name != $nameOfThisBen) {
	   		print "\tnames.push(\"{$name}\");\n";
		}
	}
?>

    function goBack() {
        location.href = "./CentralController.php?uniqcode=<?php echo $this->getArr['uniqcode']?>&VIEW=MAIN";
    }

	function validate() {
		err = false;
		msg = '<?php echo $lang_Error_PleaseCorrectTheFollowing; ?>\n\n';

		errors = new Array();

		name = trim($('txtFieldName').value);
        if (name == '') {
			err = true;
			msg += "\t- <?php echo $lang_benefits_PleaseSpecifyBenefitName; ?>\n";
        } else if (isNameInUse(name)) {
			err = true;
			msg += "\t- <?php echo $lang_benefits_NameInUse_Error; ?>\n";
        }

		if (err) {
			alert(msg);
			return false;
		} else {
			return true;
		}
	}

    function save() {

		if (validate()) {
        	$('frmBenefit').sqlState.value = "<?php echo $new ? 'NewRecord' : 'UpdateRecord'; ?>";
        	$('frmBenefit').submit();
		} else {
			return false;
		}
    }

	function reset() {
		$('frmBenefit').reset();
        oLink = $('messageCell');
        oLink.innerHTML = "&nbsp;";
	}


	function isNameInUse(name) {
		n = names.length;
		for (var i=0; i<n; i++) {
			if (names[i] == name) {
				return true;
			}
		}
		return false;
	}

	function checkName() {
		name = trim($('txtFieldName').value);
		oLink = $('messageCell');

		if (isNameInUse(name)) {
			oLink.innerHTML = "<?php echo $lang_benefits_NameInUse_Error; ?>";
		} else {
			oLink.innerHTML = "&nbsp;";
		}
	}

	function mout() {
		if(editMode) {
			$('editBtn').src='<?php echo $picDir;?>btn_save.gif';
		} else {
			$('editBtn').src='<?php echo $picDir;?>btn_edit.gif';
		}
	}

	function mover() {
		if(editMode) {
			$('editBtn').src='<?php echo $picDir;?>btn_save_02.gif';
		} else {
			$('editBtn').src='<?php echo $picDir;?>btn_edit_02.gif';
		}
	}

	function edit()	{

<?php if($locRights['edit']) { ?>
		if (editMode) {
			save();
			return;
		}
		editMode = true;
		var frm = $('frmBenefit');

		for (var i=0; i < frm.elements.length; i++) {
			frm.elements[i].disabled = false;
		}
		$('editBtn').src="<?php echo $picDir;?>btn_save.gif";
		$('editBtn').title="<?php echo $lang_Common_Save; ?>";

<?php } else {?>
		alert('<?php echo $lang_Common_AccessDenied;?>');
<?php } ?>
	}

</script>

    <link href="../../themes/<?php echo $styleSheet;?>/css/style.css" rel="stylesheet" type="text/css">
    <style type="text/css">@import url("../../themes/<?php echo $styleSheet;?>/css/style.css"); </style>

    <style type="text/css">
    <!--

    label,input, .value {
        display: block;  /* block float the labels to left column, set a width */
        float: left;
        margin: 10px 0px 2px 0px; /* set top margin same as form input - textarea etc. elements */
    }

    /* this is needed because otherwise, hidden fields break the alignment of the other fields */
    input[type=hidden] {
        display: none;
        border: none;
        background-color: red;
    }

    label {
        text-align: left;
        width: 100px;
        padding-left: 10px;
    }

    input, .value {
        width: 200px;
        padding-left: 4px;
        padding-right: 4px;
    }

    form {
        min-width: 550px;
        max-width: 600px;
    }

    br {
        clear: left;
    }

    .roundbox {
        margin-top: 10px;
        margin-left: 0px;
        width: 500px;
    }

    .roundbox_content {
        padding:15px;
    }

	.hidden {
		display: none;
	}

	.display-block {
		display: block;
	}
    -->
</style>
</head>
<body>
	<p>
		<table width='100%' cellpadding='0' cellspacing='0' border='0' class='moduleTitle'>
			<tr>
		  		<td width='100%'>
		  			<h2><?php echo $lang_benefits_heading; ?></h2>
		  		</td>
	  			<td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'></td>
	  		</tr>
		</table>
	</p>
  	<div id="navigation" style="margin:0;">
  		<img title="Back" onMouseOut="this.src='<?php echo $picDir;?>btn_back.gif';"
  			 onMouseOver="this.src='<?php echo $picDir;?>btn_back_02.gif';"
  			 src="<?php echo $picDir;?>btn_back.gif" onClick="goBack();">
	</div>
    <?php $message =  isset($this->getArr['msg']) ? $this->getArr['msg'] : (isset($this->getArr['message']) ? $this->getArr['message'] : null);
    	if (isset($message)) {
			$col_def = CommonFunctions::getCssClassForMessage($message);
			$message = "lang_Common_" . $message;
	?>
	<div class="message">
		<font class="<?php echo $col_def?>" size="-1" face="Verdana, Arial, Helvetica, sans-serif">
			<?php echo (isset($$message)) ? $$message: ""; ?>
		</font>
	</div>
	<?php }	?>
  <div class="roundbox">
  <form name="frmBenefit" id="frmBenefit" method="post" action="<?php echo $formAction;?>">
        <input type="hidden" name="sqlState" value="">
        <?php if (!$new) { ?>
            <label for="txtId">&nbsp;<?php echo $lang_benefits_id; ?></label>
            <span class="value"><?php echo $benefit->getId(); ?></span><br />
        <?php } ?>
			<input type="hidden" id="txtId" name="txtId" value="<?php echo $benefit->getId();?>"/>
			<label for="txtFieldName"><span class="error">*</span><?php echo $lang_Commn_name; ?></label>
            <input type="text" id="txtFieldName" name="txtFieldName" tabindex="1"
            	value="<?php echo CommonFunctions::escapeHtml($benefit->getName()); ?>" onblur="checkName()" onkeyup="checkName();" <?php echo $disabled;?> />
            <div id="messageCell" class="error" style="display:block; float: left; margin:10px;">&nbsp;</div>
            <br /><br />
            <div align="left">
	            <img onClick="edit();" id="editBtn"
	            	onMouseOut="mout();" onMouseOver="mover();"
	            	src="<?php echo $picDir . ($new ? 'btn_save.gif' : 'btn_edit.gif');?>">
				<img id="saveBtn" src="<?php echo $picDir;?>btn_clear.gif"
				onMouseOut="this.src='<?php echo $picDir;?>btn_clear.gif';"
				onMouseOver="this.src='<?php echo $picDir;?>btn_clear_02.gif';" onClick="reset();" >
            </div>
	</form>
    </div>
    <script type="text/javascript">
        <!--
        	if (document.getElementById && document.createElement) {
   	 			initOctopus();
			}
        -->
    </script>

    <div id="notice"><?php echo preg_replace('/#star/', '<span class="error">*</span>', $lang_Commn_RequiredFieldMark); ?>.</div>
</body>
</html>
