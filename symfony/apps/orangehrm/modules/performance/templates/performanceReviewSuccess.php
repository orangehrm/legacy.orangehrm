
<div class="box miniList" id="performanceReviewcontentContainer">
    
    <div class="head" id="formHeading" >
        <h1><?php echo __("Performance Review")?></h1>
    </div>

    <div class="inner">
        
        <?php include_partial('global/flash_messages'); ?>
        
        <form action="#" id="frmSave" class="content_inner" method="post">

            <?php echo $form['_csrf_token']; ?>
            <input type="hidden" name="id" id="id" value="<?php echo $performanceReview->getId() ?>"/>
            <input type="hidden" name="saveMode" id="saveMode" value="" />

            <fieldset>
                <ol>
                    <li>
                        <label><?php echo __("Employee") ?></label>
                        <label><?php echo $performanceReview->getEmployee()->getFirstName() ?> 
                            <?php echo $performanceReview->getEmployee()->getLastName() ?></label>
                    </li>
                    <li>
                        <label><?php echo __("Job Title") ?></label>
                        <label><?php echo htmlspecialchars_decode($performanceReview->getJobTitle()->getJobTitleName()) ?> </label>
                    </li>
                    <li>
                        <label><?php echo __("Reviewer") ?></label>
                        <label><?php echo $performanceReview->getReviewer()->getFirstName() ?> <?php echo $performanceReview->getReviewer()->getLastName() ?></label>
                    </li>
                    <li>
                        <label><?php echo __("Review Period") ?></label>
                        <label><?php echo set_datepicker_date_format($performanceReview->getPeriodFrom()) ?>-<?php echo set_datepicker_date_format($performanceReview->getPeriodTo()) ?></label>
                    </li>
                    <li>
                        <label><?php echo __("Status") ?></label>
                        <label><?php echo __($performanceReview->getTextStatus()) ?> </label>
                    </li>
                    <?php if (count($performanceReview->getPerformanceReviewComment()) > 0) { ?>
                    <li>
                        <label><?php echo __("Notes") ?></label>
                        <table>
                            <tr>
                                <td width="100px"><?php echo __("Date") ?></td>
                                <td width="150px"><?php echo __("Employee") ?></td>
                                <td width="350px"><?php echo __("Comment") ?></td>
                            </tr>
                            <?php foreach ($performanceReview->getPerformanceReviewComment() as $comment) { ?>
                            <tr>
                                <td ><?php echo set_datepicker_date_format($comment->getCreateDate()) ?></td>
                                <td ><?php echo ($comment->getEmployee()->getFullName() != '') ? 
                                $comment->getEmployee()->getFullName() : __('Admin') ?></td>
                                <td ><?php echo $comment->getComment() ?></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </li>
                    <?php } ?>
                </ol>
            </fieldset>
            <input type="hidden" name="validRate" id="validRate" value="1" />
            
            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                <thead>
                    <tr>
                        <th width="40%" scope="col"><?php echo __("Key Performance Indicator") ?></th>
                        <th scope="col" width="10%"><?php echo __("Min Rate") ?></th>
                        <th scope="col" width="10%"><?php echo __("Max Rate") ?></th>
                        <th scope="col" width="10%"><?php echo __("Rating") ?></th>
                        <th scope="col" width="30%"><?php echo __("Reviewer Comments") ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kpiList as $kpi) { ?>
                        <tr class="odd">
                            <td >
                                <?php echo $kpi->getKpi() ?>
                            </td>
                            <td >
                                <?php echo ($kpi->getMinRate() != '') ? $kpi->getMinRate() : '-' ?>
                            </td>
                            <td >
                                <?php echo ($kpi->getMaxRate() != '') ? $kpi->getMaxRate() : '-' ?>
                            </td>
                            <td  >
                                <input type="hidden" name="max<?php echo $kpi->getId() ?>" id="max<?php echo $kpi->getId() ?>" value="<?php echo $kpi->getMaxRate() ?>" />
                                <input type="hidden" name="min<?php echo $kpi->getId() ?>" id="min<?php echo $kpi->getId() ?>" value="<?php echo $kpi->getMinRate() ?>" />
                                <input id="txtRate<?php echo $kpi->getId() ?>"  name="txtRate[<?php echo $kpi->getId() ?>]" type="text"  class="smallInput" value="<?php echo trim($kpi->getRate()) ?>"  maxscale="<?php echo $kpi->getMaxRate() ?>" minscale="<?php echo $kpi->getMinRate() ?>" valiadate="1" />
                            </td>
                            <td class="">
                                <textarea id='txtComments' class="reviwerComment" name='txtComments[<?php echo $kpi->getId() ?>]'
                                            rows="2" cols="40">
                                    <?php echo htmlspecialchars_decode(trim($kpi->getComment())) ?>
                                </textarea>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php if (($isHrAdmin || $isReviwer) && 
                    ($performanceReview->getState() != PerformanceReview::PERFORMANCE_REVIEW_STATUS_APPROVED)) { ?>
            <p>
                <label width="40%"><?php echo __("Note") ?></label>
                <textarea id='txtMainComment' name='txtMainComment' class="formTextArea" rows="4" cols="60" ></textarea>
            </p>                    
            <?php } ?>

            <p>
                <?php if (($isReviwer && ($performanceReview->getState() <= PerformanceReview::PERFORMANCE_REVIEW_STATUS_BEING_REVIWED || $performanceReview->getState() == PerformanceReview::PERFORMANCE_REVIEW_STATUS_REJECTED)) || ( $isHrAdmin && $performanceReview->getState() != PerformanceReview::PERFORMANCE_REVIEW_STATUS_APPROVED)) { ?>
                    <input type="button" class="" id="saveBtn" value="<?php echo __("Edit") ?>"  />
                <?php } ?>

                <?php if ($isReviwer && ( $performanceReview->getState() == PerformanceReview::PERFORMANCE_REVIEW_STATUS_SCHDULED || $performanceReview->getState() == PerformanceReview::PERFORMANCE_REVIEW_STATUS_BEING_REVIWED || $performanceReview->getState() == PerformanceReview::PERFORMANCE_REVIEW_STATUS_REJECTED)) { ?>
                    <input type="button" class="" id="submitBtn" value="<?php echo __("Submit") ?>"  />
                <?php } ?>

                <?php if ($isHrAdmin && $performanceReview->getState() == PerformanceReview::PERFORMANCE_REVIEW_STATUS_SUBMITTED) { ?>
                    <input type="button" class="delete" id="rejectBtn" value="<?php echo __("Reject") ?>"  />
                <?php } ?>

                <?php if ($isHrAdmin && ( $performanceReview->getState() == PerformanceReview::PERFORMANCE_REVIEW_STATUS_SUBMITTED )) { ?>
                    <input type="button" class="" id="approveBtn" value="<?php echo __("Approve") ?>"  />
                <?php } ?>

                <input type="button" class="reset" id="backBtn" value="<?php echo __("Back"); ?>" />
            </p>
        </form>
        
    </div> <!-- inner -->
    
</div> <!-- performanceReviewcontentContainer -->

  <script type="text/javascript">

	//Check autosave
  	function autosave()
	  {
	      var t = setTimeout("autosave()", 20000);

	      var title = $("#txt_title").val();
	      var content = $("#txt_content").val();

	      if (title.length > 0 || content.length > 0)
	      {
	          $.ajax(
	          {
	              type: "POST",
	              url: "autosave.php",
	              data: "article_id=" + <?php echo $article_id ?>
	  + "&title=" + title + "&content=" + content,
	              cache: false,
	              success: function(message)
	              {
	                  $("#timestamp").empty().append(message);
	              }
	          });
	      }
	  }

      //Check submit
	  function checkSubmit(){
		  var valid	=	true ;
		  var msg	=	'';
		  $("input.smallInput").each(function() {
			  max	=	parseFloat($(this).attr('maxscale'));
			  min =   parseFloat($(this).attr('minscale'));
			  rate =  parseFloat(this.value) ;

			  if( !isNaN(max) || !isNaN(min)){
				  if( isNaN(rate)){
					  valid = false;
					  $(this).css('background-color', '#ffeeee');
					  $(this).css('border', 'solid 1px #ffdddd');
				  }else{
					  if( (rate > max) || (rate <min) ){
							$(this).css('background-color', '#ffeeee');
							$(this).css('border', 'solid 1px #ffdddd');
							 valid = false;

						}else{
							$(this).css('background-color', '#ffffff');
							$(this).css('border', 'solid 1px #000000');
						}
				  }

			  }
		  });
		  if( !valid ){
			  msg	=	'<?php echo __('KPI Should Be a Number Within Minimum and Maximum Value');?>';
			  $("#messageBalloon_failure ul").html('<li>'+msg+'</li>');
			  $("#performanceError").show();
		  }
		  return valid ;
	  }


	  //Check save
	  function checkSave(){
		  var valid	=	true ;
		  var msg	=	'';
		  $("input.smallInput").each(function() {
			  max	=	parseFloat($(this).attr('maxscale'));
			  min =   parseFloat($(this).attr('minscale'));
			  rate =  parseFloat(this.value) ;

			  if(!isNaN(this.value)){
				  if( isNaN(rate)){
					  valid = false;
					  $(this).css('background-color', '#ffeeee');
					  $(this).css('border', 'solid 1px #ffdddd');
				  }else{
					  if( (rate > max) || (rate <min) ){
							$(this).css('background-color', '#ffeeee');
							$(this).css('border', 'solid 1px #ffdddd');
							 valid = false;

						}else{
							$(this).css('background-color', '#ffffff');
							$(this).css('border', 'solid 1px #000000');
						}
				  }

			  }
		  });
		  if( !valid ){
			  msg	=	'<?php echo __('KPI Should Be a Number Within Minimum and Maximum Value');?>';
			  $("#messageBalloon_failure ul").html('<li>'+msg+'</li>');
			  $("#performanceError").show();
		  }
		  return valid ;
	  }

	  $(document).ready(function(){
		  	var mode	=	'edit';

			//Disable all fields
			$('#frmSave :input').attr('readonly', true);
			$('#saveBtn').removeAttr('readonly');

			//When click edit button
			 $("#saveBtn").click(function() {
					if( mode == 'edit')
					{
						$('#saveBtn').attr('value', "<?php echo __("Save")?>");
						$('#frmSave :input').removeAttr('readonly');
						mode = 'save';
					}else
					{
                  if(checkSubmit()){
                     $('#saveMode').val('save');
                     $('#frmSave').submit();
                  }
					}
				});

			//When Submit button click
				$("#submitBtn").click(function() {
					$('#frmSave :input').removeAttr('readonly');
					if(checkSubmit()){
						$('#saveMode').val('submit');
						$('#frmSave').submit();
					}
				});

			//When Submit button click
				$("#rejectBtn").click(function() {
					$('#frmSave :input').removeAttr('readonly');
					$('#saveMode').val('reject');
					$('#frmSave').submit();
				});

			//When Submit button click
				$("#approveBtn").click(function() {
					$('#frmSave :input').removeAttr('readonly');
					$('#saveMode').val('approve');
					$('#frmSave').submit();
				});

			// Back button
				$("#backBtn").click(function() {
					location.href = "<?php echo url_for('performance/viewReview');?>";
				});

			//Validate search form
				 $("#frmSave").validate({

					 rules: {
					 	txtMainComment: {maxlength: 250},
					 	validRate: {minmax:true	}

				 	 },
				 	 messages: {
				 		txtMainComment: "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 250))?>",
				 		validRate: ""
				 	 }
				 });


				 $.validator.addMethod("minmax", function(value, element) {

					 	if($('#validRate').val() == '1' )
							return true;
					 	else
					 		return false;
					});

				// check keyup on scale inputs
					$("#frmSave").delegate("keyup", "input.smallInput", function(event) {
						var id ;
						var max ;
						var min ;
						var rate ;
						var msg ;
						var error = false;
						$("input.smallInput").each(function() {

							id	=	$(this).attr('id');
							max	=	parseFloat($(this).attr('maxscale'));
							min =   parseFloat($(this).attr('minscale'));
							rate =  parseFloat(this.value) ;
							if(!isNaN(this.value)){

								if( (rate > max) || (rate <min) ){
									$(this).css('background-color', '#ffeeee');
									$(this).css('border', 'solid 1px #ffdddd');
									msg = '<?php echo __('KPI Should Be a Number Within Minimum and Maximum Value')?>';
									error = true;

								}else{
									$(this).css('background-color', '#ffffff');
									$(this).css('border', 'solid 1px #000000');
								}
							}else{
								$(this).css('background-color', '#ffeeee');
								$(this).css('border', 'solid 1px #ffdddd');
								msg = '<?php echo __('KPI Should Be a Number Within Minimum and Maximum Value')?>';
								error = true;
							}
						});

						if(error){
							$("#messageBalloon_failure ul").html('<li>'+msg+'</li>');
							$("#performanceError").show();
							$('#validRate').val('0');
						}else
						{
							$("#performanceError").hide();
							$('#validRate').val('1');
						}

						return false;
					});

					//Check Reviwer comment
					$("#frmSave").delegate("keyup", "textarea.reviwerComment", function(event) {
                  validateReviewerComment();
					});

               function validateReviewerComment() {
                  var error = false;
                  var msg ;
                  var flag = false;

						$("textarea.reviwerComment").each(function() {
							if(this.value.length >= 2000 ){
								$(this).css('background-color', '#ffeeee');
								$(this).css('border', 'solid 1px #ffdddd');
								error = true;
							}else{
								$(this).css('background-color', '#ffffff');
								$(this).css('border', 'solid 1px #000000');
                        flag = true;
							}
						});

						if(error){
							$("#messageBalloon_failure ul").html('<li><?php echo __('Comment Should Be Less Than %amount% Characters', array('%amount%' => 2000));?></li>');
							$("#performanceError").show();
							$('#validRate').val('0');
						}else{
							$("#performanceError").hide();
							$('#validRate').val('1');
						}
                  return flag;
               }

               //make sure all validations are performed before submit
               $("#frmSave").submit(function() {
                  flag = validateReviewerComment();
                  return flag;
               });
		});
  </script>