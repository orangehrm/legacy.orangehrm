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

require_once ROOT_PATH . '/lib/confs/sysConf.php';
$sysConst = new sysConf();
$maxDispLen = $sysConst->viewDescLen;

$locRights = $_SESSION['localRights'];

$currentPage = $this->popArr['currentPage'];
$searchFieldCount = $this->popArr['searchFieldCount']; 

$list = $this->popArr['list'];
$baseURL = './CentralController.php?healthcode='. $this->getArr['healthcode'];

$allowAdd = $locRights['add'];
$allowDelete = $locRights['delete'];
$showSearch = isset($this->popArr['showSearch']) ? $this->popArr['showSearch']: true;

if (!isset($this->getArr['sortField']) || ($this->getArr['sortField'] == '')) {
	$this->getArr['sortField']=0;
	$this->getArr['sortOrder0']='ASC';
}

function getNextSortOrder($curSortOrder) {
	switch ($curSortOrder) {
		case 'null' :
			return 'ASC';
			break;
		case 'ASC' :
			return 'DESC';
			break;
		case 'DESC'	:
			return 'ASC';
			break;
	}
}

function getSortOrderInWords($SortOrder) {
	if ($SortOrder == 'ASC') {
		return 'Ascending';
	} else {
		return 'Descending';
	}
}

function getDisplayValue($value, $map, $maxLen) {


	if (!empty($map) && isset($map[$value])) {
	    $value = $map[$value];
	}

	if ($maxLen <= strlen($value)) {
		$value = substr($value, 0, $maxLen) . '....';
	}
	return $value;
}

if ($searchFieldCount == 1) {
	$searchStr = isset($this->postArr['loc_name'])? stripslashes($this->postArr['loc_name']) : '';
	if (isset($this->postArr['loc_code'])) {
		$code = $this->postArr['loc_code'];
		if (isset($valueMap[$code])){
			$searchStr = getDisplayValue($searchStr, $valueMap[$code], $maxDispLen);
		}
	}
} else {
	for ($i=0; $i<$searchFieldCount; $i++) {
		$searchStr[$i] = isset($this->postArr['loc_name'][$i])? stripslashes($this->postArr['loc_name'][$i]) : '';
		if (isset($this->postArr['loc_code'][$i])) {
			$code = $this->postArr['loc_code'][$i];
			if (isset($valueMap[$code])){
				$searchStr[$i] = getDisplayValue($searchStr[$i], $valueMap[$code], $maxDispLen);
			}
		}
	}		
}

$themeDir = '../../themes/' . $styleSheet;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link href="<?php echo $themeDir;?>/css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">@import url("<?php echo $themeDir;?>/css/style.css"); </style>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../../scripts/archive.js"></script>
</head>
<script>

	// Maps to allow searching of mapped values
    var maps = new Array();
<?php
for ($i = 0; $i < count($valueMap); $i++) {
	echo "maps[{$i}] = new Array();\n";
	if (!empty($valueMap[$i]) && is_array($valueMap[$i])) {
	    foreach($valueMap[$i] as $key=>$value) {
			echo "maps[{$i}]['{$value}'] = '$key';\n";
	    }
	}
}
?>
	function nextPage() {
		var i=eval(document.standardView.pageNO.value);
		document.standardView.pageNO.value=i+1;
		document.standardView.submit();
	}

	function prevPage() {
		var i=eval(document.standardView.pageNO.value);
		document.standardView.pageNO.value=i-1;
		document.standardView.submit();
	}

	function chgPage(pNO) {
		document.standardView.pageNO.value=pNO;
		document.standardView.submit();
	}

	function sortAndSearch(fieldNum, sortOrder) {
		var url = '<?php echo $baseURL;?>&action=<?php echo $this->getArr['action'];?>';
		action = url + '&sortField=' + fieldNum + '&sortOrder' + fieldNum + '=' + sortOrder;

		document.standardView.action = action;
		document.standardView.submit();
	}

	function returnAdd() {
		location.href = '<?php echo $baseURL;?>&action=ViewAdd';
	}

	function returnDelete() {
		var check = false;
		with (document.standardView) {
			for (var i=0; i < elements.length; i++) {
				if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkID[]')){
					check = true;
					break;
				}
			}
		}

		if (check){

			var res = confirm("<?php echo $deletePrompt . ' ' . $lang_Common_ConfirmDelete; ?>");
			if (!res) {
			    return false;
			}

			document.standardView.action="<?php echo $baseURL;?>&action=Delete";
			document.standardView.pageNO.value=1;
			document.standardView.submit();
		} else {
			alert("<?php echo $lang_Common_SelectDelete; ?>");
		}
	}

	function returnSearch() {
	
<?php if ($searchFieldCount == 1) { ?> 	
		return returnSearchSingle();

<?php } else { ?>
		return returnSearchMultiple();
<?php } ?>			
	}

<?php if ($searchFieldCount > 0) { ?>
	function returnSearchMultiple() {
	
		var err = false;
		var msg = '';
		
		var selectedOptions = 0;
<?php 
		 for ($i=0; $i<$searchFieldCount; $i++) { 
?>
		
		var field = $('loc_code<?php echo $i;?>');
		var value = $('loc_name<?php echo $i;?>');
		
		if (field.value != -1) {
			selectedOptions = selectedOptions + 1;

			var searchNdx = field.value;
			var searchVal = value.value;
		
			if (searchNdx in maps) {
			    map = maps[searchNdx];
			    if (searchVal in map) {
			        value.value = map[searchVal];
			    } else {
			        var len = map.length;
			        if (len > 0) {
				        var allowed = '';
				        for ( var i in map) {
				        	if (allowed == ''){
				            	allowed = i;
				        	} else {
				            	allowed = allowed + ', ' + i;
				            }
				        }
				        msg += "<?php echo $lang_Recruit_AllowedValuesAre;?> " + allowed;
				        err = true;
			        }
			    }
			}			
		}
		
		if (selectedOptions == 0) {
			err = true;
			msg += "<?php echo $lang_Common_SelectField; ?>";
		}
		
		if (err) {
			alert(msg);
			return;
		}
				
<?php   } ?>


		document.standardView.captureState.value = 'SearchMode';
		document.standardView.pageNO.value=1;
		document.standardView.submit();
	}
<?php } ?>			
	
	function returnSearchSingle() {

		if ($('loc_code').value == -1) {
			alert("<?php echo $lang_Common_SelectField; ?>");
			$('loc_code').Focus();
			return;
		};
		var searchNdx = $('loc_code').value;
		var searchVal = $('loc_name').value;

		if (searchNdx in maps) {
		    map = maps[searchNdx];
		    if (searchVal in map) {
		        $('loc_name').value = map[searchVal];
		    } else {
		        var len = map.length;
		        if (len > 0) {
			        var allowed = '';
			        for ( var i in map) {
			        	if (allowed == ''){
			            	allowed = i;
			        	} else {
			            	allowed = allowed + ', ' + i;
			            }
			        }
			        alert("<?php echo $lang_Recruit_AllowedValuesAre;?> " + allowed);
		        return;
		        }
		    }
		}

		document.standardView.captureState.value = 'SearchMode';
		document.standardView.pageNO.value=1;
		document.standardView.submit();
	}

	function doHandleAll() {true
		with (document.standardView) {
			if(elements['allCheck'].checked == false){
				doUnCheckAll();
			}
			else if(elements['allCheck'].checked == true){
				doCheckAll();
			}
		}
	}

	function doCheckAll() {
		with (document.standardView) {
			for (var i=0; i < elements.length; i++) {
				if (elements[i].type == 'checkbox') {
					elements[i].checked = true;
				}
			}
		}
	}

	function doUnCheckAll() {
		with (document.standardView) {
			for (var i=0; i < elements.length; i++) {
				if (elements[i].type == 'checkbox') {
					elements[i].checked = false;
				}
			}
		}
	}

	function clear_form() {
<?php if ($searchFieldCount == 1) { ?> 	
		document.standardView.loc_code.options[0].selected=true;
		document.standardView.loc_name.value='';
<?php } else {
		 for ($i=0; $i<$searchFieldCount; $i++) { 
?>
		
		var field = $('loc_code<?php echo $i;?>');
		var value = $('loc_name<?php echo $i;?>');
		field.options[0].selected = true;
		value.value = '';
				
<?php   }
	} 
?>		
	}
</script>
<body>
<p>
<table width='100%' cellpadding='0' cellspacing='0' border='0' class='moduleTitle'><tr><td valign='top'>
<form name="standardView" id="standardView" method="post" action="<?php echo $baseURL;?>&action=<?php echo $this->getArr['action'];?>&sortField=<?php echo $this->getArr['sortField']?>&sortOrder<?php echo $this->getArr['sortField']?>=<?php echo $this->getArr['sortOrder'.$this->getArr['sortField']];?>">
  </td>
  <td width='100%'><h2>
      <?php echo $title?>
    </h2></td>
  <td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'></td></tr>
</table></p>
</p>
<p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td width="22%" nowrap><h3>
        <input type="hidden" name="captureState" value="<?php echo isset($this->postArr['captureState'])?$this->postArr['captureState']:''?>">

        <input type="hidden" name="pageNO" value="<?php echo isset($this->postArr['pageNO'])?$this->postArr['pageNO']:'1'?>">

<?php	if($allowAdd) { ?>
        <img border="0" title="Add" onClick="returnAdd();" onMouseOut="this.src='<?php echo $themeDir;?>/pictures/btn_add.gif';" onMouseOver="this.src='<?php echo $themeDir;?>/pictures/btn_add_02.gif';" src="<?php echo $themeDir;?>/pictures/btn_add.gif">
<?php	}

if($allowDelete) {
?>
       <img title="Delete" onClick="returnDelete();" onMouseOut="this.src='<?php echo $themeDir;?>/pictures/btn_delete.gif';" onMouseOver="this.src='<?php echo $themeDir;?>/pictures/btn_delete_02.gif';" src="<?php echo $themeDir;?>/pictures/btn_delete.gif">
<?php
    }
?>

      </h3></td>
    <td width='78%'><IMG height='1' width='1' src='../../pictures/blank.gif' alt=''></td>
  </tr>
</table>
<p>
<?php if ($showSearch || isset($this->getArr['message'])) { ?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td width="22%" nowrap><h3><?php echo ($showSearch) ? $search : ""; ?></h3></td>
    <td width='78%' align="right"><IMG height='1' width='1' src='../../pictures/blank.gif' alt=''>
     <?php
		if (isset($this->getArr['message'])) {

			$expString = $this->getArr['message'];
			$col_def = CommonFunctions::getCssClassForMessage($expString);
	?>
			<font class="<?php echo $col_def?>" size="-1" face="Verdana, Arial, Helvetica, sans-serif">
	<?php
				echo $$expString;
	?>
			</font>
	<?php
		}
		?>
      &nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
</table>
<?php } ?>
<?php if ($showSearch) { ?>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="13"><img name="table_r1_c1" src="<?php echo $themeDir;?>/pictures/table_r1_c1.gif" width="13" height="12" border="0" alt=""></td>
          <td width="339" background="<?php echo $themeDir;?>/pictures/table_r1_c2.gif"><img name="table_r1_c2" src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
          <td width="13"><img name="table_r1_c3" src="<?php echo $themeDir;?>/pictures/table_r1_c3.gif" width="13" height="12" border="0" alt=""></td>
          <td width="11"><img src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="12" border="0" alt=""></td>
        </tr>
        <tr>
          <td background="<?php echo $themeDir;?>/pictures/table_r2_c1.gif"><img name="table_r2_c1" src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
          <td>
          <table  border="0" cellpadding="5" cellspacing="0" class="">          
<?php if ($searchFieldCount == 1) { ?>         
            <tr>
              <td width="200" class="dataLabel"><?php echo $SearchBy?>&nbsp;&nbsp;
                <select style="z-index: 99;" name="loc_code" id="loc_code">
<?php               for($c=-1;count($srchlist)-1>$c;$c++) {
					    if(isset($this->postArr['loc_code']) && $this->postArr['loc_code']==$c) {
						   echo "<option selected value='" . $c ."'>".$srchlist[$c+1] ."</option>";
					    } else {
						   echo "<option value='" . $c ."'>".$srchlist[$c+1] ."</option>";
					    }
                    }
?>
                </select>
              </td>
              <td width="300" class="dataLabel" noWrap><?php echo $description?>&nbsp;&nbsp;
                <input type=text size="20" name="loc_name" id="loc_name" class=dataField  value="<?php echo $searchStr;?>">
             </td>
            <td align="right" width="180" class="dataLabel"><img tabindex=3 title="Search" onClick="returnSearch();" onMouseOut="this.src='<?php echo $themeDir;?>/pictures/btn_search.gif';" onMouseOver="this.src='<?php echo $themeDir;?>/pictures/btn_search_02.gif';" src="<?php echo $themeDir;?>/pictures/btn_search.gif">&nbsp;&nbsp;<img title="Clear" onClick="clear_form();" onMouseOut="this.src='<?php echo $themeDir;?>/pictures/btn_clear.gif';" onMouseOver="this.src='<?php echo $themeDir;?>/pictures/btn_clear_02.gif';" src="<?php echo $themeDir;?>/pictures/btn_clear.gif"></td>
		   </tr>
<?php } else {?>
<?php     for ($i=0; $i<$searchFieldCount; $i++) { ?>
            <tr>
              <td width="80" class="dataLabel"><?php echo ($i == 0) ? $SearchBy : ''; ?></td>
				<td width="120" class="dataLabel">
                <select style="z-index: 99;" name="loc_code[]" id="loc_code<?php echo $i;?>">
<?php               for($c=-1;count($srchlist)-1>$c;$c++) {
					    if(isset($this->postArr['loc_code'][$i]) && $this->postArr['loc_code'][$i]==$c) {
						   echo "<option selected value='" . $c ."'>".$srchlist[$c+1] ."</option>";
					    } else {
						   echo "<option value='" . $c ."'>".$srchlist[$c+1] ."</option>";
					    }
                    }
?>
                </select>
              </td>
              <td width="70" class="dataLabel" noWrap><?php echo ($i == 0) ? $description : ''; ?></td>              
              <td width="230" class="dataLabel" noWrap>
                <input type=text size="20" name="loc_name[]" id="loc_name<?php echo $i;?>" class=dataField  value="<?php echo $searchStr[$i];?>">
             </td>
            <td align="right" width="180" class="dataLabel">
<?php if ($i == 0) { ?>                        
            	<img tabindex=3 title="Search" onClick="returnSearch();" onMouseOut="this.src='<?php echo $themeDir;?>/pictures/btn_search.gif';" onMouseOver="this.src='<?php echo $themeDir;?>/pictures/btn_search_02.gif';" src="<?php echo $themeDir;?>/pictures/btn_search.gif">&nbsp;&nbsp;<img title="Clear" onClick="clear_form();" onMouseOut="this.src='<?php echo $themeDir;?>/pictures/btn_clear.gif';" onMouseOver="this.src='<?php echo $themeDir;?>/pictures/btn_clear_02.gif';" src="<?php echo $themeDir;?>/pictures/btn_clear.gif">
<?php } ?>            	
           	</td>
		   </tr>	
<?php     } ?>	
<?php } ?>

          </table>
          
          </td>
          <td background="<?php echo $themeDir;?>/pictures/table_r2_c3.gif"><img name="table_r2_c3" src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
          <td><img src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
        </tr>

        <tr>
          <td background="<?php echo $themeDir;?>/pictures/table_r2_c1.gif"><img name="table_r2_c1" src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
          <td><table  border="0" cellpadding="5" cellspacing="0" class="">

          </table></td>
          <td background="<?php echo $themeDir;?>/pictures/table_r2_c3.gif"><img name="table_r2_c3" src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
          <td><img src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
        </tr>
        <tr>
          <td><img name="table_r3_c1" src="<?php echo $themeDir;?>/pictures/table_r3_c1.gif" width="13" height="16" border="0" alt=""></td>
          <td background="<?php echo $themeDir;?>/pictures/table_r3_c2.gif"><img name="table_r3_c2" src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
          <td><img name="table_r3_c3" src="<?php echo $themeDir;?>/pictures/table_r3_c3.gif" width="13" height="16" border="0" alt=""></td>
          <td><img src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="16" border="0" alt=""></td>
        </tr>
      </table>
<?php } ?>      
	  <table border="0" width="100%">
	  <tr>
	  <td height="40" valign="bottom" align="right">

<?php
$count = $this->popArr['count'];
$commonFunc = new CommonFunctions();
$pageStr = $commonFunc->printPageLinks($count, $currentPage);
$pageStr = preg_replace(array('/#first/', '/#previous/', '/#next/', '/#last/'), array($lang_empview_first, $lang_empview_previous, $lang_empview_next, $lang_empview_last), $pageStr);

echo $pageStr;

	if (empty($list)) { ?>
		<span ><?php echo $dispMessage; ?></span>
<?php  } ?>
		</td>
		<td width="25"></td>
		</tr>
		</table>
		<table width="100%" border="0" cellpadding="5" cellspacing="0" class="">
		<thead>
		<tr>
          <td class="r1_c1" width="12"></td>
          <td class="tableTopMiddle" width="50"></td>
          <?php for ($j=0; $j < count($headings); $j++) {?>
          <td width="200" class="tableTopMiddle"></td>
          <?php } ?>
          <td class="tableTopMiddle" width="300"></td>
          <td class="tableTopRight"></td>
         </tr>
		 </thead>
			<tr nowrap>
				<td class="r2_c1"><img name="table_r2_c1" src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
				<td width="50" NOWRAP class="listViewThS1" scope="col">
				<?php	if($allowDelete) { ?>
					<input type='checkbox' class='checkbox' name='allCheck' value='' onClick="doHandleAll();">
				<?php	}	?>
				</td>
				<?php
					for ($j=0; $j < count($headings); $j++) {
						if (!isset($this->getArr['sortOrder'.$j])) {
							$this->getArr['sortOrder'.$j] = 'null';
						}
						$nextSortOrder = getNextSortOrder($this->getArr['sortOrder'.$j]);
						$nextSortInWords = getSortOrderInWords($nextSortOrder);
				?>
				<td scope="col" width="250" class="listViewThS1">
					<a href="#" onclick="sortAndSearch(<?php echo $j?>, '<?php echo $nextSortOrder;?>');"
						title="Sort in <?php echo $nextSortInWords; ?> order"><?php echo $headings[$j]?></a>
					<img src="<?php echo $themeDir;?>/icons/<?php echo $this->getArr['sortOrder'.$j]?>.png"
					width="8" height="10" border="0" alt="" style="vertical-align: middle"></td>
				<?php } ?>
      		<td class="listViewThS1"><img name="table_r2_c3" src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
      		<td class="r2_c3"><img src="<?php echo $themeDir;?>/pictures/spacer.gif" width="13" height="1" border="0" alt=""></td>
    		</tr>
    		<?php
				if ((isset($list)) && ($list !='')) {
					for ($j=0; $j < count($list);$j++) {
	 		?>
    		<tr>
       		<td class="r2_c1"><img name="table_r2_c1" src="<?php echo $themeDir;?>/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
       		<?php
	 	 	 			$cssClass = ($j%2) ? 'even' : 'odd';

		 	 			if($allowDelete) {
		 		?>
       		<td class="<?php echo $cssClass?>" width="50">
       				<?php if (CommonFunctions::extractNumericId($list[$j][0]) > 0) { ?>
       					<input type='checkbox' class='checkbox' name='chkID[]' value='<?php echo $list[$j][0]?>' /></td>
       				<?php } ?>
		 		<?php 	} else { ?>
       		<td class="<?php echo $cssClass?>" width="50"></td>
		 		<?php 	}  ?>
		 		<td class="<?php echo $cssClass?>" width="250"><a href="<?php echo $baseURL . '&id='. $list[$j][0];?>&action=View" class="listViewTdLinkS1"><?php echo $list[$j][0]?></a></td>
		 		<?php
		 			  $k=1;
		 			  if ($k < count($headings)) {
		 			  	    $descField = getDisplayValue($list[$j][$k], $valueMap[$k], $maxDispLen);
		 			  }
			 	?>
		 		<td class="<?php echo $cssClass?>" width="400" ><a href="<?php echo $baseURL . '&id='. $list[$j][0];?>&action=View" class="listViewTdLinkS1"><?php echo $descField?></a></td>
		 		<?php
		 				for ($k=2; $k < count($headings); $k++) {
		 			  	    $descField = getDisplayValue($list[$j][$k], $valueMap[$k], $maxDispLen);
		 		?>
		 		<td class="<?php echo $cssClass?>" width="400" ><?php echo $descField?></td>
		 		<?php } ?>
				<td class="<?php echo $cssClass?>" width="400" >&nbsp;</td>
		 		<td class="r2_c3"><img src="<?php echo $themeDir;?>/pictures/spacer.gif" width="13" height="1" border="0" alt=""></td>
		 	</tr>
		 	<?php
		 		 }
		 		}
		  ?>
		  <tr>
          <td class="r3_c1" height="16"></td>
          <td class="r3_c2" height="16"></td>
          <?php for ($j=0; $j < count($headings); $j++) {?>
          <td width="250" class="r3_c2" height="16"</td>
          <?php } ?>
          <td class="r3_c2" height="16"></td>
          <td class="r3_c3" height="16"></td>
         </tr>
 		</table>


<!--  newtable -->

</form>
</body>
</html>
