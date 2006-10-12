<?
/*
* OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures 
* all the essential functionalities required for any enterprise. 
* Copyright (C) 2006 hSenid Software International Pvt. Ltd, http://www.hsenid.com
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

/*$srchlist[0] = array( 0 , 1 , 2 );
$srchlist[1] = array( '-Select-' , 'ID' , 'Description' );
*/
	$sysConst = new sysConf(); 
	$locRights=$_SESSION['localRights'];
	 	
	//$headingInfo=$this->popArr['headinginfo'];
		
	$currentPage = $this->popArr['currentPage'];
    
	$message= $this->popArr['message'];
	
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
		
	function SortOrderInWords($SortOrder) {
		if ($SortOrder == 'ASC') {
			return 'Ascending';
		} else {
			return 'Descending';
		}
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link href="../../themes/beyondT/css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">@import url("../../themes/beyondT/css/style.css"); </style>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<script>		

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

	function returnAdd() {
<?
		$esp = isset($_GET['isAdmin'])? ('&isAdmin='.$_GET['isAdmin']) : '';
		
		switch($headingInfo[2]) {
			case 1 : echo "location.href = './CentralController.php?uniqcode=".$this->getArr['uniqcode']."&capturemode=addmode".$esp."'";
					 break;
			case 2 : echo "var popup=window.open('../../genpop.php?uniqcode=".$this->getArr['uniqcode']."','Employees','modal=yes,height=450,width=600');";
        			 echo "if(!popup.opener) popup.opener=self;";
        			 break;
		}
?>		
	}
	
	function returnDelete() {
		$check = 0;
		with (document.standardView) {
			for (var i=0; i < elements.length; i++) {
				if ((elements[i].type == 'checkbox') && (elements[i].checked == true)){
					$check = 1;
				}
			}
		}
	
		if ( $check == 1 ){
			
			var res = confirm("<?=$headingInfo[4]?>. Do you want to delete ?");
			
			if(!res) return;
			
			document.standardView.delState.value = 'DeleteMode';		
			document.standardView.pageNO.value=1;
			document.standardView.submit();
		}else{
			alert("Select At Least One Record To Delete");
		}		
	}
	
	function returnSearch() {	
		
		if (document.standardView.loc_code.value == -1) {	
			alert("Select the field to search!");
			document.standardView.loc_code.Focus();
			return;
		};	
		document.standardView.captureState.value = 'SearchMode';		
		document.standardView.pageNO.value=1;
		document.standardView.submit();
	}
	
	function doHandleAll()
	{
		with (document.standardView) {		
			if(elements['allCheck'].checked == false){
				doUnCheckAll();
			}
			else if(elements['allCheck'].checked == true){
				doCheckAll();
			}
		}	
	}
	
	function doCheckAll()
	{
		with (document.standardView) {		
			for (var i=0; i < elements.length; i++) {
				if (elements[i].type == 'checkbox') {
					elements[i].checked = true;
				}
			}
		}
	}
	
	function doUnCheckAll()
	{
		with (document.standardView) {		
			for (var i=0; i < elements.length; i++) {
				if (elements[i].type == 'checkbox') {
					elements[i].checked = false;
				}
			}
		}
	}

	function clear_form() {
		document.standardView.loc_code.options[0].selected=true;
		document.standardView.loc_name.value='';
	}
</script>
<body>
<p> 
<table width='100%' cellpadding='0' cellspacing='0' border='0' class='moduleTitle'><tr><td valign='top'>
<form name="standardView" method="post" action="<?=$_SERVER['PHP_SELF']?>?uniqcode=<?=$this->getArr['uniqcode']?>&VIEW=MAIN&sortField=<?=$this->getArr['sortField']?>&sortOrder<?=$this->getArr['sortField']?>=<?=$this->getArr['sortOrder'.$this->getArr['sortField']].$esp?>">
  </td>
  <td width='100%'><h2> 
      <?=$headingInfo[3]?>
    </h2></td>
  <td valign='top' align='right' nowrap style='padding-top:3px; padding-left: 5px;'></td></tr>
</table></p>
</p> 
<p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td width="22%" nowrap><h3> 
        <input type="hidden" name="captureState" value="<?=isset($this->postArr['captureState'])?$this->postArr['captureState']:''?>">
        <input type="hidden" name="delState" value="">
        
        <input type="hidden" name="pageNO" value="<?=isset($this->postArr['pageNO'])?$this->postArr['pageNO']:'1'?>">
    
<?	if($locRights['add']) { ?>
        <img border="0" title="Add" onClick="returnAdd();" onMouseOut="this.src='../../themes/beyondT/pictures/btn_add.jpg';" onMouseOver="this.src='../../themes/beyondT/pictures/btn_add_02.jpg';" src="../../themes/beyondT/pictures/btn_add.jpg">
<? 	} else { ?>
        <img onClick="alert('<?=$sysConst->accessDenied?>');" src="../../themes/beyondT/pictures/btn_add.jpg">
<?	}

if($headingInfo[2]==1) {
	
	if($locRights['delete']) { ?>
        <img title="Delete" onClick="returnDelete();" onMouseOut="this.src='../../themes/beyondT/pictures/btn_delete.jpg';" onMouseOver="this.src='../../themes/beyondT/pictures/btn_delete_02.jpg';" src="../../themes/beyondT/pictures/btn_delete.jpg">
<? 	} else { ?>
        <img onClick="alert('<?=$sysConst->accessDenied?>');" src="../../themes/beyondT/pictures/btn_delete.jpg">
<? 	} 
}?>

      </h3></td>
    <td width='78%'><IMG height='1' width='1' src='../../pictures/blank.gif' alt=''></td>
  </tr>
</table>
<p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td width="22%" nowrap><h3><?=$search?></h3></td>
    <td width='78%' align="right"><IMG height='1' width='1' src='../../pictures/blank.gif' alt=''> 
     <?
		if (isset($this->getArr['message'])) {
		
			$expString  = $this->getArr['message'];
			$expString = explode ("_",$expString);
			$length = count($expString);		
			
			$col_def=$expString[$length-1];
			
			$expString=$this->getArr['message'];
	?>
			<font class="<?=$col_def?>" size="-1" face="Verdana, Arial, Helvetica, sans-serif">	
	<?
				echo $$expString;
	?>
			</font>
	<?
		}		
		?>
      &nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
</table>

<!--  newtable -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td width="13"><img name="table_r1_c1" src="../../themes/beyondT/pictures/table_r1_c1.gif" width="13" height="12" border="0" alt=""></td>
                  <td width="339" background="../../themes/beyondT/pictures/table_r1_c2.gif"><img name="table_r1_c2" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td width="13"><img name="table_r1_c3" src="../../themes/beyondT/pictures/table_r1_c3.gif" width="13" height="12" border="0" alt=""></td>
                  <td width="11"><img src="../../themes/beyondT/pictures/spacer.gif" width="1" height="12" border="0" alt=""></td>
                </tr>
                <tr>
                  <td background="../../themes/beyondT/pictures/table_r2_c1.gif"><img name="table_r2_c1" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td><table  border="0" cellpadding="5" cellspacing="0" class="">
                    <tr>
                      <td width="200" class="dataLabel"><slot><?=$SearchBy?></slot>&nbsp;&nbsp;<slot>
                        <select style="z-index: 99;" name="loc_code">
<?                        for($c=-1;count($srchlist)-1>$c;$c++)
								if(isset($this->postArr['loc_code']) && $this->postArr['loc_code']==$c)
								   echo "<option selected value='" . $c ."'>".$srchlist[$c+1] ."</option>";
								else
								   echo "<option value='" . $c ."'>".$srchlist[$c+1] ."</option>";
?>								   
                        </select>
                      </slot></td>
                      <td width="200" class="dataLabel" noWrap><slot><?=$description?></slot>&nbsp;&nbsp;<slot>
                        <input type=text size="20" name="loc_name" class=dataField  value="<?=isset($this->postArr['loc_name'])?$this->postArr['loc_name']:''?>">
                     </slot></td>
                    <td align="right" width="180" class="dataLabel"><img tabindex=3 title="Search" onClick="returnSearch();" onMouseOut="this.src='../../themes/beyondT/pictures/btn_search.jpg';" onMouseOver="this.src='../../themes/beyondT/pictures/btn_search_02.jpg';" src="../../themes/beyondT/pictures/btn_search.jpg">&nbsp;&nbsp;<img title="Clear" onClick="clear_form();" onMouseOut="this.src='../../themes/beyondT/pictures/btn_clear.jpg';" onMouseOver="this.src='../../themes/beyondT/pictures/btn_clear_02.jpg';" src="../../themes/beyondT/pictures/btn_clear.jpg"></td>

                  </table></td>
                  <td background="../../themes/beyondT/pictures/table_r2_c3.gif"><img name="table_r2_c3" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td><img src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                </tr>
                <tr>
                  <td background="../../themes/beyondT/pictures/table_r2_c1.gif"><img name="table_r2_c1" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
                  <td><table  border="0" cellpadding="5" cellspacing="0" class="">

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
			  <table border="0" width="100%">
			  <tr>
			  <td height="40" valign="bottom" align="right">
			  
<?
$temp = $this->popArr['temp']; 
if($temp)    
    $recCount=$temp;
else 
	$recCount=0;
	
	$noPages=(int)($recCount/$sysConst->itemsPerPage);

	if($recCount%$sysConst->itemsPerPage)
	   $noPages++;

	if ($noPages > 1) {
			
		if($currentPage==1)
			echo "<font color='Gray'>$Previous</font>";
		else
    		echo "<a href='#' onClick='prevPage()'>$Previous</a>";
    	
    	echo "  ";
    	
		for( $c = 1 ; $noPages >= $c ; $c++) {
	    	if($c == $currentPage)
				echo "<font color='Gray'>" .$c. "</font>";
			else
	    		echo "<a href='#' onClick='chgPage(" .$c. ")'>" .$c. "</a>";
	    	
	    	echo "  ";
		}
		
		if ($currentPage == $noPages)
			echo "<font color='Gray'>$Next</font>";
		else
    		echo "<a href='#' onClick='nextPage()'>$Next</a>";
    		
	};
	
		
	if ($message == '') { ?>
		<span class="error">No records to display!</span>
<?  } ?> 
		</td>
		<td width="25"></td>
		</tr>		
		</table>
		<table width="100%" border="0" cellpadding="5" cellspacing="0" class="">
			<tr>
          <td class="r1_c1" width="12"></td>
          <td class="r1_c2" width="50"></td>
          <? for ($j=0; $j < count($headings); $j++) {?>
          <td width="200" class="r1_c2"></td>
          <? } ?>
          <td class="r1_c2" width="300"></td>
          <td class="r1_c3"></td>
         </tr>
			<tr nowrap>
				<td class="r2_c1"><img name="table_r2_c1" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
				<td width="50" NOWRAP class="listViewThS1" scope="col">
				<?	if($headingInfo[2]==1) { ?>	  
					<input type='checkbox' class='checkbox' name='allCheck' value='' onClick="doHandleAll();">
				<?	}	?>						  
				</td>
				<?php
					for ($j=0; $j < count($headings); $j++) {
						if (!isset($this->getArr['sortOrder'.$j])) {
							$this->getArr['sortOrder'.$j] = 'null';
						}
				?>
				<td scope="col" width="250" class="listViewThS1"><a href="<?=$_SERVER['PHP_SELF']?>?uniqcode=<?=$this->getArr['uniqcode']?>&VIEW=MAIN&sortField=<?=$j?>&sortOrder<?=$j?>=<?=getNextSortOrder($this->getArr['sortOrder'.$j]).$esp?>" title="Sort in <?=SortOrderInWords(getNextSortOrder($this->getArr['sortOrder'.$j]))?> order"><?=$headings[$j]?></a> <img src="../../themes/beyondT/icons/<?=$this->getArr['sortOrder'.$j]?>.png" width="8" height="10" border="0" alt="" style="vertical-align: middle"></td>
				<? } ?>	                    
      		<td class="listViewThS1"><img name="table_r2_c3" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
      		<td class="r2_c3"><img src="../../themes/beyondT/pictures/spacer.gif" width="13" height="1" border="0" alt=""></td>
    		</tr>
    		<?php
				if ((isset($message)) && ($message !='')) {	 
					for ($j=0; $j < count($message);$j++) {	
	 		?>
    		<tr>
       		<td class="r2_c1"><img name="table_r2_c1" src="../../themes/beyondT/pictures/spacer.gif" width="1" height="1" border="0" alt=""></td>
       		<?php 
	 	 	 			if(!($j%2)) { 
							$cssClass = 'odd';
			 			} else {
			 				$cssClass = 'even';
			 			}
			 		 	
		 	 			if($headingInfo[2]==1) {
		 		?>	  
       		<td class="<?=$cssClass?>" width="50"><input type='checkbox' class='checkbox' name='chkLocID[]' value='<?=$message[$j][0]?>'></td>
		 		<? 	} else { ?>
       		<td class="<?=$cssClass?>" width="50"></td>
		 		<? 	}  ?>
		 		<td class="<?=$cssClass?>" width="250"><a href="./CentralController.php?id=<?=$message[$j][0]?>&uniqcode=<?=$this->getArr['uniqcode']?>&capturemode=updatemode<?=$esp?>" class="listViewTdLinkS1"><?=$message[$j][0]?></a>
		 		<?php
		 				for ($k=1; $k < count($headings); $k++) { 
		  	 
		  	 				$descField=$message[$j][$k];
		  	 	
		  	 				if($sysConst->viewDescLen <= strlen($descField)) {
			 	   							
			 	   				$descField = substr($descField,0,$sysConst->viewDescLen);
			 	   				$descField .= "....";
			 				}
		 		?>	 
		 		<td class="<?=$cssClass?>" width="400" ><?=$descField?></td>
		 		<? } ?>
				<td class="<?=$cssClass?>" width="400" >&nbsp;</td>
		 		<td class="r2_c3"><img src="../../themes/beyondT/pictures/spacer.gif" width="13" height="1" border="0" alt=""></td>
		 	</tr>		 						
		 	<?php
		 		 } 
		 		}
		  ?>
		  <tr>
          <td class="r3_c1" height="16"></td>
          <td class="r3_c2" height="16"></td>
          <? for ($j=0; $j < count($headings); $j++) {?>
          <td width="250" class="r3_c2" height="16"</td>
          <? } ?>
          <td class="r3_c2" height="16"></td>
          <td class="r3_c3" height="16"></td>
         </tr>					
 		</table>
      
      
<!--  newtable -->

</form>
</body>
</html>
