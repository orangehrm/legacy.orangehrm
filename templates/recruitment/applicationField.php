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

$baseURL = "{$_SERVER['PHP_SELF']}?recruitcode={$_GET['recruitcode']}";
$action = $_GET['action'];

if ($action == 'ViewAdd') {
	$new = true;
	$btnAction="addSave()";
	$heading = $lang_Recruit_JobVacancy_Add_Heading;
	$formAction = "{$baseURL}&action=Add";
	$disabled = '';
} else {
	$new = false;
	$btnAction="addUpdate()";
	$heading = $lang_Recruit_JobVacancy_Edit_Heading;
	$formAction = "{$baseURL}&action=Update";
	$disabled = "disabled='true'";
}

$applicationField = $records['applicationField'];
$fieldTypes=$records['fieldTypes'];
$locRights=$_SESSION['localRights'];
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../../scripts/archive.js"></script>
<script type="text/javascript" src="../../scripts/octopus.js"></script>
<script>

	var editMode = <?php echo $new ? 'true' : 'false'; ?>;
    function goBack() {
        location.href = "<?php echo $baseURL; ?>&action=List";
    }

    function save() { 
    var msg='';
    var err=false;
     	if(document.getElementById('cmbType').selectedIndex==0){     	
     		msg+='<?php echo 'Plese select a type'?> \n';
     		err=true;
     	}
     	var value = trim(document.getElementById('txtLable').value);		
     	if(value==''){     	
     		msg+='<?php echo 'Label canot be blank'?> \n';
     		err=true;
     	}
     	if(err){
     		alert(msg);
     	}else{
       		$('frmFormField').submit();
       	}
       			
    }

	function edit()	{

<?php if($locRights['edit']) { ?>
		if (editMode) {
			save();
			return;
		}
		editMode = true;
		var frm = $('frmFormField');
		
		for (var i=0; i < frm.elements.length; i++) {
			frm.elements[i].disabled = false;
		}
		$('editBtn').src="../../themes/<?php echo $styleSheet;?>/pictures/btn_save.gif";
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

    label,select,input,textarea {
        display: block;  /* block float the labels to left column, set a width */
        width: 150px;
        float: left;
        margin: 10px 0px 2px 0px; /* set top margin same as form input - textarea etc. elements */
    }
    input[type=checkbox] {
		width: 15px;
		background-color: transparent;
		vertical-align: bottom;
    }

    #active {
        width: 15px;
        height: 15px;
        background-color: transparent;
        vertical-align: bottom;
    }

    /* this is needed because otherwise, hidden fields break the alignment of the other fields */
    input[type=hidden] {
        display: none;
        border: none;
        background-color: red;
    }

    label {
        text-align: left;
        width: 110px;
        padding-left: 10px;
    }

    select,input,textarea {
        margin-left: 10px;
    }

    input,textarea {
        padding-left: 4px;
        padding-right: 4px;
    }

    textarea {
        width: 330px;
        height: 150px;
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

	#nomanagers {
		font-style: italic;
		color: red;
        padding-left: 10px;
        width: 400px;
        border: 1px;
	}
    -->
</style>
</head>
<body>
	<p>
		<table width='100%' cellpadding='0' cellspacing='0' border='0' class='moduleTitle'>
			<tr>
		  		<td width='100%'>
		  			<h2><?php echo $heading; ?></h2>
		  		</td>
	  			<td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'></td>
	  		</tr>
		</table>
	</p>
  	<div id="navigation" style="margin:0;">
  		<img title="Back" onMouseOut="this.src='../../themes/<?php echo $styleSheet;?>/pictures/btn_back.gif';"
  			 onMouseOver="this.src='../../themes/<?php echo $styleSheet;?>/pictures/btn_back_02.gif';"
  			 src="../../themes/<?php echo $styleSheet;?>/pictures/btn_back.gif" onClick="goBack();">
	</div>
    <?php $message =  isset($_GET['message']) ? $_GET['message'] : null;
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
  <form name="frmFormField" id="frmFormField" method="post" action="<?php echo $formAction;?>">
		<input type="hidden" id="txtId" name="txtId" value="<?php echo $applicationField->getId();?>"/><br/>
		<label for="cmbJobLable"><span class="error">*</span> <?php echo 'Lable'; ?></label>
			<input type="text" id="txtLable" name="txtLable" value="<?php echo $applicationField->getLable();?>"/><br/>
		<label><span class="error">*</span> <?php echo 'Type'?></label>
        <select id="cmbType" name="cmbType" tabindex="1" <?php echo (!$new)?"disabled='disabled'":''?>>
	        <option value="-1">-- <?php echo "--select--"?> --</option>
                <?php
                	foreach ($fieldTypes as $key=>$type){ 
                		$selected='';
                		if($applicationField->getFieldType()==$type) $selected="selected='selected'";
                		?>             			
             				<option value="<?php echo $type; ?>"  <?php echo $selected ?>><?php echo $type; ?></option>             			
                	<?php 	} ?>
        </select>
        <br/>
		<label><span class="error"></span> <?php echo 'Tool Tip'; ?></label>
			<input type="text" id="txtTooltip" name="txtTooltip" value="<?php echo $applicationField->getToolTip();?>"/><br/> 
		<label><span class="error"></span> <?php echo 'Order'; ?></label>
			<input type="text" id="txtTabOrder" name="txtTabOrder" value="<?php echo $applicationField->getTabOrder();?>"/><br/>        

        <div align="left">
        <?php if($new){ ?>
        		 <img onClick="save();" id="editBtn" src="../../themes/<?php echo $styleSheet;?>/pictures/<?php echo 'btn_save.gif'?>">        
        <?php }else{ ?>
        		<img onClick="edit();" id="editBtn" src="../../themes/<?php echo $styleSheet;?>/pictures/<?php echo 'btn_save.gif'?>">
       	<?php } ?>  			
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
