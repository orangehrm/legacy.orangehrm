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


if(isset($this->popArr['perfReview'])){
	$completedPerformanceReviews=$this->popArr['perfReview'];
}
?>
<script language="javascript">
    function displayPerformanceMeasures(id){
    	xajax_showCompletedMeasures(id);
    }
</script>

<div id="performanceLayer" style="display:none;" >
<script type="text/javascript">
    toggleEmployeePerformanceReviewText();
</script>
    <h3><?php echo $lang_hremp_Completed_PerformanceReview; ?></h3><br/>
    <?php
    if(count($completedPerformanceReviews)>0){
    ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" >
    <thead>
      <th align="left" valign="top"><?php echo $lang_Performance_review_ID; ?></th>
      <th align="left" valign="top"><?php echo $lang_jobtitle_jobtitname ; ?></th>
      <th align="left" valign="top"><?php echo $lang_Performance_review_ReviewDate; ?></th>
      <th align="left" valign="top"><?php echo $lang_Performance_review_ReviewStatus; ?></th>
      <th align="left" valign="top"><?php echo $lang_Performance_Review_Notes; ?></th>
   </thead>
    <?php
     foreach($completedPerformanceReviews as $reviewRow){
        if(!empty($reviewRow[3]) && $reviewRow[3]=='1'){
    ?>
      <tr>
        <td align="center"><a href="#" onclick="displayPerformanceMeasures('<?php echo $reviewRow[0]; ?>')"><?php echo $reviewRow[0] ?></a></td>
        <td align="center"><?php echo $reviewRow[1]; ?></td>
        <td align="center"><?php echo $reviewRow[2]; ?></td>
        <td align="center"><?php echo $lang_Performance_Review_Completed ?></td>
        <td align="center"><?php echo $reviewRow[4]; ?></td>

      </tr>
     <tr> <td colspan="5"></td></tr>
     <tr>
        <td colspan="5" align="left" valign="top"><div id="measure<?php echo $reviewRow[0];?>">

        </div>
        </td>
      </tr>
    <tr>
        <td colspan="5"><hr/></td>
    </tr>
    <?php
        }
     }
    ?>
    </table>
<?php
    }else{
    	echo $lang_Error_NoRecordsFound;
    }
?>
</div>

