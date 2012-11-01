<div class="box toggableForm">
			<?php if(count($listJobTitle) == 0){?>
			<div id="messageBalloon_notice" class="messageBalloon_notice">
                            <?php echo __("No Defined Job Titles")?> <a href="<?php echo url_for('admin/viewJobTitleList') ?>"><?php echo __("Define Now")?></a>
			</div>
		<?php }?>
		<?php echo isset($templateMessage) ? templateMessage($templateMessage) : ''; ?>
        <?php include_partial('global/form_errors', array('form' => $form)); ?>
    
    <div id="formHeading" class="head"><h1><?php echo __("Search Key Performance Indicators")?></h1></div>
    <div class="inner">
        <form action="#" id="frmSearch" name="frmSearch" method="post">
            <input type="hidden" name="mode" value="search" >
            <fieldset>	
                <ol>
                    <li>
                        <label for="txtLocationCode"><?php echo __('Job Title') ?></label>
                        <select name="txtJobTitle" id="txtJobTitle" tabindex="1" >
                            <option value="all"><?php echo __('All') ?></option>
                            <?php foreach ($listJobTitle as $jobTitle) { ?>
                                <option value="<?php echo $jobTitle->getId() ?>" <?php if (isset($searchJobTitle) && $jobTitle->getId() == $searchJobTitle->getId()) {
                                echo 'selected';
                            } ?>><?php echo htmlspecialchars_decode($jobTitle->getJobTitleName());
                            if (!$jobTitle->getIsDeleted() == JobTitle::ACTIVE) {
                                echo ' (' . __('Deleted') . ')';
                            } ?></option>
<?php } ?>
                        </select>
                    </li>
                </ol>
                <p>
                    <input type="button" class="searchbutton" id="searchBtn" value="<?php echo __("Search") ?>" name="_search" />
                </p>  
            </fieldset>
        </form>			
    </div>
    
    <a href="#" class="toggle tiptip" title="Expand for options">&gt;</a>
    
</div> <!-- end-of-searchKPI -->

 <div class="box simple" id="search-results">
	   			
				
			
    <form action="<?php echo url_for('performance/deleteDefineKpi') ?>" name="frmList" id="frmList" method="post">

            <?php echo $form['_csrf_token']; ?>
			
            <div id="tableWrapper">
                 
               
            
            <?php include_partial('global/flash_messages'); ?>
            
                    <div class="inner">
                        
                        <div class="top">
                            
                            <div class="navigationHearder">
                                <?php if ($pager->haveToPaginate()) { ?>
                                    <div  class="pagingbar" >
                                        <?php include_partial('global/paging_links', array('pager' => $pager, 'url' => '@kpi_list')); ?>
                                    </div>
                                <?php } ?>      
                            </div>
                            
                            <input type="button" class="" id="addKpiBut" value="<?php echo __('Add')?>" tabindex="2"  />
                                <?php if($hasKpi){?>
                            <input type="button" class="delete"  id="deleteKpiBut"
                                value="<?php echo __('Delete')?>" tabindex="3" />
	                         <input type="button" class=""  id="copyKpiBut"
                                value="<?php echo __('Copy')?>" tabindex="4" />
	                        <?php }?>
                        </div>
                        <table cellpadding="0" cellspacing="0" width="100%" class="table tablesorter">
                            <thead>
                                <tr>
                                    <th width="2%" class="tdcheckbox">
                                        <input type="checkbox"  name="allCheck" value="" id="allCheck" />
                                    </th>
                                    <th> 
                                        <?php echo __('Key Performance Indicator') ?>
                                    </th> 
                                    <th>
                                        <?php echo __('Job Title') ?>
                                    </th>
                                    <th> 
                                        <?php echo __('Min Rate') ?>
                                    </th>
                                    <th> 
                                        <?php echo __('Max Rate') ?>
                                    </th>
                                    <th> 
                                        <?php echo __('Is Default') ?>
                                    </th>   
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!$hasKpi) { ?>
                                    <tr>
                                        <td></td>
                                        <td><?php echo __(TopLevelMessages::NO_RECORDS_FOUND); ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php } else { ?>
                                    <?php
                                    $row = 0;
                                    foreach ( $kpiList as $kpi ) {
                                        $cssClass = ($row % 2) ? 'even' : 'odd';
                                        $row = $row + 1;
                                    ?>
                                    <tr class="<?php echo $cssClass?>">
                                        
                                        <td class="tdcheckbox">
                                            <input type='checkbox' class='innercheckbox' name='chkKpiID[]' id="chkLoc" value='<?php echo  $kpi->getId()?>' />
                                        </td>
                                        <td class="">
                                            <a href="<?php echo url_for('performance/updateKpi?id='.$kpi->getId()) ?>"><?php echo  $kpi->getDesc()?></a>
                                        </td>
                                        <td class="">
                                            <?php echo  htmlspecialchars_decode($kpi->getJobTitle()->getJobTitleName())?>
                                        </td>
                                        <td class="">
                                            <?php echo  ($kpi->getRateMin()!='')?$kpi->getRateMin():'-'?>
                                        </td><td class="">
                                            <?php echo  ($kpi->getRateMax() !='')?$kpi->getRateMax():'-'?>
                                        </td><td class="">
                                            <?php echo  ($kpi->getDefault()==1)?__('Yes'):'-'?>
                                        </td>
                                        
                                    </tr>
                                    
                                <?php }
                                }
                                ?>
				
                            </tbody>
                 
                    </table> 
                    
                </div>
                
                        
        </div>
                
    </form>
           
</div>
			
			<?php // }?>		
	   


<script type="text/javascript">

		$(document).ready(function() {

			

			//search Kpi 
			$('#searchBtn').click(function(){
				$('#frmSearch').submit();
			});
			
			//Add Kpi button
			$('#addKpiBut').click(function(){
				location.href = "<?php echo url_for('performance/saveKpi') ?>";
			});

			//Copy kpi button
			$('#copyKpiBut').click(function(){
				location.href = "<?php echo url_for('performance/copyKpi') ?>";
			});

			//delete KPI 
			$('#deleteKpiBut').click(function(){
				if($('.innercheckbox').is(':checked'))
				{
					$('#frmList').submit();
				}else
				{
					
					showError('messageBalloon_warning','<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>' );
				}
			});

			//Validate search form 
			 $("#frmSearch").validate({
					
				 rules: {
				 	txtJobTitle: { required: true }
			 	 },
			 	 messages: {
			 		txtJobTitle: '<?php echo __(ValidationMessages::REQUIRED); ?>'
			 	 }
			 });

			

			// When Click Main Tick box
				$("#allCheck").click(function() {
					if ($('#allCheck').attr('checked')) {
						$('.innercheckbox').attr('checked', true);
					}else{
						$('.innercheckbox').attr('checked', false);
					}
					
				});

			 $(".innercheckbox").click(function() {
					if(!($(this).attr('checked')))
					{
						$('#allCheck').attr('checked', false);
					}
				});

			 
				
		 });

		function showError(errorType,message)
		{
			var html	=	"<div id='"+errorType+"' class='"+errorType+"' >"+message+"</div>";
			 $("#errorContainer").html(html);
			 $("#errorContainer").show();
		}
</script>


